<?php
if (!defined("_INCODE")) die("Access Denied....!");

$data = [
    "pageTitle" => "Đặt lại mật khẩu",
];

layout("header-login", $data);

$body = getBody();

if (!empty($body["token"])) {
    $token = $body["token"];

    $checkToken = firstRaw("SELECT * FROM users WHERE forgotToken = '$token'");
    if ($checkToken) {
        $userId = $checkToken['id'];
        $email = $checkToken['email'];
        $fullname = $checkToken['fullname'];

        if (isPost()) {
            $body = getBody();
            $errors = [];

            if (empty(trim($body["password"]))) {
                $errors["password"]["required"] = "Mật khẩu bắt buộc phải nhập!";
            } else {
                $password = trim($body["password"]);
                if (strlen($password) < 6) {
                    $errors["password"]["min"] = "Mật khẩu phải lớn hơn 6 ký tự";
                }
            }

            if (empty(trim($body["confirmPassword"]))) {
                $errors["confirmPassword"]["required"] = "Nhập lại mật khẩu bắt buộc phải nhập";
            } else {
                $confirmPassword = trim($body["confirmPassword"]);
                if ($confirmPassword !== $password) {
                    $errors["confirmPassword"]["match"] = "Nhập lại mật khẩu không trùng khớp";
                }
            }

            if (empty($errors)) {
                $dataUpdate = [
                    "forgotToken" => null,
                    "password" => password_hash($password, PASSWORD_DEFAULT),
                    "updatedAt" => date("Y-m-d H:i:s"),
                ];

                $updateStatus = update("users", $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData("msg", "Đổi mật khẩu thành công, bạn có thể đăng nhập ngay bây giờ.");
                    setFlashData("msg_type", "success");
                    redirect("?module=auth&action=login");
                }else{
                    setFlashData("msg", "Hệ thống bị lỗi vui lòng thử lại sau");
                    setFlashData("msg_type", "danger");
                }
            } else {
                setFlashData("msg", "Vui lòng kiểm tra dữ liệu nhập vào");
                setFlashData("msg_type", "danger");
                setFlashData("errors", $errors);
            }

            redirect("?module=auth&action=reset&token=$token");
        }
    } else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
        redirect('?module=auth&action=login');
    }


$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h2 class="text-center">Đặt lại mật khẩu</h2>
        <?php
        getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">

            <div class="form-group">
                <label for="">Mật khẩu mới</label>
                <input type="password" class="form-control" name="password">
                <?php echo getErrors('password', $errors, '<span class="error">', '</span>') ?> 
            </div>

            <div class="form-group">
                <label for="">Nhập lại mật khẩu mới</label>
                <input type="password" class="form-control" name="confirmPassword">
                <?php echo getErrors('confirmPassword', $errors, '<span class="error">', '</span>') ?>
            </div>

            <button type="submit" style="width: 100%; height: 46px;" class="btn btn-primary">Xác nhận</button>
            <input type="hidden" name="token" value="<?php echo $token ?>">
        </form>
        <hr>
        <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
        <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>


    </div>
</div>

<?php
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
}

layout("footer-login");
