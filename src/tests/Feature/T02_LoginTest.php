<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class T02_LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_メールアドレスが未入力の場合のバリデーションメッセージ()
    {
        $this->get('/login');

        $response = $this->post(route('login'), [
            'email'                 => '',
            'password'              => '12345678',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_パスワードが未入力の場合のバリデーションメッセージ()
    {
        $this->get('/login');

        $response = $this->post(route('login'), [
            'email'                 => 'test1@example.com',
            'password'              => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_入力情報が間違っている場合のバリデーションメッセージ()
    {
        User::factory()->create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
            'is_profile_completed' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        $this->get('/login');

        $response = $this->post(route('login'), [
            'email'                 => 'test123@example.com',
            'password'              => '12345678',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    public function test_正しい情報が入力された場合のログイン処理()
    {
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

        $this->get('/login');

        $this->post(route('login'), [
            'email'                 => 'test@example.com',
            'password'              => '12345678',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
