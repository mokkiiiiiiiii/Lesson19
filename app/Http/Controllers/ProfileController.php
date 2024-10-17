<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用

use App\Models\Profile;
//ProfileModelにアクセス

use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス



class ProfileController extends Controller
{
    //
    public function show($id)
    {
        $profile = Profile::('user_id', $id) -> firstOrFail();
        return view('profile.show', compact('profile'));
    }
}
