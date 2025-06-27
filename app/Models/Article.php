<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    // Field yang bisa diisi (mass assignable)
    protected $fillable = ['category_id', 'title', 'slug', 'content', 'image', 'status', 'tags'];

    // Cast kolom 'tags' menjadi array
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Hubungan many-to-one: Sebuah artikel dimiliki oleh satu kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Otomatis membuat slug saat menyimpan artikel baru jika belum ada.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            // Jika judul berubah dan slug kosong, generate ulang slug
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}