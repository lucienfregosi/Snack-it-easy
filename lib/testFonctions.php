<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once './dbconfig.php';
//$user = new USER($DB_con);
//$var = $user->getProduits();
//foreach ($var as $value) {
//    echo $value;
//    echo $value;
//}
$user->printArray($user->getProduits());
$user->printArray($user->getUsers());
?>