@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')
    <div id="successMsg" class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Thêm danh mục thành công!
    </div>
    <div id="successMsg-delete" class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Xóa danh mục thành công!
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Nút "Add" -->
                    <button 
                        id="toggleFormBtn" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-4">
                        Add
                    </button>
                
                    <!-- Form được ẩn mặc định -->
                    <form 
                        id="categoryForm" 
                        data-url="{{ route('admin.category') }}" 
                        enctype="multipart/form-data"
                        class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md space-y-4 hidden">
                        
                        @csrf <!-- CSRF token -->
                
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thêm danh mục</h2>
                
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
                            value="{{ old('name') }}" required>
                        
                            <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">Danh mục đã tồn tại</p>
                           
                        </div>
                
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            <input type="text" name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                        </div>
                
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                            Lưu
                        </button>

                    </form>

                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Description</th>
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach($categories as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->name }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->description }}</td>
                                <td class="px-4 py-2 border-b space-x-2">
                                    <a href="#"
                                       class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition btn-edit"
                                       data-id="{{ $item->id }}"
                                       data-name="{{ $item->name }}"
                                       data-description="{{ $item->description }}">
                                       Edit
                                    </a>
                                    <button
                                        id="deleteBtn"
                                        class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                        data-id="{{ $item->id }}"
                                        data-url="{{ route('admin.category.destroy', $item->id) }}">
                                        Delete
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

    <!-- Form sửa danh mục -->
    <form id="categoryForm-edit" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
        bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-md space-y-4"
        data-url="{{ route('admin.category') }}" enctype="multipart/form-data">

@csrf
<h2 class="text-xl font-semibold text-gray-800 mb-4">Sửa danh mục</h2>

<input type="hidden" name="id" id="category_id"> <!-- ID dùng khi edit -->

<div>
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
    <input type="text" name="name" id="name" required
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">Danh mục đã tồn tại</p>
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
    <input type="text" name="description" id="description"
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
</div>

<div class="flex justify-end gap-2">
    <button type="button" id="cancelBtn"
        class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Hủy</button>
    <button type="submit"
        class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">Lưu</button>
</div>
</form>

<!-- Nền mờ khi form hiện -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>



    <script>
        document.getElementById('toggleFormBtn').addEventListener('click', function () {
            const form = document.getElementById('categoryForm');
            form.classList.toggle('hidden');
        });
    </script>
@endsection