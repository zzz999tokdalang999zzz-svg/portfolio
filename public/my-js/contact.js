document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');

    // Form validation
    function validateField(field, errorElement, message) {
        if (!field.value.trim()) {
            field.classList.add('error');
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            return false;
        } else {
            field.classList.remove('error');
            errorElement.style.display = 'none';
            return true;
        }
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Real-time validation
    const fields = [
        { input: document.getElementById('sender_name'), error: document.getElementById('name-error'), message: 'Vui lòng nhập tên của bạn' },
        { input: document.getElementById('sender_email'), error: document.getElementById('email-error'), message: 'Vui lòng nhập email hợp lệ' },
        { input: document.getElementById('subject'), error: document.getElementById('subject-error'), message: 'Vui lòng nhập tiêu đề' },
        { input: document.getElementById('body'), error: document.getElementById('message-error'), message: 'Vui lòng nhập tin nhắn' }
    ];

    fields.forEach(field => {
        field.input.addEventListener('blur', function() {
            if (field.input.type === 'email') {
                if (!field.input.value.trim()) {
                    validateField(field.input, field.error, 'Vui lòng nhập email');
                } else if (!validateEmail(field.input.value)) {
                    field.input.classList.add('error');
                    field.error.textContent = 'Email không hợp lệ';
                    field.error.style.display = 'block';
                } else {
                    field.input.classList.remove('error');
                    field.error.style.display = 'none';
                }
            } else {
                validateField(field.input, field.error, field.message);
            }
        });

        field.input.addEventListener('focus', function() {
            field.input.classList.remove('error');
            field.error.style.display = 'none';
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate all fields
        fields.forEach(field => {
            if (field.input.type === 'email') {
                if (!field.input.value.trim()) {
                    validateField(field.input, field.error, 'Vui lòng nhập email');
                    isValid = false;
                } else if (!validateEmail(field.input.value)) {
                    field.input.classList.add('error');
                    field.error.textContent = 'Email không hợp lệ';
                    field.error.style.display = 'block';
                    isValid = false;
                }
            } else {
                if (!validateField(field.input, field.error, field.message)) {
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        btnText.textContent = 'Đang gửi...';
    });

    // Auto-hide success/error messages after 5 seconds
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }, 5000);
    }

    // Add smooth animations
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        setTimeout(() => {
            group.style.transition = 'all 0.6s ease';
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
