namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
public function toResponse($request)
{
// 登録直後、プロフィール登録画面（/profile/createなど）へリダイレクト
return redirect()->route('profile.create');
}
}
