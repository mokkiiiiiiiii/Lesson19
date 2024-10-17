<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ユーザー一覧を表示する
    public function index()
{
    // ログインユーザーがいるか確認しつつ、自分以外のユーザーを取得
    if (Auth::check()) {
        $users = User::where('user_id', '!=', Auth::user()->user_id)->get();
    } else {
        $users = collect([]); // ユーザーがいない場合は空のコレクションを返す
    }

    return view('users.index', compact('users'));
}


    // フォローする処理
    public function follow(User $user)
    {
        $follower = Auth::user();

        if (!$follower->isFollowing($user)) {
            $follower->followees()->attach($user->user_id);
        }

        // フォローリスト画面にリダイレクト
        return redirect()->route('follow.list');
    }

    // フォローしているユーザーのリスト表示
    public function followList()
    {
        $followees = Auth::user()->followees()->distinct()->get(); // フォローしているユーザーを取得
        return view('users.follow_list', compact('followees'));
    }

    // ユーザー検索
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // 名前で部分一致検索 (自分自身を除外)
        $users = User::where('name', 'LIKE', '%' . $keyword . '%')
                     ->where('user_id', '!=', Auth::id())
                     ->get();

        // 検索結果が空の場合
        if ($users->isEmpty()) {
            return view('users.index', ['message' => '検索結果は0件です。']);
        }

        // 検索結果をビューに渡す
        return view('users.index', compact('users'));
    }
}
