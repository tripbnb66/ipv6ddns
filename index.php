<?php
include_once __DIR__ . '/settings.php';

header("Location: /admin/index.php");
exit;

echo $twig->render("index.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'message' => $message,
        'is_admin' => $phpacl->is_admin() ? '1' : '0',
    ]
);
?>