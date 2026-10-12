
@extends('layouts.store', ['title' => 'Daftar produk'])

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Luma Market</p>
            <h1>Daftar produk</h1>
        </div>
        <a class="button button-outline" href="{{ route('home') }}">Kembali ke beranda</a>
    </div>
    <div class="product-grid">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <div class="empty-state"><h2>Belum ada produk</h2><p>Silakan kembali lagi nanti.</p></div>
        @endforelse
    </div>
@endsection
