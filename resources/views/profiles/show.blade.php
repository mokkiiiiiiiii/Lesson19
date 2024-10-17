@extends('layouts.app')

@section('content')
<div class="container">
  <h1>{{ $profile ? $profile->user->name . 'のプロフィール' : 'プロフィールが見つかりません' }}</h1>

  <!-- 三項演算子を使用して、条件に基づいて値を選択させる。「if」の簡略表現。
条件 ?　真の場合の値　：　偽の値の場合;
  $profile が存在する場合はそのユーザー名と「のプロフィール」を表示し、存在しない場合は「プロフィールが見つかりません」と表示させる -->


  @if($profile)
  <img src="{{ asset('storage/' . $profile->avatar) }}" alt="アバター" width="100">
  <p>{{ $profile->bio }}</p>
  @else
  <p>プロフィール情報が登録されていません</p>
  @endif
</div>

@endsection
