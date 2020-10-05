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

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$pw1 = filter_input(INPUT_POST, 'pw1', FILTER_SANITIZE_STRING);
$pw2 = filter_input(INPUT_POST, 'pw2', FILTER_SANITIZE_STRING);
$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$is_admin = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_INT);

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: user_add.php");
    exit;
}

if (empty($email)) {
    $_SESSION['message'] = _("帳號不正確");
    header("Location: user_add.php");
    exit;
}

if (empty($pw1) || empty($pw2) || $pw1 !== $pw2) {
    $_SESSION['message'] = _("兩次密碼輸入不正確");
    header("Location: user_add.php");
    exit;
}

if (!check_password($pw1)) {
    $_SESSION['message'] = _("很抱歉，您的密碼強度太弱，不符合系統規定");
    header("Location: user_add.php");
    exit;
}

try {
    $db->beginTransaction();

    // 檢查是否已經產生, 如果產生, 則跳過
    $sql = "select id from users where email=:email";
    $st = $db->prepare($sql);
    $st->bindParam(':email', $email, PDO::PARAM_STR);
    $st->execute();
    $n = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $n++;
    }
    if ($n > 0) {
        $_SESSION['message'] = _("此帳號已經存在請使用其他帳號");
        header("Location: user_add.php");
        exit;
    }

    // 新增一筆
    $encpw = password_hash($pw1, PASSWORD_BCRYPT);
    $sql = "insert into users (email,pw,is_verified,is_admin) values (:email,:pw,1,:is_admin)";
    $st = $db->prepare($sql);
    $st->bindParam(':email', $email, PDO::PARAM_STR);
    $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
    $st->bindParam(':is_admin', $is_admin, PDO::PARAM_INT);
    $st->execute();
    $db->commit();

    $id = $db->lastInsertId();

    $phplog->db("users", "新增users id={$id},帳號={$mail}");
    $_SESSION['message'] = _("新增成功");
    header("Location: user_add.php");
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['message'] = _("新增失敗");
    $db->rollBack();
    header("Location: user_add.php");
    exit;
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['message'] = _("新增失敗");
    header("Location: user_add.php");
    eixt;
}
