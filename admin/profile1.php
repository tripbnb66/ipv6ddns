<?php
include_once __DIR__ . '/../settings.php';

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$pw = filter_input(INPUT_POST, 'pw');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

//如果email格式錯誤，$email為空值
if (empty($email)) {
    $_SESSION['message'] = _('Email格式錯誤');
    header("Location: profile.php");
    exit;
}

if (!empty($pw)) {
    if (!check_password($pw)) {
        $_SESSION['message'] = _("很抱歉，您的密碼強度太弱，不符合系統規定");
        header("Location: profile.php");
        exit;
    }
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: profile.php");
    exit;
}

try {
    if (empty($pw)) {
        $sql = "update users set firstname=:firstname, lastname=:lastname, email=:email where id=:id";
        $st = $db->prepare($sql);
        $st->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $st->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $st->bindParam(':email', $email, PDO::PARAM_STR);
        $st->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
    } else {
        $encpw = password_hash($pw, PASSWORD_BCRYPT);
        $sql = "update users set firstname=:firstname, lastname=:lastname, email=:email, pw=:pw where id=:id";
        $st = $db->prepare($sql);
        $st->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $st->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $st->bindParam(':email', $email, PDO::PARAM_STR);
        $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
        $st->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
    }
    $st->execute();
    $_SESSION['message'] = _('更新完成');
    $phplog->db("users", "update users firstname={$firstname},lastname={$lastname},email={$email}");
} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('更新失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: profile.php");
exit;
