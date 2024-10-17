<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use Illuminate\Http\Request;


// ルートURLにアクセスされたときにログインページにリダイレクト
Route::get('/', function () {
    return redirect()->route('login');  // ログインページにリダイレクト
});

// ユーザー新規登録を行った後、ユーザーの名前情報を渡しながら、登録完了画面へ
Route::get('/register/complete', function () {
    // セッションからユーザー情報を取得
    $user = session('user');
    return view('register_complete', compact('user'));
})->name('register.complete');

//第一引数'index'の場合、~8000/indexを指す
//第二引数は、リクエストが来たときに実行するcontrollerとメソッドを指定。indexにgetリクエストが来たときに、このメソッドが実行される
//name部分はルートに名前を付与。URLのパスが変更された場合でも、ルート名を使ってそのルートにアクセスできるようになる
Route::get('index', [PostsController::class, 'index'])->name('posts.index');

Route::get('/create-form', [PostsController::class, 'createForm'])->name('posts.createForm');

//create-formで入力した情報が送信されるとき、このルートが実行される。PostsControllerのcreateメソッドを実行するよう指定
Route::post('/post/create', [PostsController::class, 'create'])->name('posts.create');

//idには投稿を識別するための任意の数値が入る。そのidの投稿に関する更新フォームが表示
Route::get('post/{id}/update-form', [PostsController::class, 'updateForm'])->name('posts.edit');

//update-formで入力した情報が送信されるとき、このルートが実行される。PostsControllerのupdateメソッドを実行するよう指定
Route::post('/post/update', [PostsController::class, 'update']);

//特定の投稿を識別し、ボタン押下時このルートが実行される。PostsControllerのdeleteメソッドを実行するよう指定
Route::get('post/{id}/delete', [PostsController::class, 'delete']);

//検索ボタン押下時このルートが実行される。PostsControllerのメソッドを実行するよう指定
Route::get('/posts/search', [PostsController::class, 'search'])->name('posts.search');

//ユーザー検索ボタン押下時にユーザー検索画面(ユーザー一覧)へ遷移する
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//検索ボタン押下時このルートが実行される。UsersControllerのメソッドを実行するよう指定
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

// フォローのアクション
Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');

Route::get('/follow-list', [FollowController::class, 'followList'])->name('follow.list');

//postリクエストによりルートに定義された処理が実行
//Auth::logoutで、Laravelの認証システムを使って現在ログインしているユーザーをログアウト
//ログアウト後にユーザーを/loginページにリダイレクト
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');


//ユーザー認証に関連する一連のルートを自動的に登録するためのもの
Auth::routes();
