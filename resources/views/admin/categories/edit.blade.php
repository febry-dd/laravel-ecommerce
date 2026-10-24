@extends('layouts.admin', ['title' => 'Ubah kategori'])

@section('content')
    <div class="admin-titlebar"><div><p class="eyebrow">Katalog toko</p><h1>Ubah kategori</h1></div><a class="button button-outline" href="{{ route('admin.categories.index') }}">Daftar kategori</a></div>
    @if (session('success'))<p class="alert-success" role="status">{{ session('success') }}</p>@endif
    <section class="form-panel" style="max-width: 760px">
        <h2>{{ $category->name }}</h2>
        @include('admin.categories.form', ['category' => $category])
    </section>
@endsection
