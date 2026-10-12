@extends('layouts.store', ['title' => 'Masuk'])

@section('content')
    <div class="auth-shell">
        <section class="auth-panel">
            <p class="eyebrow">Selamat datang kembali</p>
            <h1>Masuk ke akunmu</h1>
            <p>Lanjutkan belanja dan pantau pesananmu.</p>
            @if ($errors->any())<ul class="error-list" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></div>
                <div class="field"><label for="password">Kata sandi</label><input id="password" type="password" name="password" autocomplete="current-password" required></div>
                <label style="display:flex;align-items:center;gap:9px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button type="submit">Masuk</button>
            </form>
            <div class="auth-links"><span>Belum punya akun?</span><a href="{{ route('register') }}">Daftar sekarang</a></div>
        </section>
    </div>
@endsection