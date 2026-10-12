@extends('layouts.store', ['title' => 'Keranjang belanja'])

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Pilihanmu</p>
            <h1>Keranjang belanja</h1>
            <p class="intro-copy">Periksa kembali barang dan jumlahnya sebelum melanjutkan.</p>
        </div>
        <a class="button button-outline" href="{{ route('products.index') }}">Lanjut belanja</a>
    </div>

    @if (session('success'))
        <p class="alert-success" role="status">{{ session('success') }}</p>
    @endif
    @if ($errors->any())
        <ul class="error-list" role="alert">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    @endif

    @if (count($cartItems))
        <div class="cart-layout">
            <div class="cart-list">
                @foreach ($cartItems as $item)
                    <article class="cart-row">
                        @if ($item['product']->image)
                            <img class="cart-thumb" src="{{ Illuminate\Support\Facades\Storage::url($item['product']->image) }}" alt="{{ $item['product']->name }}">
                        @else
                            <div class="cart-thumb product-image-fallback" aria-hidden="true">{{ mb_substr($item['product']->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <h2>{{ $item['product']->name }}</h2>
                            <p>Rp{{ number_format((float) $item['product']->price, 0, ',', '.') }} per item</p>
                        </div>
                        <p class="row-subtotal">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        <div class="cart-row-actions">
                            <form class="field" action="{{ route('cart.items.update', $item['product']) }}" method="POST">
                                @csrf @method('PATCH')
                                <label for="quantity-{{ $item['product']->id }}">Jumlah</label>
                                <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" min="1" max="99" value="{{ $item['quantity'] }}" required>
                                <button class="button-small" type="submit">Perbarui</button>
                            </form>
                            <form action="{{ route('cart.items.destroy', $item['product']) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="button-danger button-small" type="submit" aria-label="Hapus {{ $item['product']->name }}">Hapus</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside class="summary-panel" aria-label="Ringkasan keranjang">
                <h2>Ringkasan belanja</h2>
                <div class="summary-line"><span>Jumlah item</span><strong>{{ collect($cartItems)->sum('quantity') }}</strong></div>
                <div class="summary-total"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <p class="product-stock">Biaya pengiriman dihitung setelah alamat dikonfirmasi.</p>
                <a class="button" href="{{ route('checkout') }}">Lanjut ke checkout</a>
            </aside>
        </div>
    @else
        <div class="empty-state">
            <h2>Keranjang Anda masih kosong.</h2>
            <p>Mulai dengan menjelajahi pilihan terbaru dari toko.</p>
            <a class="button" href="{{ route('products.index') }}">Jelajahi produk</a>
        </div>
    @endif
@endsection