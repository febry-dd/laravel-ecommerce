@extends('layouts.admin', ['title' => 'Pesanan #'.$order->id])

@section('content')
    <div class="admin-titlebar">
        <div><p class="eyebrow">Detail transaksi</p><h1>Pesanan #{{ $order->id }}</h1></div>
        <span class="status-pill is-{{ $order->status }}">{{ $order->status }}</span>
    </div>
    @if (session('success'))<p class="alert-success" role="status">{{ session('success') }}</p>@endif
    @if ($errors->any())<ul class="error-list" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif

    <div class="detail-grid">
        <div class="panel">
            <div class="panel-head"><h2>Item pesanan</h2><span>{{ $order->created_at->format('d M Y, H:i') }}</span></div>
            <div style="padding: 8px 20px 20px">
                <table class="order-items">
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr><td>{{ $item->product_name }} <span class="product-stock">× {{ $item->quantity }}</span></td><td>Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="summary-total"><span>Total pesanan</span><span>Rp{{ number_format((float) $order->total_amount, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="form-panel">
            <h2>Informasi pelanggan</h2>
            <p><strong>{{ $order->customer_name }}</strong><br><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>
            <p class="address-copy">{{ $order->shipping_address }}</p>
            <p class="summary-line"><span>Metode bayar</span><strong>{{ str_replace('_', ' ', $order->payment_method) }}</strong></p>
            <form class="admin-form" action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf @method('PUT')
                <div class="field">
                    <label for="status">Status pesanan</label>
                    <select id="status" name="status">
                        @foreach (['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="payment_status">Status pembayaran</label>
                    <select id="payment_status" name="payment_status">
                        @foreach (['pending', 'paid', 'failed', 'refunded'] as $status)
                            <option value="{{ $status }}" @selected(old('payment_status', $order->payment_status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit">Simpan perubahan</button>
            </form>
        </div>
    </div>
    <a class="back-link" href="{{ route('admin.orders.index') }}">← Kembali ke daftar pesanan</a>
@endsection