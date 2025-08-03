@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Post Details</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-pink-700 mb-4">{{ $post->title }}</h2>
        @if($post->image)
            <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="h-64 w-full object-cover rounded mb-6">
        @endif
        <div class="text-sm text-gray-600 mb-2">{{ $post->created_at->format('d/m/Y') }} by {{ $post->author->name ?? 'Admin' }}</div>
        <div class="prose max-w-none text-gray-800" style="font-size:inherit;">
            {!! $post->content !!}
        </div>
        <div class="flex gap-2 mt-4">
            <a href="{{ route('admin.post.edit', ['id' => $post->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Edit</a>
            <form action="{{ route('admin.post.destroy', ['id' => $post->id]) }}" method="POST" data-confirm-delete data-confirm-message="Are you sure you want to delete this post?">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
            </form>
            <a href="{{ route('admin.post.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Back</a>
        </div>
    </div>
</div>
@endsection