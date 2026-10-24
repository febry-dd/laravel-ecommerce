<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111827">
    <title>{{ $title ?? 'Admin' }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <header class="site-header admin-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 8.5h16l-1.25 12h-13.5L4 8.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 9V6a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span><span>{{ config('app.name') }} Admin</span></a>
            <nav class="main-nav" aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif>Ringkasan</a>
                <a href="{{ route('admin.categories.index') }}" @if (request()->routeIs('admin.categories.*')) aria-current="page" @endif>Kategori</a>
                <a href="{{ route('admin.products.index') }}" @if (request()->routeIs('admin.products.*')) aria-current="page" @endif>Produk</a>
                <a href="{{ route('admin.orders.index') }}" @if (request()->routeIs('admin.orders.*')) aria-current="page" @endif>Pesanan</a>
            </nav>
            <div class="nav-actions">
                <a class="main-nav" href="{{ route('home') }}" style="color:inherit">Lihat toko</a>
                <form action="{{ route('logout') }}" method="POST" class="inline-form">
                    @csrf
                    <button class="nav-logout" type="submit">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    <main id="main-content" class="admin-wrap">
        @yield('content')
    </main>
    <footer class="site-footer"><div class="footer-inner"><p>{{ config('app.name') }} · Panel pengelolaan</p><a href="{{ route('home') }}">Kembali ke toko</a></div></footer>
</body>
</html>