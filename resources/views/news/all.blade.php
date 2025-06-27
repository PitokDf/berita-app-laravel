@extends('layouts.app')

@section('title', 'Semua Berita - NewsPortal App')

@section('content')
    <h1 class="mb-4">Semua Berita</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('news.all') }}" method="GET" class="d-flex">
                <select name="category" class="form-select me-2 w-auto" onchange="this.form.submit()">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('news.all') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari berita..." value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <button type="submit" class="btn btn-outline-primary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('news.all', ['category' => request('category')]) }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>
    </div>

    @if($articles->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Tidak ada berita yang ditemukan.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($articles as $article)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        @if($article->image)
                            <img src="{{ $article->image }}" class="card-img-top" alt="{{ $article->title }}">
                        @else
                            <img src="[https://placehold.co/600x400/eceff1/424242?text=No+Image](https://placehold.co/600x400/eceff1/424242?text=No+Image)" class="card-img-top" alt="No Image">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text text-muted small mb-2">
                                <span class="badge bg-secondary">{{ $article->category->name }}</span>
                                <small>
                                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="align-middle text-muted">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ $article->created_at->format('d M Y') }}
                                </small>
                            </p>
                            <p class="card-text">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('news.detail', $article->slug) }}" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    @endif
@endsection