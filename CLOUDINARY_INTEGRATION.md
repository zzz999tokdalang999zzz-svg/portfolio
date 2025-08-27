# Tích hợp Cloudinary Upload

## Tổng quan

Dự án đã được tích hợp upload hình ảnh lên Cloudinary, cho phép người dùng:
- Upload từ máy tính (local file)
- Upload lên Cloudinary 
- Sử dụng URL từ internet

## Cấu hình

### 1. Database Setup
Chạy script SQL sau để thêm cột mới:
```sql
ALTER TABLE projects 
ADD COLUMN IF NOT EXISTS cloudinary_public_id VARCHAR(255) NULL 
COMMENT 'Public ID của image trên Cloudinary';
```

### 2. Cloudinary Configuration
File `config/config.php` đã được cấu hình với thông tin Cloudinary:
```php
define('CLOUDINARY_CLOUD_NAME', 'dfv2ibaba');
define('CLOUDINARY_API_KEY', '591638797321952');
define('CLOUDINARY_API_SECRET', 'qxXlBKp_I4ckef2_RNsnNwT71nI');
```

## Tính năng mới

### 1. Form Upload
- **Upload từ máy tính**: Lưu file vào thư mục `public/image/projects/`
- **Upload lên Cloudinary**: Upload trực tiếp lên Cloudinary cloud storage
- **Nhập URL**: Sử dụng URL từ internet

### 2. Cloudinary Upload Process
1. Chọn file từ máy tính
2. Validate file type (JPG, PNG, GIF, WebP) và size (max 10MB)
3. Upload lên Cloudinary với folder "projects"
4. Lưu URL và public_id vào database
5. Preview ảnh ngay lập tức

### 3. JavaScript Enhancement
- File `public/my-js/cloudinary-upload.js` chứa logic xử lý upload
- Real-time preview cho tất cả loại upload
- Status feedback cho quá trình upload
- Error handling cho các trường hợp lỗi

## Files đã được cập nhật

### Controllers
- `AdminController.php`: Thêm CloudinaryService
- `ProjectController.php`: Xử lý API upload Cloudinary

### Models
- `ProjectModel.php`: Thêm methods updateImage() và removeImage()
- `CloudinaryService.php`: Service xử lý Cloudinary operations

### Views  
- `app/views/admin/create.php`: Form với 3 options upload
- `public/my-css/create.css`: Styling cho Cloudinary upload
- `public/my-js/cloudinary-upload.js`: JavaScript handler

### Helpers
- `lib/cloudinary.php`: Cloudinary API wrapper

## Routing
- `GET/POST /index.php?controller=admin&action=create`: Form tạo/edit project
- `POST /index.php?controller=upload-image`: API endpoint upload Cloudinary

## Sử dụng

1. Vào trang Admin Dashboard
2. Chọn "Thêm dự án mới"
3. Chọn loại upload:
   - **Upload từ máy tính**: Chọn file từ máy
   - **Upload lên Cloudinary**: Chọn file và upload lên cloud
   - **Nhập URL**: Dán URL ảnh từ internet
4. Preview ảnh sẽ hiển thị ngay
5. Submit form để lưu project

## Lợi ích của Cloudinary
- **Tốc độ**: CDN global, load ảnh nhanh hơn
- **Tối ưu**: Tự động tối ưu kích thước và format
- **Transformation**: Có thể resize, crop, apply effects
- **Bandwidth**: Tiết kiệm băng thông server
- **Storage**: Không tốn storage server

## Troubleshooting

### Lỗi upload Cloudinary
1. Kiểm tra API credentials trong `config/config.php`
2. Kiểm tra kết nối internet
3. Kiểm tra file size và type
4. Xem log lỗi trong browser console

### Database error
1. Chạy script `database_update.sql` để thêm cột mới
2. Kiểm tra kết nối database trong `config/config.php`

## Tương lai
- Batch upload multiple images
- Image gallery management
- Advanced Cloudinary transformations
- Auto-delete old images from Cloudinary
