<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


//ユーザー認証に関連する一連のルートを自動的に登録するためのもの
Auth::routes();

// ルートURLにアクセスされたときにログインページにリダイレクトの分岐
Route::get('/', function () {
    //ユーザーがログインしていなければログインページにリダイレクト
    if (auth()->guest()) {
        return redirect()->route('login');
    }
    //ログインしていれば投稿一覧へ。
    return redirect()->route('posts.index');
    })->name('home');


// ユーザー新規登録を行った後、ユーザーの名前情報を渡しながら、登録完了画面へ
Route::get('/register/complete', function () {
    // セッションからユーザー情報を取得
    //セッションは、サーバー側でユーザーごとの一時的なデータを保存する仕組み。リクエスト間でデータを保持し、同じユーザーの状態を追跡することが可能。
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
Route::delete('post/{id}/delete', [PostsController::class, 'delete'])->name('post.delete');

//検索ボタン押下時このルートが実行される。PostsControllerのメソッドを実行するよう指定
Route::get('/posts/search', [PostsController::class, 'search'])->name('posts.search');



//ユーザー検索ボタン押下時にユーザー検索画面(ユーザー一覧)へ遷移する
Route::get('/users', [UserController::class, 'index'])->name('users.index');

//検索ボタン押下時このルートが実行される。UsersControllerのメソッドを実行するよう指定
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');



// フォローのアクション。フォローメソッドでuserの値を渡す
Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');

//フォロー解除。対象のユーザーIDが渡される。
Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');

//フォローリストの表示。データを変更しないGET
Route::get('/follow-list', [FollowController::class, 'followList'])->name('follow.list');

//フォロワーリストの表示。
Route::get('/follower-list', [FollowController::class, 'followerList'])->name('follower.list');



//プロフィールへのルート設定。
//authﾐﾄﾞﾙｳｪｱを指定して、ﾛｸﾞｲﾝしていないユーザーが/profileにアクセスできないようにする。
Route::get('/profile', [ProfileController::class, 'profile'])->middleware('auth')->name('profile');

// 編集画面へ行く前に、パスワード確認画面へ
Route::get('/profile/verify-password', [ProfileController::class, 'verifyPasswordForm'])->name('profile.verify.password');

// パスワード確認処理
Route::post('/profile/verify-password', [ProfileController::class, 'verifyPassword'])->name('profile.verify.password.post');

// プロフィール編集ページへのルート設定
//kernel.php。パスワード確認済みかチェック
//同じく。キャッシュを無効化
Route::get('/profile/edit', [ProfileController::class, 'edit'])
    ->middleware(['verifiedPassword', 'disableCache'])
    ->name('profiles.edit');

//編集後の更新処理を実行
//データ更新の為putメソッドを使用。
Route::put('/profile_Update', [ProfileController::class, 'update'])->name('profile.update');

// プロフィールページでの検索機能の実装
Route::get('profile/show', [ProfileController::class, 'search'])->name('profiles.search');

//ユーザーそれぞれのプロフィール画面へ遷移する
Route::get('profile/{id}', [ProfileController::class, 'show'])->name('profiles.show');



//postリクエストによりルートに定義された処理が実行
//Auth::logoutで、Laravelの認証システムを使って現在ログインしているユーザーをログアウト
//ログアウト後にユーザーを/loginページにリダイレクト
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
