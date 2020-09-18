<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (empty($id)) {
    $_SESSION['message'] = _("編號不可以為空");
    header("Location: domain-list.php");
    exit;
}

try {
    $sql = "delete from ip where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $id);
    $st->execute();

    $_SESSION['message'] = _("刪除成功");

} catch (PDOException $e) {
    $_SESSION['message'] = _("無法刪除");
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();

}

header("Location: hostname-list.php ");