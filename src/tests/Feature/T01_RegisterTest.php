<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class T01_RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_名前が未入力の場合のバリデーションメッセージ()
    {
        $this->get('/register');
        $inputData = [
            'name'                  => '',
            'email'                 => 'test1@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];
        $response = $this->post(route('register'), $inputData);
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_メールアドレスが未入力の場合のバリデーションメッセージ()
    {
        $this->get('/register');
        $inputData = [
            'name'                  => 'テスト太郎',
            'email'                 => '',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];
        $response = $this->post(route('register'), $inputData);
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_パスワードが未入力の場合のバリデーションメッセージ()
    {
        $this->get('/register');
        $inputData = [
            'name'                  => 'テスト太郎',
            'email'                 => 'test1@example.com',
            'password'              => '',
            'password_confirmation' => '12345678',
        ];
        $response = $this->post(route('register'), $inputData);
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_パスワードが7文字以下の場合のバリデーションメッセージ()
    {
        $this->get('/register');
        $inputData = [
            'name'                  => 'テスト太郎',
            'email'                 => 'test1@example.com',
            'password'              => '1234567',
            'password_confirmation' => '12345678',
        ];
        $response = $this->post(route('register'), $inputData);
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_パスワードが確認用パスワードと一致しない場合のバリデーションメッセージ()
    {
        $this->get('/register');
        $inputData = [
            'name'                  => 'テスト太郎',
            'email'                 => 'test1@example.com',
            'password'              => '123456789',
            'password_confirmation' => '12345678',
        ];
        $response = $this->post(route('register'), $inputData);
        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    use RefreshDatabase;

    public function test_全ての項目が入力されている場合_会員情報が登録され_プロフィール設定画面に遷移()
    {
        // 1. 登録処理を実行
        $response = $this->post(route('register'), [
            'name'                  => 'テスト太郎',
            'email'                 => 'test1@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ]);

        // 2. 「最初の1回目のリダイレクト先」がプロフィール編集画面であるかを確認する
        // これが最も単純で、失敗しない判定方法です
        $response->assertRedirect(route('profile.edit'));
    }
}
