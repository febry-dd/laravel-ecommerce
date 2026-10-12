@extends('layouts.admin', ['title' => 'Pesanan'])

@section('content')
    <div class="admin-titlebar">
        <div><p class="eyebrow">Kelola penjualan</p><h1>Pesanan</h1></div>
        <a class="button button-outline" href="{{ route('admin.dashboard') }}">Ringkasan</a>
    </div>
    <section class="panel">
        <div class="panel-head"><h2>{{ $orders->total() }} pesanan</h2></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead><tr><th>Nomor</th><th>Pelanggan</th><th>Dibuat</th><th>Total</th><th>Status</th><th>Pembayaran</th><th></th></tr></thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}<br><span class="product-stock">{{ $order->customer_email }}</span></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                            <td><span class="status-pill is-{{ $order->status }}">{{ $order->status }}</span></td>
                            <td><span class="status-pill is-{{ $order->payment_status }}">{{ $order->payment_status }}</span></td>
                            <td><a class="button button-outline button-small" href="{{ route('admin.orders.show', $order) }}">Kelola</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state"><h2>Belum ada pesanan</h2><p>Pesanan masuk akan tampil di sini.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="pagination">{{ $orders->onEachSide(1)->links() }}</div>
@endsection