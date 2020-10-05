<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$sql = "select * from dns_records where id=:id";
$st = $db->prepare($sql);
$st->bindParam(':id', $id, PDO::PARAM_INT);
$st->execute();
$rows = $st->fetchAll(PDO::FETCH_ASSOC);

//print_r($ip);
$sidebar_menu = 'menu1';
$sidebar_item = 'menu1f';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("hostname-update.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'show_nav' => 1,
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'rows' => $rows,
        'id' => $id,
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
