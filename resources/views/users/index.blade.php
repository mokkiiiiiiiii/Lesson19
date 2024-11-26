<!DOCTYPE html>
<html>
  <body>
    @extends('layouts.app')

    @section('content')
    <div class="container">

      <form action="{{ route('users.search') }}" method="GET">
        <input type="text" name="keyword" placeholder="検索キーワード" value="{{ request('keyword') }}">
        <button type="submit">検索</button>
      </form>

     <div class="mb-3">
      <form action="{{ route('follow.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-success btn-sm">フォローリスト</button>
      </form>

      <form action="{{ route('follower.list') }}" method="GET" style="display: inline;">
        <button type="submit" class="btn btn-primary btn-sm">フォロワーリスト</button>
      </form>
     </div>

     <h2>ユーザーリスト</h2>
     @if (isset($message))
        <p>{{ $message }}</p>
     @else
        <table class='table table-hover'>
            <thead>
              <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>フォロー</th>
             </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              @if (Auth::check() && Auth::id() !== $user->user_id)
              <tr>
                <td style="display: flex; align-items: center;">
              <!-- プロフィール画像を追加 -->
                  <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-icon.png') }}"
                  alt=""
                  style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <a href="{{ route('profiles.show', $user->user_id) }}" class="no-underline">
                        {{ $user->name }}
                </td>
                <td>{{ $user->email }}</td>
                <td>
                  @if (!Auth::user()->isFollowing($user))
                  <form action="{{ route('follow', ['user' => $user->user_id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                    <button type="submit" class="btn btn-primary btn-sm">フォロー</button>
                  </form>
                  @else
                  <span class="text-muted">フォロー済み</span>
                  <td>
                    <form action="{{ route('unfollow', ['user' => $user->user_id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">解除</button>
                    </form>
                  </td>
                  @endif
                </td>
              </tr>
              @endif
              @endforeach
            </tbody>
        </table>
      @endif
    </div>
    @endsection
  </body>
</html>
