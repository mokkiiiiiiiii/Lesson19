<!DOCTYPE html>
<html>
<head>
    <title>フォローリスト</title>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>フォローリスト</h2>
    <table class="table">
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
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
</body>
</html>
