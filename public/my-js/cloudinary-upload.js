/**
 * Cloudinary Upload Handler
 * Xử lý upload hình ảnh lên Cloudinary
 */

class CloudinaryUploader {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Xử lý chuyển đổi giữa upload file, Cloudinary và URL
        document.querySelectorAll('input[name="image_type"]').forEach(radio => {
            radio.addEventListener('change', (e) => this.handleImageTypeChange(e));
        });

        // Preview ảnh từ file upload thông thường
        const fileInput = document.getElementById('image');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.handleFilePreview(e));
        }

        // Xử lý upload lên Cloudinary
        const cloudinaryInput = document.getElementById('cloudinary_image');
        if (cloudinaryInput) {
            cloudinaryInput.addEventListener('change', (e) => this.handleCloudinaryUpload(e));
        }

        // Preview ảnh từ URL
        const urlInput = document.getElementById('image_url');
        if (urlInput) {
            urlInput.addEventListener('input', (e) => this.handleUrlPreview(e));
        }
    }

    handleImageTypeChange(e) {
        const fileUpload = document.getElementById('file-upload');
        const cloudinaryUpload = document.getElementById('cloudinary-upload');
        const urlInput = document.getElementById('url-input');
        const fileInput = document.getElementById('image');
        const cloudinaryInput = document.getElementById('cloudinary_image');
        const urlInputField = document.getElementById('image_url');
        
        // Reset tất cả các option
        fileUpload.style.display = 'none';
        cloudinaryUpload.style.display = 'none';
        urlInput.style.display = 'none';
        fileInput.required = false;
        cloudinaryInput.required = false;
        urlInputField.required = false;
        
        // Clear values
        fileInput.value = '';
        cloudinaryInput.value = '';
        urlInputField.value = '';
        document.getElementById('cloudinary_url').value = '';
        document.getElementById('cloudinary_public_id').value = '';
        document.getElementById('cloudinary-status').innerHTML = '';
        
        // Remove old preview
        this.removePreview();
        
        const isEditing = window.isEditing || false;
        
        switch (e.target.value) {
            case 'file':
                fileUpload.style.display = 'block';
                fileInput.required = !isEditing;
                break;
            case 'cloudinary':
                cloudinaryUpload.style.display = 'block';
                cloudinaryInput.required = !isEditing;
                break;
            case 'url':
                urlInput.style.display = 'block';
                urlInputField.required = true;
                break;
        }
    }

    handleFilePreview(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.showImagePreview(e.target.result, 'Ảnh đã chọn:');
            }
            reader.readAsDataURL(file);
        }
    }

    handleCloudinaryUpload(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                this.showStatus('error', 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WebP)');
                return;
            }

            // Validate file size (max 10MB)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                this.showStatus('error', 'File không được vượt quá 10MB');
                return;
            }

            this.uploadToCloudinary(file);
        }
    }

    handleUrlPreview(e) {
        const url = e.target.value;
        if (url && this.isValidImageUrl(url)) {
            this.showImagePreview(url, 'Ảnh từ URL:');
        } else if (url) {
            this.removePreview();
        }
    }

    /**
     * Upload file to Cloudinary
     */
    uploadToCloudinary(file) {
        this.showStatus('loading', 'Đang tải lên Cloudinary...');
        
        const formData = new FormData();
        formData.append('image', file);
        formData.append('project_id', this.generateTempId());
        
        // Use dynamic API URL from config
        fetch(CONFIG.getApiUrl('upload-image'), {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.showStatus('success', '✓ Upload thành công!');
                document.getElementById('cloudinary_url').value = data.url;
                document.getElementById('cloudinary_public_id').value = data.public_id || '';
                this.showImagePreview(data.url, 'Ảnh từ Cloudinary:');
            } else {
                this.showStatus('error', 'Lỗi: ' + (data.error || 'Upload thất bại'));
            }
        })
        .catch(error => {
            this.showStatus('error', 'Lỗi kết nối: ' + error.message);
            console.error('Upload error:', error);
        });
    }

    showStatus(type, message) {
        const statusDiv = document.getElementById('cloudinary-status');
        let color;
        switch (type) {
            case 'loading':
                color = 'blue';
                break;
            case 'success':
                color = 'green';
                break;
            case 'error':
                color = 'red';
                break;
            default:
                color = 'black';
        }
        statusDiv.innerHTML = `<div style="color: ${color};">${message}</div>`;
    }

    showImagePreview(src, title) {
        this.removePreview();
        
        const preview = document.createElement('div');
        preview.className = 'new-image-preview';
        preview.style.marginTop = '10px';
        preview.innerHTML = `
            <p>${title}</p>
            <img src="${src}" alt="Preview" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
        `;
        
        const formGroup = document.querySelector('.form-group:last-of-type');
        formGroup.appendChild(preview);
    }

    removePreview() {
        const existingPreview = document.querySelector('.new-image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
    }

    isValidImageUrl(url) {
        try {
            new URL(url);
            return /\.(jpg|jpeg|png|gif|bmp|webp)(\?|$)/i.test(url);
        } catch {
            return false;
        }
    }

    generateTempId() {
        return 'temp_' + Math.random().toString(36).substring(2) + Date.now();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new CloudinaryUploader();
});
