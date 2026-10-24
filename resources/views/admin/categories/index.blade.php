@extends('layouts.admin', ['title' => 'Kategori'])

@section('content')
    <div class="admin-titlebar">
        <div><p class="eyebrow">Katalog toko</p><h1>Kategori</h1></div>
        <a class="button" href="{{ route('admin.categories.create') }}">Tambah kategori</a>
    </div>
    @if (session('success'))<p class="alert-success" role="status">{{ session('success') }}</p>@endif
    <section class="panel">
        <div class="panel-head"><h2>{{ $categories->total() }} kategori</h2></div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead><tr><th>ID</th><th>Nama kategori</th><th>Jumlah produk</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->products_count }}</td>
                            <td><div class="data-table-actions">
                                <a class="button button-outline button-small" href="{{ route('admin.categories.edit', $category) }}">Ubah</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk terkait akan menjadi tanpa kategori.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button-danger button-small" type="submit">Hapus</button>
                                </form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><h2>Belum ada kategori</h2><p>Tambahkan kategori untuk mengelompokkan produk.</p><a class="button" href="{{ route('admin.categories.create') }}">Tambah kategori</a></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="pagination">{{ $categories->onEachSide(1)->links() }}</div>
@endsection
