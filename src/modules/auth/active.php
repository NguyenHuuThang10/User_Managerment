<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

$data = [
    'pageTitle' => 'Kích hoạt tải khoản',
];

layout('header-login', $data );

echo '<div class="mt-3 text-center"';

$body = getBody();

if(!empty($body['activeToken'])){
    $token = $body['activeToken'];
    $query = firstRaw("SELECT * FROM users WHERE activeToken = '$token'");
    if($query){
        $userId = $query['id'];
        $dataUpdate = [
            'status' => 1,
            'activeToken' => null,
        ];
        $updateStatus = update('users', $dataUpdate, "id=$userId");
        if($updateStatus){
            setFlashData('msg','Kích hoạt tài khoản thành công, bạn có thể đăng nhập ngay bây giờ!');
            setFlashData('msg_type','success');
        }else{
            setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau!');
            setFlashData('msg_type','danger');
        }
        redirect("?module=auth&action=login");
    }else{
        getMsg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
    }
}else{
    getMsg('Liên kết không tồn tại hoặc đã hết hạn!', 'danger');
}
redirect('?module=auth&action=active&activeToken='.$body['activeToken']);

echo '</div>';

layout('footer-login');