@extends('layouts.app')

@section('title', isset($article) ? 'Edit Berita - NewsPortal App' : 'Tambah Berita - NewsPortal App')

@section('content')
    <h1 class="mb-4">{{ isset($article) ? 'Edit Berita' : 'Tambah Berita Baru' }}</h1>

    <div class="card shadow-sm p-4">
        <form action="{{ isset($article) ? route('news.manage.update', $article->id) : route('news.manage.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($article))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="title" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('title') is-invalid @enderror" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $article->title ?? '') }}" 
                    required 
                    onkeyup="generateSlug()"
                >
                @error('title')
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
                    value="{{ old('slug', $article->slug ?? '') }}" 
                    required
                >
                <div class="form-text">Slug akan digunakan di URL. Contoh: `judul-berita-anda`.</div>
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option 
                            value="{{ $category->id }}" 
                            {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Konten Berita <span class="text-danger">*</span></label>
                <textarea 
                    class="form-control @error('content') is-invalid @enderror" 
                    id="content" 
                    name="content" 
                    rows="10" 
                    required
                >{{ old('content', $article->content ?? '') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar Berita</label>
                <input 
                    type="file" 
                    class="form-control @error('image') is-invalid @enderror" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    onchange="previewImage(event)"
                >
                <div class="form-text">Format: JPEG, PNG, JPG, GIF, SVG. Max: 2MB.</div>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if(isset($article) && $article->image)
                    <div class="mt-2">
                        <p class="mb-1">Gambar saat ini:</p>
                        <img id="image-preview" src="{{ $article->image }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px; height: auto;">
                    </div>
                @else
                    <div class="mt-2" style="display: none;" id="image-preview-container">
                        <p class="mb-1">Pratinjau Gambar:</p>
                        <img id="image-preview" src="#" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; height: auto;">
                    </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <input 
                    type="text" 
                    class="form-control @error('tags') is-invalid @enderror" 
                    id="tags" 
                    name="tags" 
                    value="{{ old('tags', $article->tags ?? '') }}" 
                    placeholder="tag1, tag2, tag3"
                >
                <div class="form-text">Pisahkan tags dengan koma (contoh: teknologi, inovasi, gadget).</div>
                @error('tags')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="draft" {{ old('status', $article->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $article->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('news.manage.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    {{ isset($article) ? 'Perbarui Berita' : 'Simpan Berita' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('body_scripts')
<script>
    function generateSlug() {
        const title = document.getElementById('title').value;
        if (title.trim() === '') {
            document.getElementById('slug').value = '';
            return;
        }

        fetch('/news/manage/generate-slug?title=' + encodeURIComponent(title))
            .then(response => response.json())
            .then(data => {
                document.getElementById('slug').value = data.slug;
            })
            .catch(error => console.error('Error:', error));
    }

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');
            output.src = reader.result;
            container.style.display = 'block'; // Tampilkan container preview
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        } else {
            // Jika file dihapus, sembunyikan preview
            document.getElementById('image-preview').src = '#';
            document.getElementById('image-preview-container').style.display = 'none';
        }
    }

    // Panggil generateSlug saat halaman dimuat jika ini form create
    @if (!isset($article))
        document.addEventListener('DOMContentLoaded', generateSlug);
    @endif
</script>
@endsection