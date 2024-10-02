<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use Illuminate\Http\Request;




// ルートURLにアクセスされたときにログインページにリダイレクト
Route::get('/', function () {
    return redirect()->route('login');
});

//第一引数'index'の場合、~8000/indexを指す
//第二引数は、リクエストが来たときに実行するcontrollerとメソッドを指定。indexにgetリクエストが来たときに、このメソッドが実行される
//name部分はルートに名前を付与。URLのパスが変更された場合でも、ルート名を使ってそのルートにアクセスできるようになる
Route::get('index', [PostsController::class, 'index'])->name('posts.index');





//ユーザー認証に関連する一連のルートを自動的に登録するためのもの
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
