<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "maLibSQL.php";	// Car on utilise la fonction valider()

/**
 * @file login.php
 * Fichier contenant des fonctions de vérification de logins
 */

/**
 * Cette fonction vérifie si le login/passe passés en paramètre sont légaux
 * Elle stocke le pseudo de la personne dans des variables de session : session_start doit avoir été appelé...
 * Elle enregistre aussi une information permettant de savoir si l'utilisateur qui se connecte est administrateur ou non
 * Elle enregistre l'état de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas être vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la création de variables de session
 */
function verifUser($login,$password)
{
	$sql="Select * from user where login='".$login."' and password='".$password."'";
	return SQLSelect($sql);
/*
	if($login=='' || $password=='') //incohérence car chaîne(s) vide(s)
	{
		return false;
	}
	else
	{
		$sql="select * from users where pseudo='".$login."' and passe='".$password."'";
		$rs=parcoursRs(SQLSelect($sql));
		if($rs)
		{
		foreach($rs as $dataContacte)
		{
			$sonId=$dataContacte["Id_User"];
			$log=proteger($dataContacte["pseudo"]);
			$passe=proteger($dataContacte["passe"]);	
			$nom=proteger($dataContacte["Prenom"])+proteger($dataContacte["Nom"]);	
		}
		
		$_SESSION["LOGIN"]=$log;
		$_SESSION["PASSE"]=$passe;
		$_SESSION["ID"]=$sonId;
		$_SESSION["NomU"]=$nom;
		
		return $rs;
		}
		else 
		return false;
	}
	*/
}

/**
 * Fonction à placer au début de chaque page privée
 * Cette fonction redirige vers la page $urlBad en envoyant un message d'erreur 
	et arrête l'interprétation si l'utilisateur n'est pas connecté
 * Elle ne fait rien si l'utilisateur est connecté, et si $urlGood est faux
 * Elle redirige vers urlGood sinon
 */
function securiser($urlBad,$urlGood=false)
{
		
		if(isset($_SESSION["connecte"]) && $_SESSION["connecte"]==false)
		{
			echo "<div style='font-weight:bold; color: red;'>Erreur ! Vous n'êtes plus connecté".$_SESSION['connecte']."et allez être redirigé: patientez...<br />";
			header("refresh:3;URL=".$urlBad.""); //3 secondes d'attente pour lecture message d'erreur puis redirection
			die("Non connecté"); //pour arrêter l'exécution et envoyer les données au client
		}
		else if(isset($_SESSION['connecte']) && $_SESSION["connecte"]==true && $urlGood==false)
		{
			//Ne rien faire.
		}
		else
		{
		//	header("Location:".$urlBad.""); //redirection directe
		}
}

?>