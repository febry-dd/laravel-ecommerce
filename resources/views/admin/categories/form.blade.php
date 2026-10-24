<form class="admin-form" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
    @csrf
    @if ($category) @method('PUT') @endif
    @if ($errors->any())<ul class="error-list" role="alert">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>@endif
    <div class="field">
        <label for="name">Nama kategori</label>
        <input id="name" name="name" value="{{ old('name', $category?->name) }}" required maxlength="255">
    </div>
    <div class="hero-actions">
        <button type="submit">Simpan kategori</button>
        <a class="button button-outline" href="{{ route('admin.categories.index') }}">Batal</a>
    </div>
</form>
