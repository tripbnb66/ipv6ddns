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

$sidebar_menu = 'menu1';
$sidebar_item = 'menu1a';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("user_add.html",
    [
        'available_locale' => $available_locale,
        'locale' => isset($_SESSION['locale']) ? $_SESSION['locale'] : 'zh_tw',
        'title' => $title,
        'csrf_token' => generateToken(),
        'recaptcha_site_key' => $recaptcha_site_key,
        't' => $VERSION,
        'message' => $message,
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'is_admin' => $_SESSION['is_admin'],
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
