@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Product') }}
    </h2>
@endsection
@section('content')
<form id="productForm-edit"
      class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
      bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-3xl space-y-4"
      action="{{ route('admin.product.update', $product->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm</label>
        <input type="text" name="name" id="name-edit" value="{{ old('name', $product->name) }}" required
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    <div>
        <label for="descriptions" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
        <textarea name="descriptions" id="descriptions-edit" cols="30" rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">{{ old('descriptions', $product->descriptions) }}</textarea>
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá</label>
        <input type="number" name="price" id="price-edit" value="{{ old('price', $product->price) }}" required min="0"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    <div>
        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
        <input type="number" name="stock_quantity" id="stock_quantity-edit" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Loại sản phẩm</label>
        <select name="category_id" id="category_id-edit" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            <option value="">-- Chọn loại sản phẩm --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Ảnh sản phẩm</label>
        <input type="file" name="image_url" id="imageInput" accept="image/*">
    </div>

    @if($product->image_url)
        <p>Ảnh hiện tại:</p>
        <img id="previewImage" src="{{ asset('images/' . $product->image_url) }}" alt="Ảnh sản phẩm" width="150">
    @endif

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.product') }}"
           class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
            Hủy
        </a>
        <button type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            Lưu
        </button>
    </div>
</form>
@endsection

<script>
    document.getElementById('imageInput')?.addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('previewImage').src = URL.createObjectURL(file);
        }
    });
</script>