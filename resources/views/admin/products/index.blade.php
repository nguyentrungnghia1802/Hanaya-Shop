@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
@endsection

@section('content')
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.product.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-block mb-10">
                        add
                    </a>

                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                            <tr>
                                <th class="px-4 py-2 border-b">#</th>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Description</th>
                                <th class="px-4 py-2 border-b">Price</th>
                                <th class="px-4 py-2 border-b">quantity</th>
                                <th class="px-4 py-2 border-b">Category</th>
                                <th class="px-4 py-2 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach($products as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border-b">{{ $item->id }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->name }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->descriptions }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->price }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->stock_quantity }}</td>
                                <td class="px-4 py-2 border-b">{{ $item->category->name }}</td>
                                <td class="px-4 py-2 border-b space-x-2">
                                    <button
                                       class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">
                                       Edit
                                    </button>
                                    <button
                                        class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition">                                 
                                        Delete
                                    </button>
                                    <button
                                        class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-red-600 transition">                                 
                                        view
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection