@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('admin.users') }}
    </h2>
@endsection

@section('content')
    <div id="successMsg"
        class="hidden fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        {{ __('admin.operation_successful') }}
    </div>

    <div id="errorMsg"
        class="hidden fixed bottom-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        {{ __('admin.cannot_delete_user_with_active_orders') }}
    </div>


    <div class="py-12 px-2 sm:px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search input field -->
                    <form id="userSearchForm" class="flex gap-2 mb-4 max-w-sm">
                        <input type="text" id="searchUserInput" placeholder="{{ __('admin.search_for_a_user') }}"
                            class="border px-3 py-2 rounded w-full" autocomplete="off">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 rounded">{{ __('admin.search') }}</button>
                    </form>

                    <!-- Action buttons -->
                    <div class="mb-4 flex flex-wrap gap-2">
                        <a href="{{ route('admin.user.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition">
                            {{ __('admin.add') }}
                        </a>
                        <button id="btn-delete-multi"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition">
                            {{ __('admin.delete') }}
                        </button>
                    </div>

                    <!-- Users table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border border-gray-300 text-sm">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                                <tr>
                                    <th class="px-2 sm:px-4 py-2 border-b">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 border-b">#</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.name') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.email') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.role') }}</th>
                                    <th class="px-2 sm:px-4 py-2 border-b">{{ __('admin.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td class="px-2 sm:px-4 py-2 border-b">
                                            <input type="checkbox" class="check-user" value="{{ $user->id }}">
                                        </td>
                                        <td class="px-2 sm:px-4 py-2 border-b">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                                        <td class="px-2 sm:px-4 py-2 border-b max-w-[120px] truncate">{{ $user->name }}</td>
                                        <td class="px-2 sm:px-4 py-2 border-b max-w-[160px] truncate">{{ $user->email }}</td>
                                        <td class="px-2 sm:px-4 py-2 border-b">{{ $user->role }}</td>
                                        <td class="px-2 sm:px-4 py-2 border-b">
                                            <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                                    class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                                    {{ __('admin.edit') }}
                                                </a>
                                                <button type="button"
                                                    class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                                    data-id="{{ $user->id }}">
                                                    {{ __('admin.delete') }}
                                                </button>
                                                <a href="{{ route('admin.user.show', $user->id) }}"
                                                    class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                                    {{ __('admin.view_details') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination links --}}
                    <div class="mt-6 flex justify-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const successMsg = document.getElementById('successMsg');

    function showSuccess(message) {
        successMsg.textContent = message;
        successMsg.classList.remove('hidden');
        setTimeout(() => successMsg.classList.add('hidden'), 3000);
    }

    function showError(message) {
        const errorMsg = document.getElementById('errorMsg');
        errorMsg.textContent = message;
        errorMsg.classList.remove('hidden');
        setTimeout(() => errorMsg.classList.add('hidden'), 4000);
    }


    function bindUserTableEvents() {
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.onclick = function () {
                const userId = this.dataset.id;
                if (!confirm('Are you sure you want to delete this account?')) return;

                fetch(`{{ url('admin/user') }}/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    console.log('Response status:', res.status);
                    return res.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        this.closest('tr').remove();
                        showSuccess("{{ __('admin.message_account_delete') }}");

                    } else{
                        showError("{{ __('admin.cannot_delete_user_with_active_orders') }}");
                    }
                })
                .catch(err => console.error('Delete error:', err));
            };
        });
    }

    document.getElementById('btn-delete-multi').addEventListener('click', function () {
        const ids = Array.from(document.querySelectorAll('.check-user:checked'))
            .map(cb => cb.value);

        if (ids.length === 0) return alert('Please select at least one account to delete!');
        if (!confirm('Are you sure you want to delete the selected accounts?')) return;

        fetch(`{{ route('admin.user.destroy.multiple') }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.check-user:checked').forEach(cb => cb.closest('tr').remove());
                showSuccess("{{ __('admin.message_selected_account_delete') }}");
            }
        })
        .catch(err => console.error(err));
    });

    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.check-user').forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('userSearchForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const keyword = document.getElementById('searchUserInput').value.trim();

        fetch(`{{ route('admin.user.search') }}?query=${encodeURIComponent(keyword)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            document.querySelector('table tbody').innerHTML = data.html;
            bindUserTableEvents(); // Rebind after search
        })
        .catch(err => console.error('Error searching users:', err));
    });

    bindUserTableEvents();
});
</script>

@endsection
