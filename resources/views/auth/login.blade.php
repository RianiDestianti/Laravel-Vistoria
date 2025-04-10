@extends('layouts.layout')

@section('content')
<h2>Login</h2>
<form action="{{ route('login') }}" method="post">
    @csrf
    <label>Email Address</label>
    <input type="text" name="email" class="form-control mb-2">
    <label>Password</label>
    <input type="password" name="password" class="form-control mb-2">

    <div class="form-check mb-2">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button class="btn btn-primary">Submit Login</button>
</form>

<a href="{{ route('register') }}">Belum punya akun? Daftar</a>
@endsection
