<!--
    UNIFIED PASSWORD VALIDATION MODAL
    Used in: Company Edit, Distributor Edit
    This is the ONLY password modal in the application - NO DUPLICATION!

    Features:
    - Displays dynamic password format (DecUrl or DistUrl)
    - Shows current datetime and expected password in frontend
    - Real-time password updates every minute
    - Secure AJAX validation
    - Copy-to-clipboard functionality
    - Theme-friendly UI (Tailwind + Lucide)
-->

<div id="password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4 animate-in fade-in zoom-in duration-200">
        <!-- Header with Close Button -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="lock" class="h-5 w-5 text-red-500"></i>
                <span id="modal-title">Decrypt URLs</span>
            </h3>
            <button type="button" onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <!-- Password Input -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold text-sm mb-2">
                Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="password-input"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Enter password"
                onkeypress="if(event.key==='Enter') submitPassword()">
            <p id="password-error" class="text-red-500 text-xs mt-1 hidden"></p>
        </div>


        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button
                type="button"
                onclick="closePasswordModal()"
                class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 font-semibold text-sm transition">
                Cancel
            </button>
            <button
                type="button"
                id="validate-decrypt-btn"
                onclick="submitPassword()"
                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm transition flex items-center justify-center gap-2">
                <i data-lucide="unlock" class="h-4 w-4"></i> Decrypt
            </button>
        </div>
    </div>
</div>

<!-- GLOBAL PASSWORD MODAL FUNCTIONS - Included Once (Not Duplicated) -->
<script>
    // ============================================================
    // UNIFIED PASSWORD MODAL CONFIGURATION
    // ============================================================
    let passwordModalConfig = {
        decryptType: 'company',      // 'company' or 'distributor'
        decryptId: null,             // Company/Distributor ID
        decryptCallback: null        // Callback function after validation
    };

    // ============================================================
    // CLOSE PASSWORD MODAL
    // ============================================================
    function closePasswordModal() {
        const modal = document.getElementById('password-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Clear inputs
            document.getElementById('password-input').value = '';
            document.getElementById('password-error').classList.add('hidden');
        }
    }

    // ============================================================
    // SHOW PASSWORD MODAL
    // ============================================================
    function showPasswordModal(type = 'company', id = null, callback = null) {
        // Store configuration
        passwordModalConfig.decryptType = type;
        passwordModalConfig.decryptId = id;
        passwordModalConfig.decryptCallback = callback;

        const modal = document.getElementById('password-modal');
        const input = document.getElementById('password-input');

        if (modal && input) {
            input.value = '';
            input.focus();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    // ============================================================
    // SUBMIT AND VALIDATE PASSWORD
    // ============================================================
    function submitPassword() {
        const password = document.getElementById('password-input').value.trim();
        const btn = document.getElementById('validate-decrypt-btn');
        const errorMsg = document.getElementById('password-error');

        if (!password) {
            errorMsg.textContent = 'Please enter the password.';
            errorMsg.classList.remove('hidden');
            return;
        }

        // Show loading state
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg> Validating...
        `;
        errorMsg.classList.add('hidden');

        // Construct endpoint
        const endpoint = `/${passwordModalConfig.decryptType}/${passwordModalConfig.decryptId}/validate-decrypt-password`;

        // Send validation request
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                password: password,
                type: passwordModalConfig.decryptType
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Password is valid, execute callback to decrypt URLs
                if (passwordModalConfig.decryptCallback) {
                    passwordModalConfig.decryptCallback(passwordModalConfig.decryptId);
                }
                closePasswordModal();
            } else {
                // Invalid password
                errorMsg.textContent = '' + (data.message || 'Invalid password.');
                errorMsg.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="unlock" class="h-4 w-4"></i> Decrypt';
                lucide.createIcons();
            }
        })
        .catch(err => {
            console.error('Password validation error:', err);
            errorMsg.textContent = 'An error occurred. Please try again.';
            errorMsg.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="unlock" class="h-4 w-4"></i> Decrypt';
            lucide.createIcons();
        });
    }

    // ============================================================
    // EVENT LISTENERS
    // ============================================================

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('password-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closePasswordModal();
            }
        }
    });

    // Close modal when clicking outside
    const modal = document.getElementById('password-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordModal();
            }
        });
    }

    // Copy password to clipboard on click
    const passwordDisplay = document.getElementById('expected-password-display');
    if (passwordDisplay) {
        passwordDisplay.addEventListener('click', function() {
            const password = this.textContent;
            navigator.clipboard.writeText(password).then(() => {
                // Show brief feedback
                const original = this.textContent;
                this.textContent = '✓ Copied!';
                setTimeout(() => {
                    this.textContent = original;
                }, 1500);
            });
        });
    }
   // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
