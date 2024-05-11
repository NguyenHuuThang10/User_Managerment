<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
const _MODULE_DEFAULT = "home";
const _ACTION_DEFAULT = "lists";

const _INCODE = true;

define("_WEB_HOST_ROOT", "http://".$_SERVER["HTTP_HOST"]."/User_Managerment/src/");

define("_WEB_HOST_TEMPLACES", _WEB_HOST_ROOT. "templaces");

define("_WEB_PATH_ROOT", __DIR__);
define("_WEB_PATH_TEMPLACES", __DIR__. "/templaces");


const _HOST = "localhost";
const _USER = "root";
const _PASS = "";
const _DB = "users_managerment";
const _DRIVER = "mysql";