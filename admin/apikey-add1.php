<?php
include_once __DIR__ . '/../settings.php';

$apikey = filter_input(INPUT_POST, 'apikey', FILTER_SANITIZE_STRING);
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

if (empty($apikey)) {
    $_SESSION['message'] = _('api key 格式錯誤');
    header("Location: apikey-add.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: apikey-add.php");
    exit;
}

try {
    if (empty($allowip)) {
        $allowip = '';
    }
    $sql = "insert into apikey (apikey) values (:apikey)";
    $st = $db->prepare($sql);
    $st->bindParam(':apikey', $apikey, PDO::PARAM_STR);
    $st->execute();
    $_SESSION['message'] = _('新增完成');
} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('新增失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: apikey-list.php");
exit;
