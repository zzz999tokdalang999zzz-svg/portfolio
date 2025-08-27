<?php 
require_once __DIR__ . '/../../../helpers/lang.php'; 
require_once __DIR__ . '/../../../helpers/path.php'; 
?>
<link rel="stylesheet" href="<?php echo asset('public/my-css/contact.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('public/my-css/toggle-switch.css'); ?>">
<body>
<body>
    <main class="contact-page">
        <div id="body-contact">
            <div id="name-contact">
                <h1><?= lang('contact') ?></h1>
                <p><?= lang('connect_with_me') ?></p>
            </div>

            <div class="contact-info">
                <p>info@mysite.com | Tel: 098-736-0860</p>
            </div>

            <?php if (isset($message)): ?>
                <div class="message <?php echo $status; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="<?php echo getActionUrl('contact', 'send'); ?>" method="post" id="contactForm">
                    <div class="form-group">
                        <label for="sender_name"><?= lang('your_name') ?> <span class="required">*</span></label>
                        <input type="text" id="sender_name" name="sender_name" required>
                        <div class="error-message" id="name-error">Vui lòng nhập tên của bạn</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="sender_email"><?= lang('email') ?> <span class="required">*</span></label>
                        <input type="email" id="sender_email" name="sender_email" required>
                        <div class="error-message" id="email-error">Vui lòng nhập email hợp lệ</div>
                    </div>

                    <div class="form-group">
                        <label for="phone"><?= lang('phone') ?></label>
                        <div class="phone-container">
                            <div class="country-code">
                                <select id="country-code" name="country_code">
                                    <option value="+84">+84</option>
                                    <option value="+1">+1</option>
                                    <option value="+86">+86</option>
                                    <option value="+82">+82</option>
                                    <option value="+81">+81</option>
                                    <option value="+65">+65</option>
                                </select>
                            </div>
                            <div class="phone-number">
                                <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject"><?= lang('topic')?><span class="required">*</span></label>
                        <input type="text" id="subject" name="subject" required>
                        <div class="error-message" id="subject-error">Vui lòng nhập tiêu đề</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="body"><?= lang('message')?><span class="required">*</span></label>
                        <textarea id="body" name="body" rows="5" required placeholder="Nhập tin nhắn của bạn..."></textarea>
                        <div class="error-message" id="message-error">Vui lòng nhập tin nhắn</div>
                    </div>
                    
                        <button type="submit" class="submit-btn" id="submitBtn">
                            <span class="btn-text"><?= lang('send') ?></span>
                        </button>
                </form>
            </div>
        </div>
    </main>
</body>
<script src="<?php echo asset('public/my-js/contact.js'); ?>"></script>


