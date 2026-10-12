<article class="product-card">
    @if ($product->image)
        <img class="product-image" src="{{ Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}" loading="lazy">
    @else
        <div class="product-image-fallback" aria-hidden="true">{{ mb_substr($product->name, 0, 1) }}</div>
    @endif
    <div class="product-card-body">
        @if ($product->category)
            <p class="product-category">{{ $product->category }}</p>
        @endif
        <h2 title="{{ $product->name }}">{{ $product->name }}</h2>
        <p class="product-description">{{ Illuminate\Support\Str::limit($product->description ?? 'Pilihan praktis untuk kebutuhan sehari-hari.', 72) }}</p>
        <p class="product-price">Rp{{ number_format((float) $product->price, 0, ',', '.') }}</p>
        @if ($product->stock > 0)
            <p class="product-stock">Tersedia · {{ $product->stock }} stok</p>
            <form action="{{ route('cart.items.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <label for="quantity-{{ $product->id }}">Jumlah</label>
                <input id="quantity-{{ $product->id }}" type="number" name="quantity" min="1" max="{{ min($product->stock, 99) }}" value="1" required>
                <button type="submit">Tambah</button>
            </form>
        @else
            <p class="product-stock">Stok habis</p>
            <button type="button" disabled aria-disabled="true" class="button-outline">Tidak tersedia</button>
        @endif
    </div>
</article>