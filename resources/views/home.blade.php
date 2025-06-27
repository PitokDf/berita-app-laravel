@extends('layouts.app')

@section('title', 'Beranda - NewsPortal App')

@section('content')
    <div class="text-center my-5">
        <h1 class="display-4">Selamat Datang di NewsPortal!</h1>
        <p class="lead">Temukan berita terkini dan informasi menarik dari berbagai kategori.</p>
        <hr class="my-4">
    </div>

    <h2 class="mb-4">Berita Terbaru</h2>

    @if ($latestArticles->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Belum ada berita yang diterbitkan.
        </div>
    @else
        <div class="mb-4">
            <form action="{{ route('home') }}" method="GET" class="d-flex align-items-center">
                <label for="categoryFilter" class="form-label me-2 mb-0">Filter Kategori:</label>
                <select name="category" id="categoryFilter" class="form-select me-2 w-auto" onchange="this.form.submit()">
                    <option value="all">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}"
                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($latestArticles as $article)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                        @if ($article->image)
                            <img src="{{ $article->image }}" class="card-img-top object-fit-cover" style="height: 200px;"
                                alt="{{ $article->title }}">
                        @else
                            <img src="https://placehold.co/600x400/eceff1/424242?text=No+Image"
                                class="card-img-top object-fit-cover" style="height: 200px;" alt="No Image">
                        @endif

                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-semibold mb-2">{{ $article->title }}</h5>

                            <div class="mb-2 d-flex align-items-center gap-2 flex-wrap text-muted small">
                                <span class="badge bg-secondary">{{ $article->category->name }}</span>
                                <div class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1.5A1.5 1.5 0 0 1 16 2.5V4H0V2.5A1.5 1.5 0 0 1 1.5 1H3V.5a.5.5 0 0 1 .5-.5zM0 5v8.5A1.5 1.5 0 0 0 1.5 15h13a1.5 1.5 0 0 0 1.5-1.5V5H0zm11 3a.5.5 0 0 1 .5.5v1.5h1.5a.5.5 0 0 1 0 1H11.5V12a.5.5 0 0 1-1 0v-1.5H9a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 .5-.5z" />
                                    </svg>
                                    {{ $article->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <p class="card-text text-secondary small mb-3">
                                {{ Str::limit(strip_tags($article->content), 100) }}
                            </p>

                            <div class="mt-auto text-end">
                                <a href="{{ route('news.detail', $article->slug) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    Baca Selengkapnya →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
