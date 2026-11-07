<?php

namespace App\Http\Controllers;

use App\Models\DokumentasiCategory;
use App\Models\DokumentasiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $categories = DokumentasiCategory::with('items')->get();
        $selectedCategory = null;
        $items = collect();

        if ($request->has('folder')) {
            $selectedCategory = DokumentasiCategory::where('slug', $request->folder)->first();
            if ($selectedCategory) {
                $items = $selectedCategory->items()->latest()->get();
            }
        } else {
            $items = DokumentasiItem::latest()->get();
        }

        return view('dokumentasi.index', compact('categories', 'selectedCategory', 'items'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:dokumentasi_categories',
            'deskripsi' => 'nullable|string'
        ]);

        DokumentasiCategory::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $this->uniqueSlug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('dokumentasi.index')->with('success', 'Folder dokumentasi berhasil dibuat!');
    }

    // Ganti nama folder
    public function updateCategory(Request $request, $id)
    {
        $category = DokumentasiCategory::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => [
                'required', 'string', 'max:255',
                Rule::unique('dokumentasi_categories', 'nama_kategori')->ignore($category->id),
            ],
        ]);

        $category->update([
            'nama_kategori' => $validated['nama_kategori'],
            'slug' => $this->uniqueSlug($validated['nama_kategori'], $category->id),
        ]);

        return redirect()->route('dokumentasi.index')->with('success', 'Nama folder berhasil diubah!');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:dokumentasi_categories,id',
            'judul' => 'required|string|max:255',
            'link_url' => 'required|url', // Validasi harus berupa URL valid (Google Drive)
            'keterangan' => 'nullable|string'
        ]);

        DokumentasiItem::create([
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'link_url' => $request->link_url,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Link dokumentasi kegiatan berhasil ditambahkan!');
    }

    // Hapus folder beserta seluruh tautan di dalamnya
    public function destroyCategory($id)
    {
        $category = DokumentasiCategory::findOrFail($id);

        DB::transaction(function () use ($category) {
            $category->items()->delete();
            $category->delete();
        });

        return redirect()->route('dokumentasi.index')->with('success', 'Folder berhasil dihapus!');
    }

    public function destroyItem($id)
    {
        $item = DokumentasiItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Link dokumentasi berhasil dihapus!');
    }

    // Slug unik; $ignoreId dipakai saat rename supaya tidak bentrok dengan dirinya sendiri
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (DokumentasiCategory::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}