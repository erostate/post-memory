<?php
include_once '../../inc/functions.inc.php';

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$user_mail = $_POST['user_mail'];
$password = $_POST['password'];
$password_conf = $_POST['password_conf'];
$strong_password = $_POST['strong_password'];
$company_name = $_POST['company_name'];
$company_code_phone = $_POST['company_code_phone'];
$company_phone = $_POST['company_phone'];
$company_mail = $_POST['company_mail'];
$company_zip = $_POST['company_zip'];
$company_city = $_POST['company_city'];
$company_address = $_POST['company_address'];
$consent = $_POST['consent'];

if ($password != $password_conf) {
    header('Location: ../register?error=password');
    exit();
}

$hashPassw = password_hash($password, PASSWORD_DEFAULT);

$checkMail = checkMail($user_mail);

if (!$checkMail) {
    $company = createCompany($company_name, $company_address, $company_city, $company_zip, $company_code_phone . $company_phone, $company_mail);
    $account = createAccount($first_name, $last_name, $user_mail, $hashPassw, 'user', $company);
}

header('Location: ../login?success=register');
exit();