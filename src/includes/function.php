<?php
if (!defined("_INCODE")) die("Access Denied....!");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName = "header", $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLACES . "/layouts/" . $layoutName . ".php")) {
        require_once _WEB_PATH_TEMPLACES . "/layouts/" . $layoutName . ".php";
    }
}

function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'nguyenhuuthangag123@gmail.com';                     //SMTP username
        $mail->Password   = 'aqyuxujrpuctzfzf';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('nguyenhuuthangag123@gmail.com', 'Nguyen Huu Thang');
        $mail->addAddress($to);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        return $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}


function getBody()
{
    $bodyArr = null;

    if (isGet()) {
        foreach ($_GET as $key => $value) {
            if (is_array($value)) {
                $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            } else {
                $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }

    if (isPost()) {
        foreach ($_POST as $key => $value) {
            if (is_array($value)) {
                $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
            } else {
                $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }

    return $bodyArr;
}


function isEmail($email){
    if(!empty($email)){
        $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    return $checkEmail;
}

function isNumberInt($num, $ranger = []) {
    if(!empty($ranger)){
        $option = ['option'=> $ranger];
        $checkNumberInt = filter_var($num, FILTER_VALIDATE_INT, $option);
    }else{
        $checkNumberInt = filter_var($num, FILTER_VALIDATE_INT);
    }

    return $checkNumberInt;

}

function isNumberFloat($num, $ranger = []) {
    if(!empty($ranger)){
        $option = ['option'=> $ranger];
        $checkNumberFloat = filter_var($num, FILTER_VALIDATE_FLOAT, $option);
    }else{
        $checkNumberFloat = filter_var($num, FILTER_VALIDATE_FLOAT);
    }

    return $checkNumberFloat;

}

function isPhone($phone){
    if(!empty($phone)){
        $checkZero = false;
        $checkLast = false;

        if($phone[0] == '0'){
            $checkZero = true;
            $phone = substr($phone,1);
        }

        if(isNumberInt($phone) && strlen($phone) == 9){
            $checkLast = true;
        }

        if($checkLast && $checkZero){
            return true;
        }
        return false;
    }

}


function getMsg($msg, $msg_type){
    echo "<div class='alert alert-$msg_type'>";
    echo $msg;
    echo '</div>';
}

function getOld($fieldName, $old){
    if(!empty($old[$fieldName])){
        return $old[$fieldName];
    }
}

function getErrors($fieldName, $errors, $beforHtml, $afterHtml){
    if(!empty($errors[$fieldName])){
        return $beforHtml.reset($errors[$fieldName]).$afterHtml;
    }
}

function redirect($path = 'index.php'){
    header('Location: '. $path);
    exit;
}


function isLogin(){
    if(!empty(getSession('loginToken'))){
        $token = getSession('loginToken');

        $query = firstRaw("SELECT * FROM logintoken WHERE token = '$token'");
        if($query){
            return $query;
        }else{
            removeSession("loginToken");
        }
    }
    return false;  

}

//từ động xóa login token nếu đăng xuất
function autoRemoveTokenLogin(){
    $allUser = getRaw("SELECT * FROM users WHERE status = 1");

    if(!empty($allUser)){
        foreach($allUser as $user){
            $now = date("Y-m-d H:i:s");

            $before = $user["lastActivity"];
        
            $diff = strtotime($now) - strtotime($before);
        
            $diff = floor($diff/60);
        
            if($diff >= 10){
                destroy("logintoken", "id=".$user['id']);
            }
        }
    }
}

function saveActivity(){
    $userId = isLogin()['userId'];
    update('users', [ 'lastActivity' => date('Y-m-d H:i:s') ], "id=$userId");
}


