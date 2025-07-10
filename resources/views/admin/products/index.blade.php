@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')
    {{-- Success message notification --}}
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Action completed successfully!
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Search input --}}
                    <form id="productSearchForm" class="flex gap-2 mb-4 max-w-sm">
                        <input type="text" id="searchProductInput" placeholder="Search product..."
                            class="border px-3 py-2 rounded w-full" autocomplete="off">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 rounded">Search</button>
                    </form>

                    {{-- Add new product --}}
                    <a href="{{ route('admin.product.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-block mb-10">
                        Add
                    </a>

                    {{-- Product table --}}
                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Description</th>
                                <th class="px-4 py-2 border-b">Price</th>
                                <th class="px-4 py-2 border-b">Quantity</th>
                                <th class="px-4 py-2 border-b">Category</th>
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach ($products as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->name }}</td>
                                    <td class="px-4 py-2 border-b max-w-xs truncate" title="{{ $item->descriptions }}">
                                        {{ \Illuminate\Support\Str::limit($item->descriptions, 50) }}
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $item->price }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->stock_quantity }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->category->name }}</td>
                                    <td class="px-4 py-2 border-b">
                                        <div class="flex flex-wrap gap-2">
                                            {{-- Edit button --}}
                                            <a href="{{ route('admin.product.edit', $item->id) }}"
                                                class="px-4 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                                Edit
                                            </a>

                                            {{-- Delete button --}}
                                            <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition">
                                                    Delete
                                                </button>
                                            </form>

                                            {{-- View Details button --}}
                                            <a href="{{ route('admin.product.show', $item->id) }}"
                                                class="px-4 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                View Details
                                            </a>

                                            {{-- Quick View button --}}
                                            <button type="button"
                                                class="px-4 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition btn-view-product"
                                                data-id="{{ $item->id }}"
                                                data-url="{{ route('admin.product.show', $item->id) }}">
                                                Quick View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for product quick view --}}
    <div id="productDetail" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-xl relative">
            <h2 class="text-xl font-bold mb-4">Product Details</h2>
            <p><strong>ID:</strong> <span id="product-view-id" class="text-gray-700"></span></p>
            <p><strong>Name:</strong> <span id="product-view-name" class="text-gray-700"></span></p>
            <p><strong>Description:</strong></p>
            <div id="product-view-description"
                class="border p-3 rounded bg-gray-50 text-sm text-gray-800 max-h-[300px] overflow-y-auto"></div>
            <p class="mt-4"><strong>Price:</strong> <span id="product-view-price" class="text-gray-700"></span></p>
            <p><strong>Quantity:</strong> <span id="product-view-quantity" class="text-gray-700"></span></p>
            <p><strong>Category:</strong> <span id="product-view-category" class="text-gray-700"></span></p>
            <p class="mt-4"><strong>Image:</strong></p>
            <img id="product-view-image" src="" alt="Product Image" class="w-48 h-auto mt-2 border rounded">
            <button id="closeProductDetail"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-lg">&times;</button>
        </div>
    </div>
    <div id="productOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide modal and overlay initially
            document.getElementById('productDetail').classList.add('hidden');
            document.getElementById('productOverlay').classList.add('hidden');

            // Hàm gán lại sự kiện cho các nút trong bảng
            function bindProductTableEvents() {
                document.querySelectorAll('.btn-view-product').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.dataset.url + '?ajax=1';

                        // Set loading state
                        document.getElementById('product-view-id').textContent = 'Loading...';
                        document.getElementById('product-view-name').textContent = 'Loading...';
                        document.getElementById('product-view-description').innerHTML =
                        'Loading...';
                        document.getElementById('product-view-price').textContent = 'Loading...';
                        document.getElementById('product-view-quantity').textContent = 'Loading...';
                        document.getElementById('product-view-category').textContent = 'Loading...';
                        document.getElementById('product-view-image').src = '';

                        // Show modal and overlay
                        document.getElementById('productDetail').classList.remove('hidden');
                        document.getElementById('productOverlay').classList.remove('hidden');

                        // Fetch product data via AJAX
                        fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error(
                                    `HTTP ${response.status}: ${response.statusText}`);
                                const contentType = response.headers.get('Content-Type') || '';
                                if (!contentType.includes('application/json')) throw new Error(
                                    'Response is not JSON');
                                return response.json();
                            })
                            .then(data => {
                                if (data.success === false) throw new Error(data.message ||
                                    'Server returned error');
                                document.getElementById('product-view-id').textContent = data
                                    .id ||
                                    'N/A';
                                document.getElementById('product-view-name').textContent = data
                                    .name || 'N/A';
                                document.getElementById('product-view-description').innerHTML =
                                    data
                                    .descriptions || '<em>No description available</em>';
                                document.getElementById('product-view-price').textContent = data
                                    .price || 'N/A';
                                document.getElementById('product-view-quantity').textContent =
                                    data
                                    .stock_quantity || 'N/A';
                                document.getElementById('product-view-category').textContent =
                                    data
                                    .category_name || 'N/A';
                                document.getElementById('product-view-image').src = data
                                    .image_url || '/images/base.jpg';
                            })
                            .catch(error => {
                                alert('An error occurred while loading product information: ' +
                                    error.message);
                                document.getElementById('productDetail').classList.add(
                                'hidden');
                                document.getElementById('productOverlay').classList.add(
                                    'hidden');
                            });
                    });
                });
            }

            // Gán sự kiện lần đầu khi trang load
            bindProductTableEvents();

            // Close modal when clicking close button or overlay
            document.getElementById('closeProductDetail').addEventListener('click', function() {
                document.getElementById('productDetail').classList.add('hidden');
                document.getElementById('productOverlay').classList.add('hidden');
            });
            document.getElementById('productOverlay').addEventListener('click', function() {
                document.getElementById('productDetail').classList.add('hidden');
                document.getElementById('productOverlay').classList.add('hidden');
            });

            // Filter sản phẩm
            document.getElementById('productSearchForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const keyword = document.getElementById('searchProductInput').value.trim();
                fetch('{{ route('admin.product.search') }}?query=' + encodeURIComponent(keyword), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.querySelector('table tbody').innerHTML = data.html;
                        // Gán lại sự kiện cho các nút sau khi filter
                        bindProductTableEvents();
                    });
            });
        });
    </script>
@endsection
