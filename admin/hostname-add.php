<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
if (empty($type)) {
    $type = 'A';
}
// // 'MX','CNAME','NS','SOA','A','PTR','AAAA','TXT'
if (!in_array($type, ['A', 'AAAA', 'CNAME', 'PTR', 'TXT', 'MX', 'CNAME', 'SOA', 'NS'])) {
    die("不支援的類型");
}

$sql = "select * from users where id=:id";
$st = $db->prepare($sql);
$st->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
$st->execute();
$items = [];
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $items[] = $row;
}

$sidebar_menu = 'menu2';
$sidebar_item = 'menu2a';
$message = $_SESSION['message'];
unset($_SESSION['message']);

/*$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
@socket_connect($sock, "8.8.8.8", 53);
@socket_getsockname($sock, $name); // $name passed by reference
$ipv4 = $name;

$sock = socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
@socket_connect($sock, "2001:4860:4860::8888", 53);
@socket_getsockname($sock, $name); // $name passed by reference
$ipv6 = $name;*/

echo $twig->render("hostname-add.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'role' => $_SESSION['role'],
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'is_admin' => isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0,
        'item' => $items[0],
        'message' => $message,
        'type' => $type,
        'ipv4' => $ipv4,
        'ipv6' => $ipv6,
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
