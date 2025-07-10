@extends('layouts.admin')

@section('header')
    <!-- Page header -->
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit User Account
    </h2>
@endsection

@section('content')
    <!-- Form to edit user information -->
    <form method="POST" action="{{ route('admin.user.update', $user->id) }}"
        class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <!-- Name field -->
        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="border px-2 py-1 rounded w-full" required>
        </div>

        <!-- Email field -->
        <div>
            <label class="block mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="border px-2 py-1 rounded w-full" required>
        </div>

        <!-- Password field (optional) -->
        <div>
            <label class="block mb-1">New Password (leave blank to keep current password)</label>
            <input type="password" name="password" class="border px-2 py-1 rounded w-full">
        </div>

        <!-- Submit button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            Save
        </button>
    </form>
@endsection
