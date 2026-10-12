
@extends('layouts.store', ['title' => 'Checkout'])

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Satu langkah lagi</p>
            <h1>Checkout</h1>
            <p class="intro-copy">Lengkapi detail pengiriman dan pilih cara pembayaran.</p>
        </div>
        <a class="button button-outline" href="{{ route('cart') }}">Kembali ke keranjang</a>
    </div>

    @if (session('success'))
        <div class="empty-state">
            <p class="eyebrow">Pesanan tersimpan</p>
            <h2>Terima kasih sudah berbelanja.</h2>
            <p role="status">{{ session('success') }}</p>
            @auth
                <a class="button" href="{{ route('account.orders.index') }}">Lihat pesanan saya</a>
            @else
                <a class="button" href="{{ route('products.index') }}">Kembali ke toko</a>
            @endauth
        </div>
    @endif

    @if ($errors->any())
        <ul class="error-list" role="alert">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    @endif

    @isset($checkoutItems)
        <div class="checkout-layout">
            <section class="form-panel">
                <h2>Informasi pengiriman</h2>
                <form class="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label for="customer_name">Nama lengkap</label>
                        <input id="customer_name" type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" autocomplete="name" required>
                        @error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="customer_email">Email</label>
                        <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" autocomplete="email" required>
                        @error('customer_email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="shipping_address">Alamat pengiriman</label>
                        <textarea id="shipping_address" name="shipping_address" maxlength="2000" autocomplete="street-address" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="payment_method">Metode pembayaran</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="bank_transfer" @selected(old('payment_method', 'bank_transfer') === 'bank_transfer')>Transfer bank</option>
                            <option value="cash_on_delivery" @selected(old('payment_method') === 'cash_on_delivery')>Bayar di tempat</option>
                        </select>
                        @error('payment_method')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit">Buat pesanan</button>
                </form>
            </section>

            <aside class="summary-panel" aria-label="Ringkasan pesanan">
                <h2>Pesananmu</h2>
                <div class="checkout-items">
                    @foreach ($checkoutItems as $item)
                        <div class="checkout-item">
                            <span>{{ $item['product']->name }} × {{ $item['item']['quantity'] }}</span>
                            <strong>Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                </div>
                <div class="summary-line"><span>Subtotal</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
                <div class="summary-total"><span>Total</span><span>Rp{{ number_format($total, 0, ',', '.') }}</span></div>
                <p class="product-stock">Pembayaran dicatat setelah pesanan dibuat. Detail lanjutan akan mengikuti metode yang dipilih.</p>
            </aside>
        </div>
    @endisset
@endsection
