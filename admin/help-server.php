<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$sidebar_menu = 'menu4';
$sidebar_item = 'menu4b';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("help-server.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
    ]
);
