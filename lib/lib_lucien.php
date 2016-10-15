<?php
	include_once"lib/maLibUtils.php";
	include_once"lib/maLibSQL.php";
	include_once"lib/maLibSecurisation.php";
	include_once"lib/maLibForms.php";



// On récupère le stade et la buvette associé à l'utilisateur
function get_stade_buvette($user_id, &$stade, &$buvette){


	$sqlBuvette = "Select buvette from serveurbuvette where serveur=".$user_id." and actif=1";
	$buvette = SQLGetChamp($sqlBuvette);

	$sqlStade = "select stade from buvette where buvetteid = ".$buvette."";
	$stade = SQLGetChamp($sqlStade);

}


function get_stade_buvette_commande($stade, $buvette){

	$sql="select * from commande where statut = 'paye_attente' and stade = ".$stade." and buvette = ".$buvette."";
	return SQLSelect($sql);
		
}

function get_commande_produits($commande_id){

	$sql="select * from produitcommande JOIN produit ON produitcommande.produit = produit.produitid where commande = ".$commande_id."";
	return SQLSelect($sql);

}


	function get_prix_produit($produit_id){
		$sql="select prix from produit WHERE produitid = $produit_id LIMIT 1";
		$res = parcoursRs(SQLSelect($sql));
		foreach ($res as $leProduit)
			return $leProduit['prix'];
	}

?>