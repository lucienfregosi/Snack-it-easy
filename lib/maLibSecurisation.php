<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "maLibSQL.php";	// Car on utilise la fonction valider()

/**
 * @file login.php
 * Fichier contenant des fonctions de v�rification de logins
 */

/**
 * Cette fonction v�rifie si le login/passe pass�s en param�tre sont l�gaux
 * Elle stocke le pseudo de la personne dans des variables de session : session_start doit avoir �t� appel�...
 * Elle enregistre aussi une information permettant de savoir si l'utilisateur qui se connecte est administrateur ou non
 * Elle enregistre l'�tat de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas �tre vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la cr�ation de variables de session
 */
function verifUser($login,$password)
{
	$sql="Select * from user where login='".$login."' and password='".$password."'";
	return SQLSelect($sql);
/*
	if($login=='' || $password=='') //incoh�rence car cha�ne(s) vide(s)
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
 * Fonction � placer au d�but de chaque page priv�e
 * Cette fonction redirige vers la page $urlBad en envoyant un message d'erreur 
	et arr�te l'interpr�tation si l'utilisateur n'est pas connect�
 * Elle ne fait rien si l'utilisateur est connect�, et si $urlGood est faux
 * Elle redirige vers urlGood sinon
 */
function securiser($urlBad,$urlGood=false)
{
		
		if(isset($_SESSION["connecte"]) && $_SESSION["connecte"]==false)
		{
			echo "<div style='font-weight:bold; color: red;'>Erreur ! Vous n'�tes plus connect�".$_SESSION['connecte']."et allez �tre redirig�: patientez...<br />";
			header("refresh:3;URL=".$urlBad.""); //3 secondes d'attente pour lecture message d'erreur puis redirection
			die("Non connect�"); //pour arr�ter l'ex�cution et envoyer les donn�es au client
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