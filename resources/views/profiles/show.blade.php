@extends('layouts.app')

@section('content')

<div class="container">
  @if (isset($users) && $users->isNotEmpty())
  @foreach ($users as $user)
  <h2>{{ $user->name }}</h2>
  <p>{{ $user->bio }}</p>







  @endforeach
  @else
  <p>検索結果が見つかりませんでした。</p>
  @endif

</div>

@endsection
