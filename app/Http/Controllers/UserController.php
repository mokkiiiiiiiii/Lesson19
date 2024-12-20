<?php

namespace App\Http\Controllers;

use App\Models\User;
//userテーブルに対する操作
use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス

class UserController extends Controller
{
  // ユーザー一覧を表示する
  public function index()
  {
    // ログインユーザーがいるか確認しつつ、自分以外のユーザーを取得
    if (Auth::check()) {
      $users = User::where('user_id', '!=', Auth::user()->user_id)
        ->orderBy('created_at', 'desc')
        // 作成日時順に並び替える
        ->get();
    } else {
      $users = collect([]);
      //ログインしていない場合、空のコレクション（collect([])) を返す。これにより、エラー発生を抑える。
    }
    return view('users.index', compact('users'));
    //compact()は、渡された変数をキーとして配列化。compact('users')は ['users' => $users] と同じ意味
  }


  // フォローする処理
  public function follow(User $user)
  {
    $follower = Auth::user();

    //ログインユーザーが既にフォローしていない場合のみフォロー処理を実行
    if (!$follower->isFollowing($user)) {
      //followees:ログインユーザーがフォローしているユーザーを表すリレーション（belongsToManyで多対多の関係を表す)
      $follower->followees()->attach($user->user_id);
        //中間テーブル（followsテーブル）にフォロー関係を追加
        //直接的な関連を持たない2つのエンティティ（例えば、ユーザーとフォロー）の間に関連付けを作る役割。どのユーザーがどのユーザーをフォローしているかを管理
    }
    // フォローリスト画面にリダイレクト
    return redirect()->route('follow.list');
  }


  // フォローしているユーザーのリスト表示
  public function followList()
  {
    $followees = Auth::user()->followees()->distinct()->get();
    //distinct():取得結果から重複するデータを除外して、フォローしているユーザーを取得
    return view('users.follow_list', compact('followees'));
  }


  public function isFollowing(User $user)
  {
    return $this->followees()->where('user_id', $user->user_id)->exists();
    //->exists()・・・データベースに該当するレコードが存在するかどうかをチェック
  }


  // ユーザー検索
  public function search(Request $request)
  {
    $keyword = $request->input('keyword');

    // 名前で部分一致検索
    $users = User::where('name', 'LIKE', '%' . $keyword . '%')
      //'!='(等しくない)を使ってログインユーザー除外
      ->where('user_id', '!=', Auth::id())
      ->get();
    // 検索結果が空の場合
    if ($users->isEmpty()) {
        $message = "「{$keyword}」の検索結果は0件です。";
        return view('users.index', compact('message', 'keyword'));
        //compact内の変数がビューに渡される
    }

    // 検索結果がある場合
     $message = "「{$keyword}」の検索結果";
    return view('users.index', compact('users','message', 'keyword'));
}
}
