<?php
	session_start();
	if(isset($_SESSION["CONNECTE"]) and $_SESSION["CONNECTE"]=true) {
		session_unset(); //Destruction des variables de session
		$_SESSION["Message"]="Un oubli ? Venez commander dès maintenant !";
		echo "Merci pour votre visite, à bientôt !";
	} else {
		echo "L'accès à cette page vous est interdit, vous allez être redirigé.";
	}
	header("refresh:2;URL=authentification.php");
?>