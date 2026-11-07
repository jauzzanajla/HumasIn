<?php

namespace App\Http\Controllers;

use App\Models\TemplateCategory;
use App\Models\TemplateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TemplateController extends Controller
{
    // Tampilkan halaman daftar folder dan file template
    public function index(Request $request)
    {
        $categories = TemplateCategory::with('items')->get();

        // Filter folder yang sedang dipilih (jika ada)
        $selectedCategory = null;
        $items = collect();

        if ($request->has('folder')) {
            $selectedCategory = TemplateCategory::where('slug', $request->folder)->first();
            if ($selectedCategory) {
                $items = $selectedCategory->items()->latest()->get();
            }
        } else {
            // Default tampilkan semua item
            $items = TemplateItem::latest()->get();
        }

        return view('template.index', compact('categories', 'selectedCategory', 'items'));
    }

    // Admin: Buat Folder Baru
    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:template_categories',
            'deskripsi' => 'nullable|string'
        ]);

        TemplateCategory::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $this->uniqueSlug($request->nama_kategori),
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('template.index')->with('success', 'Folder kategori berhasil dibuat!');
    }

    // Admin: Ganti nama folder
    public function updateCategory(Request $request, $id)
    {
        $category = TemplateCategory::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => [
                'required', 'string', 'max:255',
                Rule::unique('template_categories', 'nama_kategori')->ignore($category->id),
            ],
        ]);

        $category->update([
            'nama_kategori' => $validated['nama_kategori'],
            'slug' => $this->uniqueSlug($validated['nama_kategori'], $category->id),
        ]);

        return redirect()->route('template.index')->with('success', 'Nama folder berhasil diubah!');
    }

    // Admin: Upload File ke Folder
    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:template_categories,id',
            'judul' => 'required|string|max:255',
            'file' => 'required|file|max:20480' // Maksimal 20MB
        ]);

        $file = $request->file('file');
        $filePath = $file->store('templates', 'public');
        $extension = $file->getClientOriginalExtension();

        TemplateItem::create([
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'file_path' => $filePath,
            'tipe_file' => $extension
        ]);

        return redirect()->back()->with('success', 'File template berhasil di-upload!');
    }

    // Admin: Hapus folder beserta seluruh file di dalamnya
    public function destroyCategory($id)
    {
        $category = TemplateCategory::with('items')->findOrFail($id);

        DB::transaction(function () use ($category) {
            foreach ($category->items as $item) {
                Storage::disk('public')->delete($item->file_path); // hapus file fisik
            }
            $category->items()->delete();
            $category->delete();
        });

        return redirect()->route('template.index')->with('success', 'Folder berhasil dihapus!');
    }

    public function destroyItem($id)
    {
        $item = TemplateItem::findOrFail($id);
        if (Storage::disk('public')->exists($item->file_path)) {
            Storage::disk('public')->delete($item->file_path);
        }
        $item->delete();
        return redirect()->back()->with('success', 'File template berhasil dihapus!');
    }

    // Slug unik; $ignoreId dipakai saat rename supaya tidak bentrok dengan dirinya sendiri
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (TemplateCategory::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}