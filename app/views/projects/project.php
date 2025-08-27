<?php 
require_once __DIR__ . '/../../../helpers/lang.php'; 
require_once __DIR__ . '/../../../helpers/path.php'; 
?>
<link rel="stylesheet" href="<?php echo asset('public/my-css/project.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('public/my-css/toggle-switch.css'); ?>">
<title>Thành Long Portfolio</title>

<body>
<body>
    <?php 
    require_once __DIR__ . '/../../models/CloudinaryService.php';
    $cloudinaryService = new CloudinaryService();
    
    // Xử lý URL ảnh với Cloudinary optimization
    $imageUrl = $project['image'] ?? 'public/image/default.jpg';
    $fullImageUrl = $imageUrl;
    
    if (!empty($project['cloudinary_public_id'])) {
        // Sử dụng Cloudinary với optimization
        $imageUrl = $cloudinaryService->getProjectThumbnail($project['cloudinary_public_id'], 600, 400);
        $fullImageUrl = $cloudinaryService->getProjectFullImage($project['cloudinary_public_id'], 1200);
    } elseif (strpos($imageUrl, 'http') !== 0) {
        // Local file
        $imageUrl = asset($imageUrl);
        $fullImageUrl = $imageUrl;
    }
    ?>
    
    <!-- Main Content -->
    <div class="main-container">
        <a href="<?php echo url('/'); ?>" class="back-button">← Quay lại trang chủ</a>
        <div class="content">
            <div class="project-info">
                <div class="project-title">
                    <h2><?= htmlspecialchars($project['ten_duan'] ?? 'Không có tên') ?></h2>
                </div>
                <div class="project-description">
                    <p><?= htmlspecialchars($project['noidung'] ?? 'Không có mô tả') ?></p>
                </div>
                
                <?php if (!empty($project['cloudinary_public_id'])): ?>
                <div class="cloudinary-info">
                    <small class="text-muted">
                        📸 Hình ảnh được tối ưu bởi Cloudinary
                        <a href="<?= $fullImageUrl ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            🔍 Xem ảnh gốc
                        </a>
                    </small>
                </div>
                <?php endif; ?>
            </div>
            <div class="image-container">
                <img src="<?= htmlspecialchars($imageUrl) ?>" 
                     alt="<?= htmlspecialchars($project['ten_duan']) ?>" 
                     class="project-image" 
                     onclick="openModal('<?= htmlspecialchars($fullImageUrl) ?>')"
                     loading="lazy">
                <p class="image-hint">Click để xem phóng to</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImg" class="modal-content">
        <div class="controls">
            <button onclick="zoom(0.3)" class="btn">+</button>
            <button onclick="zoom(-0.3)" class="btn">-</button>
            <button onclick="reset()" class="btn">Reset</button>
        </div>
        <div class="hint">Lăn chuột để zoom, kéo để di chuyển</div>
    </div>
</body>
<script src="public/my-js/project.js"></script>
