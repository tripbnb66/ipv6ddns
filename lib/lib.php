<?php
include_once __DIR__ . '/../settings.php';
/*
Between start -> ^
And end -> $
of the string there has to be at least one number -> (?=.*\d)
and at least one letter -> (?=.*[A-Za-z])
and it has to be a number, a letter or one of the following: !@#$% -> [0-9A-Za-z!@#$%]
and there have to be 6-20 characters -> {6,20}
 */
function check_password($password) {
    if (preg_match('/(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{6,20}/', $password)) {
        return true;
    } else {
        return false;
    }

}

function get_ip_info($ip) {
    global $ib;

    if ($ip == '127.0.0.1') {
        return false;
    }

    $sql = "select count(*) as n from ipdata where ip=:ip";
    $st = $ib->prepare($sql);
    $st->bindParam(':ip', $ip, PDO::PARAM_STR);
    $st->execute();
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    if ($rows[0]['n'] > 0) {
        $sql = "select * from ipdata where ip=:ip";
        $st = $ib->prepare($sql);
        $st->bindParam(':ip', $ip, PDO::PARAM_STR);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        $data = [
            'country_code' => $rows[0]['country_code'], // TW
            'currency' => $rows[0]['currency'], // TWD
            'latitude' => $rows[0]['latitude'], // 25.0478
            'longtiude' => $rows[0]['longtiude'], // 121.5318
            'currency_symbol' => $rows[0]['currency_symbol'], // NT$
            'in_eu' => $rows[0]['in_eu'], // 0, 1
            'region' => $rows[0]['region'], // Taipei City
            'regioncode' => $rows[0]['regioncode'], // TPE
            'city' => $rows[0]['city'], // Taipei
            'locale' => $rows[0]['locale'],
            'time_zone' => $rows[0]['time_zone'],
        ];
        return $data;

    } else {

        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        if ($ipdat->geoplugin_status != 200 || !isset($ipdat->geoplugin_currencyCode)) {
            return false;
        } else {
            $data = [
                'country_code' => $ipdat->geoplugin_countryCode, // TW
                'currency' => $ipdat->geoplugin_currencyCode, // TWD
                'latitude' => $ipdat->geoplugin_latitude, // 25.0478
                'longtiude' => $ipdat->geoplugin_longitude, // 121.5318
                'currency_symbol' => $ipdat->geoplugin_currencySymbol, // NT$
                'in_eu' => $ipdat->geoplugin_inEU, // 0, 1
                'region' => $ipdat->geoplugin_region, // Taipei City
                'regioncode' => $ipdat->geoplugin_regionCode, // TPE
                'city' => $ipdat->geoplugin_city, // Taipei
                'locale' => get_locale(),
                'time_zone' => $ipdat->geoplugin_timezone, // Asia/Taipei
            ];

            $sql = "insert into ipdata (ip,country_code,locale,currency,latitude,longtiude,currency_symbol,in_eu,region,regioncode,city, time_zone) values (:ip, :country_code, :locale, :currency,:latitude,:longtiude,:currency_symbol,:in_eu,:region,:regioncode,:city, :time_zone)";
            $st = $ib->prepare($sql);
            $st->bindParam(':ip', $ip, PDO::PARAM_STR);
            $st->bindParam(':country_code', $data['country_code'], PDO::PARAM_STR);
            $st->bindParam(':locale', $data['locale'], PDO::PARAM_STR);
            $st->bindParam(':currency', $data['currency'], PDO::PARAM_STR);
            $st->bindParam(':latitude', $data['latitude'], PDO::PARAM_STR);
            $st->bindParam(':longtiude', $data['longtiude'], PDO::PARAM_STR);
            $st->bindParam(':currency_symbol', $data['currency_symbol'], PDO::PARAM_STR);
            $st->bindParam(':in_eu', $data['in_eu'], PDO::PARAM_INT);
            $st->bindParam(':region', $data['region'], PDO::PARAM_STR);
            $st->bindParam(':regioncode', $data['regioncode'], PDO::PARAM_STR);
            $st->bindParam(':city', $data['city'], PDO::PARAM_STR);
            $st->bindParam(':time_zone', $data['time_zone'], PDO::PARAM_STR);
            $st->execute();

            return $data;
        }
    }
}

function random_key() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789*/-+[]{}&#';
    $pass = '';
    for ($x = 0; $x < 24; $x++) {
        $pass .= $chars[hexdec(bin2hex(random_bytes(1))) % strlen($chars)];
    }
    return $pass;
}

function create_dir_if_not_exist($tmp) {
    if (!file_exists($tmp)) {
        $ret = mkdir($tmp, 0755, true);
        if ($ret == false) {
            throw new Exception("Cannot create " . $tmp, 1);
        }
    }
}

// get current URL
function get_current_url_base() {
    $current_url = 'http';
    $server_https = $_SERVER["HTTPS"];
    $server_name = $_SERVER["SERVER_NAME"];
    $server_port = $_SERVER["SERVER_PORT"];
    $request_uri = $_SERVER["REQUEST_URI"];
    if ($server_https == "on") {
        $current_url .= "s";
    }

    $current_url .= "://";
    if ($server_port != "80") {
        $current_url .= $server_name . ":" . $server_port;
    } else {
        $current_url .= $server_name;
    }

    return $current_url;
}

// 密碼須符合高強度密碼規則(密碼長度 16 個字元以上，包括英文大小寫、數字跟特殊字元)
function is_strong_password($password) {
    if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{16,}$/', $password)) {
        return false;
    } else {
        return true;
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = '127.0.0.1';
    }

    return $ipaddress;
}

// 產生 api 連線驗證的用的 key
function generateAPIKey() {
    $key = bin2hex(openssl_random_pseudo_bytes(32));
    return $key;
}

/**
 * generate CSRF token
 *
 * @param   string $formName
 * @return  string
 */
function generateToken() {
    global $salt;
    if (!session_id()) {
        session_start();
    }
    if (isset($_SESSION['csrf_token']) && !empty($_SESSION['csrf_token'])) {
        $csrf_token = $_SESSION['csrf_token'];
    } else {
        $csrf_token = base64_encode(openssl_random_pseudo_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;
    }
    //$sessionId = session_id();
    //$token = sha1($sessionId . $salt);

    return $_SESSION['csrf_token'];
}

/**
 * check CSRF token
 *
 * @param   string $csrf_token
 * @return  boolean
 */
function checkToken($csrf_token) {
    if (isset($_SESSION['csrf_token']) && !empty($_SESSION['csrf_token']) && !empty($csrf_token)) {
        if (hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            return true;
        } else {
            unset($_SESSION['csrf_token']);
            return false;
        }
    } else {
        return false;
    }
    //return $token === generateToken();
}

// 移除utf-8 BOM
function removeBOM($str = '') {
    if (substr($str, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
        $str = substr($str, 3);
    }

    return $str;
}
