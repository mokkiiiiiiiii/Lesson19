<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // Primary Key を 'user_id' に指定

    /**
     * Mass-assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Hidden attributes.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

<<<<<<< HEAD
    /**
     * ユーザーがフォローしているユーザーたちとのリレーション
     */
    public function followees()
    {
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id')
                ->distinct(); // 重複排除
    }


    /**
     * ユーザーをフォローしているユーザーたちとのリレーション
     */
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'followee_id',
            'follower_id'
        );
    }

    /**
     * ユーザーをフォローする
     */
    public function follow(User $user)
    {
        if ($this->user_id !== $user->user_id && !$this->isFollowing($user)) {
            $this->followees()->attach($user->user_id);
        }
    }

    /**
     * フォローを解除する
     */
    public function unfollow(User $user)
    {
        if ($this->isFollowing($user)) {
            $this->followees()->detach($user->user_id);
        }
    }

    /**
     * 指定ユーザーをフォローしているか確認
     */
    public function isFollowing(User $user)
    {
        return $this->followees()->where('followee_id', $user->user_id)->exists();
    }
=======

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    //     ProfileモデルでUserモデルを参照させる為に記述。
    //     hasOneは「一対一」のリレーションシップを示し、1つのユーザーが1つのプロフィールを持つことを意味する。
    //     $profile->user でユーザーにアクセス
    // 　　$user->profile でプロフィールにアクセス
    // 別でProfileを作ってしまった時の誤りなので、ここが邪魔をするなら後に削除

>>>>>>> bf17d81fbee40860027e5717cb32a7542621ca98
}
