<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // Field yang bisa diisi (mass assignable)
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Hubungan one-to-many: Sebuah kategori memiliki banyak artikel.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Otomatis membuat slug saat menyimpan kategori baru jika belum ada.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            // Jika nama berubah dan slug kosong, generate ulang slug
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
