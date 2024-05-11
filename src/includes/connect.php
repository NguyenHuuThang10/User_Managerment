<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

try {
    if (class_exists("PDO") ){
        $dsn = _DRIVER.":dbname="._DB.";host="._HOST;

        $option = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $conn = new PDO( $dsn, _USER, _PASS, $option);
    }
}catch( Exception $e ) {
    require_once "modules/error/database.php";
    die();
}