<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$sql = "select count(*) as n from dns_records";
$st = $db->prepare($sql);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $total_num = $row['n'];
}

$begin = $phppage->get_begin();
$phppage->set_total($total_num);

$sql = "select * from dns_records order by created_at desc limit :offset,:rowcount";
$st = $db->prepare($sql);
$st->bindParam(':offset', $begin, PDO::PARAM_INT);
$st->bindParam(':rowcount', $NUMBER_PER_PAGE, PDO::PARAM_INT);
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
        'p' => $p,
    ]
);
