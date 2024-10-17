<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用

use App\Models\Profile;
//ProfileModelにアクセス

use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス


// 不要なProfileテーブルを作成してしまった対のコントローラー。後に削除


class ProfileController extends Controller
{
    //
    public function show()
    {
        $user = auth()->id();

        $profile = Profile::where('user_id', $user)->first();

        // if (!$profile) {
        //     abort(404, 'プロフィールが見つかりません');
        // }

        return view('profiles.show', compact('profile'));
    }
}
