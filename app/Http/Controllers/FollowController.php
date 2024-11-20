<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FollowController extends Controller
{
    public function follow(User $user)
    {
        $follower = Auth::user();

        if ($follower->user_id !== $user->user_id && !$follower->isFollowing($user)) {
            $follower->followees()->attach($user->user_id);
        }

        return redirect()->route('follow.list'); // フォローリストにリダイレクト
    }

    public function unfollow(User $user)
    {
        $follower = Auth::user();

        if ($follower->isFollowing($user)) {
            $follower->followees()->detach($user->user_id);
        }

        return back(); // 元のページに戻る
    }

    public function followList()
    {
        $followees = Auth::user()->followees()->get(); // フォローしているユーザーを取得
        return view('users.follow_list', compact('followees'));
    }

    public function followerList()
    {
        $user = Auth::user();
        $followers = Auth::user()->followers; // 自分をフォローしているユーザーを取得
        return view('users.follower_list', compact('user','followers'));
    }
}
