<?php
include_once __DIR__ . '/../settings.php';

if (!$phpacl->is_admin()) {
    header("Location: no_permission.php");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_REGEXP, $validate_id);
$pw1 = filter_input(INPUT_POST, 'pw1');
$pw2 = filter_input(INPUT_POST, 'pw2');
$csrf_token = filter_input(INPUT_POST, 'csrf_token');

if (!checkToken($csrf_token)) {
    $_SESSION['message'] = _("Authorization is expired. Please reload this page");
    header("Location: user_update.php");
    exit;
}

if (empty($id)) {
    $_SESSION['message'] = _("編號不可為空");
    header("Location: user_list.php");
    exit;
}
if (!empty($pw1) && !empty($pw2) && $pw1 !== $pw2) {
    $_SESSION['message'] = _("兩次密碼輸入不正確");
    header("Location: user_update.php?id=" . $id);
    exit;
}

try {
    $db->beginTransaction();

    if (empty($pw1) && empty($pw2)) {
        // 不更新密碼
        $_SESSION['message'] = _('密碼不可以為空');
        header("Location: user_update.php?id=" . $id);
        exit;

    } else if ($pw1 == $pw2) {

        /*if(!is_strong_password($pw1)) {
        $_SESSION['message'] = _("密碼須符合高強度密碼規則(密碼長度 16 個字元以上，包括英文大小寫、數字跟特殊字元)");
        header("Location: /member_update.php");
        exit;
        }*/

        // 更新密碼
        $encpw = password_hash($pw1, PASSWORD_BCRYPT);
        $sql = "update users set pw=:pw where id=:id";
        $st = $db->prepare($sql);
        $st->bindParam(':pw', $encpw, PDO::PARAM_STR);
        $st->bindParam(':id', $id, PDO::PARAM_STR);
        $st->execute();

    }
    //$subject = "update name={$name},role={$role},id={$id}";
    //$body = "update name={$name},role={$role},id={$id}";
    //$phpmail->send($admin_email,$MAIL_TO_MANAGER,$subject,$body);
    $phplog->db("user", "update users firstname={$firstname},lastname={$lastname},mobile={$mobile},country_code={$country_code},role_id={$role_id},id={$id}");
    $_SESSION['message'] = _("更新成功");
    $db->commit();
    header("Location: user_update.php?id=" . $id);
    exit;
} catch (PDOException $e) {
    $db->rollBack();
    log_exception(__FILE__, $e);
    error_log($e->getMessage());
    $_SESSION['message'] = $e->getMessage();
    header("Location: user_update.php?id=" . $id);
    exit;
}
