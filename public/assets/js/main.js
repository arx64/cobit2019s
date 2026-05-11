/**
 * Main JavaScript
 * Sistem Analisis Risiko TI berbasis COBIT 2019
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    // Check if mobile view
    function isMobile() {
        return window.innerWidth <= 991.98;
    }
    
    if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle button active state
            this.classList.toggle('active');
            
            if (isMobile()) {
                // Mobile behavior: slide sidebar from left
                if (sidebar.classList.contains('active')) {
                    // Close sidebar
                    sidebar.classList.remove('active');
                    sidebar.style.marginLeft = '';
                    removeOverlay();
                } else {
                    // Open sidebar
                    sidebar.classList.add('active');
                    sidebar.style.marginLeft = '0';
                    createOverlay();
                }
            } else {
                // Desktop behavior: collapse/expand sidebar
                if (sidebar.classList.contains('collapsed')) {
                    // Expand sidebar
                    sidebar.classList.remove('collapsed');
                    sidebar.style.width = '';
                    sidebar.style.opacity = '';
                    sidebar.style.marginLeft = '';
                    if (content) {
                        content.classList.remove('full-width');
                    }
                } else {
                    // Collapse sidebar
                    sidebar.classList.add('collapsed');
                    if (content) {
                        content.classList.add('full-width');
                    }
                }
            }
        });
    }
    
    // Create overlay for mobile
    function createOverlay() {
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            document.body.appendChild(overlay);
            
            // Trigger animation
            setTimeout(() => overlay.style.opacity = '1', 10);
            
            // Click to close
            overlay.addEventListener('click', function() {
                closeSidebar();
            });
        }
    }
    
    // Remove overlay
    function removeOverlay() {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => overlay.remove(), 300);
        }
    }
    
    // Close sidebar function
    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('active');
            sidebar.classList.remove('collapsed');
            // Clear inline styles
            sidebar.style = '';
        }
        if (content) {
            content.classList.remove('full-width');
        }
        if (sidebarCollapse) {
            sidebarCollapse.classList.remove('active');
        }
        removeOverlay();
    }
    
    // Close sidebar when clicking on a link in mobile view
    const sidebarLinks = document.querySelectorAll('.components li a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (isMobile()) {
                closeSidebar();
            }
        });
    });
    
    // Close sidebar when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', debounce(function() {
        // Clear any stuck inline styles on resize
        if (sidebar) {
            sidebar.style = '';
        }
        if (!isMobile()) {
            // Reset mobile styles when switching to desktop
            removeOverlay();
            if (sidebar && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('active');
            }
        }
    }, 250));
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
    
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popover initialization
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Confirm delete action
    const deleteBtns = document.querySelectorAll('.btn-delete');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
    
    // Form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Print functionality
    const printBtns = document.querySelectorAll('.btn-print');
    printBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    });
});

/**
 * Format number with thousand separator
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Debounce function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * AJAX helper function
 */
function ajaxRequest(url, method, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    if (method === 'POST') {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                callback(null, xhr.responseText);
            } else {
                callback(new Error('Request failed'), null);
            }
        }
    };
    
    xhr.send(data);
}
