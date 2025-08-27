<?php

// Import các lớp của PHPMailer vào namespace toàn cục
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Yêu cầu file autoload của composer để load thư viện
require_once __DIR__ . '/../../vendor/autoload.php';

class MailController {

    public function showForm() {
        // Chỉ cần hiển thị form
        $content = 'app/views/pages/contact.php';
        include 'app/views/layouts/app.php';
    }

    public function send() {
        $status = 'error';
        $message = 'Đã có lỗi xảy ra, vui lòng thử lại.';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ form do người dùng nhập
            $senderName = $_POST['sender_name'];
            $senderEmail = $_POST['sender_email'];
            $subject = $_POST['subject'];
            $body = $_POST['body'];

            // Khởi tạo và cấu hình PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Cấu hình server
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'zzz999tokdalang999zzz@gmail.com'; // Email của bạn dùng để nhận trong trường hợp này là zzz999tokdalang999zzz@gmail.com
                $mail->Password   = 'apul wmfh ajbw saau'; // Mật khẩu ứng dụng của email trên
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Người gửi và người nhận
                // setFrom: Đặt email gửi đi (hiển thị trong header "From")
                $mail->setFrom('zzz999tokdalang999zzz@gmail.com', 'Thông báo từ Website');

                // addAddress: Email của người nhận (chủ blog)
                $mail->addAddress('zzz999tokdalang999zzz@gmail.com', 'Blog Owner');

                // addReplyTo: Quan trọng! Giúp chủ blog có thể nhấn "Reply" và trả lời trực tiếp cho người dùng
                $mail->addReplyTo($senderEmail, $senderName);

                // Nội dung email
                $mail->isHTML(true);
                $mail->Subject = "[Liên hệ] - " . $subject; // Thêm tiền tố để dễ nhận biết

                // Tạo nội dung email đẹp hơn, bao gồm cả thông tin người gửi
                $mail->Body    = "
                    <h2>Bạn có một liên hệ mới từ website:</h2>
                    <p><strong>Từ:</strong> " . htmlspecialchars($senderName) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($senderEmail) . "</p>
                    <hr>
                    <h3>Nội dung:</h3>
                    <p>" . nl2br(htmlspecialchars($body)) . "</p>
                ";

                $mail->AltBody = "Từ: $senderName ($senderEmail)\n\nNội dung:\n$body";

                $mail->send();
                $status = 'success';
                $message = 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi thành công!';
            } catch (Exception $e) {
                // Hiển thị lỗi chi tiết hơn để dễ debug (chỉ nên làm vậy trong môi trường phát triển)
                $message = "Gửi email thất bại. Lỗi: {$mail->ErrorInfo}";
            }
        }

        // Hiển thị lại form với thông báo
                $content = 'app/views/pages/contact.php';
                include 'app/views/layouts/app.php';
    }
}

