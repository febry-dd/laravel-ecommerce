@extends('layouts.store', ['title' => 'Katalog produk'])

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Temukan pilihanmu</p>
            <h1>Katalog produk</h1>
            <p class="intro-copy">Cari produk berdasarkan nama atau jelajahi pilihan dari kategori yang tersedia.</p>
        </div>
        <p class="product-stock">{{ $products->total() }} produk ditemukan</p>
    </div>

    <form class="filter-bar" action="{{ route('products.index') }}" method="GET">
        <div class="field">
            <label for="search">Nama atau deskripsi produk</label>
            <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Contoh: kemeja linen">
        </div>
        <div class="field">
            <label for="category">Kategori</label>
            <select id="category" name="category">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Tampilkan hasil</button>
    </form>

    <div class="product-grid">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state">
                <h2>Produk belum ditemukan</h2>
                <p>Coba kata kunci lain atau hapus filter kategori.</p>
                <a class="button button-outline" href="{{ route('products.index') }}">Atur ulang pencarian</a>
            </div>
        @endforelse
    </div>

    <div class="pagination">{{ $products->onEachSide(1)->links() }}</div>
@endsection