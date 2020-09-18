<?php
include_once __DIR__ . '/../settings.php';

$hostname = filter_input(INPUT_POST, 'hostname', FILTER_SANITIZE_STRING);
$domain_id = filter_input(INPUT_POST, 'domain_id', FILTER_VALIDATE_DOMAIN);
$csrf_token = filter_input(INPUT_POST, 'csrf_token');
$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
$host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
$is_local = filter_input(INPUT_POST, 'is_local', FILTER_VALIDATE_INT);
$mx_priority = filter_input(INPUT_POST, 'mx_priority', FILTER_VALIDATE_INT);

if (empty($is_local)) {
    $is_local = 0;
}

// 'MX','CNAME','NS','SOA','A','PTR','AAAA'
//如果email格式錯誤，$email為空值
if (empty($domain_id)) {
    $_SESSION['message'] = _('網域錯誤');
    header("Location: hostname-add.php");
    exit;
}
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
    $ttl = 600;
    $refresh = 600;
    $expire = 86400;
    $minimum = 3600;
    $serial = date("Ymdhi");

    // 插入正向解析
    if (in_array($type, ['A', 'AAAA', 'CNAME', 'NS', 'TXT'])) {
        $sql = "insert into dns_records (zone,host,type,data,ttl,domain_id) values (:zone,:host,:type,:data,:ttl,:domain_id)";
        $st = $db->prepare($sql);
        $st->bindParam(':zone', $zone, PDO::PARAM_STR); // 域名
        $st->bindParam(':host', $host, PDO::PARAM_STR); // 記錄名稱
        $st->bindParam(':type', $type, PDO::PARAM_STR); // 記錄類型
        $st->bindParam(':data', $data, PDO::PARAM_STR); // 記錄值
        $st->bindParam(':ttl', $ttl, PDO::PARAM_STR); // ttl(存活時間)
        $st->bindParam(':domain_id', $domain_id, PDO::PARAM_STR);
        $st->execute();
    }
    // 插入反向解析
    if ($type == 'NS' || $type == 'PTR') {
        $sql = "insert into dns_records (zone,host,type,data,domain_id) values (:zone,:host,:type,:data,:domain_id)";
        $st = $db->prepare($sql);
        $st->bindParam(':zone', $zone, PDO::PARAM_STR); // 域名
        $st->bindParam(':host', $host, PDO::PARAM_STR); // 記錄名稱
        $st->bindParam(':type', $type, PDO::PARAM_STR); // 記錄類型
        $st->bindParam(':data', $data, PDO::PARAM_STR); // 記錄值
        $st->bindParam(':domain_id', $domain_id, PDO::PARAM_STR);
        $st->execute();
    }
    if ($type == 'SOA') {

        $sql = "insert into dns_records (zone,host,type,data,ttl,mx_priority,refresh,retry,expire,minimum,serial,resp_person,primary_ns,domain_id) values (:zone,:host,:type,:data,:ttl,:mx_priority,:refresh,:retry,:expire,:minimum,:serial,:resp_person,:primary_ns,:domain_id)";
        $st = $db->prepare($sql);
        $st->bindParam(':zone', $zone, PDO::PARAM_STR); // 域名
        $st->bindParam(':host', $host, PDO::PARAM_STR); // 記錄名稱
        $st->bindParam(':type', $type, PDO::PARAM_STR); // 記錄類型
        $st->bindParam(':data', $data, PDO::PARAM_STR); // 記錄值
        $st->bindParam(':ttl', $ttl, PDO::PARAM_STR); // ttl(存活時間)
        $st->bindParam(':mx_priority', $mx_priority, PDO::PARAM_STR); // mx優先級
        $st->bindParam(':refresh', $refresh, PDO::PARAM_STR); // 刷新時間間隔
        $st->bindParam(':retry', $retry, PDO::PARAM_STR); // 重試時間間隔
        $st->bindParam(':expire', $expire, PDO::PARAM_STR); // 過期時間
        $st->bindParam(':minimum', $minimum, PDO::PARAM_STR); // 最小時間
        $st->bindParam(':serial', $serial, PDO::PARAM_STR); // 序列號,每次更改配置都會在原來的基礎上加1
        $st->bindParam(':resp_person', $resp_person, PDO::PARAM_STR); // 責任人
        $st->bindParam(':primary_ns', $primary_ns, PDO::PARAM_STR); // 主域名
        $st->bindParam(':domain_id', $domain_id, PDO::PARAM_STR);
        $st->execute();
    }

    // 插入客戶端

    $sql = "insert into dns_records (zone,host,type,data,ttl,mx_priority,refresh,retry,expire,minimum,serial,resp_person,primary_ns,dynaload,datestamp,regnumber) values (:zone,:host,:type,:data,:ttl,:mx_priority,:refresh,:retry,:expire,:minimum,:serial,:resp_person,:primary_ns,:dynaload,:datestamp,:regnumber)";
    $st = $db->prepare($sql);
    $st->bindParam(':zone', $zone, PDO::PARAM_STR); // 域名
    $st->bindParam(':host', $host, PDO::PARAM_STR); // 記錄名稱
    $st->bindParam(':type', $type, PDO::PARAM_STR); // 記錄類型
    $st->bindParam(':data', $data, PDO::PARAM_STR); // 記錄值
    $st->bindParam(':ttl', $ttl, PDO::PARAM_STR); // ttl(存活時間)
    $st->bindParam(':mx_priority', $mx_priority, PDO::PARAM_STR); // mx優先級
    $st->bindParam(':refresh', $refresh, PDO::PARAM_STR); // 刷新時間間隔
    $st->bindParam(':retry', $retry, PDO::PARAM_STR); // 重試時間間隔
    $st->bindParam(':expire', $expire, PDO::PARAM_STR); // 過期時間
    $st->bindParam(':minimum', $minimum, PDO::PARAM_STR); // 最小時間
    $st->bindParam(':serial', $serial, PDO::PARAM_STR); // 序列號,每次更改配置都會在原來的基礎上加1
    $st->bindParam(':resp_person', $resp_person, PDO::PARAM_STR); // 責任人
    $st->bindParam(':primary_ns', $primary_ns, PDO::PARAM_STR); // 主域名
    $st->bindParam(':dynaload', $dynaload, PDO::PARAM_STR); //
    $st->bindParam(':datestamp', $datestamp, PDO::PARAM_STR);
    $st->bindParam(':regnumber', $regnumber, PDO::PARAM_STR);
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
