<?php
include_once __DIR__ . '/../settings.php';

$apikey = filter_input(INPUT_POST, 'apikey', FILTER_SANITIZE_STRING);
$idList = filter_input(INPUT_POST, 'idList', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$ipv4 = filter_input(INPUT_POST, 'ipv4', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$ipv6 = filter_input(INPUT_POST, 'ipv6', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

//error_log(print_r($_POST, 1));

if (empty($apikey)) {
    $ret = ['message' => _("無效的API KEY") . " apikey=" . $apikey, 'success' => 0];
    error_log(print_r($ret, 1));
    return json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($idList)) {
    $ret = ['message' => _("沒有選擇要更新的hostname"), 'success' => 0];
    error_log(print_r($ret, 1));
    return json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($ipv4) && empty($ipv6)) {
    $ret = ['message' => _("IP位址沒有提供"), 'success' => 0];
    error_log(print_r($ret, 1));
    return json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}

if (count($idList) == 0) {
    $ret = ['message' => _("沒有提供要更新的hostname"), 'success' => 0];
    error_log(print_r($ret, 1));
    return json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}

$ip = get_client_ip();
$_SESSION['ip'] = $ip;

try {

    // 檢查該ip 最近30分鐘內,是否已經連續三次錯誤?
    if ($phplog->check_ipfail_try($ip) >= 3) {
        $ret = ['message' => _("此IP多次登入失敗，已經被鎖，請稍後再試。"), 'success' => 0];
        error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 檢查該id 最近30分鐘內,是否已經連續三次錯誤?
    if ($phplog->check_loginfail_try($apikey) >= 3) {
        $ret = ['message' => _("此帳號多次登入失敗，已經被鎖，請稍後再試。"), 'success' => 0];
        error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $n = 0;
    $sql = "select count(*) as n from apikey where apikey=:apikey";
    $st = $db->prepare($sql);
    $st->bindParam(':apikey', $apikey, PDO::PARAM_STR);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    if ($rows[0]['n'] == 1) {
        foreach ($idList as $id) {
            if (!empty($id)) {
                error_log("update id=$id, ipv4={$ipv4[0]}, ipv6={$ipv6[0]}");
                $sql = "select * from dns_records where id=:id";
                $st2 = $db->prepare($sql);
                $st2->bindParam(':id', $id, PDO::PARAM_INT);
                $st2->execute();
                while ($row2 = $st2->fetch(PDO::FETCH_ASSOC)) {
                    if ($row2['type'] == 'A' && !empty($ipv4[0])) {
                        $sql = "update dns_records set data=:data where id=:id and type='A'";
                        $st3 = $db->prepare($sql);
                        $st3->bindParam(':id', $id, PDO::PARAM_INT);
                        $st3->bindParam(':data', $ipv4[0], PDO::PARAM_STR);
                        $st3->execute();
                        error_log("update id=$id,ipv4={$ipv4[0]} ok");
                    } else if ($row2['type'] == 'AAAA' && !empty($ipv6[0])) {
                        $sql = "update dns_records set data=:data where id=:id and type='AAAA' ";
                        $st3 = $db->prepare($sql);
                        $st3->bindParam(':id', $id, PDO::PARAM_INT);
                        $st3->bindParam(':data', $ipv6[0], PDO::PARAM_STR);
                        $st3->execute();
                        error_log("update id=$id,ipv6={$ipv6[0]} ok");
                    }
                }
            }
        }
        $sql = "select * from dns_records where (type='A' or type='AAAA' ) order by `type` desc";
        $st4 = $db->prepare($sql);
        $st4->bindParam(':apikey', $apikey, PDO::PARAM_STR);
        $st4->execute();
        $data = $st4->fetchAll(PDO::FETCH_ASSOC);
        $ret = ['message' => $data, 'success' => 1];
        //error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        error_log(print_r($ret, 1));
        exit;
    } else {
        $ret = ['message' => _("尚未有建立Domain，請先在DDNS平台上建立Domain"), 'success' => 0];
        error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);

        exit;
    }

    error_log("OK. return result to client");
} catch (PDOException $e) {
    error_log(print_r($e, 1));
    $ret = ['message' => _("系統錯誤"), 'success' => 0];
    error_log(print_r($ret, 1));
    header('Content-Type: application/json');
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}
