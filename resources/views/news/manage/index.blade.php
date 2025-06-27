@extends('layouts.app')

@section('title', 'Manajemen Berita - NewsPortal App')

@section('content')
    <h1 class="mb-4">Manajemen Berita</h1>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <a href="{{ route('news.manage.create') }}" class="btn btn-success d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
            Tambah Berita
        </a>

        <form action="{{ route('news.manage.index') }}" method="GET" class="row gx-2 gy-2 align-items-center w-100">
            <div class="col-auto">
                <select name="category" class="form-select">
                    <option value="all">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}"
                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="all">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari judul berita..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">Filter / Cari</button>
                @if (request('category') || request('status') || request('search'))
                    <a href="{{ route('news.manage.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>


    @if ($articles->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            Tidak ada berita yang ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Gambar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}</td>
                            <td>{{ Str::limit($article->title, 60) }}</td>
                            <td class="text-center">{{ $article->category->name }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $article->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($article->image)
                                    <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                        style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span class="text-muted">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('news.manage.edit', $article->id) }}" class="btn btn-sm btn-warning"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteArticleModal{{ $article->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>

                                {{-- Modal Konfirmasi --}}
                                <div class="modal fade" id="deleteArticleModal{{ $article->id }}" tabindex="-1"
                                    aria-labelledby="deleteArticleModalLabel{{ $article->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                Yakin ingin menghapus berita
                                                <strong>"{{ Str::limit($article->title, 50) }}"</strong>?
                                                <br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('news.manage.destroy', $article->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- End Modal --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>

    @endif
@endsection

@section('body_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>

@endsection
