@props(['id' => 'captcha', 'name' => 'captcha', 'style' => 'flat', 'label' => 'Verification Code'])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end" {{ $attributes }}>
    <!-- Captcha Image -->
    <div class="md:col-span-2">
        <x-input-label for="captcha-image" :value="__($label)" />
        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg border border-gray-300 dark:border-gray-600 flex items-center justify-between">
            <div id="captcha-image" class="flex-1 flex items-center justify-center">
                @php
                    try {
                        echo captcha_img($style);
                    } catch (\Exception $e) {
                        echo '<div class="text-sm text-gray-600">Captcha unavailable</div>';
                    }
                @endphp
            </div>
            <button
                type="button"
                id="refreshCaptcha"
                class="ml-3 px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition border border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary-600"
                title="Refresh captcha"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Captcha Input -->
    <div>
        <x-input-label for="{{ $name }}" :value="__('Enter Code')" />
        <x-text-input
            id="{{ $name }}"
            class="block w-full text-center tracking-widest"
            type="text"
            name="{{ $name }}"
            placeholder="Code"
            required
            autocomplete="off"
            maxlength="6"
        />
        <x-input-error :messages="$errors->get($name)" />
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const refreshBtn = document.getElementById('refreshCaptcha');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const captchaImage = document.getElementById('captcha-image');

                // Show loading state
                captchaImage.style.opacity = '0.6';

                // Fetch new captcha
                fetch('{{ route("captcha.refresh") }}')
                    .then(response => response.text())
                    .then(html => {
                        captchaImage.innerHTML = html;
                        document.getElementById('{{ $name }}').value = '';
                        document.getElementById('{{ $name }}').focus();
                        captchaImage.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error refreshing captcha:', error);
                        captchaImage.style.opacity = '1';
                    });
            });
        }
    });
</script>
