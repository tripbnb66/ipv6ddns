<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_login()) {
    header("Location: login.php");
    exit;
}

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$pw1 = filter_input(INPUT_POST, 'pw1');
$pw2 = filter_input(INPUT_POST, 'pw2');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$is_admin = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_INT);

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: user_update.php");
    exit;
}

if (empty($id)) {
    $_SESSION['message'] = _("編號不可為空");
    header("Location: user_list.php");
    exit;
}
if (!empty($pw1) && !empty($pw2) && $pw1 !== $pw2) {
    $_SESSION['message'] = _("兩次密碼輸入不正確");
    header("Location: user_update.php?id=" . $id);
    exit;
}

if (!empty($pw1) && !check_password($pw1)) {
    $_SESSION['message'] = _("很抱歉，您的密碼強度太弱，不符合系統規定");
    header("Location: user_update.php?id=" . $id);
    exit;
}

try {
    $db->beginTransaction();

    if (!empty($pw1)) {
        $encpw = password_hash($pw1, PASSWORD_BCRYPT);
        $sql = "update users set pw=:pw where id=:id";
        $st = $db->prepare($sql);
        $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
        $st->bindParam(':id', $id, PDO::PARAM_INT);
        $st->execute();
    }
    $sql = "update users set is_admin=:is_admin where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
    $st->bindParam(':id', $id, PDO::PARAM_INT);
    $st->execute();

    $phplog->db("user", "update users id={$id}");
    $_SESSION['message'] = _("更新成功");
    $db->commit();
    header("Location: user_update.php?id=" . $id);
    exit;
} catch (PDOException $e) {
    $db->rollBack();
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();
    header("Location: user_update.php?id=" . $id);
    exit;
}
