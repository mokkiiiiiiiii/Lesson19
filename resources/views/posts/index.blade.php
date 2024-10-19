<!DOCTYPE html>

<html>
  <body>
  @extends('layouts.app')
  <!-- 親テンプレートとして継承。複数のページで共通のレイアウト（layouts/app.blade.php） -->
  @section('content')
  <!-- app.balde.php内の@yield('content')で指定されている箇所に、設定したコードを反映 -->
    <div class='container'>
      <!-- /create-formへ -->
      <p class="pull-right">
        <a class="btn btn-success" href="/create-form">投稿する</a>
      </p>
      <p class="pull-right">
        <a class="btn btn-primary" href="{{ route('users.index') }}">ユーザー検索</a>
      </p>

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
        <td><a class="btn btn-primary" href="/post/{{ $list->id }}/update-form">編集</a></td>
        <!-- $list->idが投稿id -->

        <td><a class="btn btn-danger" href="/post/{{ $list->id }}/delete"
            onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a></td>
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
