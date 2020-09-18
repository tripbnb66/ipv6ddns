<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: login.php");
    exit;
}

$sidebar_menu = 'menu1';
$sidebar_item = 'menu1a';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = null;
}

//print_r($room_cat);exit;
echo $twig->render('index.html', [
    'title' => $title,
    'csrf_token' => generateToken(),
    't' => $VERSION,
    'message' => $message,
    'menu' => $sidebar_menu,
    'menu_item' => $sidebar_item,
    'is_admin' => isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0,
]
);
