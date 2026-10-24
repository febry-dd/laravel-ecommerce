<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f7fb">
    <title>{{ $title ?? config('app.name') }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php($cartCount = collect(session('cart', []))->sum('quantity'))
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="{{ config('app.name') }}, beranda">
                <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 8.5h16l-1.25 12h-13.5L4 8.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 9V6a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>
                <span>{{ config('app.name') }}</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
                <a href="{{ route('products.index') }}" @if (request()->routeIs('products.*')) aria-current="page" @endif>Produk</a>
                @auth
                    <a href="{{ route('account.orders.index') }}" @if (request()->routeIs('account.orders.*')) aria-current="page" @endif>Pesanan saya</a>
                @endauth
            </nav>
            <div class="nav-actions">
                <a class="cart-link" href="{{ route('cart') }}" aria-label="Keranjang, {{ $cartCount }} item">
                    Keranjang <span class="cart-count">{{ $cartCount }}</span>
                </a>
                @auth
                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline-form">
                        @csrf
                        <button class="nav-logout" type="submit">Keluar</button>
                    </form>
                @else
                    <a class="button button-small" href="{{ route('login') }}">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main-content" class="page-wrap">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 8.5h16l-1.25 12h-13.5L4 8.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 9V6a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span>{{ config('app.name') }}</span></a>
            <p>Belanja sederhana, pilihan yang terasa pas.</p>
            <a href="{{ route('products.index') }}">Jelajahi produk</a>
        </div>
    </footer>
    @if (session('error'))
        <dialog id="access-denied-dialog" class="access-denied-dialog" aria-labelledby="access-denied-title" aria-describedby="access-denied-message">
            <div class="access-denied-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3 20 6v5c0 5.1-3.4 8.6-8 10-4.6-1.4-8-4.9-8-10V6l8-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M12 8v4m0 3h.01" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                </svg>
            </div>
            <h2 id="access-denied-title">Access restricted</h2>
            <p id="access-denied-message">{{ session('error') }}</p>
            <form method="dialog">
                <button class="access-denied-confirm" type="submit" autofocus>Okay, got it</button>
            </form>
        </dialog>
        <script>
            document.getElementById('access-denied-dialog').showModal();
        </script>
    @endif
</body>
</html>