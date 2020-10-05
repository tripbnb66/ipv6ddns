<?php
include_once __DIR__ . '/../settings.php';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = null;
}

echo $twig->render("message.html",
    [
        'available_locale' => $available_locale,
        'available_currency' => $available_currency,
        'currency' => isset($_SESSION['currency']) ? $_SESSION['currency'] : 'USD',
        'locale' => isset($_SESSION['locale']) ? $_SESSION['locale'] : 'en',
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'is_user' => $phpacl->is_user() ? '1' : '0',
        'message' => $message,
        'avatar' => empty($_SESSION['avatar']) ? '/image/avator-default.png' : $_SESSION['avatar'],
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);
?>