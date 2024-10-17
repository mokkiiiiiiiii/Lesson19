<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seederする前にユーザー登録を行ってしまったので、ユーザーが既に存在しているのを前提にユーザーを取得
        $user = User::first();

        if ($user) {
            Profile::create([
                'user_id' => $user->id,
                'username' => 'sample_username',
                'bio' => 'ユーザーの自己紹介',
                'avatar' => ''
            ]);
        } else {
            echo 'ユーザーが存在しません';
        }
    }
}
