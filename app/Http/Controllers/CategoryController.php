<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori untuk manajemen.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Pencarian berdasarkan nama atau deskripsi
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%');
        }

        $categories = $query->withCount('articles')->paginate(10); // Dengan jumlah artikel per kategori

        return view('categories.manage.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('categories.manage.form');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        Session::flash('success', 'Kategori berhasil ditambahkan!');
        return redirect()->route('categories.manage.index');
    }

    /**
     * Menampilkan form untuk mengedit kategori yang sudah ada.
     */
    public function edit(Category $category)
    {
        return view('categories.manage.form', compact('category'));
    }

    /**
     * Memperbarui kategori di database.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        Session::flash('success', 'Kategori berhasil diperbarui!');
        return redirect()->route('categories.manage.index');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Category $category)
    {
        // Cek apakah kategori masih digunakan oleh artikel
        if ($category->articles()->count() > 0) {
            Session::flash('error', 'Tidak bisa menghapus kategori karena masih digunakan oleh artikel.');
            return redirect()->route('categories.manage.index');
        }

        $category->delete();

        Session::flash('success', 'Kategori berhasil dihapus!');
        return redirect()->route('categories.manage.index');
    }

    /**
     * Menghasilkan slug dari nama kategori.
     */
    public function generateSlug(Request $request)
    {
        $name = $request->input('name');
        return response()->json(['slug' => Str::slug($name)]);
    }
}
?>