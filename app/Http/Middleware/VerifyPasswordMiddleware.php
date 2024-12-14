<?php
//プロフィール編集ページにアクセスする際、パスワード確認が必要であることを保証するためのミドルウェア
namespace App\Http\Middleware;

use Closure;
//リクエストを次の処理に渡すための関数型オブジェクト
use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス
use Illuminate\Support\Facades\Log;
//デバッグ用にログを記録するための機能

class VerifyPasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // VerifyPasswordMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!session('password_verified')) {
        // セッションがリセットされた場合は確認画面にリダイレクト
        return redirect()->route('profile.verify.password')
            ->with('error', 'パスワードの確認が必要です。');
        //false または未設定の場合、パスワード確認が未完了とみなし、リダイレクト
    }

    // 編集画面に一度到達したら、再度パスワード確認を要求するようにセッションフラグをリセット
    //この処理により、「戻るボタン」や「再訪問」の場合でも、再度パスワード確認を要求する
    session()->forget('password_verified');
    return $next($request);
}
}
