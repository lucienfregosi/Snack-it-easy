<?php


/*
Ce fichier d�finit diverses fonctions permettant de faciliter la production de mises en formes complexes : 
tableaux, formulaires, ...
*/
// Exemple d'appel :  mkLigneEntete($data,array('pseudo', 'couleur', 'connecte'));
function mkLigneEntete($tabAsso,$listeChamps=false)
{
	// Fonction appel�e dans mkTable, produit une ligne d'ent�te
	// contenant les noms des champs � afficher dans mkTable
	// Les champs � afficher sont d�finis � partir de la liste listeChamps 
	// si elle est fournie ou du tableau tabAsso

	if (!$listeChamps)	// listeChamps est faux  : on utilise le not : '!'
	{
		// tabAsso est un tableau associatif dont on affiche TOUTES LES CLES
		echo "\t<tr>\n";
		foreach ($tabAsso as $cle => $val)	
		{
			echo "\t\t<th>$cle</th>\n";
		}
		echo "\t</tr>\n";
	}
	else		// Les noms des champs sont dans $listeChamps 	
	{
		echo "\t<tr>\n";
		foreach ($listeChamps as $nomChamp)	
		{
			echo "\t\t<th>$nomChamp</th>\n";
		}
		echo "\t</tr>\n";
	}
}

function mkLigne($tabAsso,$listeChamps=false)
{
	// Fonction appel�e dans mkTable, produit une ligne 	
	// contenant les valeurs des champs � afficher dans mkTable
	// Les champs � afficher sont d�finis � partir de la liste listeChamps 
	// si elle est fournie ou du tableau tabAsso

	if (!$listeChamps)	// listeChamps est faux  : on utilise le not : '!'
	{
		// tabAsso est un tableau associatif
		echo "\t<tr>\n";
		foreach ($tabAsso as $cle => $val)	
		{
			echo "\t\t<td>$val</td>\n";
		}
		echo "\t</tr>\n";
	}
	else	// les champs � afficher sont dans $listeChamps
	{
		echo "\t<tr>\n";
		foreach ($listeChamps as $nomChamp)	
		{
			if($nomChamp=='avatar')
			{
				echo "\t\t<td><img src=$tabAsso[$nomChamp] alt='Avatar introuvable' height='50'></td>\n";
			}
			else if($nomChamp=='promo')
			{
				echo "\t\t<td>L$tabAsso[$nomChamp]</td>\n";
			}
			else if($nomChamp=='admin')
			{
				if($tabAsso[$nomChamp]==1)
				{
					echo "\t\t<td><div class='estAdmin'>Administrateur</div></td>\n";
				}
				else
				{
					echo "\t\t<td>Utilisateur</td>\n";
				}
			}
			else
			{	
				echo "\t\t<td>$tabAsso[$nomChamp]</td>\n";
			}
		}
		echo "\t</tr>\n";
	}
}

// Exemple d'appel :  mkTable($users,array('pseudo', 'couleur', 'connecte'));	
function mkTable($tabData,$listeChamps=false)
{

	// Attention : le tableau peut etre vide 
	// On produit un code ROBUSTE, donc on teste la taille du tableau
	if (count($tabData) == 0) return;

	echo "<table border=\"1\">\n";
	// afficher une ligne d'entete avec le nom des champs
	mkLigneEntete($tabData[0],$listeChamps);

	//tabData est un tableau indic� par des entier
	foreach ($tabData as $data)	
	{
		// afficher une ligne de donn�es avec les valeurs, � chaque it�ration
		mkLigne($data,$listeChamps);
	}
	echo "</table>\n";

	// Produit un tableau affichant les donn�es pass�es en param�tre
	// Si listeChamps est vide, on affiche toutes les donn�es de $tabData
	// S'il est d�fini, on affiche uniquement les champs list�s dans ce tableau, 
	// dans l'ordre du tableau
	
}
// exemple d'appel : 
// $users = listerUtilisateurs("both");
// mkSelect("idUser",$users,"id","pseudo");
// TESTER AVEC mkSelect("idUser",$users,"id","pseudo",2,"couleur");

function mkSelect($nomChampSelect, $tabData,$champValue, $champLabel,$selected=false,$champLabel2=false)
{

	echo "<select name=\"$nomChampSelect\">\n";
	foreach ($tabData as $data)
	{
		$sel = "";	// par d�faut, aucune option n'est pr�selectionn�e 
		// MAIS SI le champ selected est fourni
		// on teste s'il est �gal � l'identifiant de l'�l�ment en cours d'affichage
		// cet identifiant est celui qui est affich� dans le champ value des options
		// i.e. $data[$champValue]
		if ( ($selected) && ($selected == $data[$champValue]) )
			$sel = "selected=\"selected\"";

		echo "<option $sel value=\"$data[$champValue]\">\n";
		echo  $data[$champLabel] . "\n";
		if ($champLabel2) 	// SI on demande d'afficher un second label
			echo  " ($data[$champLabel2])\n";
		echo "</option>\n";
	}
	echo "</select>";

	// Produit un menu d�roulant portant l'attribut name = $nomChampSelect
	// TNE: Si cette variable se termine par '[]', il faudra affecter l'attribut multiple � la balise select

	// Produire les options d'un menu d�roulant � partir des donn�es pass�es en premier param�tre
	// $champValue est le nom des cases contenant la valeur � envoyer au serveur
	// $champLabel est le nom des cases contenant les labels � afficher dans les options
	// $selected contient l'identifiant de l'option � s�lectionner par d�faut
	// si $champLabel2 est d�fini, il indique le nom d'une autre case du tableau 
	// servant � produire les labels des options
}

function mkInput($type,$name,$value="")
{
	// Produit un champ formulaire
	echo "<input type=\"$type\" name=\"$name\" value=\"$value\"/>";
}

function mkRadioCb($type,$name,$value,$checked=false)
{
	// Produit un champ formulaire de type radio ou checkbox
	// Et s�lectionne cet �l�ment si le quatri�me argument est vrai
	$selectionne = "";	
	if ($checked) 
		$selectionne = "checked=\"checked\"";
	echo "<input type=\"$type\" name=\"$name\" value=\"$value\"  $selectionne />";
}
?>