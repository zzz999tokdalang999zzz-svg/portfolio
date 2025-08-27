<?php
require_once __DIR__ . '/../../../helpers/path.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . getActionUrl('auth'));
    exit();
}

// Kiểm tra xem đây là form thêm mới hay chỉnh sửa
$isEditing = isset($project);
$pageTitle = $isEditing ? "Chỉnh sửa dự án" : "Thêm dự án mới";
$submitText = $isEditing ? "Cập nhật" : "Thêm mới";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="<?php echo asset('public/my-css/create.css'); ?>">
    <script>
        // Pass PHP variable to JavaScript
        window.isEditing = <?= $isEditing ? 'true' : 'false' ?>;
    </script>

    
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1><?= $pageTitle ?></h1>
            <a href="<?php echo getActionUrl('admin', 'dashboard'); ?>" class="back-btn">← Quay lại</a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ten_duan">Tên dự án:</label>
                <input type="text" id="ten_duan" name="ten_duan" required 
                       value="<?= $isEditing ? htmlspecialchars($project['ten_duan']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="noidung">Mô tả dự án:</label>
                <textarea id="noidung" name="noidung" required><?= $isEditing ? htmlspecialchars($project['noidung']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Hình ảnh:</label>
                
                <!-- Radio buttons để chọn loại upload -->
                <div class="image-type-selector">
                    <label>
                        <input type="radio" name="image_type" value="file" checked> Upload từ máy tính
                    </label>
                    <label>
                        <input type="radio" name="image_type" value="cloudinary"> Upload lên Cloudinary
                    </label>
                    <label>
                        <input type="radio" name="image_type" value="url"> Nhập URL
                    </label>
                </div>

                <!-- Upload file -->
                <div id="file-upload" class="upload-option">
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <!-- Cloudinary upload -->
                <div id="cloudinary-upload" class="upload-option" style="display: none;">
                    <input type="file" id="cloudinary_image" name="cloudinary_image" accept="image/*">
                    <div id="cloudinary-status" style="margin-top: 10px;"></div>
                    <input type="hidden" id="cloudinary_url" name="cloudinary_url">
                    <input type="hidden" id="cloudinary_public_id" name="cloudinary_public_id">
                </div>

                <!-- URL input -->
                <div id="url-input" class="upload-option" style="display: none;">
                    <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                </div>

                <?php if ($isEditing && !empty($project['image'])): ?>
                    <div class="image-preview">
                        <p>Ảnh hiện tại:</p>
                        <img src="<?= strpos($project['image'], 'http') === 0 ? htmlspecialchars($project['image']) : asset(htmlspecialchars($project['image'])) ?>" alt="Current project image">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-btn"><?= $submitText ?></button>
        </form>
    </div>

    <script src="<?php echo asset('public/my-js/config.js'); ?>"></script>
    <script src="<?php echo asset('public/my-js/cloudinary-upload.js'); ?>"></script>
</body>
</html>
