@extends('layouts.admin', ['title' => 'Ubah produk'])

@section('content')
    <div class="admin-titlebar"><div><p class="eyebrow">Katalog toko</p><h1>Ubah produk</h1></div><a class="button button-outline" href="{{ route('admin.products.index') }}">Daftar produk</a></div>
    @if (session('success'))<p class="alert-success" role="status">{{ session('success') }}</p>@endif
    <section class="form-panel" style="max-width: 760px">
        <h2>{{ $product->name }}</h2>
        @include('admin.products.form', ['product' => $product])
    </section>
@endsection