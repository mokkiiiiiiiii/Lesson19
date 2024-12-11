@extends('layouts.app')

@section('content')
<div class="container">
    <h1>パスワード確認</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('profile.verify.password.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">現在のパスワード</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">確認する</button>
    </form>
</div>
@endsection
