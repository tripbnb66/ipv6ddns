<?php
include_once dirname(__FILE__) . '/../settings.php';
//use \PDO;
//use \PDOException;
//use Exception;

class PHPACL {
    function __construct() {
    }

    function is_admin() {
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            return true;
        } else {
            return false;
        }
    }

}