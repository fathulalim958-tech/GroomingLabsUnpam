<?php

namespace App\Http\Controllers;

use App\Models\CategoryContent;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the explanation page for a specific service category.
     */
    public function show(string $category)
    {
        if (!in_array($category, ['barbershop', 'mua'])) {
            abort(404);
        }

        // Get or initialize category content
        $content = CategoryContent::where('category', $category)->first();

        if (!$content) {
            $defaultTitle = $category === 'barbershop' ? 'Grooming Lab Barbershop' : 'Grooming Lab MUA Studio';
            $defaultTagline = $category === 'barbershop' 
                ? 'Perawatan Rambut & Grooming Pria Maskulin Modern' 
                : 'Tata Rias Cantik, Flawless, & Elegan untuk Hari Spesial';
            $defaultDesc = $category === 'barbershop'
                ? 'Layanan perawatan rambut dan grooming pria terbaik dengan kapster profesional.'
                : 'Layanan tata rias profesional untuk pernikahan, pesta, wisuda, dan pre-wedding.';

            $content = CategoryContent::create([
                'category' => $category,
                'title' => $defaultTitle,
                'tagline' => $defaultTagline,
                'description' => $defaultDesc,
                'is_active' => true,
            ]);
        }

        // Get active services in this category
        $services = Service::active()
            ->where('category', $category)
            ->orderBy('price')
            ->get();

        return view('categories.show', compact('content', 'category', 'services'));
    }
}
