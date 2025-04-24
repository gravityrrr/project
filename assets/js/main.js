
/**
 * Main JavaScript file for the FitFusion Gym Management System
 */

// Toast notification function
function showToast(message, type = 'info', duration = 3000) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    // Append to body
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Hide and remove toast after duration
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, duration);
}

// Mobile menu toggle
function toggleMobileMenu() {
    const sidebar = document.querySelector('aside');
    sidebar.classList.toggle('mobile-open');
}

// Data table pagination
function setupPagination(tableId, itemsPerPage = 10) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const pageCount = Math.ceil(rows.length / itemsPerPage);
    
    // Create pagination container
    const pagination = document.createElement('div');
    pagination.className = 'flex items-center justify-center my-4 space-x-2';
    
    // Hide all rows initially
    rows.forEach(row => {
        row.style.display = 'none';
    });
    
    // Show first page
    for (let i = 0; i < Math.min(itemsPerPage, rows.length); i++) {
        rows[i].style.display = '';
    }
    
    // Create pagination buttons
    for (let i = 1; i <= pageCount; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = 'px-3 py-1 rounded-md border ' + (i === 1 ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700');
        pageButton.textContent = i;
        
        pageButton.addEventListener('click', () => {
            // Hide all rows
            rows.forEach(row => {
                row.style.display = 'none';
            });
            
            // Show rows for selected page
            const start = (i - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, rows.length);
            
            for (let j = start; j < end; j++) {
                rows[j].style.display = '';
            }
            
            // Update active button
            pagination.querySelectorAll('button').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            });
            
            pageButton.classList.remove('bg-white', 'text-gray-700');
            pageButton.classList.add('bg-indigo-600', 'text-white');
        });
        
        pagination.appendChild(pageButton);
    }
    
    // Append pagination to table container
    table.parentNode.appendChild(pagination);
}

// Initialize components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const text = this.getAttribute('data-tooltip');
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-50';
            tooltipEl.textContent = text;
            tooltipEl.style.bottom = 'calc(100% + 5px)';
            tooltipEl.style.left = '50%';
            tooltipEl.style.transform = 'translateX(-50%)';
            this.style.position = 'relative';
            this.appendChild(tooltipEl);
        });
        
        tooltip.addEventListener('mouseleave', function() {
            const tooltipEl = this.querySelector('div');
            if (tooltipEl) this.removeChild(tooltipEl);
        });
    });
    
    // Initialize data tables
    const tables = document.querySelectorAll('[data-table]');
    tables.forEach(table => {
        setupPagination(table.id, parseInt(table.getAttribute('data-items-per-page') || 10));
    });
    
    // Initialize form validation for all forms with validate class
    const forms = document.querySelectorAll('form.validate');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let valid = true;
            
            // Check required fields
            const required = form.querySelectorAll('[required]');
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    valid = false;
                    
                    // Create or update error message
                    let errorMsg = field.parentNode.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                        field.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'This field is required';
                } else {
                    field.classList.remove('border-red-500');
                    const errorMsg = field.parentNode.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                }
            });
            
            if (!valid) {
                event.preventDefault();
                showToast('Please fill all required fields', 'error');
            }
        });
    });
});
