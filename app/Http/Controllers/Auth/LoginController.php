<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//ルーティングに関する設定を管理し、アプリケーションのルートの登録を行う場所
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//AuthenticatesUsersトレイトは、Laravelでユーザー認証を実装するための機能
use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
//HTTPリクエストに対してどのような処理を行うかを定義
{

    //Laravelが提供する標準的なログイン処理の機能をコントローラーに追加するためのトレイト
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'index';
    //ユーザーがログインした後にどのページにリダイレクトされるか指定


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //ログインしていないユーザーのみがアクセス
        //logoutアクションに対しては、guestミドルウェアを適用しない
    }


    /**
     * ログイン時のバリデーションと処理
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */

     
     //ログインフォームを送信したときに呼び出されるメソッド
    public function login(Request $request)
    {
        // バリデーションの追加
        $request->validate([
            'email' => 'required|string|email',
            //必須、文字列、メール
            'password' => ['required','string','min:6'],
        ], [
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは6文字以上で入力してください。',
        ]);
        // ログイン処理
       if (Auth::attempt($request->only('email', 'password'))) {
    return redirect()->route('posts.index'); // ここで直接リダイレクト先を指定
}



        // ログイン失敗時の処理
        return back()->withErrors([
            'email' => '認証に失敗しました。メールアドレスまたはパスワードが間違っています。',
        ])->withInput($request->only('email'));
        //ユーザーが入力したメールアドレスは再度表示
    }


    /**
     * ログアウト後のリダイレクト先を設定
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/login');  // ログアウト後にログインページにリダイレクト
    }
}
