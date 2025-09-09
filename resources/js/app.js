import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import Chart from 'chart.js/auto';

window.flatpickr = flatpickr;
window.Chart = Chart;
window.Alpine = Alpine;

// Initialize Alpine.js
Alpine.start();

// Dark mode support for Flatpickr
document.addEventListener('DOMContentLoaded', () => {
    // Initialize date pickers
    const datePickers = document.querySelectorAll('input[type="date"]');
    datePickers.forEach(input => {
        flatpickr(input, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y',
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = input.parentElement.querySelector('.file-upload-preview');
            if (!preview) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                        <button type="button" class="remove-file" onclick="this.parentElement.parentElement.innerHTML = ''; document.getElementById('${input.id}').value = '';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        });
    });
});
