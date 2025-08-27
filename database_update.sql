-- Script để thêm cột cloudinary_public_id vào bảng projects
-- Chạy script này để hỗ trợ lưu trữ Cloudinary public_id

-- Kiểm tra và thêm cột cloudinary_public_id nếu chưa tồn tại
ALTER TABLE projects 
ADD COLUMN IF NOT EXISTS cloudinary_public_id VARCHAR(255) NULL 
COMMENT 'Public ID của image trên Cloudinary';

-- Hiển thị cấu trúc bảng sau khi update
DESCRIBE projects;
