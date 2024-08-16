<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
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
        return view('products.create');
    }
    
    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());
        
        return redirect()->route('products.index');
    }
}
