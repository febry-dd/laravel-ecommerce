
@extends('layouts.store', ['title' => 'Belanja mudah setiap hari'])

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <p class="eyebrow">Pilihan sehari-hari, terasa lebih dekat</p>
            <h1>Temukan barang yang pas untuk harimu.</h1>
            <p>Jelajahi pilihan sederhana untuk rumah, gaya, dan rutinitas. Temukan yang kamu suka, lalu kami siapkan dengan rapi.</p>
            <div class="hero-actions">
                <a class="button button-light" href="{{ route('products.index') }}">Mulai jelajah</a>
                <a class="button button-quiet" style="color:white" href="{{ route('cart') }}">Lihat keranjang</a>
            </div>
        </div>
        <aside class="hero-note">
            <strong>Belanja tanpa ribet</strong>
            <p>Pilihan produk, harga, dan stok yang jelas sebelum kamu checkout.</p>
        </aside>
    </section>

    <section aria-labelledby="featured-title">
        <div class="section-head">
            <div>
                <p class="eyebrow">Baru ditambahkan</p>
                <h2 id="featured-title">Pilihan terbaru</h2>
                <p>Temukan sesuatu yang menarik untuk dibawa pulang.</p>
            </div>
            <a class="button button-outline" href="{{ route('products.index') }}">Lihat semua produk</a>
        </div>
        <div class="product-grid">
            @forelse ($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <div class="empty-state">
                    <h2>Etalase sedang disiapkan</h2>
                    <p>Produk baru akan muncul di sini. Coba lihat lagi sebentar nanti.</p>
                    <a class="button" href="{{ route('products.index') }}">Jelajahi katalog</a>
                </div>
            @endforelse
        </div>
    </section>

    <section class="notice-band">
        <p><strong>Sudah tahu yang dicari?</strong> Cari nama atau pilih kategori di katalog.</p>
        <a class="button button-outline" href="{{ route('products.index') }}">Cari produk</a>
    </section>
@endsection
