<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$sql = "select * from apikey";
$st = $db->prepare($sql);
$st->execute();
$items = [];
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $items[] = $row;
}

$sidebar_menu = 'menu3';
$sidebar_item = 'menu3b';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("apikey-list.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'items' => $items,
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
