<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::paginate(10);

        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        dd('create');
    }
    
    public function store(Request $request)
    {
        // 
    }
}
