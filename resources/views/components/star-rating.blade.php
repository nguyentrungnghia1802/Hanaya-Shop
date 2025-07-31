{{-- Star Rating Component --}}
@props([
    'rating' => 5,
    'maxRating' => 5,
    'size' => 'base', // sm, base, lg, xl
    'showText' => false,
    'readonly' => true
])

@php
    $rating = (float) $rating;
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = $maxRating - $fullStars - ($hasHalfStar ? 1 : 0);
    
    $sizeClasses = [
        'sm' => 'text-sm',
        'base' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['base'];
@endphp

<div class="flex items-center space-x-1 {{ $sizeClass }}">
    {{-- Full Stars --}}
    @for($i = 0; $i < $fullStars; $i++)
        <span class="text-yellow-400">★</span>
    @endfor
    
    {{-- Half Star --}}
    @if($hasHalfStar)
        <span class="relative">
            <span class="text-gray-300">★</span>
            <span class="absolute inset-0 text-yellow-400 overflow-hidden" style="width: 50%;">★</span>
        </span>
    @endif
    
    {{-- Empty Stars --}}
    @for($i = 0; $i < $emptyStars; $i++)
        <span class="text-gray-300">★</span>
    @endfor
    
    {{-- Rating Text --}}
    @if($showText)
        <span class="ml-2 text-sm text-gray-600">
            {{ number_format($rating, 1) }}/{{ $maxRating }}
        </span>
    @endif
</div>
