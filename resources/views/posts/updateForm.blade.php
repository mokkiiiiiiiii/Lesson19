<!DOCTYPE html>

<html>
  <head>
    <meta charset='utf-8"'>
    <link rel='stylesheet' href='/css/app.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body>
    @extends('layouts.app')
    <!-- 親テンプレートとして継承。複数のページで共通のレイアウト（layouts/app.blade.php） -->
    @section('content')
    <!-- app.balde.php内の@yield('content')で指定されている箇所に、設定したコードを反映 -->

    <div class='container'>
      <h2 class='page-header'>投稿内容を変更する</h2>
      <!-- post/updateにデータ送信 -->
      {!! Form::open(['url' => '/post/update']) !!}
      <div class="form-group">
        {!! Form::hidden('id', $post->id) !!}
        <!-- 現在編集している投稿のidが取得され、その値が隠しフィールドに設定される。このidは、サーバー側でどの投稿を更新するかを判断するために使用 -->
        {!! Form::input('text', 'upPost', $post->contents, ['required', 'class' => 'form-control']) !!}
        <!-- text...入力フィールドのタイプ
             upPost...入力フィールドのname属性
             required...入力必須を意味。空欄ではないか検証
             class...inputタグに適用されるcssクラス -->
      </div>

      <button type="submit" class="btn btn-primary pull-right">更新</button>
      {!! Form::close() !!}
    </div>

    <footer>
      <small>Laravel@crud.curriculum</small>
    </footer>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    @endsection
  </body>
</html>
