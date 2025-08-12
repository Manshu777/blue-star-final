@extends('layouts.app')

@section('title', 'Merchandise Store')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Merchandise</h2>
    <p class="text-muted">Shop exclusive merchandise — apparel, accessories, and more.</p>

    <!-- Filter -->
    <form method="GET" action="{{ route('store.merchandise') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search merchandise" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="">Sort By</option>
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <!-- Merchandise Grid -->
    <div class="row">
        @forelse($merchandise as $product)
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted mb-2">{{ Str::limit($product->description, 50) }}</p>
                        <p class="fw-bold text-primary">₹{{ number_format($product->price, 2) }}</p>
                        @if($product->stock > 0)
                            <a href="{{ route('store.merchandise.show', $product->id) }}" class="btn btn-outline-primary btn-sm mt-auto">
                                <i class="fas fa-shopping-cart"></i> Buy Now
                            </a>
                        @else
                            <span class="badge bg-danger mt-auto">Out of Stock</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">No merchandise available.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $merchandise->links() }}
    </div>
</div>
@endsection
