<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4">
        <!-- Icon -->
        <div class="flex items-center justify-center mb-4">
            <div id="confirmIcon" class="bg-red-100 rounded-full p-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h3 id="confirmTitle" class="text-lg font-bold text-gray-900 text-center mb-2">Confirm Action</h3>

        <!-- Message -->
        <p id="confirmMessage" class="text-gray-600 text-center mb-6 text-sm">Are you sure you want to proceed?</p>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium text-sm">
                Cancel
            </button>
            <button type="button" onclick="confirmAction()" id="confirmActionBtn" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm flex items-center justify-center gap-2">
                <span id="confirmButtonText">Confirm</span>
                <div id="confirmLoader" class="hidden">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </button>
        </div>
    </div>
</div>

<!-- Hidden Form for Actions -->
<form id="confirmForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let confirmConfig = {
        title: 'Confirm Action',
        message: 'Are you sure you want to proceed?',
        actionType: 'delete', // delete, warning, success, etc.
        actionUrl: '',
        actionMethod: 'delete'
    };

    /**
     * Open the confirm modal
     * @param {string} title - Modal title
     * @param {string} message - Modal message
     * @param {string} url - Action URL
     * @param {string} actionType - Type of action (delete, warning, success)
     * @param {string} actionMethod - HTTP method (delete, post, put)
     */
    function openConfirmModal(title, message, url, actionType = 'delete', actionMethod = 'delete') {
        confirmConfig = {
            title,
            message,
            actionType,
            actionUrl: url,
            actionMethod
        };

        // Update title and message
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;

        // Update button text based on action type
        const buttonText = actionType === 'delete' ? 'Delete' : 'Confirm';
        document.getElementById('confirmButtonText').textContent = buttonText;

        // Update icon color based on action type
        const iconDiv = document.getElementById('confirmIcon');
        if (actionType === 'delete') {
            iconDiv.className = 'bg-red-100 rounded-full p-3';
            document.getElementById('confirmActionBtn').className = 'flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm flex items-center justify-center gap-2';
        } else if (actionType === 'warning') {
            iconDiv.className = 'bg-yellow-100 rounded-full p-3';
            document.getElementById('confirmActionBtn').className = 'flex-1 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium text-sm flex items-center justify-center gap-2';
        } else if (actionType === 'success') {
            iconDiv.className = 'bg-green-100 rounded-full p-3';
            document.getElementById('confirmActionBtn').className = 'flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm flex items-center justify-center gap-2';
        }

        // Show modal
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    /**
     * Close the confirm modal
     */
    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        confirmConfig.actionUrl = '';
    }

    /**
     * Execute the confirmation action
     */
    function confirmAction() {
        const confirmForm = document.getElementById('confirmForm');
        const confirmButton = document.getElementById('confirmActionBtn');
        const confirmButtonText = document.getElementById('confirmButtonText');
        const confirmLoader = document.getElementById('confirmLoader');

        // Show loader and disable button
        confirmButtonText.classList.add('hidden');
        confirmLoader.classList.remove('hidden');
        confirmButton.disabled = true;

        // Set form method and action
        confirmForm.method = confirmConfig.actionMethod === 'delete' ? 'POST' : confirmConfig.actionMethod;
        confirmForm.action = confirmConfig.actionUrl;

        // Add method field if needed
        if (confirmConfig.actionMethod === 'delete') {
            const methodField = confirmForm.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.value = 'DELETE';
            }
        }

        // Submit form
        confirmForm.submit();
    }

    /**
     * Close modal on Escape key
     */
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
