<?php

	
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	include_once"../lib/maLibUtils.php";
	include_once"../lib/maLibSQL.php";
	include_once"../lib/maLibSecurisation.php";
	include_once"../lib/maLibForms.php";
	include_once"../lib/lib_lucien.php";
	include_once"../config.php";


	$commandeid = $_POST['commandeid'];


	$sql="Update commande set statut = 'paye_prepare' where commandeid = ".$commandeid."";
	SQLUpdate($sql);

?>