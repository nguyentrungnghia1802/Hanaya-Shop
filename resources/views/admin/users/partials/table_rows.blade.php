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