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

    <form action="{{ route('follower.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-primary btn-sm">フォロワーリスト</button>
    </form>
  </div>

    <h2>フォローリスト</h2>
    <table class='table table-hover'>
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($followees as $followee)
                <tr>
                    <td>{{ $followee->name }}</td>
                    <td>{{ $followee->email }}</td>
                    <td>
                    <form action="{{ route('unfollow', ['user' => $followee->user_id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">フォロー解除</button>
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
</body>
</html>
