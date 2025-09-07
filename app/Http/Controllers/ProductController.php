<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        if ($sort = $request->get('sort')) {
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        }

        $products = $query->paginate(12);

        $categories = ProductCategory::all();

        // Example for featured
        $featuredProducts = Product::inRandomOrder()->limit(3)->get();

        return view('shop.shop', compact('products', 'categories', 'featuredProducts'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        $suggestedProducts = Product::where('type', $product->type)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'suggestedProducts'));
    }
}