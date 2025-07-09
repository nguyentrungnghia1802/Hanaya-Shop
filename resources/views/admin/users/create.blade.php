@extends('layouts.admin')

@section('header')
    <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
        Thêm tài khoản người dùng
    </h2>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.user.store') }}"
        class="relative z-10 max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg space-y-6 border border-gray-200">
        @csrf

        <div id="user-list" class="space-y-4">
            <div class="user-item grid grid-cols-12 gap-4 items-center">
                <input type="text" name="users[0][name]" placeholder="Tên"
                    class="col-span-4 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <input type="email" name="users[0][email]" placeholder="Email"
                    class="col-span-4 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <input type="password" name="users[0][password]" placeholder="Mật khẩu"
                    class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <button type="button"
                    class="btn-remove-user hidden col-span-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition">
                    X
                </button>
            </div>
        </div>

        <div class="flex justify-center gap-4 pt-4">
            <button type="button" id="btn-add-user"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + Thêm dòng
            </button>
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                💾 Lưu
            </button>
        </div>
    </form>

    <script>
        document.getElementById('btn-add-user').addEventListener('click', function() {
            const list = document.getElementById('user-list');
            const idx = list.children.length;
            const div = document.createElement('div');
            div.className = 'user-item grid grid-cols-12 gap-4 items-center';

            div.innerHTML = `
            <input type="text" name="users[${idx}][name]" placeholder="Tên"
                   class="col-span-4 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                   required>
            <input type="email" name="users[${idx}][email]" placeholder="Email"
                   class="col-span-4 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                   required>
            <input type="password" name="users[${idx}][password]" placeholder="Mật khẩu"
                   class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                   required>
            <button type="button"
                    class="btn-remove-user col-span-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition">
                X
            </button>
        `;
            list.appendChild(div);

            div.querySelector('.btn-remove-user').addEventListener('click', function() {
                div.remove();
            });
        });

        document.querySelectorAll('.btn-remove-user').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.user-item').remove();
            });
        });
    </script>
@endsection
