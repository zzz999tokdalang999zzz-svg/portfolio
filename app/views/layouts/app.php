
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require_once __DIR__ . '/../../../helpers/path.php'; ?>
    <link href="<?php echo asset('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
</head>
<body>
    <?php include('app/views/partials/header.php'); ?>
    <?php include($content); ?>
    <?php include('app/views/partials/footer.php'); ?>
    
    <script src="<?php echo asset('public/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo asset('public/my-js/config.js'); ?>"></script>
    <script src="<?php echo asset('public/my-js/cloudinary.js'); ?>"></script>
</body>
</html>