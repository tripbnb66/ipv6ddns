<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$sql = "select i.id,d.domain,h.hostname,i.name,i.data_type,i.priority,i.is_local from domain as d join hostname as h on h.domain_id=d.id join ip as i on i.hostname_id=h.id where h.id=:id";
$st = $db->prepare($sql);
$st->bindParam(':id', $id, PDO::PARAM_INT);
$st->execute();
while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $ip = ['data_type' => $row['data_type'], 'domain' => $row['domain'], 'hostname' => $row['hostname'], 'hostname_id' => $row['id'], 'name' => $row['name'], 'priority' => $row['priority'], 'is_local' => $row['is_local']];
}

//print_r($ip);
$sidebar_menu = 'menu1';
$sidebar_item = 'menu1f';
$message = $_SESSION['message'];
unset($_SESSION['message']);

echo $twig->render("hostname-update.html",
    [
        'title' => $title,
        'csrf_token' => generateToken(),
        't' => $VERSION,
        'show_nav' => 1,
        'menu' => $sidebar_menu,
        'menu_item' => $sidebar_item,
        'message' => $message,
        'is_admin' => $_SESSION['is_admin'],
        'ip' => $ip,
        'id' => $id,
        'is_local' => $ip['is_local'],
    ]
);
