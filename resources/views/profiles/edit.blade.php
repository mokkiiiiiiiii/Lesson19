@extends('layouts.app')

@section('content')

<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>

<div class="container">
  <h1>プロフィール編集</h1>

  @if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif

  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="name" class="form-label">名前</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
      @error('name')
      <div class="invalid-feedback">{{ $message }}
      </div>
      @enderror
    </div>

    <div class="mb-3">
  <label for="password" class="form-label">新しいパスワード</label>
  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
  @error('password')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="password_confirmation" class="form-label">新しいパスワード（確認）</label>
  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
</div>

    <div class="mb-3">
      <label for="bio" class="form-label">自己紹介</label>
      <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
      @error('bio')
      <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-3">
        <label for="profile_image" class="form-label">プロフィール画像</label>
        <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
        @error('profile_image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary"><a href="{{ route('profiles.edit') }}"></a>更新する</button>
  </form>
</div>
@endsection
