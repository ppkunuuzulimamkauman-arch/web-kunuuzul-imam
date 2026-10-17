<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(?User $user = null): View
    {
        $me = auth()->user();

        $contacts = User::query()
            ->where('id', '!=', $me->id)
            ->whereIn('role', ['superadmin', 'operator', 'admin'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $all = AdminMessage::query()
            ->where(function ($q) use ($me) {
                $q->where('sender_id', $me->id)->orWhere('receiver_id', $me->id);
            })
            ->orderByDesc('created_at')
            ->get();

        $last = [];
        $unread = [];
        foreach ($all as $m) {
            $pid = $m->sender_id === $me->id ? $m->receiver_id : $m->sender_id;
            if (! isset($last[$pid])) {
                $last[$pid] = $m;
            }
            if ($m->receiver_id === $me->id && $m->read_at === null) {
                $unread[$pid] = ($unread[$pid] ?? 0) + 1;
            }
        }

        $active = ($user && $user->id !== $me->id) ? $contacts->firstWhere('id', $user->id) : null;
        if (! $active && $all->isNotEmpty()) {
            $first = $all->first();
            $pid = $first->sender_id === $me->id ? $first->receiver_id : $first->sender_id;
            $active = $contacts->firstWhere('id', $pid);
        }
        if (! $active) {
            $active = $contacts->first();
        }

        $thread = collect();
        if ($active) {
            $thread = AdminMessage::query()
                ->where(function ($q) use ($me, $active) {
                    $q->where('sender_id', $me->id)->where('receiver_id', $active->id);
                })
                ->orWhere(function ($q) use ($me, $active) {
                    $q->where('sender_id', $active->id)->where('receiver_id', $me->id);
                })
                ->orderBy('created_at')
                ->get();

            AdminMessage::query()
                ->where('sender_id', $active->id)
                ->where('receiver_id', $me->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $contacts = $contacts->sortByDesc(function ($c) use ($last) {
            return optional($last[$c->id] ?? null)->created_at?->timestamp ?? 0;
        })->values();

        return view('admin.chat.index', [
            'contacts' => $contacts,
            'active' => $active,
            'thread' => $thread,
            'last' => $last,
            'unread' => $unread,
        ]);
    }

    public function send(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);
        abort_unless($user->isAdmin(), 404);

        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $msg = AdminMessage::create([
            'sender_id' => $me->id,
            'receiver_id' => $user->id,
            'body' => trim($data['body']),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => $this->payload($msg)]);
        }

        return redirect()->route('admin.chat.show', $user);
    }

    public function poll(Request $request, User $user): JsonResponse
    {
        $me = auth()->user();
        abort_if($user->id === $me->id, 422);
        abort_unless($user->isAdmin(), 404);

        $after = (int) $request->query('after', 0);

        $msgs = AdminMessage::query()
            ->where(function ($q) use ($me, $user) {
                $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($me, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
            })
            ->where('id', '>', $after)
            ->orderBy('id')
            ->get();

        AdminMessage::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

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

    protected function payload(AdminMessage $m): array
    {
        return [
            'id' => $m->id,
            'sender_id' => $m->sender_id,
            'body' => $m->body,
            'mine' => $m->sender_id === auth()->id(),
            'time' => $m->created_at->format('H:i'),
            'date' => $m->created_at->locale('id')->translatedFormat('d M Y'),
        ];
    }
}
