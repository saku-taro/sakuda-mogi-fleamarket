<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification; // 追加
use Illuminate\Auth\Notifications\VerifyEmail; // 追加
use App\Models\User;

class T16_EmailVerificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_会員登録後、認証メールが送信される()
    {
        Notification::fake();

        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post(route('register'), [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);

        $response->assertSee('<a class="verify-email__link" href="http://localhost:8025" target="_blank">認証はこちらから</a>', false);
    }

    public function test_メール認証が完了するとプロフィール画面に遷移する()
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(1),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );

        $this->actingAs($user);
        $response = $this->get($url);

        $response->assertRedirect(route('profile.edit'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
