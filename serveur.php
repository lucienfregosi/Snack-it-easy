




<?php
	session_start();
	error_reporting(E_ERROR | E_PARSE);
	include_once"lib/maLibUtils.php";
	include_once"lib/maLibSQL.php";
	include_once"lib/maLibSecurisation.php";
	include_once"lib/maLibForms.php";
	include_once"lib/lib_lucien.php";

?>


<!DOCTYPE html>
<html >	
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script>
	// On appele cette fonction quand on veut cacher la commande
	function hide_commande(e){

		// On récupère la commande id
		var commandeid = e.id.substring(3);

		// On cache l'élément parent (ici le tableua)
    	e.parentNode.parentNode.style.display =  'none';


    	// On appele une page php pour mettre à jour la base de données
    	$.ajax({
    		url: "ajax/update_commande.php",
    		type: "POST",
    		data: {'commandeid': commandeid},
    		success: function (rep) {
        		console.log(rep);
    		}
		});
		
	}
	</script>
</head>

<?php
	// Permet de vérifier que l'utilisateur est bien connecté
	securiser("index.php");

	// Il nous faut récupérer la buvette et le stade à laquelle le serveur est associé
	get_stade_buvette($_SESSION["USER_ID"], $stade, $buvette);

	
	// Récupération des commandes en cours
	$reponse = get_stade_buvette_commande($stade, $buvette);

	// On parcourt la liste des commandes récupérées
	$rs=parcoursRs($reponse); 


	// Créons le tableau 
	echo '
	<body>
	<table class="table-fill">
		<tr>
			<th style="max-width:50px;" class="text-left"> Ref Commande</th>
			<th style="max-width:50px;" class="text-left"> Heure de livraison</th>
			<th class="text-left"> Produits</th>
			<th style="max-width:50px;" ></th>
		</tr>';

	foreach($rs as $commande)
	{
		$reponse2 = get_commande_produits($commande["commandeid"]);
		
		// On récupère le nombre d'heures de la commande
		$horaireHeures = floor($commande["horaireVoulu"] / 60);

		// On récupère le nombre de minutes de la commande
		$horaireMinutes = $commande["horaireVoulu"] % 60;
		// On gère le cas ou le nombre est inférieur a 10
		$horaireMinutes = sprintf('%02d',$horaireMinutes);
		// on reconstitue l'heure en format mm/hh
		$horaire = $horaireHeures.":".$horaireMinutes;

		echo'
			<tr>
				<td class="text-left">'.$commande["commandeid"].'</td>
				<td class="text-left">'.$horaire.'</td>
				<td class="text-left">'; 
					$rs2=parcoursRs($reponse2); 
					foreach($rs2 as $produit){
						echo "
							<label class='text-left'>".$produit['quantite']."</label>
							<label class='text-left'>".$produit['produitnom']."</label>

						";
					}
				
				echo '
				</td>
				<td class="text-center"><input class="newButton" type="button" onclick="hide_commande(this);" id="bt_'.$commande["commandeid"].'" value="Terminé"></input></td>
				</tr>
		';
		

	}
	echo '</table></body>';
?>