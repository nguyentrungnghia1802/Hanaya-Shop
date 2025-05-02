@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form 
                    id="productForm" 
                    class="mb-20 max-w-md mx-auto bg-white p-6 rounded-lg shadow-md space-y-4">
                    
                    @csrf <!-- CSRF token -->
            
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Thêm sản phẩm</h2>
            
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
                        value="{{ old('name') }}" required>
                    
                        <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">Danh mục đã tồn tại</p>
                       
                    </div>
            
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <input type="text" name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá</label>
                        <div class="flex items-center">
                            <input type="number" name="price" id="price" min="0" step="1000"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                            <span class="ml-2">VNĐ</span>
                        </div>
                    </div>
                    
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                        <input type="number" name="quantity" id="quantity" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Loại món ăn</label>
                        <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                            <option value="">-- Chọn loại sản phẩm --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Ảnh sản phẩm</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
                    </div>
                    
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">Ảnh hiện tại:</p>
                        <img id="create-image" src="{{ asset('images/base.jpg') }}" alt="Ảnh món ăn" width="150" class="mt-1 rounded border">
                    </div>
            
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                        Lưu
                    </button>

                </form>
                </div>
            </div>
        </div>
    </div>
@endsection