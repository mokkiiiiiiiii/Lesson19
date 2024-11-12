<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用

use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス

use App\Models\User;
//UserModelにアクセス


class ProfileController extends Controller
{
    //
    public function profile()
    {
        $user = auth::user();
        //ログインユーザー情報の取得

        $posts = $user->posts()->orderBy('created_at', 'desc')->get();
        //投稿を新しい順序で取得する。

        $lists = $posts;


        return view('profiles.profile', compact('user', 'posts', 'lists'));
        //profile.bladeにユーザー情報を渡す。
    }



    //編集画面の表示
    //editメソッドで取得したユーザー情報を"profiles.edit"ビューに渡す。

    public function edit()
    {
        $user = Auth::user();
        //ログインユーザーを取得
        return view('profiles.edit', compact('user'));
    }
    //プロフィールの更新処理
    //updateメソッドでフォームから送信されたデータをバリデートし、ユーザーのプロフィールを更新させる。
    public function update(Request $request)
    {
        //バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_image' => ''
            //画像更新処理は難しそうなので後回し中
        ]);

        //ログインユーザーを更新
        $user = Auth::user();
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('profiles.edit')->with('success', 'プロフィールが更新されました');
    }


    //検索機能
    public function search(Request $request)
    {

        $query = $request->input('query');
        //検索クエリを取得させる
        $users = User::where('name', 'like', "%$query%")->get();
        //名前のあいまい検索

        return view('profiles.show', compact('query', 'users'));
    }



    // 該当ユーザーを検索し、そのユーザーのプロフィールを表示するビューにデータを渡す。
    public function show($id)
    {

        $user = User::findOrFail($id);
        //IDに一致するユーザーを取得する

        return view('profiles.show', compact('user'));
        //データをビューに渡す。
    }
}
