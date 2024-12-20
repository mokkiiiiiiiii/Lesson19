<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス
use App\Models\User;
//UserModelにアクセス
use Illuminate\Support\Facades\Hash;
//パスワードハッシュ化やハッシュ検証(ユーザーが入力したパスワードが一致するか)を行うためのファサード


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
    //パスワード確認フォームの表示処理
    {
    return view('profiles.verifyPassword');
    }

    public function verifyPassword(Request $request)
{
    // バリデーション
    $request->validate([
        'password' => 'required|string',
        // フィールドが必須で文字列であることをチェック
    ]);

    $user = Auth::user();

    //Hash::check():1つ目の引数に、リクエストされたパスワード
    //2つ目の引数に、ユーザーのハッシュ化されたパスワード。リクエストされたパスワードが正しいかどうかを確認
    // パスワードが一致しない場合、エラーメッセージを返す
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->route('profile.verify.password')
            ->withErrors(['password' => 'パスワードが正しくありません。']);
    }

    // 正しいパスワードを入力した場合にこのフラグを設定。現在のユーザーがパスワード確認を完了したことを示す
    session(['password_verified' => true]);

    // 確認済みの状態で編集画面にリダイレクト
    return redirect()->route('profiles.edit')
        ->with('password_verified_once', true);
        //with()メソッド:一時的なデータ（フラッシュデータ）をセッションに保存します。このデータは次のリクエストで利用可能で、それ以降は破棄
        //password_verified_once:キー名であり、このキーで一時的なデータにアクセス
        //true:保存する値で、ここでは「パスワード確認済みであること」を示すフラグとして trueを設定
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
        //hasFile:新しい画像がアップロードされた場合のみ処理を行います
        if ($user->profile_image) {
            Storage::delete('public/' . $user->profile_image);
            //ユーザーに既存の画像がある場合、それを削除
        }

        // 新しい画像を保存
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $path;
        //新しい画像を保存し、そのパスをユーザーのprofile_imageフィールドに設定
        //store()メソッド:ファイルを指定したディスク（ストレージ）に保存。引数で保存先ディレクトリとストレージディスクを指定
        //保存されたファイルのパスを、$userオブジェクトのprofile_imageプロパティに代入
      }
        $user->name = $request->name;
        $user->bio = $request->bio;

        if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
        //パスワードが入力されている場合のみ処理
        //bcrypt:パスワードをハッシュ化して保存
    }
        $user->save();

        session(['password_verified' => false]);
        //プロフィール更新後、password_verifiedフラグをリセット。これにより次回編集時には再度パスワード確認が必要
        //logger:更新後のセッション内容をログに記録

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
