@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm bài viết mới</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        @if(isset($edit) && $edit && isset($post))
            <form method="POST" action="{{ route('admin.post.update', ['id' => $post->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nội dung</label>
                    <textarea name="content" class="w-full px-4 py-2 border rounded description">{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Ảnh</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    @if($post->image)
                        <img id="previewImage" src="{{ asset('images/posts/' . $post->image) }}" alt="Ảnh hiện tại" class="h-32 mt-2 rounded">
                    @else
                        <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                    @endif
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" {{ $post->status ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ !$post->status ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Cập nhật</button>
                    <button type="button" onclick="confirmCancel()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Huỷ</button>
                </div>
            </form>
        @else
            <form method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Tiêu đề</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nội dung</label>
                    <textarea name="content" class="w-full px-4 py-2 border rounded description">{{ old('content') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Ảnh</label>
                    <input type="file" name="image" id="imageInput" class="w-full px-4 py-2 border rounded">
                    <img id="previewImage" src="#" alt="Preview" class="h-32 mt-2 rounded" style="display:none;">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2 border rounded">
                        <option value="1" selected>Hiển thị</option>
                        <option value="0">Ẩn</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Tạo mới</button>
                    <button type="button" onclick="confirmCancel()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Huỷ</button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
function confirmCancel() {
    if (confirm('Bạn có chắc chắn muốn huỷ? Dữ liệu đã nhập sẽ bị mất.')) {
        window.location.href = '{{ route("admin.post.index") }}';
    }
}
</script>
@endsection
