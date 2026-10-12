@extends('layouts.store', ['title' => 'Pesanan saya'])

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Akun kamu</p><h1>Pesanan saya</h1><p class="intro-copy">Pantau status pesanan dan detail belanjamu.</p></div><a class="button button-outline" href="{{ route('products.index') }}">Lanjut belanja</a></div>
    <div class="cart-list">
        @forelse ($orders as $order)
            <article class="cart-row">
                <div class="brand-mark" aria-hidden="true">#</div>
                <div><h2><a href="{{ route('account.orders.show', $order) }}">Pesanan #{{ $order->id }}</a></h2><p>{{ $order->created_at->format('d M Y') }} · {{ $order->payment_status }}</p></div>
                <span class="status-pill is-{{ $order->status }}">{{ $order->status }}</span>
                <p class="row-subtotal">Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</p>
            </article>
        @empty
            <div class="empty-state"><h2>Belum ada pesanan</h2><p>Pesanan yang kamu buat akan muncul di sini.</p><a class="button" href="{{ route('products.index') }}">Jelajahi produk</a></div>
        @endforelse
    </div>
    <div class="pagination">{{ $orders->onEachSide(1)->links() }}</div>
@endsection