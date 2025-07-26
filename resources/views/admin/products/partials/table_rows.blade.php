@if($products->count())
    @foreach($products as $item)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-4 py-2 border-b">{{ $item->id }}</td>
        <td class="px-4 py-2 border-b">{{ $item->name }}</td>
        <td class="px-4 py-2 border-b max-w-xs truncate" title="{{ $item->descriptions }}">
            {{ \Illuminate\Support\Str::limit($item->descriptions, 50) }}
        </td>
        <td class="px-4 py-2 border-b">{{ $item->price }}</td>
        <td class="px-4 py-2 border-b">{{ $item->stock_quantity }}</td>
        <td class="px-4 py-2 border-b">{{ $item->category ? $item->category->name : '' }}</td>
        <td class="px-4 py-2 border-b">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.product.edit', $item->id) }}"
                    class="px-4 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                    Edit
                </a>
                                <form action="{{ route('admin.product.destroy', $item->id) }}" method="POST" data-confirm-delete data-confirm-message="Are you sure you want to delete?" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition">
                        Delete
                    </button>
                </form>
                <a href="{{ route('admin.product.show', $item->id) }}"
                    class="px-4 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                    View Details
                </a>
                <button type="button"
                    class="px-4 py-1 bg-gray-500 text-white text-xs font-medium rounded hover:bg-gray-600 transition btn-view-product"
                    data-id="{{ $item->id }}"
                    data-url="{{ route('admin.product.show', $item->id) }}">
                    Quick View
                </button>
            </div>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="px-4 py-2 border-b text-center text-gray-500">No products found.</td>
    </tr>
@endif