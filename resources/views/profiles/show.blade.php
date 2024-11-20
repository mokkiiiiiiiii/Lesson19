

@extends('layouts.app')

@section('content')

<div class="container">
    <!-- プロフィール表示 -->
    <h1>{{ $user->name }}さんのプロフィール</h1>

    <!-- 自己紹介 -->
    <p>自己紹介: {{ $user->bio }}</p>

    <!-- プロフィール画像 -->
    <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-icon.png') }}"
         alt="プロフィール画像" width="100" height="100">

    <!-- 投稿一覧 -->
    <h2>投稿一覧</h2>
    <table class="table table-hover">
        <tr>
            <th>投稿内容</th>
            <th>投稿日時</th>
        </tr>
        @foreach ($user->posts as $post)
        <tr>
            <td>{{ $post->contents }}</td>
            <td>{{ $post->created_at }}</td>
        </tr>
        @endforeach
    </table>
</div>

@endsection
