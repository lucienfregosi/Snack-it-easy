<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<title>welcome <?php /* if($user->is_loggedin() != ""){echo($userRow['user_email']);}else{echo "to HouseAlert!";} */ ?> </title>-->
        <title>EasySnack, le coupe-file pour une experience la plus totale!</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Homepage Flyzheelz inc">
        <meta name="author" content="HouseAlert Team">
        <link rel="icon" href="favicon.ico">
        <link href="ressource/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="ressource/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
        <link href="theme.css" rel="stylesheet">
        <script src="ressource/bootstrap/js/bootstrap.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    </head>
    <body role="document">

        <?php include 'menus.php'; ?>
