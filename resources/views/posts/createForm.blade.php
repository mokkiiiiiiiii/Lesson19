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
<h2 class='page-header'>新しく投稿する</h2>

{!! Form::open(['url' => 'post/create']) !!}
<!-- urlが'post/create'となっているところにフォームの値を送る設定 -->

<div class="form-group">
{!! Form::input('text', 'newPost', null, ['required', 'class' => 'form-control', 'placeholder' => '投稿内容']) !!}
</div>
<!-- text...入力フィールドのタイプ
     newPost...入力フィールドのname属性
     null...初期値は設定していない
     required...入力必須を意味。空欄ではないか検証
     class...inputタグに適用されるcssクラス
     placeholder...入力フィールドが空のときに表示されるガイドテキストを指定-->

<button type="submit" class="btn btn-success pull-right">投稿する</button>

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
