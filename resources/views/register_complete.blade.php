<!-- resources/views/register_complete.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success" role="alert">
        <h2 class="alert-heading">登録完了</h2>
        <p>ユーザー新規登録が完了しました！</p>
        <!-- 登録したユーザーの名前を表示 -->
        <p>ようこそ、{{ $user->name }} さん！</p>
        <p class="mb-0"><a href="{{ route('login') }}" class="btn btn-primary">ログイン画面</a></p>
    </div>
</div>
@endsection
