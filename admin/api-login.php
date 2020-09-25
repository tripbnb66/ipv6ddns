<?php
include_once __DIR__ . '/../settings.php';

$apikey = filter_input(INPUT_POST, 'apikey', FILTER_SANITIZE_STRING);

if (empty($apikey)) {
    $ret = ['message' => _("無效的API KEY") . " apikey=" . $apikey, 'success' => 0];
    error_log(print_r($ret, 1));
    return json_encode($ret);
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
        $sql = "select * from dns_records where (type='A' or type='AAAA' ) order by `type` desc";
        $st = $db->prepare($sql);
        $st->bindParam(':apikey', $apikey, PDO::PARAM_STR);
        $st->execute();
        $data = $st->fetchAll(PDO::FETCH_ASSOC);
        $ret = ['message' => $data, 'success' => 1];
        //error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);
        //error_log(print_r($ret, 1));
        exit;
    } else {
        $ret = ['message' => _("無效的 API KEY"), 'success' => 0];
        error_log(print_r($ret, 1));
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE);

        exit;
    }

    error_log("OK. return result to client");
} catch (PDOException $e) {
    $ret = ['message' => _("系統錯誤"), 'success' => 0];
    error_log(print_r($ret, 1));
    header('Content-Type: application/json');
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    exit;
}
