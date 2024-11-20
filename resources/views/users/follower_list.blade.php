<!DOCTYPE html>
<html>
<head>
    <title>フォローリスト</title>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="mb-3">
    <form action="{{ route('users.index') }}" method="GET" style="display: inline;">
    <button type="submit" class="btn btn-secondary btn-sm">ユーザーリスト</button>
    </form>

    <form action="{{ route('follow.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-secondary btn-sm">フォローリスト</button>
    </form>
  </div>

  <h2>フォロワーリスト</h2>
  <table class='table table-hover'>
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($followers as $follower)
                <tr>
                    <td style="display: flex; align-items: center;">
              <!-- プロフィール画像を追加 -->
                        <img src="{{ $follower->profile_image ? asset('storage/' . $follower->profile_image) : asset('images/default-icon.png') }}"
                        alt=""
                        style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                        <a href="{{ route('profiles.show', $follower->user_id) }}" class="no-underline">
                            {{ $follower->name }}
                        </a>
                      </td>
                    <td>{{ $follower->email }}</td>
                </tr>
          @endforeach
        </tbody>
</div>
@endsection
</body>
</html>
