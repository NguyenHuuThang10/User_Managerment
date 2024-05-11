<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

$data = [
    "pageTitle" => "Quên mật khẩu",
];

layout("header-login", $data);
if (isPost() ) {
    $body = getBody();
    
    if(empty($body["email"])) {
        setFlashData("msg", "Vui lòng nhập đầy đủ thông tin!");
        setFlashData("msg_type", "danger");
    }else{
        $email = $body["email"];

        if(!isEmail($email)){
            setFlashData("msg", "Email không đúng định dạng");
            setFlashData("msg_type","danger");
        }else{
            $checkMail = firstRaw("SELECT * FROM users WHERE email = '$email'");
            if($checkMail){
                $forgotToken = sha1(uniqid().time());
                $email = $checkMail['email'];
                $fullname = $checkMail['fullname'];
                
                $dataUpdate = [
                    "forgotToken" => $forgotToken,
                ];

                $updateStatus = update("users", $dataUpdate, "email='$email'");

                if($updateStatus){
                    $linkReset = _WEB_HOST_ROOT."?module=auth&action=reset&token=$forgotToken";
                    $subject = "Đặt lại mật khẩu";
                    $content = 'Chào bạn: ' . $fullname . '<br>';
                    $content .= 'Vui lòng nhấp chọn vào link dưới đây để đặt lại mật khẩu. <br>';
                    $content .= $linkReset . '<br>';
                    $content .= 'Trân trọng!';
                    $sendStatus = sendMail($email, $subject, $content);
                    if($sendStatus){
                        setFlashData('msg', 'Vui lòng kiểm tra email để đặt lại mật khẩu.');
                        setFlashData('msg_type', 'success');
                    }else{
                        setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau!');
                        setFlashData('msg_type','danger');
                    }
                }else{
                    setFlashData("msg", "Hệ thống bị lỗi vui lòng thử lại sau");
                    setFlashData("msg_type","danger");
                }
                
            }else{
                setFlashData("msg", "Email không tồn tại trong hệ thống");
                setFlashData("msg_type","danger");
            }
        }
    }
    redirect("?module=auth&action=forgot");
        

}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h2 class="text-center">Quên mật khẩu</h2>
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">

            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email">
            </div>

            <button type="submit" style="width: 100%; height: 46px;" class="btn btn-primary">Xác nhận</button>
        </form>
        <hr>
        <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
        <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        

    </div>
</div>

<?php
layout("footer-login");