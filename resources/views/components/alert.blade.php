
@if(session('error'))
    <div class="flex items-start gap-3 p-4 mb-4 rounded-lg border border-red-300 bg-red-50 text-red-800 animate-fade-in">
        <svg class="w-5 h-5 mt-1 text-red-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v2m0 4h.01m-.01-10a9 9 0 110 18 9 9 0 010-18z"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if(session('success'))
    <div class="flex items-start gap-3 p-4 mb-4 rounded-lg border border-green-300 bg-green-50 text-green-800 animate-fade-in">
        <svg class="w-5 h-5 mt-1 text-green-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 13l4 4L19 7"/>
        </svg>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
