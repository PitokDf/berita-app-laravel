<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 lucide lucide-newspaper">
                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                <path d="M10 12h8"/><path d="M10 16h8"/><path d="M10 8h8"/>
            </svg>
            NewsPortal
        </a>
        
        <button 
            class="navbar-toggler" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @php
                    $menuItems = [
                        ['id' => 'home', 'label' => 'Home', 'icon' => 'home', 'url' => '/'],
                        ['id' => 'all-news', 'label' => 'Semua Berita', 'icon' => 'newspaper', 'url' => '/news'],
                        ['id' => 'news-manage', 'label' => 'Kelola Berita', 'icon' => 'file-text', 'url' => '/news/manage'],
                        ['id' => 'categories-browse', 'label' => 'Kategori', 'icon' => 'grid-3x3', 'url' => '/categories'],
                        ['id' => 'categories-manage', 'label' => 'Kelola Kategori', 'icon' => 'tags', 'url' => '/categories/manage'],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <li class="nav-item">
                        <a 
                            class="nav-link d-flex align-items-center 
                                {{-- Menentukan kelas 'active' berdasarkan URL saat ini --}}
                                @if (request()->is(ltrim($item['url'], '/')) || (request()->is('/') && $item['id'] === 'home'))
                                    active
                                @endif
                            "
                            href="{{ url($item['url']) }}"
                        >
                            {{-- Ikon sesuai item. Menggunakan SVG inline dari Lucide React --}}
                            @if ($item['icon'] === 'home')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-home">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                    <polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                            @elseif ($item['icon'] === 'newspaper')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-newspaper">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                    <path d="M10 12h8"/><path d="M10 16h8"/><path d="M10 8h8"/>
                                </svg>
                            @elseif ($item['icon'] === 'file-text')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-file-text">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>
                                </svg>
                            @elseif ($item['icon'] === 'grid-3x3')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-grid-3x3">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                    <path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/>
                                </svg>
                            @elseif ($item['icon'] === 'tags')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-tags">
                                    <path d="M9 5H2v7l6.29 6.29c.94.94 2.48.94 3.42 0l3.58-3.58c.94-.94.94-2.48 0-3.42L9 5Z"/>
                                    <path d="M6 9.01V9"/><path d="M18 12v4"/><path d="M22 10V6a2 2 0 0 0-2-2h-4L10.9 2.9A2 2 0 0 0 9 5v0"/>
                                </svg>
                            @elseif ($item['icon'] === 'info')
                                <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-info">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4"/><path d="M12 8h.01"/>
                                </svg>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            
            <div class="d-flex">
                <a
                    class="btn btn-outline-light d-flex align-items-center"
                    href="{{ route('news.manage.create') }}"
                >
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1 lucide lucide-plus">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    Tambah Berita
                </a>
            </div>
        </div>
    </div>
</nav>