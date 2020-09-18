<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_REGEXP, $validate_id);
$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$domain = filter_input(INPUT_POST, 'domain', FILTER_VALIDATE_DOMAIN);

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: user_update.php");
    exit;
}

if (empty($id)) {
    $_SESSION['message'] = _("編號不可為空");
    header("Location: domain-list.php");
    exit;
}

if (empty($domain)) {
    $_SESSION['message'] = _("網域不可為空");
    header("Location: domain-list.php");
    exit;
}

try {
    $db->beginTransaction();
    $sql = "update domain set domain=:domain where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':domain', $domain, PDO::PARAM_STR);
    $st->bindParam(':id', $id, PDO::PARAM_INT);
    $st->execute();

    $_SESSION['message'] = _("更新成功");
    $db->commit();
    header("Location: domain-list.php");
    exit;
} catch (PDOException $e) {
    $db->rollBack();
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();
    header("Location: domain-update.php?id=" . $id);
    exit;
}
