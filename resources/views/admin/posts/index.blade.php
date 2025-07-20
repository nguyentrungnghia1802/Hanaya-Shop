@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Quản lý bài viết
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('admin.post.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 font-semibold">Thêm bài viết mới</a>
        
        <!-- Form tìm kiếm -->
        <form method="GET" action="{{ route('admin.post.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm bài viết..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tìm kiếm</button>
            @if(request('search'))
                <a href="{{ route('admin.post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Xóa bộ lọc</a>
            @endif
        </form>
        
        @if(session('success'))
            <div class="text-green-600 font-semibold">{{ session('success') }}</div>
        @endif
    </div>
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">STT</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tác giả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày tạo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $index => $post)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ($posts->currentPage() - 1) * $posts->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-pink-700">{{ $post->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->author->name ?? 'Admin' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($post->status)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Hiển thị</span>
                            @else
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs">Ẩn</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right flex gap-2 justify-end">
                            <a href="{{ route('admin.post.show', ['id' => $post->id]) }}" class="text-blue-600 hover:underline">Xem chi tiết</a>
                            <form action="{{ route('admin.post.destroy', ['id' => $post->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có bài viết nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $posts->appends(request()->query())->links() }}</div>
</div>
@endsection
