<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use App\User;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle() {
        // Google へのリダイレクト
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        // 返ってきたユーザーの情報を変数に格納
        $google_user = Socialite::driver('google')->stateless()->user();
        // email が合致するユーザーを取得
        // dd($google_user);
        $user = User::where('email', $google_user->email)->first();
        // 見つからなければ新しくユーザーを作成
        if ($user == null) {
            // $user = new User;
            // $user->name = $gUser->name;
            // $user->email = $gUser->email;
            // $user->avatar = $gUser->avatar;
            // $user->password = Hash::make(123456789);
            // $user->save();
            // Auth::login($user, true);
            return view('auth.login', compact('google_user'));
        } else {
            // if($user->avatar !== $google_user->avatar){
            //     $user->avatar = $google_user->avatar;
            //     $user->save();
            // }
            Auth::login($user, true);
            return redirect('/');
            // ログイン処理
        }
    }
}
