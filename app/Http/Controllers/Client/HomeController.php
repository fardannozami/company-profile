<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Client;
use App\Models\Article;

class HomeController extends Controller
{
    /**
     * Display the public homepage with featured galleries, products, articles and clients.
     *
     * Note: $companyProfile isn't fetched here — it's shared globally to every
     * view by AppServiceProvider, with a safe empty-instance fallback.
     */
    public function index()
    {
        $galleries = Gallery::where('is_active', true)->orderByDesc('updated_at')->limit(4)->get();
        $products = Product::where('is_active', true)->orderBy('order')->limit(6)->get();
        $articles = Article::published()->latest('published_at')->limit(3)->get();
        $clients = Client::where('is_active', true)->orderBy('order')->limit(8)->get();

        return view('pages.client.home.index', compact(
            'galleries',
            'products',
            'articles',
            'clients'
        ));
    }
}
