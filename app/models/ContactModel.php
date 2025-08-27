<?php
class ContactModel {
    public function sendEmail($senderName, $senderEmail, $subject, $body) {
        // Tạm thời không sử dụng PHPMailer, chỉ trả về thành công
        // Bạn có thể cài đặt PHPMailer sau hoặc sử dụng mail() function
        
        // Sử dụng mail() function của PHP (cần cấu hình SMTP trên server)
        $to = "your-email@example.com"; // Thay bằng email của bạn
        $headers = "From: $senderEmail\r\n";
        $headers .= "Reply-To: $senderEmail\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $fullMessage = "From: $senderName ($senderEmail)<br><br>" . nl2br($body);
        
        // Tạm thời chỉ return success để test
        // $mailSent = mail($to, $subject, $fullMessage, $headers);
        
        // Tạm thời luôn trả về thành công để test website
        return ['success' => true];
        
        // Uncomment dòng này khi muốn thực sự gửi email
        // return $mailSent ? ['success' => true] : ['success' => false, 'message' => 'Failed to send email'];
    }
}
