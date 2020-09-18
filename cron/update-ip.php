<?php
include_once __DIR__ . '/../settings.php';

$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_connect($sock, "8.8.8.8", 53);
socket_getsockname($sock, $name); // $name passed by reference
$ipv4 = $name;

$sock = socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
socket_connect($sock, "8.8.8.8", 53);
socket_getsockname($sock, $name); // $name passed by reference
$ipv6 = $name;

if (!empty($ipv4)) {
    $sql = "update ip set name=:name where data_type='ipv4' and is_local=1";
    $st = $db->prepare($sql);
    $st->bindParam(':name', $ipv4, PDO::PARAM_STR);
    $st->execute();
}
if (!empty($ipv6)) {
    $sql = "update ip set name=:name where data_type='ipv6' and is_local=1";
    $st = $db->prepare($sql);
    $st->bindParam(':name', $ipv6, PDO::PARAM_STR);
    $st->execute();
}