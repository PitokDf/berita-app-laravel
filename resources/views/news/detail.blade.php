@extends('layouts.app')

@section('title', $article->title . ' - NewsPortal App')

@section('content')
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <article class="blog-post">
                <h1 class="display-5 fw-bold mb-3">{{ $article->title }}</h1>
                
                <div class="text-muted mb-4 small">
                    <span class="badge bg-secondary me-2">{{ $article->category->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="align-middle text-muted">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    {{ $article->created_at->format('d M Y H:i') }}
                </div>

                @if($article->image)
                    <img src="{{ $article->image }}" class="img-fluid rounded mb-4" alt="{{ $article->title }}">
                @else
                    <img src="https://placehold.co/800x450/eceff1/424242?text=No+Image" class="img-fluid rounded mb-4" alt="No Image">
                @endif

                <div class="lead text-dark mb-4">
                    {!! nl2br(e($article->content)) !!} {{-- Menggunakan nl2br untuk baris baru, e untuk escape --}}
                </div>

                @if($article->tags && count($article->tags) > 0)
                    <div class="my-4">
                        <h6 class="text-muted">Tags:</h6>
                        @foreach($article->tags as $tag)
                            <span class="badge bg-info text-dark me-1">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <hr class="my-5">

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="align-middle">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('news.manage.edit', $article->id) }}" class="btn btn-sm btn-outline-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="align-middle">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        </svg>
                        Edit Berita
                    </a>
                </div>
            </article>
        </div>
    </div>
@endsection