<?php

namespace Tests\Feature;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    private function admin(string $name, string $email, string $role = 'operator'): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password123',
            'role' => $role,
            'is_active' => true,
        ]);
    }

    public function test_halaman_dan_kirim_pesan(): void
    {
        $me = $this->admin('Tes Saya', 'tes-saya@example.test');
        $other = $this->admin('Tes Lain', 'tes-lain@example.test', 'superadmin');

        $this->actingAs($me)->get(route('admin.chat.index'))->assertOk();
        $this->actingAs($me)->get(route('admin.chat.show', $other))->assertOk();

        $this->actingAs($me)->postJson(route('admin.chat.send', $other), ['body' => 'Assalamualaikum'])
            ->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('admin_messages', [
            'sender_id' => $me->id,
            'receiver_id' => $other->id,
            'body' => 'Assalamualaikum',
        ]);

        $this->actingAs($other)->getJson(route('admin.chat.poll', $me))
            ->assertOk()->assertJsonCount(1, 'messages');
        $this->actingAs($other)->getJson(route('admin.chat.unread'))
            ->assertOk()->assertJson(['count' => 0]);
    }

    public function test_balas_bintang_semat_hapus_dan_teruskan(): void
    {
        $me = $this->admin('A', 'a@example.test', 'superadmin');
        $other = $this->admin('B', 'b@example.test');
        $third = $this->admin('C', 'c@example.test');

        $msg = AdminMessage::create(['sender_id' => $other->id, 'receiver_id' => $me->id, 'body' => 'Halo']);
        $second = AdminMessage::create([
            'sender_id' => $me->id, 'receiver_id' => $other->id, 'body' => 'Balasan',
            'reply_to_id' => $msg->id,
        ]);

        $this->assertSame($msg->id, $second->fresh()->reply_to_id);

        $this->actingAs($me)->postJson(route('admin.chat.star', $msg))->assertOk()->assertJson(['starred' => true]);
        $this->assertContains($me->id, $msg->fresh()->starred_by);

        $this->actingAs($me)->postJson(route('admin.chat.pin', $msg))->assertOk()->assertJson(['pinned' => true]);
        $this->assertNotNull($msg->fresh()->pinned_at);

        $this->actingAs($me)->postJson(route('admin.chat.forward', $msg), ['target_id' => $third->id])
            ->assertOk()->assertJson(['ok' => true]);
        $this->assertDatabaseHas('admin_messages', ['sender_id' => $me->id, 'receiver_id' => $third->id, 'body' => 'Halo']);

        $this->actingAs($me)->getJson(route('admin.chat.search', $other).'?q=Balasan')
            ->assertOk()->assertJson(['ok' => true]);

        $this->actingAs($me)->deleteJson(route('admin.chat.message.destroy', $second))->assertOk();
        $this->assertSoftDeleted('admin_messages', ['id' => $second->id]);
    }

    public function test_monitor_hanya_untuk_admin_utama(): void
    {
        $utama = $this->admin('U', 'u@example.test', 'superadmin');
        $op = $this->admin('O', 'o@example.test');
        $op2 = $this->admin('O2', 'o2@example.test');

        $this->actingAs($op)->get(route('admin.chat.monitor'))->assertForbidden();
        $this->actingAs($utama)->get(route('admin.chat.monitor.show', ['a' => $op->id, 'b' => $op2->id]))->assertOk();
    }
}
