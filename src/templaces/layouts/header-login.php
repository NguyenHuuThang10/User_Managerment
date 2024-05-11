<?php
if ( ! defined("_INCODE") ) die("Access Denied....!");

if(isLogin()){
    redirect("?module=users");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data) ? $data['pageTitle'] : "Unicode" ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/fonts/css/all.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/css/style.css?ver=<?php echo rand(); ?>">
</head>
<body>
    
