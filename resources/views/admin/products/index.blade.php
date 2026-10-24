@extends('layouts.admin', ['title' => 'Produk'])

@section('content')
    <div class="admin-titlebar">
        <div><p class="eyebrow">Katalog toko</p><h1>Produk</h1></div>
        <a class="button" href="{{ route('admin.products.create') }}">Tambah produk</a>
    </div>
    @if (session('success'))<p class="alert-success" role="status">{{ session('success') }}</p>@endif
    <section class="panel">
        <div class="panel-head"><h2>{{ $products->total() }} produk</h2></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Gambar</th><th>Nama</th><th>Deskripsi</th><th>Kategori</th><th>Stok</th><th>Harga</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if ($product->image)
                                    <img class="admin-product-thumb" src="{{ Illuminate\Support\Facades\Storage::url($product->image) }}" alt="Gambar {{ $product->name }}" loading="lazy">
                                @else
                                    <span>—</span>
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ Illuminate\Support\Str::limit($product->description ?? '—', 100) }}</td>
                            <td>{{ $product->categoryRelation?->name ?? ($product->category ?: '—') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</td>
                            <td><div class="data-table-actions">
                                <a class="button button-outline button-small" href="{{ route('admin.products.edit', $product) }}">Ubah</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="button-danger button-small" type="submit">Hapus</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="empty-state"><h2>Belum ada produk</h2><p>Tambahkan produk pertama untuk mulai mengisi etalase.</p><a class="button" href="{{ route('admin.products.create') }}">Tambah produk</a></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="pagination">{{ $products->onEachSide(1)->links() }}</div>
@endsection