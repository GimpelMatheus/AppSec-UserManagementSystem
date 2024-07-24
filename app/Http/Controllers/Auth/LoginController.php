use Illuminate\Support\Facades\Auth;
use App\Models\User;

protected function sendFailedLoginResponse(Request $request)
{
    $user = User::where('email', $request->email)->first();
    if ($user) {
        $user->increment('failed_login_attempts');
        if ($user->failed_login_attempts >= 5) {
            $user->account_locked = true;
            $user->save();
        }
    }

    throw ValidationException::withMessages([
        $this->username() => [trans('auth.failed')],
    ]);
}
