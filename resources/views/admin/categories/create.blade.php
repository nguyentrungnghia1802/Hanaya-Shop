@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Thêm loại hoa') }}
    </h2>
@endsection
@section('content')
   <!-- Form được ẩn mặc định -->
                    <form 
                        id="categoryForm" 
                        action="{{ route('admin.category.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="mb-20 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md space-y-4">
                        
                        @csrf <!-- CSRF token -->
                
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thêm loại hoa</h2>
                
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên loại hoa</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">loại hoa đã tồn tại</p>
                           
                        </div>

                        <div>
                            <label for="image">Ảnh loại hoa:</label>
                            <input type="file" name="image" id="imageInput" accept="image/*">
                            <p>Ảnh hiện tại:</p>
                            <img id="previewImage" src="{{ asset('images/base.jpg') }}" alt="Ảnh loại hoa" width="150">
                        </div>
                
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            {{-- <input type="text" name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"> --}}
                            <textarea type="text" name="description" id="description" class="w-full h-[300px] px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none description"></textarea>
                        </div>
                
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-200">
                            Lưu
                        </button>

                    </form>
@endsection