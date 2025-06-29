@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Categories') }}
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
                    <a 
                        href="{{ route('admin.category.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mb-[20px] inline-block transition duration-200">
                        Add
                    </a>
                

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
                                    <a
                                       id="btn-edit"
                                       href="{{ route('admin.category.edit', $item->id) }}"
                                       class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition btn-edit">
                                       Edit
                                    </a>
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
                                         data-url="{{ route('admin.category.show', $item->id) }}">
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

<div id="categoryDetail" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 
    bg-white shadow-lg rounded-lg p-6 z-50 w-full max-w-xl">
    <h2 class="text-xl font-bold mb-4">Thông tin loại hoa</h2>
    <p><strong>ID:</strong> <span id="view-id"></span></p>
    <p><strong>Tên:</strong> <span id="view-name"></span></p>
    <p><strong>Mô tả:</strong></p>
    <div id="view-description" class="border p-2 rounded bg-gray-50 mb-4"></div>
    <p><strong>Ảnh:</strong></p>
    <img id="view-image" src="" alt="Ảnh loại hoa" class="w-48 h-auto border rounded">
    <div class="mt-4 text-right">
        <button id="closeDetail" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Đóng
        </button>
    </div>
</div>

<!-- Nền mờ -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>
    
<script>
document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', function () {
        const categoryId = this.getAttribute('data-id');

        fetch(`/admin/category/${id}`) // Route cần trả về JSON
            .then(response => response.json())
            .then(data => {
                // Hiển thị dữ liệu vào form
                document.getElementById('view-id').textContent = data.id;
                document.getElementById('view-name').textContent = data.name;

                // CHÚ Ý: sử dụng innerHTML để render thẻ HTML trong description
                document.getElementById('view-description').innerHTML = data.description || '(Không có mô tả)';

                document.getElementById('view-image').src = data.image_path 
                    ? `/images/${data.image_path}` 
                    : '/images/base.jpg';

                document.getElementById('categoryDetail').classList.remove('hidden');
                document.getElementById('overlay').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Lỗi khi fetch category:', error);
            });
    });
});

// Đóng form
document.getElementById('closeDetail').addEventListener('click', function () {
    document.getElementById('categoryDetail').classList.add('hidden');
    document.getElementById('overlay').classList.add('hidden');
});
</script>

@endsection