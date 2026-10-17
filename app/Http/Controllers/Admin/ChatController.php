<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    /* ============================== pages ============================== */

    public function index(?User $user = null): View
    {
        $me = auth()->user();
        $contacts = $this->contacts($me);

        $all = $this->conversationQuery($me)
            ->with('reply')
            ->orderByDesc('created_at')
            ->get();

        $last = [];
        $unread = [];
        foreach ($all as $m) {
            $pid = $m->partnerId($me->id);
            if (! isset($last[$pid])) {
                $last[$pid] = $m;
            }
            if ($m->receiver_id === $me->id && $m->read_at === null) {
                $unread[$pid] = ($unread[$pid] ?? 0) + 1;
            }
        }

        $active = ($user && $user->id !== $me->id) ? $contacts->firstWhere('id', $user->id) : null;
        if (! $active && $all->isNotEmpty()) {
            $active = $contacts->firstWhere('id', $all->first()->partnerId($me->id));
        }
        if (! $active) {
            $active = $contacts->first();
        }

        $thread = collect();
        $pinned = collect();
        if ($active) {
            $thread = $this->threadQuery($me, $active)->with('reply')->orderBy('created_at')->get();
            $pinned = $this->threadQuery($me, $active)->with('reply')->whereNotNull('pinned_at')
                ->orderByDesc('pinned_at')->get();

            $this->markRead($me, $active);
        }

        $contacts = $contacts->sortByDesc(function ($c) use ($last) {
            return optional($last[$c->id] ?? null)->created_at?->timestamp ?? 0;
        })->values();

        $messages = $thread->map(fn ($m) => $this->payload($m))->values();
        $pinnedPayload = $pinned->first() ? $this->payload($pinned->first()) : null;
        $pinnedCount = $pinned->count();

        return view('admin.chat.index', compact(
            'contacts', 'active', 'thread', 'last', 'unread', 'messages', 'pinnedPayload', 'pinnedCount'
        ));
    }

    /** Pantau: admin utama melihat obrolan antar pengelola. */
    public function monitor(?User $a = null, ?User $b = null): View
    {
        $me = auth()->user();
        abort_unless($me->isUtama(), 403);

        $contacts = User::query()
            ->whereIn('role', ['superadmin', 'operator', 'admin'])
            ->orderBy('name')->get();

        $thread = collect();
        $pairA = $pairB = null;
        if ($a && $b && $a->id !== $b->id) {
            $pairA = $contacts->firstWhere('id', $a->id);
            $pairB = $contacts->firstWhere('id', $b->id);
        }
        if (! $pairA || ! $pairB) {
            $pairA = $contacts->first();
            $pairB = $contacts->skip(1)->first();
        }

        if ($pairA && $pairB) {
            $thread = AdminMessage::query()
                ->where(function ($q) use ($pairA, $pairB) {
                    $q->where('sender_id', $pairA->id)->where('receiver_id', $pairB->id);
                })
                ->orWhere(function ($q) use ($pairA, $pairB) {
                    $q->where('sender_id', $pairB->id)->where('receiver_id', $pairA->id);
                })
                ->with('reply')
                ->orderBy('created_at')
                ->get();
        }

        return view('admin.chat.monitor', compact('contacts', 'pairA', 'pairB', 'thread'));
    }

    /* ============================ message ops =========================== */

    public function send(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);
        abort_unless($user->isAdmin(), 404);

        $data = $request->validate([
            'body' => 'nullable|string|max:2000',
            'reply_to_id' => 'nullable|integer',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $body = trim((string) ($data['body'] ?? ''));
        $hasFile = $request->hasFile('attachment');
        abort_if($body === '' && ! $hasFile, 422, 'Pesan kosong.');

        $attrs = [
            'sender_id' => $me->id,
            'receiver_id' => $user->id,
            'body' => $body,
        ];

        if (! empty($data['reply_to_id'])) {
            $rep = AdminMessage::find($data['reply_to_id']);
            if ($rep && ($rep->sender_id === $me->id || $rep->receiver_id === $me->id)) {
                $attrs['reply_to_id'] = $rep->id;
            }
        }

        if ($hasFile) {
            $file = $request->file('attachment');
            $mime = (string) $file->getMimeType();
            $attrs['type'] = Str::startsWith($mime, 'image/') ? 'image'
                : (Str::startsWith($mime, 'audio/') ? 'audio'
                : (Str::startsWith($mime, 'video/') ? 'video' : 'file'));
            $attrs['attachment_path'] = $file->store('chat', 'public');
            $attrs['attachment_name'] = $file->getClientOriginalName();
            $attrs['attachment_size'] = $file->getSize();
            $attrs['attachment_mime'] = $mime;
        } else {
            $attrs['type'] = 'text';
        }

        $msg = AdminMessage::create($attrs)->load('reply');

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => $this->payload($msg)]);
        }

        return redirect()->route('admin.chat.show', $user);
    }

    public function toggleStar(AdminMessage $message): JsonResponse
    {
        $me = auth()->user();
        $this->authorizeMessage($message, $me);

        $starred = $message->starred_by ?? [];
        if (in_array($me->id, $starred, true)) {
            $starred = array_values(array_diff($starred, [$me->id]));
        } else {
            $starred[] = $me->id;
        }
        $message->update(['starred_by' => $starred]);

        return response()->json([
            'ok' => true,
            'starred' => in_array($me->id, $starred, true),
        ]);
    }

    public function togglePin(AdminMessage $message): JsonResponse
    {
        $me = auth()->user();
        $this->authorizeMessage($message, $me);

        $pin = $message->pinned_at === null;
        $message->update(['pinned_at' => $pin ? now() : null]);

        return response()->json([
            'ok' => true,
            'pinned' => $pin,
            'message' => $this->payload($message->fresh('reply')),
        ]);
    }

    public function destroyMessage(AdminMessage $message): JsonResponse
    {
        $me = auth()->user();
        $this->authorizeMessage($message, $me);

        $message->delete();

        return response()->json(['ok' => true]);
    }

    public function forward(Request $request, AdminMessage $message): JsonResponse
    {
        $me = auth()->user();
        $this->authorizeMessage($message, $me);

        $data = $request->validate(['target_id' => 'required|integer']);
        $target = User::find($data['target_id']);
        abort_unless($target && $target->id !== $me->id && $target->isAdmin(), 422);

        AdminMessage::create([
            'sender_id' => $me->id,
            'receiver_id' => $target->id,
            'body' => $message->body,
            'type' => $message->type,
            'attachment_path' => $message->attachment_path,
            'attachment_name' => $message->attachment_name,
            'attachment_size' => $message->attachment_size,
            'attachment_mime' => $message->attachment_mime,
        ]);

        return response()->json(['ok' => true, 'name' => $target->name]);
    }

    public function clear(User $user): RedirectResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);

        AdminMessage::withTrashed()
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->forceDelete();

        return redirect()->route('admin.chat.show', $user)->with('sukses', 'Obrolan dibersihkan.');
    }

    /* ============================== polling ============================= */

    public function poll(Request $request, User $user): JsonResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);
        abort_unless($user->isAdmin(), 404);

        $after = (int) $request->query('after', 0);

        $msgs = $this->threadQuery($me, $user)
            ->with('reply')
            ->where('id', '>', $after)
            ->orderBy('id')
            ->get();

        $this->markRead($me, $user);

        return response()->json([
            'ok' => true,
            'messages' => $msgs->map(fn ($m) => $this->payload($m))->values(),
        ]);
    }

    public function unreadCount(): JsonResponse
    {
        $count = AdminMessage::query()
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['ok' => true, 'count' => $count]);
    }

    /**
     * Denyut notifikasi chat: mengembalikan jumlah belum dibaca, id pesan masuk terbaru,
     * dan daftar pesan baru (setelah `after`) untuk memicu notifikasi realtime tanpa refresh.
     */
    public function inbox(Request $request): JsonResponse
    {
        $me = auth()->user();
        $after = $request->query('after');

        $items = collect();
        if ($after !== null && $after !== '') {
            $items = AdminMessage::query()
                ->where('receiver_id', $me->id)
                ->where('id', '>', (int) $after)
                ->with('sender')
                ->orderBy('id')
                ->get()
                ->map(fn (AdminMessage $m) => [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'sender' => $m->sender->name ?? 'Admin',
                    'preview' => $m->type === 'text'
                        ? Str::limit((string) $m->body, 90)
                        : 'Lampiran ('.($m->type ?: 'file').')',
                    'url' => route('admin.chat.show', $m->sender_id),
                ])
                ->values();
        }

        return response()->json([
            'ok' => true,
            'count' => AdminMessage::query()
                ->where('receiver_id', $me->id)
                ->whereNull('read_at')
                ->count(),
            'max_id' => (int) AdminMessage::query()->where('receiver_id', $me->id)->max('id'),
            'items' => $items,
        ]);
    }

    public function search(Request $request, User $user): JsonResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);
        $q = trim((string) $request->query('q', ''));

        $ids = $q === '' ? collect() : $this->threadQuery($me, $user)
            ->where(function ($w) use ($q) {
                $w->where('body', 'like', '%'.$q.'%')
                    ->orWhere('attachment_name', 'like', '%'.$q.'%');
            })
            ->with('reply')->orderBy('id')->pluck('id');

        return response()->json(['ok' => true, 'ids' => $ids]);
    }

    /* ============================== helpers ============================= */

    private function contacts(User $me)
    {
        return User::query()
            ->where('id', '!=', $me->id)
            ->whereIn('role', ['superadmin', 'operator', 'admin'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function conversationQuery(User $me)
    {
        return AdminMessage::query()->where(function ($q) use ($me) {
            $q->where('sender_id', $me->id)->orWhere('receiver_id', $me->id);
        });
    }

    private function threadQuery(User $me, User $partner)
    {
        return AdminMessage::query()
            ->where(function ($q) use ($me, $partner) {
                $q->where('sender_id', $me->id)->where('receiver_id', $partner->id);
            })
            ->orWhere(function ($q) use ($me, $partner) {
                $q->where('sender_id', $partner->id)->where('receiver_id', $me->id);
            });
    }

    private function markRead(User $me, User $partner): void
    {
        AdminMessage::query()
            ->where('sender_id', $partner->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function authorizeMessage(AdminMessage $m, User $me): void
    {
        abort_unless($m->sender_id === $me->id || $m->receiver_id === $me->id, 403);
    }

    private function payload(AdminMessage $m): array
    {
        $me = auth()->id();
        $reply = null;
        if ($m->reply_to_id && $m->relationLoaded('reply') && $m->reply) {
            $reply = [
                'id' => $m->reply->id,
                'body' => $m->reply->type === 'text'
                    ? Str::limit($m->reply->body, 120)
                    : 'Lampiran',
                'name' => $m->reply->sender_id === $me ? 'Anda' : 'Admin lain',
            ];
        }

        return [
            'id' => $m->id,
            'sender_id' => $m->sender_id,
            'body' => $m->body,
            'type' => $m->type,
            'mine' => $m->sender_id === $me,
            'starred' => in_array($me, $m->starred_by ?? [], true),
            'pinned' => $m->pinned_at !== null,
            'read' => $m->read_at !== null,
            'time' => $m->created_at->format('H:i'),
            'date' => $m->created_at->locale('id')->translatedFormat('d M Y'),
            'reply' => $reply,
            'attachment' => $m->attachment_path ? [
                'url' => asset('storage/'.$m->attachment_path),
                'name' => $m->attachment_name,
                'size' => $m->attachment_size,
                'mime' => $m->attachment_mime,
            ] : null,
        ];
    }
}
