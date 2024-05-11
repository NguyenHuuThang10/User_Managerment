<?php
if(!defined('_INCODE')) die('Access Denied...');
?>

<div style="width: 600px; text-align: center; margin: 0 auto">
    <h2>Lỗi liên quan đến cơ sở dữ liệu</h2>

    <hr>
    <p><?php echo $e->getMessage() ?></p>
    <p>File: <?php echo $e->getFile() ?></p>
    <p>Line: <?php echo $e->getLine() ?></p>

    <p></p>
</div>