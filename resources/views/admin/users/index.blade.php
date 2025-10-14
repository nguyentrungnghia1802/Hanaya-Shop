@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>
@endsection

@section('content')
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        Operation successful!
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search input field -->
                    <input type="text" id="searchInput" placeholder="Search for a user..."
                        class="border px-3 py-2 rounded mb-4 w-full max-w-sm">

                    <!-- Action buttons -->
                    <div class="mb-4 flex flex-wrap gap-2">
                        <a href="{{ route('admin.user.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition">
                            Add User
                        </a>
                        <button id="btn-delete-multi"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition">
                            Delete Selected
                        </button>
                    </div>

                    <!-- Users table -->
                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                <th class="px-4 py-2 border-b"><input type="checkbox" id="checkAll"></th>
                                <th class="px-4 py-2 border-b">ID</th>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Email</th>
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-4 py-2 border-b">
                                        <input type="checkbox" class="check-user" value="{{ $user->id }}">
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $user->id }}</td>
                                    <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border-b">{{ $user->email }}</td>
                                    <td class="px-4 py-2 border-b">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.user.edit', $user->id) }}"
                                                class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                                Edit
                                            </a>
                                            <button type="button"
                                                class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                                data-id="{{ $user->id }}">
                                                Delete
                                            </button>
                                            <a href="{{ route('admin.user.show', $user->id) }}"
                                                class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                View Details
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle all checkboxes
            document.getElementById('checkAll').addEventListener('change', function() {
                document.querySelectorAll('.check-user').forEach(cb => cb.checked = this.checked);
            });

            // Delete a single user
            function bindDeleteButtons() {
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete this account?')) {
                            fetch('{{ route('admin.user.destroy') }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ids: [this.dataset.id]
                                })
                            }).then(res => res.json()).then(data => {
                                if (data.success) location.reload();
                            });
                        }
                    });
                });
            }
            bindDeleteButtons();

            // Delete multiple selected users
            document.getElementById('btn-delete-multi').addEventListener('click', function() {
                const ids = Array.from(document.querySelectorAll('.check-user:checked')).map(cb => cb
                .value);
                if (ids.length === 0) return alert('Please select at least one account to delete!');
                if (confirm('Are you sure you want to delete the selected accounts?')) {
                    fetch('{{ route('admin.user.destroy') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ids
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.success) location.reload();
                    });
                }
            });

            // Search filter: show/hide rows based on input
            document.getElementById('searchInput').addEventListener('input', function() {
    const keyword = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');

    rows.forEach(row => {
        const tds = row.querySelectorAll('td');
        if (tds.length < 5) {
            row.style.display = 'none';
            return;
        }
        const id = (tds[1].innerText || '').trim().toLowerCase();
        const name = (tds[2].innerText || '').trim().toLowerCase();
        const email = (tds[3].innerText || '').trim().toLowerCase();

        if (
            id.includes(keyword) ||
            name.includes(keyword) ||
            email.includes(keyword)
        ) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
        });
    </script>
@endsection
