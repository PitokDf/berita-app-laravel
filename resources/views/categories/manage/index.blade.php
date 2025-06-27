@extends('layouts.app')

@section('title', 'Manajemen Kategori - NewsPortal App')

@section('content')
    <h1 class="mb-4">Manajemen Kategori</h1>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <a href="{{ route('categories.manage.create') }}" class="btn btn-success d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-1" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
            Tambah Kategori
        </a>

        <form action="{{ route('categories.manage.index') }}" method="GET"
            class="row row-cols-1 row-cols-md-auto g-2 align-items-center mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                    value="{{ request('search') }}">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
            </div>
            @if (request('search'))
                <div class="col">
                    <a href="{{ route('categories.manage.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>

    </div>

    @if ($categories->isEmpty())
        <div class="alert alert-info text-center">Tidak ada kategori yang ditemukan.</div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($categories as $category)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 transition-hover">
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-semibold">
                                {{ $category->name }}
                            </h5>
                            <p class="card-text text-muted small mb-2">
                                {{ $category->description ? Str::limit($category->description, 100) : 'Tidak ada deskripsi.' }}
                            </p>
                            <p class="card-text text-secondary small mb-3">
                                <code>{{ $category->slug }}</code>
                            </p>
                            <span class="badge bg-info text-dark align-self-start mb-3">
                                {{ $category->articles_count }} Artikel
                            </span>

                            <div class="mt-auto d-flex justify-content-end gap-2">
                                <a href="{{ route('categories.manage.edit', $category->id) }}"
                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit Kategori">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal{{ $category->id }}"
                                    @if ($category->articles_count > 0) disabled title="Kategori ini memiliki artikel, tidak dapat dihapus." @endif>
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </div>

                        {{-- Modal Hapus --}}
                        <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1"
                            aria-labelledby="deleteCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteCategoryModalLabel{{ $category->id }}">Konfirmasi
                                            Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus kategori
                                        <strong>{{ $category->name }}</strong>?<br>
                                        <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('categories.manage.destroy', $category->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End Modal --}}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
    @endif
@endsection
