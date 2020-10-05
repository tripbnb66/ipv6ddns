<?php
include_once __DIR__ . '/../settings.php';

if ($phpacl->is_admin()) {
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = null;
}

echo $twig->render(
    'login.html', [
        'available_locale' => $available_locale,
        'locale' => isset($_SESSION['locale']) ? $_SESSION['locale'] : 'zh_tw',
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'message' => $message,
        'is_admin' => $phpacl->is_admin() ? '1' : '0',
        'is_login' => isset($_SESSION['id']) ? '1' : '0',
    ]
);

?>