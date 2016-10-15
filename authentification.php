<?php
session_start();
//error_reporting();
//error_reporting(0);
include_once"lib/maLibUtils.php";
include_once"lib/maLibSQL.php";
include_once"lib/maLibSecurisation.php";
include_once"lib/maLibForms.php";
include 'header.php';
if (($action=valider("envoyer"))!= false) // l'utilisateur appuie sur le bouton
{
	if((($log=valider("lelog","POST"))!= false) && (($pass=valider("lepass","POST"))!= false)) // verifie que les 2 champs sont remplis
	{

		$reponse=verifUser($log,$pass); // compare avec la bdd
		if($reponse!=false) // mdp et login ok
		{
			$_SESSION["USER_LOGIN"] = $log;
			$rs=parcoursRs($reponse); // 
			foreach($rs as $dataContact)
			{
				$user_id = $dataContact["userid"]; //initialise la variable sonId
				$_SESSION["USER_ID"] = $user_id;


				$user_type = $dataContact["type"];
				$_SESSION["USER_TYPE"] = $user_type;
			}
			echo $user_type;
			$_SESSION["CONNECTE"] = true;
			if($user_type == 'supporter'){
				header('Location: client.php');  
			}
			elseif($user_type == 'serveur'){	
				header('Location: serveur.php'); 			
			}
			elseif($user_type == 'admin'){
				header('Location: admin.php'); 
			}
			$_SESSION["CONNECTE"] = false;
		}
	$_SESSION["Message"] = "id / pass invalide";
	header('URL= authentification.php'); 	
		
	}

	
}


//<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
AUTHENTIFICATION
</title>

<style type="text/css">
</style>
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

<form action="" method="POST">
<?php

if(isset($_SESSION['Message'])){
	echo $_SESSION["Message"];
	$_SESSION['Message']=null;
}
?>
<!-- Ensemble de champs: Données personnelles -->
<div id="LOGIN" class="figure" >
Login: <input id="txtLogin" name="lelog" type="text" value="" autofocus/> <br/>
</div>
<div id="PASSWORD" class="figure">
Mot de passe: <input id="txtPasse" name="lepass" type="password" value="" /> <br/>
</div>


<!-- Envoi des donnés -->
<div id="button" class="figure">
<input type="submit" name="envoyer" />
</div>


</form>
<a href="motDePasseOublie.php">J’ai oublié mon mot de passe</a> <!-- redirige vers la page mdpOublié -->
</div>
</form>
</form>
<a href="creerCompte.php">Créer un compte pour accéder à l'application</a> <!-- redirige vers la page creaCompte -->
</div>
</form>
</body>
<!-- **** F I N **** B O D Y **** -->

</html>
<?php
include 'footer.php';
?>