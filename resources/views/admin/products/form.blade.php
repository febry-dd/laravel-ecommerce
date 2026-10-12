<form class="admin-form" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($product) @method('PUT') @endif
    @if ($errors->any())<ul class="error-list" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
    <div class="field"><label for="name">Nama produk</label><input id="name" name="name" value="{{ old('name', $product?->name) }}" required maxlength="255"></div>
    <div class="field"><label for="category">Kategori</label><input id="category" name="category" value="{{ old('category', $product?->category) }}" maxlength="100"></div>
    <div class="field"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $product?->description) }}</textarea></div>
    <div class="field"><label for="price">Harga (Rp)</label><input id="price" type="number" name="price" min="0" step="0.01" value="{{ old('price', $product?->price) }}" required></div>
    <div class="field"><label for="stock">Jumlah stok</label><input id="stock" type="number" name="stock" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required></div>
    <div class="field"><label for="image">Foto produk · JPG, PNG, atau WebP · maks. 2 MB</label><input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp"></div>
    @if ($product?->image)<img src="{{ Illuminate\Support\Facades\Storage::url($product->image) }}" alt="Foto {{ $product->name }}">@endif
    <div class="hero-actions"><button type="submit">Simpan produk</button><a class="button button-outline" href="{{ route('admin.products.index') }}">Batal</a></div>
</form>