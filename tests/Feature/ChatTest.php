<?php

namespace Tests\Feature;

use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use DatabaseTransactions;

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

    public function test_chat_halaman_dan_kirim_pesan(): void
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

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

        $this->assertSame(1, AdminMessage::count());
    }
}
