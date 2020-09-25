<?php
include_once __DIR__ . '/../settings.php';

$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$zone = filter_input(INPUT_POST, 'zone', FILTER_SANITIZE_STRING);
$host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$ttl = filter_input(INPUT_POST, 'ttl', FILTER_VALIDATE_INT);
$mx_priority = filter_input(INPUT_POST, 'mx_priority', FILTER_VALIDATE_INT);

//如果email格式錯誤，$email為空值
if (empty($id)) {
    $_SESSION['message'] = _('編號錯誤');
    header("Location: hostname-list.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: hostname-list.php");
    exit;
}

try {
    $db->beginTransaction();

    $ttl = !empty($ttl) ? $ttl : 600;
    $mx_priority = !empty($mx_priority) ? $mx_priority : 0;
    $refresh = 600;
    $expire = 86400;
    $minimum = 3600;
    //$retry = 15;
    //$serial = date("Ymdhi");
    //$resp_person = "admin";
    //$primary_ns = "dns.google";
    $sql = "select max(serial) as serial from dns_records";
    $st = $db->prepare($sql);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows[0]['serial'])) {
        $serial = 2020091603;
    } else {
        $serial = intval($rows[0]['serial']) + 1;
    }

    $sql = "update dns_records set zone=:zone,host=:host,type=:type,data=:data, ttl=:ttl, mx_priority=:mx_priority, serial=:serial where id=:id";
    $st = $db->prepare($sql);
    $st->bindParam(':zone', $zone, PDO::PARAM_STR);
    $st->bindParam(':host', $host, PDO::PARAM_STR);
    $st->bindParam(':type', $type, PDO::PARAM_STR);
    $st->bindParam(':data', $data, PDO::PARAM_STR);
    $st->bindParam(':ttl', $ttl, PDO::PARAM_STR);
    $st->bindParam(':mx_priority', $mx_priority, PDO::PARAM_STR);
    $st->bindParam(':serial', $serial, PDO::PARAM_STR);
    $st->bindParam(':id', $id, PDO::PARAM_STR);
    $st->execute();

    $_SESSION['message'] = _('更新完成');
    $db->commit();
    //$phplog->db("org", "add domain = {$domain}}");
} catch (PDOException $e) {
    $db->rollBack();
    error_log(print_r($e, 1));
    $_SESSION['message'] = _('新增失敗，系統異常');
    $message = $e->getMessage();
}

header("Location: hostname-list.php");
exit;
