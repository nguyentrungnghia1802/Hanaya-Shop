@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <p>{{ __("Chào mừng đến với Hanaya Shop Dashboard!") }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Category Card -->
                <div class="bg-blue-500 text-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-3xl font-bold mb-2">
                        {{ $categoryCount ?? 0 }}
                    </div>
                    <div class="text-lg">Danh mục</div>
                </div>
                <!-- Product Card -->
                <div class="bg-green-500 text-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-3xl font-bold mb-2">
                        {{ $productCount ?? 0 }}
                    </div>
                    <div class="text-lg">Sản phẩm</div>
                </div>
                <!-- User Card -->
                <div class="bg-pink-500 text-white rounded-lg shadow p-6 flex flex-col items-center">
                    <div class="text-3xl font-bold mb-2">
                        {{ $userCount ?? 0 }}
                    </div>
                    <div class="text-lg">Người dùng</div>
                </div>
            </div>
        </div>
    </div>
@endsection