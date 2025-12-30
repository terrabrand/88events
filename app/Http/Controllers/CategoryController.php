<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $events = $category->events()
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('pages.categories.show', compact('category', 'events'));
    }
}
