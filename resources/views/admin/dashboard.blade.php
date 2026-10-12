@extends('layouts.admin', ['title' => 'Ringkasan'])

@section('content')
    <div class="admin-titlebar">
        <div><p class="eyebrow">Ikhtisar toko</p><h1>Ringkasan</h1></div>
        <a class="button" href="{{ route('admin.products.create') }}">Tambah produk</a>
    </div>
    <div class="stats-grid">
        <article class="stat-tile"><span>Total produk</span><strong>{{ $productCount }}</strong></article>
        <article class="stat-tile"><span>Total pesanan</span><strong>{{ $orderCount }}</strong></article>
        <article class="stat-tile"><span>Menunggu diproses</span><strong>{{ $pendingOrderCount }}</strong></article>
    </div>
    <section class="panel">
        <div class="panel-head"><h2>Pesanan terbaru</h2><a href="{{ route('admin.orders.index') }}">Semua pesanan</a></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}<br><span class="product-stock">{{ $order->customer_email }}</span></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="status-pill is-{{ $order->status }}">{{ $order->status }}</span></td>
                            <td><a class="button button-outline button-small" href="{{ route('admin.orders.show', $order) }}">Buka</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="empty-state"><h2>Belum ada pesanan</h2><p>Pesanan pelanggan akan muncul di sini.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection