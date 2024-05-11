<?php
if(!defined('_INCODE')) die('Access Denied...');

if(isLogin()){
    $token = getSession('loginToken');
    destroy('logintoken', "token='$token'");
    removeSession('loginToken');
    redirect('?module=auth&action=login');
}