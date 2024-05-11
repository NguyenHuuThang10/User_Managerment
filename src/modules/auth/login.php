<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

$data = [
    "pageTitle" => "Đăng nhập hệ thống",
];

layout("header-login", $data);
if (isPost() ) {
    $body = getBody();

    if(!empty($body["email"]) && !empty($body['password'])) {
        $email = $body['email'];
        $password = $body['password'];

    

        $subQuery = firstRaw("SELECT id , password FROM users WHERE email = '$email' AND status = 1");

        if($subQuery) {
            $passwordHash = $subQuery['password'];
            $userId = $subQuery['id']; 

            $checkPass = password_verify($password, $passwordHash);

       
            if($checkPass) {
                $loginToken = sha1(uniqid().time());
                $dataInsert = [
                    'userId' => $userId,
                    'token' => $loginToken,
                    'createdAt' => date('Y-m-d H:i:s'),
                ];

                $insertStatus = insert('logintoken', $dataInsert);

                if($insertStatus){
                    setSession('loginToken' , $loginToken);
                    redirect('?module=users');
                }else{
                    setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau!');
                    setFlashData('msg_type','danger');
                }
            }else{
                setFlashData("msg","Sai thông tin đăng nhập!");
                setFlashData("msg_type","danger");
            }
        }else{
            setFlashData("msg","Email không tồn tại trong hệ thống hoặc chưa được kích hoạt");
            setFlashData("msg_type","danger");
        }

    }else{
        setFlashData("msg","Vui lòng nhập đầy đủ thông tin đăng nhập");
        setFlashData("msg_type","danger");
    }
    redirect("?module=auth&action=login");

}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h2 class="text-center">Đăng nhập hệ thống</h2>
        <?php
            getMsg($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email">
            </div>

            <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <button type="submit" style="width: 100%; height: 46px;" class="btn btn-primary">Đăng nhập</button>
        </form>
        <hr>
        <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
        <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        

    </div>
</div>

<?php
layout("footer-login");