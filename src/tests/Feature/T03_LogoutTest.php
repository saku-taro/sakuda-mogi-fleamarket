<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class T03_LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_ログアウトが可能かテスト()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
            'is_profile_completed' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $this->assertGuest();

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));

        $this->assertGuest();
    }
}
