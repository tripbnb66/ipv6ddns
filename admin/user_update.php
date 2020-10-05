<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_login()) {
    header("Location: login.php");
    exit;
}

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$sql = "select * from users where id=:id and is_deleted=0";
$st = $db->prepare($sql);
$st->bindParam(':id', $id, PDO::PARAM_INT);
$st->execute();
$items = $st->fetchAll(PDO::FETCH_ASSOC);

$sidebar_menu = 'menu1';
$sidebar_item = 'menu1b';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("user_update.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'show_nav' => 1,
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'items' => $items,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
