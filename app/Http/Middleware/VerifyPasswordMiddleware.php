<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    }

    // 編集画面表示後にセッションをリセット
    session()->forget('password_verified');
    return $next($request);
}
}
