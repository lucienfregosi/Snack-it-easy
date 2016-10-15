<?php
require_once '../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//$DB_host = "localhost";
//$DB_user = "root";
//$DB_pass = "";
//$DB_name = "housealert";
//$DB_port = "3306";
//$db_name  = 'familyalertsql1';
//$hostname = 'localhost';
//$username = 'familyalertsql1';
//$password = 'AHPXu7BL';

$db_name = $BDD_base;
$hostname = $BDD_host;
$username = $BDD_user;
$password = $BDD_password;


try {
    //$DB_con = new PDO("mysql: host={$DB_host};dbname={$DB_name};",$DB_user,$DB_pass);
    $DB_con = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
    //$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}


include_once 'class.user.php';
$user = new USER($DB_con);
