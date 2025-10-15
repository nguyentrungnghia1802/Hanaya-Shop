@extends('layouts.admin')

@section('header')
    <!-- Page header title -->
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categories') }}
    </h2>
@endsection

@section('content')
    <!-- Notification messages for successful operations -->
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Category added successfully!
    </div>
    <div id="successMsg-delete"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Category deleted successfully!
    </div>
    <div id="successMsg-edit"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Category updated successfully!
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Search input --}}
                    <form id="categorySearchForm" class="flex gap-2 mb-4 max-w-sm">
                        <input type="text" id="searchCategoryInput" placeholder="Search category..."
                            class="border px-3 py-2 rounded w-full" autocomplete="off">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 rounded">Search</button>
                    </form>

                    <!-- Add category button -->
                    <a href="{{ route('admin.category.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-[20px] inline-block transition duration-200">
                        Add
                    </a>

                    <!-- Category list table -->
                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach ($categories as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $item->name }}</td>
                                    <td class="px-4 py-2 border-b space-x-2">
                                        <!-- Edit button -->
                                        <a href="{{ route('admin.category.edit', $item->id) }}"
                                            class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                            Edit
                                        </a>

                                        <!-- Delete button -->
                                        <button type="button"
                                            class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('admin.category.destroy', $item->id) }}">
                                            Delete
                                        </button>

                                        <!-- View full detail button (redirects to detail page) -->
                                        <a href="{{ route('admin.category.show', $item->id) }}"
                                            class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                            View Details
                                        </a>

                                        <!-- Quick View (opens modal) -->
                                        <button type="button"
                                            class="inline-block px-3 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition btn-view"
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('admin.category.show', $item->id) }}">
                                            Quick View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal popup for category quick view -->
    <div id="categoryDetail" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-xl relative">
            <h2 class="text-xl font-bold mb-4">Category Information</h2>

            <p><strong>ID:</strong> <span id="view-id" class="text-gray-700"></span></p>
            <p><strong>Name:</strong> <span id="view-name" class="text-gray-700"></span></p>

            <p class="mt-2"><strong>Description:</strong></p>
            <div id="view-description"
                class="border p-3 rounded bg-gray-50 text-sm text-gray-800 max-h-[300px] overflow-y-auto"></div>

            <p class="mt-4"><strong>Image:</strong></p>
            <img id="view-image" src="" alt="Category image" class="w-48 h-auto mt-2 border rounded">

            <!-- Close modal button -->
            <button id="closeDetail"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-lg">&times;</button>
        </div>
    </div>

    <!-- Black background overlay behind modal -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>

    <!-- Client-side JavaScript logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bind events to the category table after initial load
            function bindCategoryTableEvents() {
                // Bind Quick View buttons - GIỮ NGUYÊN CODE CŨ
                document.querySelectorAll('.btn-view').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.dataset.url + '?ajax=1';

                        // Show loading placeholders
                        document.getElementById('view-id').textContent = 'Loading...';
                        document.getElementById('view-name').textContent = 'Loading...';
                        document.getElementById('view-description').innerHTML = 'Loading...';
                        document.getElementById('view-image').src = '';

                        // Show modal and overlay
                        document.getElementById('categoryDetail').classList.remove('hidden');
                        document.getElementById('overlay').classList.remove('hidden');

                        // Fetch category data via Ajax
                        fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(
                                        `HTTP ${response.status}: ${response.statusText}`);
                                }
                                const contentType = response.headers.get('Content-Type') || '';
                                if (!contentType.includes('application/json')) {
                                    throw new Error('Response is not JSON');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success === false) {
                                    throw new Error(data.message || 'Server returned an error');
                                }

                                // Display category data in modal
                                document.getElementById('view-id').textContent = data.id ||
                                    'N/A';
                                document.getElementById('view-name').textContent = data.name ||
                                    'N/A';
                                document.getElementById('view-description').innerHTML = data
                                    .description || '<em>No description</em>';
                                document.getElementById('view-image').src = data.image_path ||
                                    '/images/base.jpg';
                            })
                            .catch(error => {
                                alert('Error loading category details: ' + error.message);
                                document.getElementById('categoryDetail').classList.add(
                                    'hidden');
                                document.getElementById('overlay').classList.add('hidden');
                            });
                    });
                });

                // Bind Delete buttons - GIỮ NGUYÊN CODE CŨ
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (confirm('Are you sure you want to delete this category?')) {
                            const url = this.dataset.url;
                            fetch(url, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        alert('Error deleting category');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Error deleting category');
                                });
                        }
                    });
                });
            }

            // Bind events on initial load
            bindCategoryTableEvents();

            // Handle modal close events - GIỮ NGUYÊN CODE CŨ
            document.getElementById('closeDetail').addEventListener('click', () => {
                document.getElementById('categoryDetail').classList.add('hidden');
                document.getElementById('overlay').classList.add('hidden');
            });
            document.getElementById('overlay').addEventListener('click', () => {
                document.getElementById('categoryDetail').classList.add('hidden');
                document.getElementById('overlay').classList.add('hidden');
            });

            // Handle search form submission
            document.getElementById('categorySearchForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const keyword = document.getElementById('searchCategoryInput').value.trim();
                fetch('{{ route('admin.category.search') }}?query=' + encodeURIComponent(keyword), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        document.querySelector('table tbody').innerHTML = data.html;
                        bindCategoryTableEvents(); // Rebind events after filtering
                    })
                    .catch(error => {
                        console.error('Error fetching categories:', error);
                    });
            });
        });
    </script>
@endsection
