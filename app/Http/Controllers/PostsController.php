<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Support\Facades\DB;
//データベースからデータの取得、挿入のために使用
use Illuminate\Support\Facades\Auth;
//認証システムへのアクセス
use App\Models\Post;
//postテーブルに対する操作

class PostsController extends Controller
//HTTPリクエストに対してどのような処理を行うかを定義
{
  public function __construct()
  {
    $this->middleware('auth');
    //middleware('auth') を呼び出し、このコントローラーのすべてのアクションに対してauthミドルウェアが適用され、ログインしているユーザーだけがアクセスできるようになる
    //ミドルウェアを適用とは、HTTPリクエストがコントローラーのアクションに到達する前に、リクエストに対して特定の処理を実行することを意味
  }


  public function index()
  {
    $list = DB::table('posts')
      //postsテーブルのuser_idカラムと、usersテーブルのuser_idカラムを結合し、投稿したユーザー情報を取得
      ->join('users', 'posts.user_id', '=', 'users.user_id')
      //postsテーブルのすべてのカラム（posts.*）を取得。usersテーブルのnameとprofile_imageカラムも取得。as user_nameは、users.nameをuser_nameという別名で取得し、ビューで区別。
      ->select('posts.*', 'users.name as user_name', 'users.profile_image')
      //投稿を作成日時（created_at）の降順に並べ替え、最新の投稿を最初に表示。
      ->orderBy('posts.created_at', 'desc')
      //DB::table('posts')で、postsテーブルのデータを指定し、->get();でデータを取得
      ->get();

    $user = Auth::user();

    //postsディレクトリの中にあるindex.blade.phpを呼び出す
    return view('posts.index', [
      //コントローラからビューへ値を渡す。投稿一覧データ（$list）変数を"lists"という名前でビューに渡す。
      'lists' => $list,
      // ログインしているユーザー情報をビューに渡す。
      'user' => $user
    ]);
  }


  public function createForm()
  {
    return view('posts.createForm');
  }

  public function create(Request $request)
  {
    //newPostフィールドが以下の条件を満たしているかチェック
    $request->validate([
      'newPost' => [
        'required',  //入力必須
        'string',  //文字列
        'max:100',  //100文字以内
        'regex:/\S+/',  // スペースのみの入力を無効
      ],
    ], [
      'contents.required' => '投稿内容は必須項目です。',
      'contents.max' => '投稿内容は100文字以内で入力してください。',
      'upPost.regex' => '投稿内容には空白以外の文字を含めてください。',
    ]);
    //ユーザーが入力した投稿内容を取得、取得されたデータは $post変数に格納
    $post = $request->input('newPost');

    //取得した投稿内容が、データベースのpostsテーブルに挿入
    DB::table('posts')->insert([
      'contents' => $post,
      'user_name' => Auth::user()->name,
      'user_id' => Auth::id(),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    return redirect('/index');
  }


  //$idは、編集したい特定の投稿を識別するためのid
  public function updateForm($id)
  {
    $post = DB::table('posts')
      ->where('id', $id)
       //指定されたidを持つ投稿を検索
      ->first();  //idが一致する投稿が1件だけ取得
    return view('posts.updateForm', ['post' => $post]);
    //updateFormへ。ビュー内でpostという変数を使って、特定の投稿のデータにアクセス
  }


  public function update(Request $request)
  {
    $id = $request->input('id');

    //upPostフィールドが以下の条件を満たしているかチェック
    $request->validate([
      'upPost' => [
        'required',  //入力必須
        'string',  //文字列
        'max:100',  //100文字以内
        'regex:/\S+/',  // スペースのみの入力を無効
      ],
    ], [
      'upPost.required' => '投稿内容は必須項目です。',
      'upPost.max' => '投稿内容は100文字以内で入力してください。',
      'upPost.regex' => '投稿内容には空白以外の文字を含めてください。',
    ]);

    //ユーザーが入力した投稿内容を取得、取得されたデータは $up_post変数に格納
    $up_post = $request->input('upPost');

    $post = DB::table('posts')->where('id', $id)->first();

    if (!$post || $post->user_id !== Auth::id()) {  // ログインユーザーが投稿の作成者であるか確認
      abort(403, 'Unauthorized action.');  // 権限がない場合は403エラー
    }

    DB::table('posts')
      ->where('id', $id)  //指定されたidを持つ投稿を検索
      ->update(
        ['contents' => $up_post]
      );  //contentsカラムが、フォームで入力した新しい内容（$up_post）に更新される
    return redirect('/index');
  }


  public function delete($id)
  {
    //idカラムが$idに一致する投稿を検索、1件のレコードを取得し、このレコードが$postという変数に格納
    $post = DB::table('posts')->where('id', $id)->first();
    //ログインユーザーが投稿の作成者であるか確認
    if ($post->user_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
      //権限がない場合は403エラー
    }

    DB::table('posts')
      ->where('id', $id)  //指定されたidを持つ投稿を検索
      ->delete();
    return redirect('/index');
  }


  public function search(Request $request)
  {
    //フォームから送信された検索キーワードを取得
    $keyword = $request->input('keyword');
    // キーワードが入力されている場合のみ部分一致検索を実行。contentsカラムの値が検索キーワードを含むレコードをすべて取得
    $lists = Post::where('contents', 'LIKE', '%' . $keyword . '%')->get();
    $user = Auth::user();

    // 検索結果が空かどうか確認し、該当する投稿がない場合はposts.indexビューを表示し、messageという変数に「検索結果は0件です。」というメッセージを渡す
    if ($lists->isEmpty()) {
      return view('posts.index', ['message' => '検索結果は0件です。',
      'user' => $user
    ]);
    }

    // 検索結果が存在する場合、リスト（$lists）を渡す
    return view('posts.index', ['lists' => $lists,
    'user' => $user
  ]);
  }
}
