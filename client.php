<?php

session_start();
error_reporting(E_ERROR | E_PARSE);
include_once"lib/maLibUtils.php";
include_once"lib/maLibSQL.php";
include_once"lib/maLibSecurisation.php";
include_once"lib/maLibForms.php";
include './lib/header.php';
?>

<head>
	<script type="text/javascript">
		function gererCheckbox(valeur)
		{
			elementQuantite = document.getElementById("produit_liste_" + valeur);
			if (elementQuantite.style.visibility == 'visible')
			{
				elementQuantite.style.visibility = 'hidden';
				elementQuantite.value = 0;
			}
			else {
				elementQuantite.style.visibility = 'visible';
				elementQuantite.value = 1;
			}
			autoriserCommande();
		}
		function autoriserCommande()
		{
			var estAutorise = false;
			var count = 1;
			var continuer = true;
			var idProduit = "";

			// tant qu'on trouve encore des produits dans la liste proposee
			while(continuer) {
				idProduit = "produit_liste_" + count;
				element = document.getElementById(idProduit);
				if (element != null) {
					if (parseInt(element.value) > 0) {
						estAutorise = true;
						// On a trouvé un exemple marchant donc ok on coupe court
						continuer = false;
					}
				} else
					continuer = false;
				count++;
			}

			elementCommande = document.getElementById('boutonCommande');
			if (estAutorise) {
				elementCommande.value = "Confirmer ma commande";
				elementCommande.disabled = false;
			} else {
				elementCommande.value = "En attente de produits";
				elementCommande.disabled = true;
			}
		}
	</script>
</head>

<body>
<div style="heigth:700px; width:320px; margin:auto; border: solid 5px black; text-align:center; margin-top: 10%;">	
<?php	
if (($action=valider("envoyer"))== false) // l'utilisateur appuie sur le bouton
{	
?>

<form action="" method="post">
    <select name="Stade">
	<?php
	$listeStade="Select * From Stade";
	$arrayExpression=parcoursRs(SQLSelect($listeStade));
	foreach ($arrayExpression as $ComboStade){
		echo '<option>'.$ComboStade["nom"].'</option>';
	}
	?>
    </select>
	<div id="LOGIN" class="figure" >
		Porte: <input id="txtPorte" name="Porte" type="text" value=""/> <br/>
	</div>
	
	<div id="button" class="figure">
		<input type="submit" name="envoyer" />
	</div>
</form>

<?php
}else{
//Produits

	$_SESSION["STADE"]=trim(valider("Stade","POST"));

	//On cherche l'id du stade
	$stadeIdSql = "SELECT stadeid FROM stade where stade.nom LIKE '".$_SESSION["STADE"]."' LIMIT 1";
	$res = parcoursRs(SQLSelect($stadeIdSql));
	foreach ($res as $leStade) {
		$_SESSION["STADE_ID"] = $leStade['stadeid'];
	}

	$PorteVal=valider("Porte","POST");

	$listeBuvetteSQL="Select nombuvette, buvetteid From buvette, porte, stade where porte.buvette = buvetteid AND portelib = 'Porte ".$PorteVal ."' AND stade.stadeid = buvette.stade AND stade.nom LIKE '".$_SESSION["STADE"]."'";
	$listeBuvette=parcoursRs(SQLSelect($listeBuvetteSQL));
	$nomBuvette=null;
	foreach ($listeBuvette as $Buvette){
		$nomBuvette=$Buvette['nombuvette'];
		$_SESSION["BUVETTE_ID"]=$Buvette['buvetteid'];
	}
	if($nomBuvette == null){
		$listeBuvetteSQL="Select * From buvette, stade where stade.stadeid = buvette.stade AND stade.nom LIKE '".$_SESSION["STADE"]."' limit 1 " ;
		$listeBuvette=parcoursRs(SQLSelect($listeBuvetteSQL));
		foreach ($listeBuvette as $Buvette){
			$nomBuvette=$Buvette['nombuvette'];
			$_SESSION["BUVETTE_ID"]=$Buvette['buvetteid'];
		}	
	}
	

	echo '<p> Buvette la plus proche de la Porte '.strtoupper($PorteVal)."   ". $nomBuvette .'</p>';

?>

<!-- a voir si on garde -->
<!-- <form action="" method="post">
	<div id="LOGIN" class="figure" >
		Changer de porte: <input id="txtPorte" name="Porte" type="text" value="<?php echo $PorteVal ?>" style="width:30px; text-align:center;" autofocus />
		<input type="hidden" name="Stade" value="<?php echo trim($_SESSION['STADE']) ?>" />
		<input type="submit" name="envoyer" />
	</div>
</form>	-->


<form action='./paiement.php' method='post'>
<?php
	echo '<ul>';

	$listeProduitSQL="Select * From Stade, produitStade, Produit where Stade.nom LIKE '".$_SESSION["STADE"]."' AND Produit.produitid = produitStade.produit ";  //produitnom & urlimg & prix & alcool

	$listeProduit=parcoursRs(SQLSelect($listeProduitSQL));
	$count = 0;
	foreach ($listeProduit as $Produit) {
		$count++;
		echo "<li>";
		echo " <div id='produit'><img src=\"".$Produit['urlimg']."\" />  ".$Produit['produitnom']."  :  ".$Produit['prix']." euro   ";
		if ($Produit['alcool'] == true)
			echo "Moderation sur ce produit";
		echo "<input type='checkbox' onclick=\"gererCheckbox(this.value)\" value='".$count."'/>";
		echo " <input id=produit_liste_".$count." onclick=\"autoriserCommande()\" type=\"number\" min=\"0\" max=\"20\" value=\"0\" name=".$Produit['produitid']." style='visibility: hidden; width:40px;' />";
		echo "</div></li>";
	}

	echo "</ul>";
	echo "<input id='boutonCommande' type='submit' name='confirmerCommande' enable='false' disabled value='En attente de produits'>";
	echo "</form>";
}
?>
</div>
</body>
<?php
include './lib/footer.php';