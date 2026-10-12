@extends('layouts.admin', ['title' => 'Tambah produk'])

@section('content')
    <div class="admin-titlebar"><div><p class="eyebrow">Katalog toko</p><h1>Tambah produk</h1></div></div>
    <section class="form-panel" style="max-width: 760px">
        <h2>Detail produk</h2>
        @include('admin.products.form', ['product' => null])
    </section>
@endsection