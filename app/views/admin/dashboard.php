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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
        <link rel="stylesheet" href="<?php echo asset('public/my-css/dashboard.css'); ?>">
    <style>
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
            font-weight: bold;
        }
        .bg-primary { background-color: #007bff; }
        .bg-info { background-color: #17a2b8; }
        .bg-secondary { background-color: #6c757d; }
        .bg-light { background-color: #f8f9fa; }
        .text-dark { color: #343a40; }
        .text-muted { color: #6c757d; }
    </style>
    
</head>
<body>
    <div class="dashboard-header">
        <h1>Quản lý dự án</h1>
        <div>
            <a href="<?php echo getActionUrl('admin', 'resetIds'); ?>" 
               class="reset-btn" 
               onclick="return confirm('Bạn có chắc muốn reset lại ID không?')">Reset ID</a>
            <a href="<?php echo getActionUrl('auth', 'logout'); ?>" 
               class="logout-btn">Đăng xuất</a>
        </div>
    </div>

    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <a href="<?php echo getActionUrl('admin', 'create'); ?>" class="add-project-btn">+ Thêm dự án mới</a>

    <table class="projects-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên dự án</th>
                <th>Mô tả</th>
                <th>Hình ảnh</th>
                <th>Nguồn</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            require_once __DIR__ . '/../../models/CloudinaryService.php';
            $cloudinaryService = new CloudinaryService();
            ?>
            <?php if(isset($projects) && !empty($projects)): ?>
                <?php foreach($projects as $project): ?>
                <tr>
                    <td><?= htmlspecialchars($project['id']) ?></td>
                    <td><?= htmlspecialchars($project['ten_duan']) ?></td>
                    <td><?= htmlspecialchars(substr($project['noidung'], 0, 80)) ?>...</td>
                    <td>
                        <?php if (!empty($project['image'])): ?>
                            <?php 
                            $imageUrl = $project['image'];
                            if (!empty($project['cloudinary_public_id'])) {
                                $imageUrl = $cloudinaryService->getProjectThumbnail($project['cloudinary_public_id'], 120, 80);
                            } elseif (strpos($imageUrl, 'http') !== 0) {
                                $imageUrl = asset($imageUrl);
                            }
                            ?>
                            <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                 alt="Project Image" 
                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <span class="text-muted">Không có ảnh</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($project['cloudinary_public_id'])): ?>
                            <span class="badge bg-primary">📸 Cloudinary</span>
                        <?php elseif (strpos($project['image'] ?? '', 'http') === 0): ?>
                            <span class="badge bg-info">🌐 URL</span>
                        <?php elseif (!empty($project['image'])): ?>
                            <span class="badge bg-secondary">💾 Local</span>
                        <?php else: ?>
                            <span class="badge bg-light text-dark">❌ None</span>
                        <?php endif; ?>
                    </td>
                    <td class="action-buttons">
                        <a href="<?php echo getActionUrl('admin', 'edit', ['id' => $project['id']]); ?>" 
                           class="edit-btn">Sửa</a>
                        <a href="<?php echo getActionUrl('admin', 'delete', ['id' => $project['id']]); ?>" 
                           class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa dự án này?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Chưa có dự án nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
