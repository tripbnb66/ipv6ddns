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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (empty($id)) {
    $_SESSION['message'] = _("編號不可以為空");
    header("Location: user_list.php");
    exit;
}

try {
    $db->beginTransaction();

    $sql = "select * from users where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $id);
    $st->execute();
    $n = 0;
    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $email = $row['email'];
        $n++;
    }

    if ($n == 0) {
        $_SESSION['message'] = _("帳號不存在");
        header("Location: user_list.php");
        exit;
    }

    $sql = "delete from users where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':id', $id);
    $st->execute();
    $db->commit();

    $phplog->db("users", "刪除帳號 {$email}, id={$id}");
    $_SESSION['message'] = _("刪除成功");

} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();
    $db->rollBack();
}

header("Location: user_list.php ");