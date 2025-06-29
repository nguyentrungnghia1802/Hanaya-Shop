@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Category') }}
    </h2>
@endsection
@section('content')
<!-- Form sửa loại hoa -->
    <form id="categoryForm-edit" 
          class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
        bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-3xl space-y-4"
          action="{{ route('admin.category.update', $category->id) }}"
          method="POST" 
          enctype="multipart/form-data">

        @csrf
        @method('PUT')
        <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên loại hoa</label>
        <input type="text" name="name" id="name-edit" value="{{ old('name', $category->name) }}" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
        <p id="errorMsg-edit" class="hidden text-red-500 text-sm mt-1">Loại hoa đã tồn tại</p>
    </div>

    <input type="file" name="image" id="imageInput" accept="images/*" value="{{ $category->image_path }}">

    @if($category->image_path)
        @if ($category->image_path)
            <p>Ảnh hiện tại:</p>
            <img id="previewImage" src="{{ asset('images/' . $category->image_path) }}" alt="Ảnh loại hoa" width="150">
        @endif
    @endif

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" id="description-edit" cols="30" rows="10"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none description">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="flex justify-end gap-2">
        <button type="button" id="cancelBtn"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
            Hủy
        </button>
        <button type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            Lưu
        </button>
    </div>
    </form>
@endsection