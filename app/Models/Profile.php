<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// 作成手順に謝りがあったので関係なし。後に削除

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['bio', 'avatar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
//ユーザーのプロフィール情報管理する為に作成。
// belongsToはEloquentを使用して、ProfileのモデルがUserモデルに「従属している」＝　Prfolieモデルのデータベーステーブルに、UserテーブルのIDを保存する**外部キー（通常は user_id）**があり、ProfileはUserに属するという構造。なお、相互参照させる為にUserモデルにも追加記述有り。

    //     $profile->user でユーザーにアクセス
    // 　　$user->profile でプロフィールにアクセス
