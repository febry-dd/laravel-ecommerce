@extends('layouts.store', ['title' => 'Pesanan #'.$order->id])

@section('content')
    <div class="page-heading"><div><p class="eyebrow">Detail transaksi · {{ $order->created_at->format('d M Y') }}</p><h1>Pesanan #{{ $order->id }}</h1></div><span class="status-pill is-{{ $order->status }}">{{ $order->status }}</span></div>
    <div class="detail-grid">
        <section class="panel">
            <div class="panel-head"><h2>Ringkasan barang</h2><span class="status-pill is-{{ $order->payment_status }}">Pembayaran {{ $order->payment_status }}</span></div>
            <div style="padding: 8px 20px 20px">
                <table class="order-items"><tbody>
                    @foreach ($order->items as $item)
                        <tr><td>{{ $item->product_name }} <span class="product-stock">× {{ $item->quantity }}</span></td><td>Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}</td></tr>
                    @endforeach
                </tbody></table>
                <div class="summary-total"><span>Total pesanan</span><span>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</span></div>
            </div>
        </section>
        <aside class="detail-panel"><h2>Pengiriman</h2><p class="address-copy">{{ $order->shipping_address }}</p><div class="summary-line"><span>Metode pembayaran</span><strong>{{ str_replace('_', ' ', $order->payment_method) }}</strong></div></aside>
    </div>
    <a class="back-link" href="{{ route('account.orders.index') }}">← Kembali ke pesanan saya</a>
@endsection