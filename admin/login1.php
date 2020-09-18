<?php
include_once __DIR__ . '/../settings.php';

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$pw = filter_input(INPUT_POST, 'pw');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

if (!checkToken($csrf_token)) {
    $phplog->loginfail($id);
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: login.php");
    exit;
}

if (!check_password($pw)) {
    $_SESSION['message'] = _("很抱歉，您的密碼強度太弱，不符合系統規定");
    header("Location: login.php");
    exit;
}

if (empty($email)) {
    $_SESSION['message'] = _("帳號不可以為空");
    header('Location: login.php');
    exit;
}

if (empty($pw)) {
    $_SESSION['message'] = _("密碼不可以為空");
    header('Location: login.php');
    exit;
}

$ip = get_client_ip();
$_SESSION['ip'] = $ip;

try {
    $n = 0;
    $sql = "select * from users where email=:email";
    $st = $db->prepare($sql);
    $st->bindParam(':email', $email, PDO::PARAM_STR);
    $st->execute();
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $n = 1;
        if (password_verify($pw, $row['pw'])) {
            // regenerate session on successful login
            session_regenerate_id();
            $_SESSION['id'] = $row['id'];
            $_SESSION['is_admin'] = 1;
            $_SESSION['message'] = _("登入成功");
            $phplog->login($email);
            header("Location: index.php");
            exit;
        } else {
            $phplog->loginfail($email);
            $_SESSION['message'] = _("帳號或密碼錯誤");
            header("Location: login.php");
            exit;
        }
    }

    if ($n == 0) {
        $phplog->loginfail($email);
        $_SESSION['message'] = _("帳號不存在");
        header("Location: login.php");
        exit;
    }

} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $_SESSION['message'] = _("系統錯誤");
    header("Location: login.php");
    exit;
}

?>
