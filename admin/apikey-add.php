<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$key = generateAPIKey();

$sidebar_menu = 'menu3';
$sidebar_item = 'menu3a';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("apikey-add.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'role' => $_SESSION['role'],
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'is_admin' => isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0,
        'key' => $key,
        'message' => $message,
    ]
);
