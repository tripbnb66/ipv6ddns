<?php
//use \PDO;
//use \PDOException;

include_once __DIR__.'/../settings.php';

class PHPLog {
	function __construct() {
	}

	// 檢查最近30分鐘內的 IP 失敗次數
	function check_ipfail_try($ip) {
		global $logdb;

		$sql = "select count(*) as n from ipfail where ip=:ip and created_at > date_sub(now(), interval 30 minute)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':ip', $ip, PDO::PARAM_STR);
		$st->execute();
		$rows = $st->fetchAll(PDO::FETCH_ASSOC);
		return $rows[0]['n'];
	}

	// 檢查最近30分鐘內的失敗次數
	function check_loginfail_try($email) {
		global $logdb;

		$sql = "select count(*) as n from loginfail where email=:email and created_at > date_sub(now(), interval 30 minute)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':email', $email, PDO::PARAM_STR);
		$st->execute();
		$rows = $st->fetchAll(PDO::FETCH_ASSOC);
		return $rows[0]['n'];
	}

	function login() {
		global $salt;
		global $logdb;

		$sql = "insert into login (email,ip,checkval) values (:email,:ip,:checkval)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
		$st->bindParam(':ip', $_SESSION['ip'], PDO::PARAM_STR);
		$st->bindParam(':checkval', sha1($salt.$_SESSION['id'].$_SESSION['ip']), PDO::PARAM_STR);
		$st->execute();
	}

	function loginfail($email) {
		global $salt;
		global $logdb;

		$sql = "insert into loginfail (email,ip,checkval) values (:email,:ip,:checkval)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':email', $email, PDO::PARAM_STR);
		$st->bindParam(':ip', $_SESSION['ip'], PDO::PARAM_STR);
		$st->bindParam(':checkval', sha1($salt.$id.$_SESSION['ip']), PDO::PARAM_STR);
		$st->execute();
	}

	function email($sender,$receiver,$title,$content) {
		global $salt;
		global $logdb;

		$sql = "insert into email (ip,sender,receiver,title,content,checkval) values (:ip,:sender,:receiver,:title,:content,:checkval)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':ip', $_SESSION['ip'], PDO::PARAM_STR);
		$st->bindParam(':sender', $sender, PDO::PARAM_STR);
		$st->bindParam(':receiver', $receiver, PDO::PARAM_STR);
		$st->bindParam(':title', $title, PDO::PARAM_STR);
		$st->bindParam(':content', $content, PDO::PARAM_STR);
		$st->bindParam(':checkval', sha1($salt.$_SESSION['id'].$_SESSION['ip']), PDO::PARAM_STR);
		$st->execute();
	}

	function db($title,$content) {
		global $salt;
		global $logdb;

		$sql = "insert into db (ip,title,content,checkval) values (:ip,:title,:content,:checkval)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':ip', $_SESSION['ip'], PDO::PARAM_STR);
		$st->bindParam(':title', $title, PDO::PARAM_STR);
		$st->bindParam(':content', $content, PDO::PARAM_STR);
		$checkval = sha1($salt.$_SESSION['id'].$_SESSION['ip']);
		$st->bindParam(':checkval', $checkval, PDO::PARAM_STR);
		$st->execute();
	}

	function other($title,$content) {
		global $salt;
		global $logdb;

		$sql = "insert into other (ip,title,content,checkval) values (:ip,:title,:content,:checkval)";
		$st = $logdb->prepare($sql);
		$st->bindParam(':ip', $_SESSION['ip'], PDO::PARAM_STR);
		$st->bindParam(':title', $title, PDO::PARAM_STR);
		$st->bindParam(':content', $content, PDO::PARAM_STR);
		$checkval = sha1($salt.$_SESSION['id'].$_SESSION['ip']);
		$st->bindParam(':checkval', $checkval, PDO::PARAM_STR);
		$st->execute();
	}

	function error_log($message) {
		error_log($message);
	}
}