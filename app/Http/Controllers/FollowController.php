<?php

namespace App\Http\Controllers;

use App\Models\User;
//userテーブルに対する操作
use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス
use Illuminate\Support\Facades\DB;
//データベースからデータの取得、挿入のために使用


class FollowController extends Controller
{

    public function follow(User $user)
    {
        $follower = Auth::user();

        if ($follower->user_id !== $user->user_id && !$follower->isFollowing($user)) {
            $follower->followees()->attach($user->user_id);
        }
        //ログインユーザー自身をフォローしないようにチェック&ログインユーザーがすでに指定のユーザーをフォローしていないかを確認
        //&&とは、論理AND演算子 と呼ばれ、条件を結合するために使用。両方の条件がtrueの場合にのみ、全体の結果がtrueになる
        //followees()は、$followerのリレーションで、ログインユーザーがフォローしているユーザー
        //attach・・・フォロー対象のユーザーIDを中間テーブルに追加。新しいフォロー関係がデータベースに記録
        return redirect()->route('follow.list');
        // フォロー後にフォローリストにリダイレクト
    }


    public function unfollow(User $user)
    {
        $follower = Auth::user();

        //isFollowingメソッドを使用して、現在のユーザーが指定されたユーザー（$user）をフォローしているかを確認
        //followeesでログイン中のユーザーがフォローしているユーザーのリレーション（belongsToMany）を取得
        //中間テーブルから、指定されたユーザー（$user->user_id）のレコードを削除
        if ($follower->isFollowing($user)) {
            $follower->followees()->detach($user->user_id);
        }
        return back();
        // 元のページに戻る
    }


    public function followList()
    {
        $followees = Auth::user()->followees()->get();
        // ログインユーザーがフォローしているユーザーを取得
        return view('users.follow_list', compact('followees'));
        //取得したフォロイーリストをビューに渡す。users.follow_listでフォローリストを表示
        //compact()は、渡された変数をキーとして配列化
    }


    public function followerList()
{
    $user = Auth::user();

    // 自分をフォローしているユーザーを取得
    $followers = $user->followers;

    // ログインユーザーがすでにフォローしているユーザーIDのリストを取得
    //pluck('user_id'):リレーションによって取得されたデータ（followeesのデータから、特定のカラム（user_id）だけを抽出
    //toArray():pluckの結果を配列に変換
    $followedUserIds = $user->followees()->pluck('user_id')->toArray();

    // ビューにデータを渡す
    return view('users.follower_list', compact('user', 'followers', 'followedUserIds'));
}

}
