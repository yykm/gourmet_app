<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン完了後の処理メソッドをオーバライド
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \App\User
     */
    protected function authenticated(Request $request, $user)
    {
        // ログインしたユーザを返す
        return $user;
    }


    /**
     * ログアウト完了後の処理メソッドをオーバライド
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function loggedOut(Request $request)
    {
        // ステータス200を返す
        return response()->json();
    }

    /**
     * バリデーションをオーバーライド
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email:rfc,dns',
            'password' => 'required|string|alpha_num|between:8,20',
        ]);
    }
}
