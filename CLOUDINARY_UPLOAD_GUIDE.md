# Cloudinary Upload System - Hướng dẫn

## Tổng quan
Hệ thống upload ảnh sử dụng Cloudinary với khả năng tự động phát hiện đường dẫn, hoạt động trên mọi môi trường.

## Cấu trúc Files

### JavaScript Files
- `public/my-js/config.js` - Cấu hình đường dẫn API động
- `public/my-js/cloudinary-upload.js` - Xử lý upload cho form tạo project
- `public/my-js/cloudinary.js` - Xử lý upload cho project management

### PHP Files  
- `app/controllers/ProjectController.php` - API endpoints (uploadImage, deleteImage)
- `app/models/CloudinaryService.php` - Service xử lý Cloudinary
- `helpers/path.php` - Helper functions cho đường dẫn

## Cách hoạt động

### 1. Phát hiện đường dẫn tự động
- `CONFIG.getBasePath()` tự động phát hiện base path từ URL
- Hoạt động với cả `/personal-portfolio/` và root domain
- Không cần config thủ công

### 2. API Endpoints
- `POST /index.php?controller=upload-image` - Upload ảnh
- `DELETE /index.php?controller=delete-image` - Xóa ảnh

### 3. Upload Flow
1. User chọn file
2. JavaScript validate file (type, size)
3. Gọi API với FormData
4. Server upload lên Cloudinary
5. Return URL và public_id
6. Update UI với ảnh mới

## Include Order
Đảm bảo include theo thứ tự:
```html
<script src="config.js"></script>
<script src="cloudinary-upload.js"></script> <!-- hoặc cloudinary.js -->
```

## Môi trường hỗ trợ
- ✅ Local development (XAMPP, AMPPS, etc.)
- ✅ Shared hosting
- ✅ VPS/Dedicated server  
- ✅ Subdomain/subfolder setup
