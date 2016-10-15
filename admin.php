<?php

session_start();
error_reporting(E_ERROR | E_PARSE);
include_once"lib/maLibUtils.php";
include_once"lib/maLibSQL.php";
include_once"lib/maLibSecurisation.php";
include_once"lib/maLibForms.php";

/*
	evenement
	buvette
	code promo
	produit	
*/
?>

<head>
<script type="text/javascript">
function afficherSection(idSection)
{
	document.getElementByClassName("sections").style.visibility = 'hidden';
	document.getElementById(idSection).style.visibility = 'visible';
}
</script>
</head>
<body>

	<?php
	
	$listeStadeSQL="Select * From Stade, user where Stade.stadeAdmin=user.userid AND user.userid = '". $_SESSION["USER_ID"] ."'";
	$listeStade=parcoursRs(SQLSelect($listeStadeSQL));
	$idStade=null;
	foreach ($listeStade as $StadeUser){
		echo "Bienvenue ".$_SESSION["USER_LOGIN"].".</br>  Stade: ".$StadeUser['nom'];
		$idStade=$StadeUser['stadeid'];
	}
	?>
</br>

<?php
echo $action;
//$action=valider($action);
echo $action;
echo "<input type='button' onclick=\"afficherSection(1)\" value='listEvent' />";
echo "<input type='button' onclick=\"afficherSection(2)\" value='addBuvette' />";
	
    echo "<div class='sections' id='1' style='visibility:hidden'>";
        //listEvent
	
		$listeEventSQL="Select * From event where event.idstade='".$idStade."' AND STR_TO_DATE(event.date,'%d/%m/%Y') >= CURRENT_DATE()";
		$listeEvent=parcoursRs(SQLSelect($listeEventSQL));
		echo "</br>EVENEMENTS:<ul>";
		foreach ($listeEvent as $event){
			echo "<li>  Date: ". $event['date'] .",   ". $event['name'].",   horaire de debut: ". $event['horairedebut'].",   horaire de Fin: ". $event['horairefin'].".</br>";
			echo " 	Quota de service: ".$event['quotaparplage'].",    Taille des plages : ".$event['dureeplage']." minutes.";
			$listePauseSQL="Select * From pause where pause.event=".$event['eventid'];
			$listePause=parcoursRs(SQLSelect($listePauseSQL));
			foreach($listePause as $Pause){
				echo "<ul>-  Pause:  horaireDebut: ".$Pause['horairedebut']."   horaireFin: ".$Pause['horairefin']."</ul>" ;
			}
			echo "</li></br>";
		}
		echo "</ul>";
		
	echo "</div>";
	?>
	<div class='sections' id='2' style='visibility:hidden'>
			<form action="" method="post">
			Nouvelle buvette:</br>
			nom buvette: <input type="text" name="nomBuvette" /> </br>
			
			Porte Associée de la buvette:</br>
			Porte Nom: <input type="text" name="nomPorte" /> </br>
			Buvette associée: <input type="text" name="" /> </br>
			
			<input type="submit" name="envoyer" />
		</form>
	</div>
	<?php

switch ($action) {
    case "":
        //	menu
	
		?>		

		
		<form action="" method="post">
			<input type="submit" value="addEvent" name="addEvent" />
			
			<input type="submit" value="addBuvette"  name="addBuvette" />
			<input type="submit" value="listBuvette"  name="listBuvette" />		
			<input type="submit" value="addProduit"  name="addProduit" />		
			<input type="submit" value="listProduit"  name="listProduit" />		
		</form>
<?php		
		break;
    
	case valider("addEvent"):
        //addEvent
		?>		
		<form action="" method="post">
			Nouvel evenement:</br>
			nom evenement: <input type="text" name="nom" /> </br>
			date evenement: <input type="date" name="date" /> </br>
			horaire de debut: <input type="time" name="horaireDebut" /> </br>
			horaire de fin: <input type="time" name="horaireFin" /> </br>
			quota par plage: <input type="number" min=0 name="quotaParPlage" /> </br>
			taille de plage: <input type="number" min=0 step=5 name="taillePlage" /> </br>
			Pause:</br>
			debut de la pause: <input type="time" name="pauseDebut" /> </br>
			fin de la pause: <input type="time" name="pauseFin" /> </br>
			
			<input type="submit" name="envoyer" />
		</form>
		<?php				
		
        break;
    case valider("listBuvette"):
        //listBuvette
		
		$listeBuvetteSQL="Select * From buvette, stade where stade.stadeid=buvette.stade AND stade.nom='".$_SESSION['STADE']."'";
		$listeBuvette=parcoursRs(SQLSelect($listeBuvetteSQL));
		echo "</br>BUVETTES:<ul>";
		foreach ($listeBuvette as $buvette){
			echo "<li>  Nom : ". $buvette['nomBuvette'] .".";

			$listePorteSQL="Select * From porte where porte.buvette='".$buvette['buvetteid']."'";
			$listePorte=parcoursRs(SQLSelect($listePorteSQL));
			foreach($listePorte as $Porte){
				echo "<ul>-  Porte: ".$Porte['portelib']."</ul>" ;
			}
			echo "</li></br>";
		}
		echo "</ul>";
		
	    break;
    case valider("addPromo"):
        //addPromo
		
		
        break;
    case valider("listPromo"):
        //listPromo
		
		
		
		
        break;		
	case valider("addProduit"):
        //addProduit
		
		?>		
		<form action="" method="post">
			Nouveau produit:</br>
			nom produit: <input type="text" name="nomProduit" /> </br>
			url image: <input type="text" name="urlImage" /> </br>
			prix: <input type="number" min=0 step=0.01 name="prixProduit" /> </br>
			type alcoolisé: <input type="checkbox" name="alcool" > </br>
			
			<input type="submit" name="envoyer" />
		</form>
		<?php			
		
		
        break;
    case valider("listProduit"):
        //listProduit
		
		$listeProduitSQL="Select * From produit, produitstade where produitstade.produit=produit.produitid AND produitstade.stade='".$_SESSION['STADE_ID']."'";
		$listeProduit=parcoursRs(SQLSelect($listeProduitSQL));
		echo "</br>PRODUITS:<ul>";
		foreach ($listeProduit as $produit){
			echo "<li><img src=\"".$produit['urlimg']."\"  Nom : ". $produit['produitnom']."  Prix: ". $produit['prix'] .".";
			echo "</li></br>";
		}
		echo "</ul>";
		
		
        break;
		
		
		
		}

?>


	<?php
	// evenement

	?>






</body>
