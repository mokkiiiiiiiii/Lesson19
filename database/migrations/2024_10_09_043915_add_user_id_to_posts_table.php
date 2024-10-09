<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('posts', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->after('id'); // user_id カラムを追加
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // 外部キー制約を追加
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
        $table->dropForeign(['user_id']); // 外部キー制約を削除
        $table->dropColumn('user_id'); // カラムを削除
    });
    }
}
