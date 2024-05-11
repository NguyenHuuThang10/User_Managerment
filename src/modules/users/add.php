<?php
if (!defined("_INCODE")) die("Access Denid.....");

$data = [
    "pageTitle" => "Thêm tài khoản",
];

layout("header", $data);

if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Họ tên bắc buộc phải nhập';
    } else {
        $fullname = trim($body['fullname']);

        if (mb_strlen($fullname, 'UTF-8') < 6) {
            $errors['fullname']['min'] = 'Họ tên phải từ 6 ký tự trở lên';
        }
    }

    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email bắc buộc phải nhập';
    } else {
        $email = trim($body['email']);

        if (!isEmail($email)) {
            $errors["email"]["isEmail"] = "Email không đúng dịnh dạng";
        } else {
            $checkMail = firstRaw("SELECT * FROM users WHERE email = '$email'");
            if ($checkMail) {
                $errors['email']['unique'] = 'Email đã tồn tại trong hệ thống';
            }
        }
    }

    if(empty(trim($body["phone"]))) {
        $errors["phone"]["required"] = "Số điện thoại bắt buộc phải nhập";
    }else{
        $phone = trim($body["phone"]);
        if(!isPhone(trim($phone))) {
            $errors["phone"]["isPhone"] = "Số điện thoại không đúng định dạng";
        }
    }

    if (empty(trim($body["password"]))) {
        $errors["password"]["required"] = "Mật khẩu bắt buộc phải nhập!";
    }else{
        $pass = trim($body["password"]);
        if(strlen($pass) < 6) {
            $errors["password"]["min"] = "Mật khẩu phải lớn hơn 6 ký tự";
        }
    }

    if(empty(trim($body["confirmPassword"]))) {
        $errors["confirmPassword"]["required"] = "Nhập lại mật khẩu bắt buộc phải nhập";
    }else{
        $confirmPass = trim($body["confirmPassword"]);
        if($confirmPass !== $pass) {
            $errors["confirmPassword"]["match"] = "Nhập lại mật khẩu không trùng khớp";
        }
    }

    if(empty($body['status'])){
        $errors['status']['required'] = 'Trạng thái bắt buộc phải chọn';
    }else{
        $status = trim($body['status']);
        if($status == 2){
            $status = 0;
        }
    }

    if(empty($errors)){ 
        $dataInsert = [
            'fullname' => $fullname,
            'email' => $email,
            'phone'=> $phone,
            'password'=> password_hash($pass, PASSWORD_DEFAULT),
            'status' => $status,
            'createdAt' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('users', $dataInsert);
        if($insertStatus){
            setFlashData('msg', 'Thêm tài khoản thành công');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Hệ thống bị lỗi vui lòng thử lại sau');
            setFlashData('msg_type','danger');
        }

    }else{
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        setFlashData('msg', 'Vui lòng kiểm tra lại thông tin nhập vào');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=users&action=add');
}

$old = getFlashData('old');
$errors = getFlashData('errors');
$msg = getFlashData("msg");
$msg_type = getFlashData("msg_type");
?>
<h2 class="mt-3">Thêm tài khoản</h2>

<?php
getMsg($msg, $msg_type);
?>

<form action="" method="post">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="">Họ tên</label>
                <input type="text" class="form-control" name="fullname" value="<?php echo getOld('fullname', $old); ?>">
                <?php echo getErrors('fullname', $errors, '<span class="error">', '</span>') ?>
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo getOld('email', $old); ?>">
                <?php echo getErrors('email', $errors, '<span class="error">', '</span>') ?>
            </div>

            <div class="form-group">
                <label for="">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" value="<?php echo getOld('phone', $old); ?>">
                <?php echo getErrors('phone', $errors, '<span class="error">', '</span>') ?>
            </div>

        </div>

        <div class="col-6">
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="password">
                <?php echo getErrors('password', $errors, '<span class="error">', '</span>') ?>
            </div>

            <div class="form-group">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="confirmPassword">
                <?php echo getErrors('confirmPassword', $errors, '<span class="error">', '</span>') ?>
            </div>

            <div class="form-group">
                <label for="">Trạng thái</label>
                <select name="status" id="" class="form-control">
                    <option value="">Chọn trạng thái</option>
                    <option value="1" <?php echo (getOld('status', $old) == 1) ? 'selected' : false; ?>>Kích hoạt</option>
                    <option value="2" <?php echo (getOld('status', $old) == 2) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                </select>
                <?php echo getErrors('status', $errors, '<span class="error">', '</span>') ?>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Xác nhận</button>
    <a href="?module=users" class="btn btn-success">Quay lại</a>
</form>

<?php
layout("footer");
