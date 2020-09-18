<?php
include_once __DIR__ . '/../settings.php';

//print_r($_SESSION);
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("no_permission.html",
    [
        'available_locale' => $available_locale,
        'locale' => isset($_SESSION['locale']) ? $_SESSION['locale'] : 'zh_tw',
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'show_nav' => 0,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
    ]
);