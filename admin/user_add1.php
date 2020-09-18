<?php
die("function disabled");
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_user()) {
    header("Location: login.php");
    exit;
}
if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}
if (!$phpacl->check_permission()) {
    header("Location: no_permission.php");
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$pw1 = filter_input(INPUT_POST, 'pw1');
$pw2 = filter_input(INPUT_POST, 'pw2');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: user_add.php");
    exit;
}

if (empty($email)) {
    $_SESSION['message'] = _("Email不正確");
    header("Location: user_add.php");
    exit;
}

if (empty($pw1) || empty($pw2) || $pw1 !== $pw2) {
    $_SESSION['message'] = _("兩次密碼輸入不正確");
    header("Location: user_add.php");
    exit;
}

try {
    $db->beginTransaction();

    // 檢查是否已經產生, 如果產生, 則跳過
    $sql = "select id from merchant where email=:email";
    $st = $db->prepare($sql);
    $st->bindParam(':email', $email, PDO::PARAM_STR);
    $st->execute();
    $n = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $n++;
    }
    if ($n > 0) {
        $_SESSION['message'] = _("此Email已經存在請使用其他Email");
        header("Location: user_add.php");
        exit;
    }

    // 新增一筆
    $encpw = password_hash($pw1, PASSWORD_BCRYPT);
    $sql = "insert into user (email,pw,is_verified) values (:email,:pw,,1)";
    $st = $db->prepare($sql);
    $st->bindParam(':email', $email, PDO::PARAM_STR);
    $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
    $st->bindParam(':country_code', $country_code, PDO::PARAM_STR);
    $st->execute();
    $db->commit();

    $id = $db->lastInsertId();

    $phplog->db("user", "新增user id={$id},email={$mail},firstname={$firstname}, lastname={$lastname}");
    $subject = "新增user帳號 id={$id},email={$mail},firstname={$firstname}, lastname={$lastname}";
    $body = "新增user id={$id},email={$mail},firstname={$firstname}, lastname={$lastname}";
    $phpmail->send($admin_email, $MAIL_TO_MANAGER, $subject, $body);
    $_SESSION['message'] = _("新增成功");
    header("Location: user_add.php");
    exit;
} catch (PDOException $e) {
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    //$_SESSION['message'] = $e->getMessage();
    $_SESSION['message'] = _("新增失敗");
    $db->rollBack();
    header("Location: user_add.php");
    exit;
} catch (Exception $e) {
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    //$_SESSION['message'] = $e->getMessage();
    $_SESSION['message'] = _("新增失敗");
    $db->rollBack();
    header("Location: user_add.php");
    eixt;
}
