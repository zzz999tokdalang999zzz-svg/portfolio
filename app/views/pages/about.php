<?php 
require_once __DIR__ . '/../../../helpers/lang.php'; 
require_once __DIR__ . '/../../../helpers/path.php'; 
?>
<link rel="stylesheet" href="<?php echo asset('public/my-css/about.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('public/my-css/toggle-switch.css'); ?>">
<body>
<body>
    <body>
        <div id="body-about">
            <div id="img">
                <img src="<?php echo asset('public/image/avatar.jpg'); ?>" alt="">
            </div>
            <div id="about">
                <p style="font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;font-size: 30px;"><?= lang('about_me') ?></p>
                <p id="text-about" style="font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
                    <?php if(isset($_SESSION['lang']) && $_SESSION['lang']==='en'): ?>
                        My name is Trinh Thanh Long, and I really love Stephen Chow.<br>
                        “To be a person without dreams is better off being a goldfish under water.<br> If you have no dreams, you’re no different from salted fish.”<br>
                        — Shaolin Soccer<br>
                        “Losing memory is also good, just consider it a kind of happiness.”<br>
                        — Kungfu Hustle
                    <?php else: ?>
                        Tôi tên Trịnh Thành Long, và tôi rất yêu thích Châu Tinh Trì <br>
                        “Làm người mà không có ước mơ thà làm cá vàng dưới nước còn hơn.<br> Làm người nếu như không có ước mơ thì cũng chẳng khác gì cá muối.”<br>
                        — Đội bóng thiếu lâm<br>
                        “Mất trí nhớ cũng tốt, hãy coi như đó là một thứ hạnh phúc đi.”<br>
                        — Tuyệt đỉnh Kungfu
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </body>
</body>
