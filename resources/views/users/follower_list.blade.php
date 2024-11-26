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
        <button type="submit" class="btn btn-success btn-sm">フォローリスト</button>
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
                        <img src="{{ $follower->profile_image ? asset('storage/' . $follower->profile_image) : asset('images/default-icon.png') }}"
                        alt=""
                        style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                        <a href="{{ route('profiles.show', $follower->user_id) }}" class="no-underline">
                            {{ $follower->name }}
                        </a>
                      </td>
                    <td>{{ $follower->email }}</td>
                    <td>
              {{-- フォロー済みか判定 --}}
              @if (!in_array($follower->user_id, $followedUserIds))
                  {{-- フォローボタン --}}
                  <form action="{{ route('follow', ['user' => $follower->user_id]) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-primary btn-sm">フォローする</button>
                  </form>
              @else
                  {{-- フォロー解除ボタン --}}
                  <form action="{{ route('unfollow', ['user' => $follower->user_id]) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-danger btn-sm">フォロー解除</button>
                  </form>
              @endif
          </td>
                </tr>
          @endforeach
        </tbody>
</div>
@endsection
</body>
</html>
