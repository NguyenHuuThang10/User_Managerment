<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

$data = [
    "pageTitle" => "Đăng ký tài khoản",
];

layout("header-login", $data);

if(isPost()) {
    $body = getBody();
    $errors = [];

    if(empty(trim($body["fullname"]))) {
        $errors['fullname']['required'] = "Họ tên bắt buộc phải nhập!";
    }else{
        $fullname = trim($body["fullname"]);
        if(mb_strlen($fullname) < 6) {
            $errors["fullname"]["min"] = "Họ tên phải lớn hơn 6 ký tự!";
        }
    }

    if(empty(trim($body["email"]))) {
        $errors["email"]["required"] = "Email bắt buộc phải nhập";
    }else{
        $email = $body["email"];
        if(!isEmail(trim($email))) {
            $errors["email"]["isEmail"] = "Email không đúng định dạng";
        }else{
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $checkUnique = getRows($sql);
            if($checkUnique > 0){
                $errors["email"]["unique"] = "Email đã tồn tại trong hệ thống";
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

    if(empty($errors)){
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'password'=> password_hash($pass, PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'createdAt' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('users', $dataInsert);
        if($insertStatus){
            $linkActive = _WEB_HOST_ROOT.'?module=auth&action=active&activeToken='.$activeToken;
            $subject = "Kích hoạt tài khoản";
            $content = 'Chào bạn: ' . $fullname . '<br>';
            $content .= 'Vui lòng nhấp chọn vào link dưới đây để kích hoạt tài khoản. <br>';
            $content .= $linkActive . '<br>';
            $content .= 'Trân trọng!';
            $sendStatus = sendMail($email, $subject, $content);
            if($sendStatus){
                setFlashData('msg', 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau!');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau!');
            setFlashData('msg_type','danger');
        }
    }else{
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào!');
        setFlashData('msg_type','danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
    }
    redirect('?module=auth&action=register');
}
$errors = getFlashData('errors');
$old = getFlashData('old');
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h2 class="text-center">Đăng ký tải khoản</h2>
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            
            <div class="form-group">
                <label for="">Họ tên</label>
                <input type="text" class="form-control" name="fullname" placeholder="Họ tên" value="<?php echo getOld('fullname', $old) ?>">
                <?php echo getErrors('fullname', $errors, '<span class="error">', '</span>') ?>           
            </div>

            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo getOld('email', $old) ?>">
                <?php echo getErrors('email', $errors, '<span class="error">', '</span>') ?>   
            </div>

            <div class="form-group">
                <label for="">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" placeholder="Số điện thoại" value="<?php echo getOld('phone', $old) ?>">
                <?php echo getErrors('phone', $errors, '<span class="error">', '</span>') ?>   
            </div>

            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu">
                <?php echo getErrors('password', $errors, '<span class="error">', '</span>') ?>   
            </div>

            <div class="form-group">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="confirmPassword" placeholder="Nhập lại mật khẩu">
                <?php echo getErrors('confirmPassword', $errors, '<span class="error">', '</span>') ?>   
            </div>

            <button type="submit" style="width: 100%; height: 46px;" class="btn btn-primary">Đăng ký</button>
        </form>
        <hr>
        <p class="text-center"><a href="?module=auth&action=login">Đăng nhập hệ thống</a></p>
        

    </div>
</div>

<?php
layout("footer-login");