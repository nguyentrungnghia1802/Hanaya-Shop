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

    <!-- Modal hiển thị thông tin loại hoa -->
<div id="categoryDetail" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-xl relative">
        <h2 class="text-xl font-bold mb-4">Thông tin loại hoa</h2>
        
        <p><strong>ID:</strong> <span id="view-id" class="text-gray-700"></span></p>
        <p><strong>Tên:</strong> <span id="view-name" class="text-gray-700"></span></p>
        
        <p class="mt-2"><strong>Mô tả:</strong></p>
        <div id="view-description" class="border p-3 rounded bg-gray-50 text-sm text-gray-800 max-h-[300px] overflow-y-auto"></div>
        
        <p class="mt-4"><strong>Ảnh:</strong></p>
        <img id="view-image" src="" alt="Ảnh loại hoa" class="w-48 h-auto mt-2 border rounded">

        <!-- Nút đóng -->
        <button id="closeDetail" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-lg">&times;</button>
    </div>
</div>

<!-- Nền mờ -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>

<script>
document.querySelectorAll('.btn-view').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;

        fetch(`/admin/category/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('view-id').textContent = data.id;
                document.getElementById('view-name').textContent = data.name;
                document.getElementById('view-description').innerHTML = data.description || '<em>Không có mô tả</em>';
                document.getElementById('view-image').src = data.image_path;

                document.getElementById('categoryDetail').classList.remove('hidden');
                document.getElementById('overlay').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Lỗi khi tải thông tin:', error);
            });
    });
});

document.getElementById('closeDetail').addEventListener('click', function () {
    document.getElementById('categoryDetail').classList.add('hidden');
    document.getElementById('overlay').classList.add('hidden');
});

document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

        if (id.includes(keyword) || name.includes(keyword)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("table tbody");

    tableBody.addEventListener("click", async function (e) {
        const deleteBtn = e.target.closest(".btn-delete");

        if (deleteBtn) {
            e.preventDefault();

            const id = deleteBtn.dataset.id;
            const url = deleteBtn.dataset.url;

            if (confirm("Bạn có chắc chắn muốn xóa?")) {
                try {
                    const response = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                            Accept: "application/json",
                        },
                    });

                    if (response.ok) {
                        const row = deleteBtn.closest("tr");
                        row.remove();

                        const successMsg =
                            document.getElementById("successMsg-delete");
                        successMsg.classList.remove("hidden");
                        setTimeout(() => {
                            successMsg.classList.add("hidden");
                        }, 3000);
                    } else {
                        console.error("Xóa thất bại");
                    }
                } catch (err) {
                    console.error("Lỗi khi xóa danh mục:", err);
                }
            }
        }
    });
});

</script>

@endsection