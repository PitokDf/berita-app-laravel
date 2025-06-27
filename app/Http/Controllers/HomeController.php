<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan daftar berita.
     */
    public function index(Request $request)
    {
        // Mendapatkan semua kategori untuk filter
        $categories = Category::all();

        // Mendapatkan berita terbaru, opsional filter berdasarkan kategori
        $articlesQuery = Article::where('status', 'published')->latest();

        if ($request->has('category') && $request->input('category') !== 'all') {
            $categorySlug = $request->input('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $articlesQuery->where('category_id', $category->id);
            }
        }

        // Ambil artikel terbaru, misalnya 6 untuk home page
        $latestArticles = $articlesQuery->limit(6)->get();

        return view('home', compact('latestArticles', 'categories'));
    }

    /**
     * Menampilkan detail berita berdasarkan slug.
     */
    public function showArticleDetail(string $slug)
    {
        $article = Article::where('slug', $slug)
                        ->where('status', 'published')
                        ->firstOrFail();
        return view('news.detail', compact('article'));
    }

    /**
     * Menampilkan daftar semua berita (untuk menu "Semua Berita").
     */
    public function allNews(Request $request)
    {
        $categories = Category::all();

        $query = Article::where('status', 'published');

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->input('category') !== 'all') {
            $categorySlug = $request->input('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Pencarian berdasarkan judul atau konten
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        $articles = $query->latest()->paginate(9); // Paginasi 9 artikel per halaman

        return view('news.all', compact('articles', 'categories'));
    }

    /**
     * Menampilkan daftar kategori untuk browsing (bukan manajemen).
     */
    public function browseCategories()
    {
        $categories = Category::withCount('articles')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan halaman 'Tentang'.
     */
    public function about()
    {
        return view('about');
    }
}
?>
