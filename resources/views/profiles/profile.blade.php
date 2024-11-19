@extends('layouts.app')

@section('content')

<div class="container">


  <h1>プロフィール</h1>
  <a class="btn btn-success" href="{{ route( 'profiles.edit' )}}">プロフィール編集</a></p>
  <p>名前: {{ $user->name }}</p>
  <p>自己紹介: {{ $user->bio }}</p>

  <!-- 画像表示を修正する。 -->
  <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-icon.png') }}" alt="アイコン" width="100" height="100">
  <br>
  <a class="btn btn-success" href="{{ route( 'users.follow_list' )}}">フォローリスト</a> <a class="btn btn-success" href="{{ route( 'follower.list' )}}">フォロワーリスト</a></p>

  <h2>投稿一覧</h2>
  <table class='table table-hover'>
    <tr>
      <th>名前</th>
      <th>投稿内容</th>
      <th>投稿日時</th>
    </tr>
    @foreach ($lists as $list)
    <tr>
      <td>{{ $list->user_name }}</td>
      <td>{{ $list->contents }}</td>
      <td>{{ $list->created_at }}</td>
      @if ($list->user_id === Auth::id())
      <td><a class="btn btn-primary" href="/post/{{ $list->id }}/update-form">編集</a></td>
      <td><a class="btn btn-danger" href="/post/{{ $list->id }}/delete"
          onclick="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">削除</a></td>
      @endif
    </tr>
    @endforeach
  </table>
</div>












@endsection
