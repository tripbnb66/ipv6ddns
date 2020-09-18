<?php

//error_reporting(E_ERROR);

//ini_set('display_errors',1);
ini_set("memory_limit", "2048M");
ini_set("session.cookie_httponly", 1);
ini_set("session.cookie_samesite", "Strict");
ini_set("expose_php", 0);
ini_set("session.use_strict_mode", 1);
ini_set("apc.use_request_time", 0); # 解決apc不會清除的問題
ini_set("opcache.enable_file_override", 1); // 可以透過opcache大幅提升composer autoload的速度
// https://stackoverflow.com/questions/47687475/why-php-compose-autoload-s-performance-so-lowly
ini_set("log_errors_max_len", 0); // 不要限制 error_log() 長度
ini_set('expose_php', 0); // 隱藏php資訊
session_start();

include_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

static $NUMBER_PER_PAGE = 50; // 每頁幾筆
static $NUMBER_PAGINATION = 10; // 分頁顯示
static $NUMBER_PAGINATION_OFFSET = 3; // 頁碼間距

$db_host = "localhost";
$db_name = "ipv6ddns";
$db_user = "root";
$db_pw = '';
$debug_receiver = ['david9y9@gmail.com'];
$smtp_username = '';
$smtp_pasword = '';
$smtp_server = 'localhost';
$smtp_port = 2500;
$TWIG_DEBUG = true; // template是否要cache或者不要, true:不需要cache, false: 需要cache
$VERSION = time();
$debug_email = true; // 發email到測試信箱或者不要, true :email通通寄給測試帳號, false: email寄給真的收件人

$admin_email = 'david9y9@gmail.com';
$MAIL_TO_MANAGER = ['david9y9@gmail.com'];

$salt = "kjfhdo3u3943jf";
$title = "ipv6ddns";
$from_name = "ipv6ddns";

try {
    $db = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pw);
    $db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET time_zone = '{$sys_timezone}'");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $logdb = $db;

} catch (PDOException $e) {
    error_log($e->getMessage());

    $_SESSION['messages'] = _("Oops! There are something wrong. Please try later.");
    if (!empty($_SESSION['messages']) && $_GET['messages'] == "") {
        header("Location: /message.php?messages=" . $_SESSION['messages']);
    }

}

try {
} catch (PDOException $e) {
    error_log($e->getMessage());

    $_SESSION['messages'] = _("Oops! There are something wrong. Please try later.");
    if (!empty($_SESSION['messages']) && $_GET['messages'] == "") {
        header("Location: /message.php?messages=" . $_SESSION['messages']);
    }

}

include_once __DIR__ . '/lib/lib.php'; // 一些常用的function
include_once __DIR__ . '/lib/phplog.php'; // log處理
include_once __DIR__ . '/lib/phpacl.php'; // 控制權限
include_once __DIR__ . '/lib/phppage.php'; // 分頁功能

$phppage = new PHPPage(); // 分頁
$phplog = new PHPLog(); // log處理 (放在這邊是因為可以供下面class使用)
$phpacl = new PHPACL(); // 控制權限

//
$include_by_files = get_included_files();
$template_dir = dirname($include_by_files[0]);
$loader = new \Twig\Loader\FilesystemLoader($template_dir . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => $template_dir . '/cache',
    'auto_reload' => true,
    'debug' => false,
]);

$domain = 'messages';
$language = $available_locale[$_SESSION['locale']]['locale'] . '.UTF-8';

use PhpMyAdmin\Twig\Extensions\I18nExtension;
$twig->addExtension(new I18nExtension());
putenv('LC_ALL=' . $language);
setlocale(LC_ALL, $language);
// Specify the location of the translation tables
bindtextdomain($domain, 'locale');
bind_textdomain_codeset($domain, 'UTF-8');
// Choose domain
textdomain($domain);

if ($phpacl->is_admin()) {
    if (!isset($_SESSION['timestamp'])) {
        // 第一次登入
        $_SESSION['timestamp'] = time();
    } else if (time() - $_SESSION['timestamp'] > 30 * 60) {
        // 30分鐘沒動作,就自動 logout
        header("Location: logout.php");
        exit;
    } else {
        $_SESSION['timestamp'] = time();
    }
}
