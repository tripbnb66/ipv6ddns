<?php
include_once __DIR__ . '/../settings.php';

$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($type == "ipv4") {
    $ipv4 = filter_input(INPUT_POST, 'ipv4', FILTER_SANITIZE_STRING);
    $is_local = filter_input(INPUT_POST, 'is_local', FILTER_VALIDATE_INT);
    if (empty($is_local)) {
        $is_local = 0;
    }
} else if ($type == "ipv6") {
    $ipv6 = filter_input(INPUT_POST, 'ipv6', FILTER_SANITIZE_STRING);
    $is_local = filter_input(INPUT_POST, 'is_local', FILTER_VALIDATE_INT);
    if (empty($is_local)) {
        $is_local = 0;
    }
} else if ($type == "cname") {
    $cname = filter_input(INPUT_POST, 'cname', FILTER_SANITIZE_STRING);
} else if ($type == "txt") {
    $txt = filter_input(INPUT_POST, 'txt', FILTER_SANITIZE_STRING);
} else if ($type == "mx") {
    $mx = filter_input(INPUT_POST, 'mx', FILTER_SANITIZE_STRING);
    $priority = filter_input(INPUT_POST, 'priority', FILTER_VALIDATE_INT);
} else {
    die("不支援的格式" . $type);
}

//如果email格式錯誤，$email為空值
if (empty($id)) {
    $_SESSION['message'] = _('編號錯誤');
    header("Location: hostname-list.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: hostname-list.php");
    exit;
}

try {
    $db->beginTransaction();

    $sql = "update ip set name=:name, priority=:priority,is_local=:is_local where hostname_id=:hostname_id and data_type=:data_type";
    $st = $db->prepare($sql);
    $st->bindParam(':hostname_id', $id, PDO::PARAM_INT);

    if ($type == "ipv4") {
        $data_type = 'ipv4';
        $priority = 0;
        $st->bindParam(':name', $ipv4, PDO::PARAM_STR);
        $st->bindParam(':data_type', $data_type, PDO::PARAM_STR);
        $st->bindParam(':priority', $priority, PDO::PARAM_INT);
        $st->bindParam(':is_local', $is_local, PDO::PARAM_INT);
        $st->execute();
    } else if ($type == "ipv6") {
        $data_type = 'ipv6';
        $priority = 0;
        $st->bindParam(':name', $ipv6, PDO::PARAM_STR);
        $st->bindParam(':data_type', $data_type, PDO::PARAM_STR);
        $st->bindParam(':priority', $priority, PDO::PARAM_INT);
        $st->bindParam(':is_local', $is_local, PDO::PARAM_INT);
        $st->execute();
    } else if ($type == "cname") {
        $data_type = 'cname';
        $priority = 0;
        $st->bindParam(':name', $cname, PDO::PARAM_STR);
        $st->bindParam(':data_type', $data_type, PDO::PARAM_STR);
        $st->bindParam(':priority', $priority, PDO::PARAM_INT);
        $st->execute();
    } else if ($type == "txt") {
        $data_type = 'txt';
        $priority = 0;
        $st->bindParam(':name', $txt, PDO::PARAM_STR);
        $st->bindParam(':data_type', $data_type, PDO::PARAM_STR);
        $st->bindParam(':priority', $priority, PDO::PARAM_INT);
        $st->execute();
    } else if ($type == "mx") {
        $data_type = 'mx';
        $st->bindParam(':name', $mx, PDO::PARAM_STR);
        $st->bindParam(':data_type', $data_type, PDO::PARAM_STR);
        $st->bindParam(':priority', $priority, PDO::PARAM_INT);
        $st->execute();
    }

    $_SESSION['message'] = _('更新完成');
    $db->commit();
    //$phplog->db("org", "add domain = {$domain}}");
} catch (PDOException $e) {
    $db->rollBack();
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('新增失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: hostname-list.php");
exit;
