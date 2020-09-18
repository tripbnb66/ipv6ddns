<?php
include_once __DIR__ . '/../settings.php';

$domain = filter_input(INPUT_POST, 'domain', FILTER_VALIDATE_DOMAIN);
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

//如果email格式錯誤，$email為空值
if (empty($domain)) {
    $_SESSION['message'] = _('網域格式錯誤');
    header("Location: domain-add.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: domain-add.php");
    exit;
}

try {
    $sql = "insert into domain (domain) values (:domain)";
    $st = $db->prepare($sql);
    $st->bindParam(':domain', $domain, PDO::PARAM_STR);

    $st->execute();
    $_SESSION['message'] = _('新增完成');
    $phplog->db("org", "add domain = {$domain}}");
} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('新增失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: domain-list.php");
exit;
