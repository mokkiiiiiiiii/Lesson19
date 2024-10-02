<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//ルーティングに関する設定を管理し、アプリケーションのルートの登録を行う場所
use App\Models\User;
//データベースのusersテーブルと対応しており、ユーザーに関連するデータの操作を行う
use Illuminate\Foundation\Auth\RegistersUsers;
//RegistersUsersトレイトには、新しいユーザーを登録するためのメソッドやリダイレクト処理が含まれる
use Illuminate\Support\Facades\Hash;
//Hashファサードを指し、パスワードのハッシュ化やチェックに使用。登録の際に入力したパスワードをハッシュ化して保存。ハッシュ化されたパスワードは、後でユーザーがログインするときにチェックされる
use Illuminate\Support\Facades\Validator;
//Validatorファサードを指し、データの検証（バリデーション）を行うために使用。フォームに入力したデータ（名前やメールアドレスなど）を検証し、入力が正しいかどうかをチェック
use Illuminate\Http\Request;
//HTTPリクエストクラスの情報を操作、アクセスに使用
use Illuminate\Auth\Events\Registered;
//ユーザーが登録されたときに発生するイベント

class RegisterController extends Controller
//HTTPリクエストに対してどのような処理を行うかを定義
{

     use RegistersUsers;
    //Laravelのユーザー登録に関連する標準的な機能を提供するトレイト

    //ユーザー登録後にどのURLにリダイレクトするか
    protected function redirectTo()
    {
        \Log::info('Redirecting to /login');
        //リダイレクト前にログメッセージを記録
        return '/login';
    }


    //既存のregisterメソッドをオーバーライドして自動ログインを防ぐ
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();//入力されたデータをバリデーション（検証）

        $user = $this->create($request->all());
        //バリデーションが成功すると新しいユーザーが作成され、createメソッドを使用してデータベースに保存

        event(new Registered($user));

        return redirect($this->redirectPath());
        //すぐ上でredirectTo()メソッドでloginに行くよう設定済み
    }
    /**
     * Where to redirect users after registration.
     *
     * @var string
     * このプロパティがstring型の値（つまり文字列）
     */
    //protected $redirectTo = '/login';？


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
         //ログインしていないユーザー（ゲスト）のみがこのコントローラーのアクションにアクセスできるようになる
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *@paramは、このメソッドが受け取る引数を説明するためのタグ。arrayは、引数のデータ型を示す。この場合、$data が配列であることを示す。$dataは、メソッドが受け取る引数の名前。この配列にはユーザーがフォームに入力したデータ（名前、メールアドレス、パスワードなど）が含まれる
     * @return \Illuminate\Contracts\Validation\Validator
     */

     //ユーザーが入力したデータを検証するために使用
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            ], [
            'name.required' => '名前は必須項目です。',
            'name.string' => '有効な名前を入力してください。',
            'name.max' => '名前は最大255文字です。',
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは6文字以上で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

     //バリデーションが成功した後、新しいユーザーをデータベースに作成するために使用
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
