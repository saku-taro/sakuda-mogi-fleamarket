<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;

class T14_ProfileEditTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'テスト 太郎',
            'postcode' => '123-4567',
            'address' => 'テスト区',
            'building' => 'テストビル',
            'profile_image' => 'profile/test.jpg',
            'is_profile_completed' => true,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('storage/profile/test.jpg', false);
        $response->assertSee('テスト 太郎');
        $response->assertSee('123-4567');
        $response->assertSee('テスト区');
        $response->assertSee('テストビル');

        $file = UploadedFile::fake()->create('test2.jpg', 500, 'image/jpeg');

        $response = $this->patch(route('profile.update'), [
            'name' => 'テスト 次郎',
            'postcode' => '987-6543',
            'address' => 'test市',
            'building' => 'testハイツ',
            'profile_image' => $file,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $updatedProfileImage = $user->fresh()->profile_image;

        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('storage/' . $updatedProfileImage, false);
        $response->assertSee('テスト 次郎');
        $response->assertSee('987-6543');
        $response->assertSee('test市');
        $response->assertSee('testハイツ');
    }
}
