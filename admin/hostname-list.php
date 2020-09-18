<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$sql = "select h.id,d.domain,h.hostname,i.name,i.data_type,i.priority,i.is_local from domain as d join hostname as h on h.domain_id=d.id join ip as i on i.hostname_id=h.id";
$st = $db->prepare($sql);
$st->execute();
$items = [];
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $items[] = $row;
}

$sidebar_menu = 'menu2';
$sidebar_item = 'menu2b';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("hostname-list.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'items' => $items,
        'pagnation' => $phppage->page_number(),
    ]
);
