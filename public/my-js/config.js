/**
 * Configuration for JavaScript files
 * Cấu hình cho các file JavaScript
 */

const CONFIG = {
    // Lấy base path từ pathname
    getBasePath: function() {
        const path = window.location.pathname;
        
        // Nếu đang ở trong thư mục public, lấy thư mục cha
        if (path.includes('/public/')) {
            return path.split('/public/')[0];
        }
        
        // Tìm thư mục project (personal-portfolio)
        const parts = path.split('/');
        let projectIndex = parts.findIndex(part => part === 'personal-portfolio');
        if (projectIndex !== -1) {
            return '/' + parts.slice(1, projectIndex + 1).join('/');
        }
        
        // Fallback: lấy thư mục hiện tại
        parts.pop();
        return parts.join('/') || '';
    },
    
    // Tạo URL API
    getApiUrl: function(controller, action = '') {
        const basePath = this.getBasePath();
        let url = basePath + '/index.php?controller=' + controller;
        if (action) {
            url += '&action=' + action;
        }
        return url;
    }
};

// Export global
window.CONFIG = CONFIG;
