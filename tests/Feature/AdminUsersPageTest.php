<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminUsersPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_users_page_renders_for_superadmin(): void
    {
        $this->withoutExceptionHandling();

        $utama = User::create([
            'name' => 'Utama Tes',
            'email' => 'utama-tes@example.test',
            'password' => 'password123',
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Operator Tes',
            'email' => 'operator-tes@example.test',
            'password' => 'password123',
            'role' => 'operator',
            'is_active' => true,
        ]);

        $this->actingAs($utama)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($utama)->get(route('admin.dashboard'))->assertOk();
    }
}
