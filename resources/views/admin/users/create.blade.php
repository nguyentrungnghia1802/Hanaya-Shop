@extends('layouts.admin')

@section('header')
    <!-- Page header title -->
    <h2 class="font-bold text-2xl text-gray-900 leading-tight text-center">
        {{ __('admin.add_user_account') }}
    </h2>
@endsection

@section('content')
    <!-- Form to submit multiple users -->
    <form method="POST" action="{{ route('admin.user.store') }}"
        class="relative z-10 max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg space-y-6 border border-gray-200">
        @csrf

        <!-- Dynamic user input fields list -->
        <div id="user-list" class="space-y-4">
            <!-- Default row for first user -->
            <div class="user-item grid grid-cols-12 gap-4 items-center">
                <input type="text" name="users[0][name]" placeholder="{{ __('admin.name') }}"
                    class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <input type="email" name="users[0][email]" placeholder="{{ __('admin.email') }}"
                    class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <input type="password" name="users[0][password]" placeholder="{{ __('admin.password') }}"
                    class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                    required>
                <select name="users[0][role]" class="col-span-2 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center" required>
                    <option value="user" selected>{{ __('admin.user') }}</option>
                    <option value="admin">{{ __('admin.admin') }}</option>
                </select>
                <button type="button"
                    class="btn-remove-user hidden col-span-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition">
                    X
                </button>
            </div>
        </div>

        <!-- Add & Submit buttons -->
        <div class="flex justify-center gap-4 pt-4">
            <button type="button" id="btn-add-user"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                + {{ __('admin.add_row') }}
            </button>
            <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                💾 {{ __('admin.save') }}
            </button>
        </div>
    </form>

    <!-- JavaScript for dynamic field handling -->
    <script>
        // Add new user input row
        document.getElementById('btn-add-user').addEventListener('click', function () {
            const list = document.getElementById('user-list');
            const idx = list.children.length;
            const div = document.createElement('div');
            div.className = 'user-item grid grid-cols-12 gap-4 items-center';

            // Create input fields dynamically
            div.innerHTML = `
                <input type="text" name="users[${idx}][name]" placeholder="{{ __('admin.name') }}"
                       class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                       required>
                <input type="email" name="users[${idx}][email]" placeholder="{{ __('admin.email') }}"
                       class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                       required>
                <input type="password" name="users[${idx}][password]" placeholder="{{ __('admin.password') }}"
                       class="col-span-3 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center"
                       required>
                <select name="users[${idx}][role]" class="col-span-2 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition text-center" required>
                    <option value="user" selected>{{ __('admin.user') }}</option>
                    <option value="admin">{{ __('admin.admin') }}</option>
                </select>
                <button type="button"
                        class="btn-remove-user col-span-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition">
                    X
                </button>
            `;

            // Append the new row to the list
            list.appendChild(div);

            // Add event listener to remove button of this row
            div.querySelector('.btn-remove-user').addEventListener('click', function () {
                div.remove();
            });
        });

        // Remove user row when remove button is clicked
        document.querySelectorAll('.btn-remove-user').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('.user-item').remove();
            });
        });
    </script>
@endsection
