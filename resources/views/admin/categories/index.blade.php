@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')
    <div id="successMsg" class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Thêm loại hoa thành công!
    </div>
    <div id="successMsg-delete" class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Xóa loại hoa thành công!
    </div>
    <div id="successMsg-edit" class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Sửa loại hoa thành công!
    </div>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm loại hoa..." 
                        class="border px-3 py-2 rounded mb-4 w-full max-w-sm"> <br>

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
                        class="mb-20 max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md space-y-4 hidden">
                        
                        @csrf <!-- CSRF token -->
                
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Thêm loại hoa</h2>
                
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên loại hoa</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"
                            value="{{ old('name') }}" required>
                        
                            <p id="errorMsg" class="hidden text-red-500 text-sm mt-1">loại hoa đã tồn tại</p>
                           
                        </div>
                
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                            {{-- <input type="text" name="description" id="description" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"> --}}
                            <textarea type="text" name="description" id="description" class="w-full h-[300px] px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none"></textarea>
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
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach($categories as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->name }}</td>
                                <td class="px-4 py-2 border-b space-x-2">
                                    <button
                                       id="btn-edit"
                                       class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition btn-edit"
                                       data-id="{{ $item->id }}"
                                       data-name="{{ $item->name }}"
                                       data-description="{{ $item->description }}">
                                       Edit
                                    </button>
                                    <button
                                        id="deleteBtn"
                                        class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                        data-id="{{ $item->id }}"
                                        data-url="{{ route('admin.category.destroy', $item->id) }}">
                                        Delete
                                    </button>
                                    <button
                                        id="viewBtn"
                                        class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition btn-view"
                                        data-id="{{ $item->id }}"
                                        data-url="#">
                                        view
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

    <!-- Form sửa loại hoa -->
    <form id="categoryForm-edit" 
          class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
        bg-white p-6 rounded-lg shadow-lg z-50 w-full max-w-3xl space-y-4"
          data-url="{{ route('admin.category.update', ['id' => '__ID__']) }}">

        @csrf
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Sửa loại hoa</h2>
        <input type="hidden" name="id" id="category_id"> <!-- ID dùng khi edit -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên loại hoa</label>
            <input type="text" name="name" id="name-edit" required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            <p id="errorMsg-edit" class="hidden text-red-500 text-sm mt-1">loại hoa đã tồn tại</p>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
            <textarea type="text" name="description" id="description-edit" cols="30" rows="10" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            </textarea>
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

<!-- Load TinyMCE từ CDN -->
<!-- TinyMCE đã được thêm ở <head> -->
<!-- Load CKEditor từ CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>


<script>
    document.getElementById('toggleFormBtn').addEventListener('click', function () {
            const form = document.getElementById('categoryForm');
            form.classList.toggle('hidden');
            form.reset(); // Reset form khi mở
    });

    ClassicEditor
    .create(document.querySelector('#description-edit'), {
      toolbar: [
        'undo', 'redo',
        '|', 'bold', 'italic', 'underline',
        '|', 'bulletedList', 'numberedList',
        '|', 'alignment',
        '|', 'link',
        '|', 'removeFormat'
      ]
    }).then(editor => {
      editorInstance = editor;
    })
    .catch(error => {
      console.error(error);
    });
</script>
    
@endsection