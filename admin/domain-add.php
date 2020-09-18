<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$sql = "select * from users where id=:id";
$st = $db->prepare($sql);
$st->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
$st->execute();
$items = [];
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $items[] = $row;
}

$sidebar_menu = 'menu1';
$sidebar_item = 'menu1a';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("domain-add.html",
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
    ]
);
