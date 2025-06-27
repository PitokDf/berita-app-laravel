<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk manajemen.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Article::query();

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->input('category') !== 'all') {
            $category = Category::where('slug', $request->input('category'))->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Pencarian berdasarkan judul
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        $articles = $query->latest()->paginate(10);

        return view('news.manage.index', compact('articles', 'categories'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('news.manage.form', compact('categories'));
    }

    /**
     * Menyimpan artikel baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|string', // Akan dipecah menjadi array
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles_images', 'public');
            $validated['image'] = Storage::url($imagePath); // Simpan path yang bisa diakses publik
        }

        // Pecah tags menjadi array
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : [];

        Article::create($validated);

        Session::flash('success', 'Berita berhasil ditambahkan!');
        return redirect()->route('news.manage.index');
    }

    /**
     * Menampilkan form untuk mengedit artikel yang sudah ada.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        // Gabungkan tags menjadi string koma-separated untuk form
        $article->tags = implode(', ', $article->tags ?? []);
        return view('news.manage.form', compact('article', 'categories'));
    }

    /**
     * Memperbarui artikel di database.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles')->ignore($article->id),
            ],
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($article->image) {
                Storage::delete(str_replace('/storage/', 'public/', $article->image));
            }
            $imagePath = $request->file('image')->store('public/articles_images');
            $validated['image'] = Storage::url($imagePath);
        } else {
            // Jika tidak ada gambar baru diupload dan ada gambar lama, pertahankan gambar lama
            // Jika form dikirim tanpa input file gambar (dan tidak ada yang baru), imagePath akan null
            // Jadi kita perlu mempertahankan nilai image yang sudah ada
            if (!isset($validated['image'])) {
                $validated['image'] = $article->image;
            }
        }

        // Pecah tags menjadi array
        $validated['tags'] = $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : [];

        $article->update($validated);

        Session::flash('success', 'Berita berhasil diperbarui!');
        return redirect()->route('news.manage.index');
    }

    /**
     * Menghapus artikel dari database.
     */
    public function destroy(Article $article)
    {
        // Hapus gambar terkait jika ada
        if ($article->image) {
            Storage::delete(str_replace('/storage/', 'public/', $article->image));
        }

        $article->delete();

        Session::flash('success', 'Berita berhasil dihapus!');
        return redirect()->route('news.manage.index');
    }

    /**
     * Menghasilkan slug dari judul artikel.
     */
    public function generateSlug(Request $request)
    {
        $title = $request->input('title');
        return response()->json(['slug' => Str::slug($title)]);
    }
}
?>