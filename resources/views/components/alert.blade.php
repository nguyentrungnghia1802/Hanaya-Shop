@if(session('success') || session('error') || $errors->any())
    <div id="notification-overlay"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div id="notification-box"
             class="max-w-md w-full p-6 rounded-lg shadow-lg bg-white relative animate-fade-in pointer-events-auto">

            {{-- Success --}}
            @if(session('success'))
                <div class="flex items-start gap-3 text-green-800">
                    <svg class="w-5 h-5 mt-1 text-green-600 shrink-0" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            {{-- Error --}}
            @if(session('error'))
                <div class="flex items-start gap-3 text-red-800">
                    <svg class="w-5 h-5 mt-1 text-red-600 shrink-0" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01m-.01-10a9 9 0 110 18 9 9 0 010-18z"/>
                    </svg>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mt-2 text-red-700">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('notification-overlay');
        if (overlay) {
            setTimeout(() => {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => overlay.remove(), 300); // remove sau khi fade out
            }, 1000); // 1 giây
        }
    });
</script>
