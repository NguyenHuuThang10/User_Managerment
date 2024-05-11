<?php
if (!defined("_INCODE")) die("Access Denied....!");

$data = [
    "pageTitle" => "Quản lý hệ thống",
];

layout("header", $data);

// Xử lý tìm kiếm
$filter = '';
if(isGet()) {
    $body = getBody();

    if(!empty($body['status'])) {
        $status = $body['status'];
        if($status == 2) {
            $statusSql = 0;
        }else{
            $statusSql = $status;
        }
        
        if(!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        }else{
            $operator = 'WHERE';
        }
    
        $filter = "$operator status=$statusSql";
    }

    if(!empty($body['keyword'])){
        $keyWord = $body['keyword'];

        if(!empty($filter) && strpos($filter, 'WHERE') > 0) {
            $operator = 'AND';
        }else{
            $operator = 'WHERE';
        }
    
        $filter = "$operator fullname LIKE '%$keyWord%'";

    }
}

// Xử lý phân trang
$allUsers = getRows("SELECT * FROM users $filter");
$perPage = 4;
$maxPage = ceil($allUsers / $perPage);
if (!empty(getBody()["page"])) {
    $page = getBody()["page"];

    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;

$listAllUsers = getRaw("SELECT * FROM users $filter ORDER BY createdAt DESC LIMIT $offset, $perPage");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=users', '', $queryString);
    $queryString = str_replace('&page=' . $page, '', $queryString);
    $queryString = trim($queryString, '&');
    if(!empty($queryString)) {
        $queryString = '&'.$queryString;
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<h2 class="mt-2">Quản lý tài khoản</h2>

<a href="?module=users&action=add" class="btn btn-success mt-2">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
<?php
    getMsg($msg, $msg_type);
?>
<hr>
<form action="" method="get">
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <select name="status" id="" class="form-control">
                    <option value="0">Chọn trạng thái</option>
                    <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>Kích hoạt</option>
                    <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                </select>
            </div>
        </div>

        <div class="col-6">
            <input type="text" class="form-control" name="keyword" placeholder="Tìm kiếm" value="<?php echo (!empty($keyWord)) ? $keyWord : false; ?>">
        </div>

        <div class="col-3">
            <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
        </div>
    </div>
    <input type="hidden" name="module" value="users">
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Số điện thoại</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Sửa</th>
            <th scope="col">Xóa</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($listAllUsers)) {
            $count = 0;
            foreach ($listAllUsers as $item) :
        ?>

                <tr>
                    <th scope="row"><?php echo ++$count ?></th>
                    <td><?php echo $item['fullname']; ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo $item['phone']; ?></td>
                    <td><?php echo ($item['status'] == 1) ? "<button class='btn btn-success'>Kích hoạt</button>" : "<button class='btn btn-danger'>Chưa kích hoạt</button>" ?></td>
                    <td>
                        <p><a href="?module=users&action=edit&id=<?php echo $item['id'] ?>" class="btn btn-warning"><i class="fa-regular fa-pen-to-square"></i></a></p>
                    </td>

                    <td>
                        <p><a href="?module=users&action=delete&id=<?php echo $item['id'] ?>" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></a></p>
                    </td>
                </tr>

            <?php
            endforeach;
        } else {
            ?>
            <tr class="text-center">
                <td colspan="7">Không có dữ liệu</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>


<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        if ($page > 1) :
        ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php
        endif;
        $begin = $page - 2;
        if ($begin < 1) {
            $begin = 1;
        }
        $end = $page + 2;
        if ($end > $maxPage) {
            $end = $maxPage;
        }
        for ($i = $begin; $i <= $end; ++$i) :
        ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $i; ?>"><?php echo $i ?></a></li>
        <?php
        endfor;
        if ($page < $maxPage) :
        ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php
        endif;
        ?>
    </ul>
</nav>

<?php
layout("footer");
