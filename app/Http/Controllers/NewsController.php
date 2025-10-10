<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')->paginate(6);
        return view('pages.news', compact('news'));
    }

    public function show($slug)
    {
        $item = News::where('slug', $slug)->firstOrFail();
        return view('pages.news-detail', compact('item'));
    }
}
