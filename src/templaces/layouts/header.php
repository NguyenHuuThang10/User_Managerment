<?php
if (!defined("_INCODE")) die("Access Denied....!");

if (!isLogin()) {
    redirect("?module=auth&action=login");
}else{
    $userId = isLogin()['userId'];
    $userDetail = firstRaw("SELECT * FROM users WHERE id = $userId");
}

saveActivity();
autoRemoveTokenLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data) ? $data['pageTitle']:'Piti'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/fonts/css/all.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLACES; ?>/css/style.css?ver=<?php echo rand() ?>">
</head>

<body>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><?php echo !empty($userDetail['fullname']) ? $userDetail['fullname'] : 'PITI';  ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown profile">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            Hi, <?php echo !empty($userDetail['fullname']) ? $userDetail['fullname'] : 'PITI'; ?>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Thông tin cá nhân</a>
                            <a class="dropdown-item" href="#">Đổi mật khẩu</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="?module=auth&action=logout">Đăng xuất</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>