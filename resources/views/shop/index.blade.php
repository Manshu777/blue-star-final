@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 min-h-screen">
        <h1 class="text-4xl font-extrabold mb-10 text-center text-gray-800 tracking-tight">
            🛍️ Discover Our Shop
        </h1>

        <div class="flex gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full md:w-1/4 bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800">Filters</h2>

                <!-- Search -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-gray-600">Search Products</label>
                    <input id="searchInput" type="text" placeholder="Type to search..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>

                <!-- Type Filter -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-gray-600">Type</label>
                    <select id="typeFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="photo">Photos</option>
                        <option value="video">Videos</option>
                        <option value="merchandise">Merchandise</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-gray-600">Category</label>
                    <select id="categoryFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-gray-600">Sort By</label>
                    <select id="sortFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Default</option>
                        <option value="price_asc">Price: Low → High</option>
                        <option value="price_desc">Price: High → Low</option>
                        <option value="name_asc">Name: A → Z</option>
                    </select>
                </div>
            </aside>

            <!-- Product Grid -->
            <main class="flex-1">
                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($products as $product)
                        <div class="product-card bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1"
                            data-type="{{ $product->type }}" data-category="{{ $product->category_id }}"
                            data-name="{{ strtolower($product->name) }}" data-price="{{ $product->price }}">

                            <!-- Product Preview -->
                            <div class="relative">
                                @if ($product->type === 'photo' || $product->type === 'merchandise')
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-56 object-cover">
                                @elseif ($product->type === 'video')
                                    <video src="{{ $product->preview_url }}" class="w-full h-56 object-cover" controls muted
                                        loop></video>
                                @endif
                                <span
                                    class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
                                    {{ ucfirst($product->type) }}
                                </span>
                            </div>

                            <!-- Product Details -->
                            <div class="p-5">
                                <h2 class="text-lg font-semibold mb-1 text-gray-900">{{ $product->name }}</h2>
                                <p class="text-gray-600 mb-3 text-sm line-clamp-2">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div id="pagination" class="flex justify-center mt-10 space-x-2"></div>
            </main>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById("searchInput");
        const typeFilter = document.getElementById("typeFilter");
        const categoryFilter = document.getElementById("categoryFilter");
        const sortFilter = document.getElementById("sortFilter");
        const productCards = [...document.querySelectorAll(".product-card")];
        const productsGrid = document.getElementById("productsGrid");
        const pagination = document.getElementById("pagination");

        const perPage = 9; // Show 9 products per page
        let currentPage = 1;

        function applyFilters() {
            const searchText = searchInput.value.toLowerCase();
            const type = typeFilter.value;
            const category = categoryFilter.value;
            const sort = sortFilter.value;

            let visibleProducts = productCards.filter(card => {
                const matchesSearch = card.dataset.name.includes(searchText);
                const matchesType = !type || card.dataset.type === type;
                const matchesCategory = !category || card.dataset.category === category;
                return matchesSearch && matchesType && matchesCategory;
            });

            // Sorting
            if (sort) {
                visibleProducts.sort((a, b) => {
                    const priceA = parseFloat(a.dataset.price);
                    const priceB = parseFloat(b.dataset.price);
                    const nameA = a.dataset.name;
                    const nameB = b.dataset.name;

                    if (sort === "price_asc") return priceA - priceB;
                    if (sort === "price_desc") return priceB - priceA;
                    if (sort === "name_asc") return nameA.localeCompare(nameB);
                });
            }

            renderPage(visibleProducts, currentPage);
            renderPagination(visibleProducts);
        }

        function renderPage(products, page) {
            productsGrid.innerHTML = "";
            const start = (page - 1) * perPage;
            const end = start + perPage;
            const paginated = products.slice(start, end);

            if (paginated.length) {
                paginated.forEach(p => productsGrid.appendChild(p));
            } else {
                productsGrid.innerHTML = `<p class="text-center text-gray-500 col-span-full">No products found.</p>`;
            }
        }

        function renderPagination(products) {
            pagination.innerHTML = "";
            const totalPages = Math.ceil(products.length / perPage);

            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.textContent = i;
                btn.className =
                    `px-4 py-2 rounded-lg border ${i === currentPage ? "bg-blue-600 text-white" : "bg-white text-gray-700 hover:bg-gray-100"}`;
                btn.addEventListener("click", () => {
                    currentPage = i;
                    applyFilters();
                });
                pagination.appendChild(btn);
            }
        }

        [searchInput, typeFilter, categoryFilter, sortFilter].forEach(el => {
            el.addEventListener("input", () => {
                currentPage = 1; // Reset to first page on filter change
                applyFilters();
            });
            el.addEventListener("change", () => {
                currentPage = 1;
                applyFilters();
            });
        });

        // Initial render
        applyFilters();
    </script>
@endsection