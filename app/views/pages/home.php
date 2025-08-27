    <?php 
require_once __DIR__ . '/../../../helpers/lang.php'; 
require_once __DIR__ . '/../../../helpers/path.php'; 
?>
    <link rel="stylesheet" href="<?php echo asset('public/my-css/toggle-switch.css'); ?>">
    <title><?= lang('projects') ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('public/my-css/home.css'); ?>">
<body>
    <?php 
    require_once __DIR__ . '/../../models/CloudinaryService.php';
    $cloudinaryService = new CloudinaryService();
    ?>
    
    <main class="main-content">
        <div class="container">
            <h1 class="text-center"><?= lang('projects') ?></h1>
                    <div class="project-grid">
                 <?php foreach ($projects as $pj): ?>
                <div class="project-card">
                    <?php 
                    // Sử dụng Cloudinary optimization nếu là Cloudinary image
                    $imageUrl = $pj['image'] ?? 'public/image/default.jpg';
                    if (!empty($pj['cloudinary_public_id'])) {
                        // Sử dụng Cloudinary với optimization cho thumbnail
                        $imageUrl = $cloudinaryService->getProjectThumbnail($pj['cloudinary_public_id'], 400, 300);
                    } elseif (strpos($imageUrl, 'http') !== 0) {
                        // Local file
                        $imageUrl = asset($imageUrl);
                    }
                    ?>
                    <img src="<?= htmlspecialchars($imageUrl) ?>" 
                         alt="<?= htmlspecialchars($pj['ten_duan']) ?>" 
                         class="project-image"
                         loading="lazy">
                    <div class="project-overlay">
                        <h3 class="project-title"><?= htmlspecialchars($pj['ten_duan']) ?></h3>
                    </div>
                    <a href="<?php echo getActionUrl('show', '', ['id' => $pj['id']]); ?>" 
                       class="project-link" 
                       aria-label="Xem chi tiết <?= htmlspecialchars($pj['ten_duan']) ?>"></a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>