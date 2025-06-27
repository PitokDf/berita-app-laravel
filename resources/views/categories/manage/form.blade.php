@extends('layouts.app')

@section('title', isset($category) ? 'Edit Kategori - NewsPortal App' : 'Tambah Kategori - NewsPortal App')

@section('content')
    <h1 class="mb-4">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h1>

    <div class="card shadow-sm p-4">
        <form action="{{ isset($category) ? route('categories.manage.update', $category->id) : route('categories.manage.store') }}" method="POST">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $category->name ?? '') }}" 
                    required 
                    onkeyup="generateSlug()"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('slug') is-invalid @enderror" 
                    id="slug" 
                    name="slug" 
                    value="{{ old('slug', $category->slug ?? '') }}" 
                    required
                >
                <div class="form-text">Slug akan digunakan di URL. Contoh: `berita-teknologi`.</div>
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea 
                    class="form-control @error('description') is-invalid @enderror" 
                    id="description" 
                    name="description" 
                    rows="3"
                >{{ old('description', $category->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('categories.manage.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($category) ? 'Perbarui Kategori' : 'Simpan Kategori' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('body_scripts')
<script>
    function generateSlug() {
        const name = document.getElementById('name').value;
        // Hanya generate slug jika input nama tidak kosong
        if (name.trim() === '') {
            document.getElementById('slug').value = '';
            return;
        }

        fetch('/categories/manage/generate-slug?name=' + encodeURIComponent(name))
            .then(response => response.json())
            .then(data => {
                document.getElementById('slug').value = data.slug;
            })
            .catch(error => console.error('Error:', error));
    }

    // Panggil generateSlug saat halaman dimuat jika ini form create
    // Untuk form edit, slug sudah ada dari database
    @if (!isset($category))
        document.addEventListener('DOMContentLoaded', generateSlug);
    @endif
</script>
@endsection