<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryContent;
use Illuminate\Http\Request;

class CategoryContentController extends Controller
{
    /**
     * Display a listing of category explanation pages and login page content.
     */
    public function index()
    {
        // Ensure default login content exists
        CategoryContent::firstOrCreate(
            ['category' => 'login'],
            [
                'title'       => 'Selamat Datang',
                'tagline'     => 'Grooming Experience',
                'description' => 'Masuk untuk memesan layanan atau mengelola antrian',
                'facilities'  => 'Better Together',
                'terms'       => 'Akses akun Anda untuk melanjutkan',
                'banner_image'=> null,
                'is_active'   => true,
            ]
        );

        $contents = CategoryContent::orderByRaw("CASE WHEN category = 'barbershop' THEN 1 WHEN category = 'mua' THEN 2 ELSE 3 END")->get();

        return view('admin.category-contents.index', compact('contents'));
    }

    /**
     * Show the form for editing the category or login page content.
     */
    public function edit(CategoryContent $categoryContent)
    {
        return view('admin.category-contents.edit', compact('categoryContent'));
    }

    /**
     * Preview the login page as admin without being redirected by guest middleware.
     */
    public function previewLogin()
    {
        $loginContent = CategoryContent::firstOrCreate(
            ['category' => 'login'],
            [
                'title'       => 'Selamat Datang',
                'tagline'     => 'Grooming Experience',
                'description' => 'Masuk untuk memesan layanan atau mengelola antrian',
                'facilities'  => 'Better Together',
                'terms'       => 'Akses akun Anda untuk melanjutkan',
                'banner_image'=> null,
                'is_active'   => true,
            ]
        );
        $previewMode = true;

        return view('auth.login', compact('loginContent', 'previewMode'));
    }

    /**
     * Update the category explanation page or login content.
     */
    public function update(Request $request, CategoryContent $categoryContent)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'tagline'      => 'nullable|string|max:255',
            'description'  => 'required|string',
            'facilities'   => 'nullable|string',
            'terms'        => 'nullable|string',
            'is_active'    => 'nullable',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'title.required' => 'Judul konten wajib diisi.',
            'description.required' => 'Deskripsi konten wajib diisi.',
            'banner_image.image' => 'File background harus berupa gambar.',
            'banner_image.max' => 'Ukuran file gambar maksimal 3MB.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('category-contents', 'public');
            $validated['banner_image'] = $path;
        }

        $categoryContent->update($validated);

        $namaKonten = $categoryContent->category === 'login'
            ? 'Halaman Login'
            : "Halaman Kategori {$categoryContent->title}";

        return redirect()->route('admin.category-contents.index')
            ->with('success', "Konten {$namaKonten} berhasil diperbarui.");
    }
}

