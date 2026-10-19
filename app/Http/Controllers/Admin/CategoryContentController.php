<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryContent;
use Illuminate\Http\Request;

class CategoryContentController extends Controller
{
    /**
     * Display a listing of category explanation pages.
     */
    public function index()
    {
        $contents = CategoryContent::orderBy('category')->get();

        return view('admin.category-contents.index', compact('contents'));
    }

    /**
     * Show the form for editing the category explanation page.
     */
    public function edit(CategoryContent $categoryContent)
    {
        return view('admin.category-contents.edit', compact('categoryContent'));
    }

    /**
     * Update the category explanation page content.
     */
    public function update(Request $request, CategoryContent $categoryContent)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'tagline'     => 'nullable|string|max:255',
            'description' => 'required|string',
            'facilities'  => 'nullable|string',
            'terms'       => 'nullable|string',
            'is_active'   => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $categoryContent->update($validated);

        return redirect()->route('admin.category-contents.index')
            ->with('success', "Konten halaman kategori {$categoryContent->title} berhasil diperbarui.");
    }
}

