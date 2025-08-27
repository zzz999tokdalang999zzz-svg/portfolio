<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
    require_once __DIR__ . '/../../../helpers/lang.php'; 
    require_once __DIR__ . '/../../../helpers/path.php'; 
    ?>
    <link rel="stylesheet" href="<?php echo asset('public/my-css/global-reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('public/my-css/header.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('public/my-css/toggle-switch.css'); ?>">

</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="header-content">
            <h1><?= (isset($_SESSION['lang']) && $_SESSION['lang']==='en') ? 'Thanh Long Portfolio' : 'Thành Long Portfolio' ?></h1>
            <div class="nav-links">
                <a href="<?php echo getActionUrl('home'); ?>"><?= lang('projects') ?></a>
                <a href="<?php echo getActionUrl('about'); ?>"><?= lang('about') ?></a>
                <a href="<?php echo getActionUrl('contact'); ?>"><?= lang('contact') ?></a>
            </div>
            <div class="lang-toggle">
                <form method="get" style="display:inline;">
                    <?php
                    // Giữ lại các tham số GET khác khi chuyển đổi ngôn ngữ
                    foreach ($_GET as $k => $v) {
                        if ($k !== 'lang') {
                            echo '<input type="hidden" name="' . htmlspecialchars($k) . '" value="' . htmlspecialchars($v) . '">';
                        }
                    }
                    ?>
                    <input type="hidden" name="lang" value="<?= (isset($_SESSION['lang']) && $_SESSION['lang']==='en') ? 'vi' : 'en' ?>">
                    <label class="toggle-switch">
                        <input type="checkbox" onchange="this.form.submit()" <?= (isset($_SESSION['lang']) && $_SESSION['lang']==='en') ? 'checked' : '' ?> >
                        <span class="slider">
                            <span class="flag-icon vi">us</span>
                            <span class="flag-icon en">vn</span>
                        </span>
                    </label>
                </form>
            </div>
        </div>
        
    </header>
</body>
</html>