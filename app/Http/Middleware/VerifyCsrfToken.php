<?php
// アプリケーションでの CSRF (クロスサイトリクエストフォージェリ)攻撃 を防ぐために使用。フォーム送信時に正当なユーザーからのリクエストであることを確認するためのセキュリティ機能
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //配列が空であるため、すべてのリクエストで CSRF トークンの検証が有効
    ];
}
