@extends('layouts.admin')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Sửa tài khoản người dùng
    </h2>
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.user.update', $user->id) }}"
        class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-1">Tên</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border px-2 py-1 rounded w-full"
                required>
        </div>
        <div>
            <label class="block mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="border px-2 py-1 rounded w-full" required>
        </div>
        <div>
            <label class="block mb-1">Mật khẩu mới (bỏ qua nếu không đổi)</label>
            <input type="password" name="password" class="border px-2 py-1 rounded w-full">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Lưu</button>
    </form>
@endsection
