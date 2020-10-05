<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_login()) {
    header("Location: login.php");
    exit;
}

$pw = filter_input(INPUT_POST, 'pw');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

if (empty($pw)) {
    $_SESSION['message'] = _("很抱歉，密碼不能為空");
    header("Location: profile.php");
    exit;
}

if (!check_password($pw)) {
    $_SESSION['message'] = _("很抱歉，您的密碼強度太弱，不符合系統規定");
    header("Location: profile.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: profile.php");
    exit;
}

try {

    $encpw = password_hash($pw, PASSWORD_BCRYPT);
    $sql = "update users set pw=:pw where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
    $st->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);

    $st->execute();
    $_SESSION['message'] = _('更新完成');
} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('更新失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: profile.php");
exit;
