<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用

use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス

use App\Models\User;
//UserModelにアクセス

use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
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


    public function verifyPasswordForm()
    {
    return view('profiles.verifyPassword');
    }

    public function verifyPassword(Request $request)
{
    // バリデーション
    $request->validate([
        'password' => 'required|string',
    ]);

    $user = Auth::user();

    // パスワードが一致しない場合、エラーメッセージを返す
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->route('profile.verify.password')
            ->withErrors(['password' => 'パスワードが正しくありません。']);
    }

    // セッションにパスワード確認済みフラグを設定
    session(['password_verified' => true]);

    // 編集画面にリダイレクトし、フラグを一度だけ使用するためのマーカーを設定
    return redirect()->route('profiles.edit')
        ->with('password_verified_once', true); // 一度限りのフラグ
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
            'name' => 'required|string|max:255|regex:/^(?![\s　]*$).+$/u',
            'password' => 'nullable|string|min:6|confirmed',
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => '名前は必須項目です。',
            'name.string' => '有効な名前を入力してください。',
            'name.max' => '名前は最大255文字です。',
            'name.regex' => '名前にスペースのみを入力することはできません。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは6文字以上で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
            'bio.string' => '有効な文字を入力してください。',
            'bio.max:500' => '500文字以内で入力してください。',
            'profiles_image.image' => '有効な画像を入力してください。',
            'profiles_image.mimes:jpeg,png,jpg,gif' => '有効なファイル形式を入力してください。',
            'profiles_image.max:2048' => 'ファイルサイズがオーバーしています。',
      ]);

        //ログインユーザーを更新
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
        // 古い画像を削除
        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
        }

        // 新しい画像を保存
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
      }
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->filled('password')) {
        $user->password = bcrypt($request->password); // パスワードをハッシュ化して保存
    }
        $user->save();

        session(['password_verified' => false]);
        logger()->info('Session after profile update: ' . json_encode(session()->all()));

        return redirect()->route('profile')->with('success', 'プロフィールが更新されました');
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
        $posts = $user->posts()->orderBy('created_at', 'desc')->get(); // 投稿を取得

        return view('profiles.show', compact('user', 'posts')); // データをビューに渡す

    }



}
