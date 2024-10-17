@extends('layouts.app')

@section('content')
<div class="container">
  <h1>{{ $profile->user->name }}のプロフィール</h1>
  <img src="{{ asset('storage/' . $profile->avatar) }}" alt="アバター" width="100">
  <p>{{ $profile->bio }}</p>
</div>

@endsection
