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
    $db->beginTransaction();

    $sql = "select * from domain where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $id);
    $st->execute();
    $n = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $domain = $row['domain'];
        $n++;
    }

    if ($n == 0) {
        $_SESSION['message'] = _("domain不存在");
        header("Location: domain-list.php");
        exit;
    }

    $sql = "delete from domain where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $id);
    $st->execute();
    $db->commit();

} catch (PDOException $e) {
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();
    $db->rollBack();
}

header("Location: domain-list.php ");