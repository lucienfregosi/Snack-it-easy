<?php

session_start();
include_once"lib/lib_lucien.php";
tprint($_POST);
?>

<body>
<?php
	echo "Paiement";

	//On cree la transaction pour preparer la commande
	$sqlCreerTransaction = "INSERT INTO transaction (date) VALUES (".date("d/m/Y").")";
	$transactionId = SQLInsert($sqlCreerTransaction);

	//On cree la commande en mode panier (pas encore paye)
	$sqlCreerCommande = "INSERT INTO commande (user, stade, buvette, horaireVoulu, statut, transaction) VALUES (".$_SESSION["USER_ID"].", ".$_SESSION["STADE_ID"].", ".$_SESSION["BUVETTE_ID"].", 0, 'panier', $transactionId)";
	$commandeId = SQLInsert($sqlCreerCommande);

	$total = 0;

	foreach($_POST as $produitsCommandesId => $quantitesCommandes) {
		//On ne passe que des id de produits, ex de tableau : Array([1] => 1, [2] => 0, [3] => 3, [4] => 0, [confirmerCommande] => Confirmer ma commande)
		//On tombe sur un nombre donc un produit (on evite le cas du bouton de soumission)
		if (intval($produitsCommandesId) > 0)
		{
			//On ignore les produits non commandes
			if (intval($quantitesCommandes) > 0) {
				echo $produitsCommandesId;
				echo "\n";
				echo $quantitesCommandes;
				$total += get_prix_produit($produitsCommandesId) * $quantitesCommandes;
			}
		}
	}

	echo "\nTotal : ".$total;

	//On update la transaction avec le montant
?>
</body>
