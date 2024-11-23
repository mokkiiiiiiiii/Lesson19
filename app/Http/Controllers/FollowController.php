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
        //followees()は、$followerのリレーションで、ログインユーザーがフォローしているユーザー
        //attachでフォローテーブルに、新しいフォロー関係を挿入
        return redirect()->route('follow.list');
        // フォロー後にフォローリストにリダイレクト
    }


    public function unfollow(User $user)
    {
        $follower = Auth::user();

        if ($follower->isFollowing($user)) {
            $follower->followees()->detach($user->user_id);
        }
        //ログインユーザーが指定のユーザーをフォローしているか確認。フォローしている場合にのみ解除を実行
        return back();
        // 元のページに戻る
    }


    public function followList()
    {
        $followees = Auth::user()->followees()->get();
        // フォローしているユーザーを取得
        return view('users.follow_list', compact('followees'));
        //取得したフォロイーリストをビューに渡す。users.follow_listでフォローリストを表示します。
    }


    public function followerList()
    {
        $user = Auth::user();
        $followers = Auth::user()->followers;
        // 自分をフォローしているユーザーを取得
        return view('users.follower_list', compact('user','followers'));
        //ログインユーザーとフォロワーリストをビューに渡し、フォロワーリストを表示
    }
}
