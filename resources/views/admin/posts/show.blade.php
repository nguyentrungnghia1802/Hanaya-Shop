@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chi tiết bài viết</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-pink-700 mb-4">{{ $post->title }}</h2>
        @if($post->image)
            <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="h-64 w-full object-cover rounded mb-6">
        @endif
        <div class="text-sm text-gray-600 mb-2">{{ $post->created_at->format('d/m/Y') }} bởi {{ $post->author->name ?? 'Admin' }}</div>
        <div class="prose max-w-none text-gray-800" style="font-size:inherit;">
            {!! $post->content !!}
        </div>
        <div class="flex gap-2 mt-4">
            <a href="{{ route('admin.post.edit', ['id' => $post->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Sửa</a>
            <form action="{{ route('admin.post.destroy', ['id' => $post->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Xóa</button>
            </form>
            <a href="{{ route('admin.post.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Quay lại</a>
        </div>
    </div>
</div>
@endsection
