@extends('layouts.admin', ['title' => 'Tambah kategori'])

@section('content')
    <div class="admin-titlebar"><div><p class="eyebrow">Katalog toko</p><h1>Tambah kategori</h1></div></div>
    <section class="form-panel" style="max-width: 760px">
        <h2>Detail kategori</h2>
        @include('admin.categories.form', ['category' => null])
    </section>
@endsection
