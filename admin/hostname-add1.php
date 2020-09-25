<?php
include_once __DIR__ . '/../settings.php';

$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$zone = filter_input(INPUT_POST, 'zone', FILTER_SANITIZE_STRING);
$host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$ttl = filter_input(INPUT_POST, 'ttl', FILTER_VALIDATE_INT);
$mx_priority = filter_input(INPUT_POST, 'mx_priority', FILTER_VALIDATE_INT);

// 'MX','CNAME','NS','SOA','A','PTR','AAAA'
//如果email格式錯誤，$email為空值
if (!in_array($type, ['A', 'AAAA', 'CNAME', 'PTR', 'TXT', 'MX', 'CNAME', 'SOA', 'NS'])) {
    $_SESSION['message'] = _('不支援的類型');
    header("Location: hostname-add.php");
    exit;
}

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: hostname-add.php");
    exit;
}

try {
    $db->beginTransaction();

    $ttl = !empty($ttl) ? $ttl : 60;
    $mx_priority = !empty($mx_priority) ? $mx_priority : 0;
    $refresh = 600;
    $expire = 86400;
    $minimum = 3600;
    //$retry = 15;
    //$serial = date("Ymdhi");
    $sql = "select max(serial) as serial from dns_records";
    $st = $db->prepare($sql);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows[0]['serial'])) {
        $serial = 2020091603;
    } else {
        $serial = intval($rows[0]['serial']) + 1;
    }

    //$resp_person = "admin";
    //$primary_ns = "dns.google";

    //$sql = "insert into dns_records (zone,host,type,data,ttl,mx_priority,refresh,retry,expire,minimum,serial,resp_person,primary_ns) values (:zone,:host,:type,:data,:ttl,:mx_priority,:refresh,:retry,:expire,:minimum,:serial,:resp_person,:primary_ns)";
    $sql = "insert into dns_records (zone,host,type,data,ttl,mx_priority,serial) values (:zone,:host,:type,:data,:ttl,:mx_priority,:serial)";
    $st = $db->prepare($sql);
    $st->bindParam(':zone', $zone, PDO::PARAM_STR); // 域名
    $st->bindParam(':host', $host, PDO::PARAM_STR); // 記錄名稱
    $st->bindParam(':type', $type, PDO::PARAM_STR); // 記錄類型
    $st->bindParam(':data', $data, PDO::PARAM_STR); // 記錄值
    $st->bindParam(':ttl', $ttl, PDO::PARAM_STR); // ttl(存活時間)
    $st->bindParam(':mx_priority', $mx_priority, PDO::PARAM_STR); // mx優先級
    //$st->bindParam(':refresh', $refresh, PDO::PARAM_STR); // 刷新時間間隔
    // $st->bindParam(':retry', $retry, PDO::PARAM_STR); // 重試時間間隔
    //$st->bindParam(':expire', $expire, PDO::PARAM_STR); // 過期時間
    //$st->bindParam(':minimum', $minimum, PDO::PARAM_STR); // 最小時間
    $st->bindParam(':serial', $serial, PDO::PARAM_STR); // 序列號,每次更改配置都會在原來的基礎上加1
    //$st->bindParam(':resp_person', $resp_person, PDO::PARAM_STR); // 責任人
    //$st->bindParam(':primary_ns', $primary_ns, PDO::PARAM_STR); // 主域名
    $st->execute();

    $id = $db->lastInsertId();

    $_SESSION['message'] = _('新增完成');
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
