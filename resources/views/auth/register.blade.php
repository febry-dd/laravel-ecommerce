@extends('layouts.store', ['title' => 'Buat akun'])

@section('content')
    <div class="auth-shell">
        <section class="auth-panel">
            <p class="eyebrow">Mulai dari sini</p>
            <h1>Buat akun</h1>
            <p>Simpan pesanan dan belanja lebih mudah di kunjungan berikutnya.</p>
            @if ($errors->any())<ul class="error-list" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="field"><label for="name">Nama</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required></div>
                <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></div>
                <div class="field"><label for="password">Kata sandi</label><input id="password" type="password" name="password" autocomplete="new-password" required></div>
                <div class="field"><label for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required></div>
                <button type="submit">Buat akun</button>
            </form>
            <div class="auth-links"><span>Sudah punya akun?</span><a href="{{ route('login') }}">Masuk</a></div>
        </section>
    </div>
@endsection