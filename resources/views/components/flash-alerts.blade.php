@if (session('success') || session('error') || $errors->any() || session('warning') || session('info'))
    <div x-data="{ show: true }" x-show="show" class="px-4 pt-2 space-y-3">
        {{-- Success Message --}}
        @if (session('success'))
            <div class="flash-success border flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="h-5 w-5"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" @click="show = false" class="opacity-60 hover:opacity-100 transition-opacity">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.querySelector('.flash-success');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 4000);
            </script>
        @endif

        {{-- Error/Validation Messages --}}
        @if (session('error') || $errors->any())
            <div class="flash-error border flex items-start justify-between" role="alert">
                <div class="flex items-start gap-2">
                    <i data-lucide="alert-circle" class="h-5 w-5 mt-0.5"></i>
                    <div>
                        <span
                            class="text-sm font-semibold">{{ session('error') ?? 'There were some issues with your submission:' }}</span>
                        @if ($errors->any())
                            <ul class="text-xs list-disc list-inside mt-1 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                <button type="button" @click="show = false" class="opacity-60 hover:opacity-100 transition-opacity">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif

        {{-- Warning Message --}}
        @if (session('warning'))
            <div class="flash-warning border flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="h-5 w-5"></i>
                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                </div>
                <button type="button" @click="show = false" class="opacity-60 hover:opacity-100 transition-opacity">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif

        {{-- Info Message --}}
        @if (session('info'))
            <div class="flash-info border flex items-center justify-between" role="alert">
                <div class="flex items-center gap-2">
                    <i data-lucide="info" class="h-5 w-5"></i>
                    <span class="text-sm font-medium">{{ session('info') }}</span>
                </div>
                <button type="button" @click="show = false" class="opacity-60 hover:opacity-100 transition-opacity">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        @endif
    </div>
@endif