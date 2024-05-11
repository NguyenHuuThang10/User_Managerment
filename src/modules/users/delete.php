<?php
if(!defined('_INCODE')) die('Access Denied....');

$body = getBody();

if(!empty($body['id'])){
    $userId = $body['id'];

    $query = getRows("SELECT * FROM users WHERE id = $userId");

    if($query > 0){
        $deleteToken = destroy("logintoken", "id=$userId");
        if($deleteToken){
            $deleteUser = destroy("users", "id=$userId");
            if($deleteUser){
                setFlashData('msg','Xóa người dùng thành công');
                setFlashData('msg_type','Success');

            }else{
                setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau');
            setFlashData('msg_type','danger');
        }
    }else{
        setFlashData('msg','Người dùng không tồn tại trong hệ thống');
        setFlashData('msg_type','danger');
    }
}else{
    setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau');
    setFlashData('msg_type','danger');
}
redirect("?module=users");