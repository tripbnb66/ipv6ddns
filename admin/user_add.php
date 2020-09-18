<?php
die("function disabled");
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_user()) {
    header("Location: login.php");
    exit;
}
if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}
if (!$phpacl->check_permission()) {
    header("Location: no_permission.php");
    exit;
}

$sql = "select * from countries order by callingcode asc";
$st = $db->prepare($sql);
$st->execute();
$countries = [];
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $countries[$row['alpha2']] = $row;
}

$sql = "select * from role";
$st = $db->query($sql);
$role = $st->fetchAll(PDO::FETCH_ASSOC);

$sidebar_menu = 'menu5';
$sidebar_item = 'menu5a';
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
        'role' => $role,
        'is_admin' => $_SESSION['is_admin'],
        'is_user' => $phpacl->is_user() ? '1' : '0',
        'countries' => $countries,
    ]
);
