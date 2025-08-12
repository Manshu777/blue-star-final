@extends('layouts.app')

@section('title', 'Photo Store')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Photo Store</h2>
        <p class="text-muted">Browse and purchase high-quality photos and albums.</p>

        <!-- Search & Filter -->
        <form method="GET" action="{{ route('store.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search photos or albums"
                    value="{{ request('search') }}">
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
                    <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to
                        High</option>
                    <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to
                        Low</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>

        <!-- Store Items -->
        <div class="row">
            @forelse($items as $item)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" class="card-img-top" alt="{{ $item->title }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text text-muted mb-2">{{ Str::limit($item->description, 50) }}</p>
                            <p class="fw-bold text-primary">₹{{ number_format($item->price, 2) }}</p>
                            <a href="{{ route('store.show', $item->id) }}" class="btn btn-outline-primary btn-sm mt-auto">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No items found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
@endsection