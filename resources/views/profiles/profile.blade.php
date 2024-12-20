@extends('layouts.app')

@section('content')

<div class="container">


  <h1>プロフィール</h1>
  <form action="{{ route('profiles.edit') }}" method="GET" style="display: inline;">
    <button type="submit" class="btn btn-secondary btn-sm">プロフィール編集</button>
  </form>
  <p>名前: {{ $user->name }}</p>
  <p>自己紹介: {{ $user->bio }}</p>

  <!-- 画像表示を修正する。 -->
  <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-icon.png') }}" alt="アイコン" width="100" height="100">

  <div class="mb-3">
    <!-- フォローリストの表示 -->
    <form action="{{ route('follow.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-success btn-sm">フォローリスト</button>
    </form>

    <!-- フォロワーリストの表示 -->
    <form action="{{ route('follower.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-primary btn-sm">フォロワーリスト</button>
    </form>
  </div>

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
      <td>
        <form action="/post/{{ $list->id }}/update-form" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-primary">編集</button>
        </form>
      </td>
      <td>
        <form action="{{ route('post.delete', ['id' => $list->id]) }}" method="POST" style="display: inline;"
        onsubmit="return confirm('こちらの投稿を削除してもよろしいでしょうか？')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">削除</button>
        </form>
      </td>
      @endif
    </tr>
    @endforeach
  </table>
</div>
@endsection
