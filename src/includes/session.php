<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

function setSession ($key, $value){
    if(!empty(session_id())){
        $_SESSION[$key] = $value;
        return true;    
    }
    return false;
}

function getSession ($key=''){
    if(empty($key)){
        return $_SESSION;
    }else{
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }
}

function removeSession ($key=''){
    if(empty($key)){
        session_destroy();
    }else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}

function setFlashData ( $key, $value ){
    $key = 'flash_'.$key;
    return setSession($key, $value);
}

function getFlashData ($key= ''){
    $key = 'flash_'.$key;
    $data = getSession($key);
    removeSession($key);

    return $data;
}