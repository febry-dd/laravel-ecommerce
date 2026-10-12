<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7f3">
    <title>{{ $title ?? 'Luma Market' }} · Luma Market</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php($cartCount = collect(session('cart', []))->sum('quantity'))
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('home') }}" aria-label="Luma Market, beranda">
                <span class="brand-mark" aria-hidden="true">L</span>
                <span>Luma Market</span>
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
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark" aria-hidden="true">L</span><span>Luma Market</span></a>
            <p>Belanja sederhana, pilihan yang terasa pas.</p>
            <a href="{{ route('products.index') }}">Jelajahi produk</a>
        </div>
    </footer>
</body>
</html>