<!DOCTYPE html>

<html>



<body>
  @extends('layouts.app')
  <!-- 親テンプレートとして継承。複数のページで共通のレイアウト（layouts/app.blade.php） -->
  @section('content')
  <!-- app.balde.php内の@yield('content')で指定されている箇所に、設定したコードを反映 -->

  <div class='container'>
    <div class="profile">
      <h2>
        <p>{{ $user->name }}</p>
      </h2>
      <a class="btn btn-success" href="{{ route( 'profile' )}}">プロフィール</a>

    </div>
    <div class="main">

      <!-- /create-formへ -->
      <p class="pull-right"><a class="btn btn-success" href="/create-form">投稿する</a></p>

      <div class='container'>
        <!-- /create-formへ -->
        <div class="d-flex justify-content-end mb-2">
          <form action="{{ route('users.index') }}" method="GET" style="display: inline;">
            <button type="submit" class="btn btn-primary me-2">ユーザー検索</button>
          </form>
          <form action="/create-form" method="GET" style="display: inline;">
            <button type="submit" class="btn btn-success me-2">投稿する</button>
          </form>
        </div>

        <!-- フォームが送信される際に、リクエストが送信されるURLをposts.searchに指定 -->
        <!-- name="keyword"は、フォームが送信されたときにサーバー側でこのフィールドの値を識別するため -->
        <!-- placeholder="検索キーワード"は、入力フィールドが空のときに表示されるガイドテキストを指定 -->
        <!-- value="{{ request('keyword')}}"は、ユーザーが入力した検索キーワードがフォームに残った状態になる -->
        <form action="{{ route('posts.search') }}" method="GET">
          <input type="text" name="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}">
          <button type="submit">検索</button>
        </form>

        <h2 class='page-header'>投稿一覧</h2>
        @if (isset($message))
        <p>{{ $message }}</p>
        <!-- 検索結果が何もない場合、投稿のリストは表示されず、メッセージが表示される -->
        @else
        <table class='table table-hover'>
          <tr>
            <th>名前</th>
            <th>投稿内容</th>
            <th>投稿日時</th>
          </tr>
          @foreach ($lists as $list)
          <!-- ループの作成。$listsでコントローラーからの投稿リストを保持し、$listでここの投稿の情報にアクセス -->
          <tr>
            <td>{{ $list->user_name }}</td>
            <td>{{ $list->contents }}</td>
            <td>{{ $list->created_at }}</td>
            <!-- 個々の情報の表示 -->

            @if ($list->user_id === Auth::id())
            <!-- ログインユーザーが投稿の所有者の場合のみ、更新・削除ボタンを表示。$list->idでどのユーザーの情報か判断。Auth::id()で現在ログインしているユーザーのidを取得し、識別 -->
            <td>
              <form action="/post/{{ $list->id }}/update-form" method="GET" style="display: inline;">
                <button type="submit" class="btn btn-primary">編集</button>
              </form>
            </td>
            <!-- $list->idが投稿id -->

            <td>
              <form action="/post/{{ $list->id }}/delete" method="POST" style="display: inline;"
                onsubmit="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">
                @csrf
                <button type="submit" class="btn btn-danger">削除</button>
              </form>
            </td>
            <!-- confirm()でユーザーに確認ダイアログが表示される。ユーザーがOKをクリックすると削除が実行され、「キャンセル」をクリックすると削除が中止 -->
            @endif
          </tr>
          @endforeach
        </table>
        @endif
      </div>
      @endsection
</body>

</html>
