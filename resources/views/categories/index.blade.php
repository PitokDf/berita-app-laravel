@extends('layouts.app')

@section('title', 'Kategori Berita - NewsPortal App')

@section('content')
    <h1 class="mb-4">Kategori Berita</h1>

    @if($categories->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Belum ada kategori yang ditambahkan.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($categories as $category)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title mb-2">
                                <a href="{{ route('news.all', ['category' => $category->slug]) }}"
                                    class="text-decoration-none text-primary fw-semibold">
                                    {{ $category->name }}
                                </a>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ $category->description ? Str::limit($category->description, 100) : 'Tidak ada deskripsi.' }}
                            </p>
                            <div class="mt-auto pt-3 border-top text-end">
                                <span class="badge bg-info text-dark">
                                    {{ $category->articles_count }} Artikel
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
