import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true; // Send cookies (required for sessions)

// Configure axios with CSRF token and Sanctum Bearer token
(function() {
    function setTokens() {
        // CSRF token (required for web middleware routes like cart)
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (csrfTokenElement) {
            const csrfToken = csrfTokenElement.getAttribute('content');
            if (csrfToken) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
            }
        }
        
        // Sanctum Bearer token (for protected API routes)
        const apiTokenElement = document.querySelector('meta[name="api-token"]');
        if (apiTokenElement) {
            const token = apiTokenElement.getAttribute('content');
            if (token) {
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                console.log('Axios configured with Bearer token');
            } else {
                delete window.axios.defaults.headers.common['Authorization'];
            }
        } else {
            delete window.axios.defaults.headers.common['Authorization'];
        }
    }
    
    // Set tokens on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setTokens);
    } else {
        setTokens();
    }
    
    // Re-check tokens periodically (in case they're updated after login)
    setInterval(setTokens, 2000);
})();
