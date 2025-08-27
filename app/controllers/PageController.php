<?php
require_once 'app/models/HomeModel.php';

class HomeController {
    private $model;
    public function __construct($db) {  // Thêm: Nhận $db (là $conn)
        $this->model = new HomeModel($db);  // Pass $db vào model
    }
    public function index() {
        $projects = $this->model->getAllProjects();  
        $content = 'app/views/pages/home.php';
        include 'app/views/layouts/app.php';
    }
}
?>

<!-- Phần ContactController và AboutController giữ nguyên -->

<?php
require_once 'app/models/ContactModel.php';
class ContactController {
    private $model;

    public function __construct() {
        $this->model = new ContactModel();
    }

    public function getForm() {
        $content = 'app/views/pages/contact.php';
        include 'app/views/layouts/app.php';
    }

    public function sendForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->model->sendEmail(
                $_POST['sender_name'],
                $_POST['sender_email'],
                $_POST['subject'],
                $_POST['body']
            );

            if ($result['success']) {
                $status = 'success';
                $message = 'Email đã được gửi thành công!';
            } else {
                $status = 'error';
                $message = 'Có lỗi xảy ra: ' . $result['message'];
            }
        }

        $content = 'app/views/pages/contact.php';
        include 'app/views/layouts/app.php';
    }
}
?>
<?php
require_once 'app/models/AboutModel.php';

class AboutController {
    public function getText() {
        $model = new AboutModel();
        $message = $model->getText();

        $content = 'app/views/pages/about.php';
        include 'app/views/layouts/app.php';
    }
}
?>




