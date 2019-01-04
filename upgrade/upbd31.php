<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique
Université d’Orléans
Rue de Chartre – BP6759
45067 Orléans Cedex 2

Ce logiciel est un programme informatique servant à la gestion d'une chimiothèque de produits de synthèses. 

Ce logiciel est régi par la licence CeCILL soumise au droit français et respectant les principes de diffusion des logiciels libres.
Vous pouvez utiliser, modifier et/ou redistribuer ce programme sous les conditions de la licence CeCILL telle que diffusée par le CEA,
 le CNRS et l'INRIA sur le site "http://www.cecill.info".

En contrepartie de l'accessibilité au code source et des droits de copie, de modification et de redistribution accordés par cette licence,
 il n'est offert aux utilisateurs qu'une garantie limitée. Pour les mêmes raisons, seule une responsabilité restreinte pèse sur l'auteur du
 programme, le titulaire des droits patrimoniaux et les concédants successifs.

A cet égard l'attention de l'utilisateur est attirée sur les risques associés au chargement, à l'utilisation, à la modification et/ou au développement
 et à la reproduction du logiciel par l'utilisateur étant donné sa spécificité de logiciel libre, qui peut le rendre complexe à manipuler et qui le
réserve donc à des développeurs et des professionnels avertis possédant des connaissances informatiques approfondies. Les utilisateurs sont donc 
invités à charger et tester l'adéquation du logiciel à leurs besoins dans des conditions permettant d'assurer la sécurité de leurs systèmes et ou de
 leurs données et, plus généralement, à l'utiliser et l'exploiter dans les mêmes conditions de sécurité. 

Le fait que vous puissiez accéder à cet en-tête signifie que vous avez pris connaissance de la licence CeCILL, et que vous en avez accepté les
termes.
*/
include_once '../script/secure.php';
include_once '../protection.php';
include_once '../langues/'.$_SESSION['langue'].'/presentation.php';
echo "<tr>
    <td bgcolor=\"#FFFFFF\" align=\"center\" valign=\"middle\" width=\"100%\" colspan=\"2\">";

require '../script/connectiona.php';

$sql="SELECT chi_statut FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournés dans la variable $result
$resulta =$db->query($sql);
$row =$resulta->fetch(PDO::FETCH_NUM);
$i=0;
if ($row[0]=='ADMINISTRATEUR') {
	
	$sql="ALTER TABLE `equipe` CHANGE `equi_initiale_numero` `equi_initiale_numero` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
	$resultat1=$db->exec($sql);
	if($resultat1!==false) {
		echo "<p>Modification du champ 'equi_initiale_numero`' dans la table 'equipe'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'equi_initiale_numero' dans la table 'equipe'</p>";
	
	$sql="ALTER TABLE `chimiste` CHANGE `chi_id_responsable` `chi_id_responsable` SMALLINT(6) UNSIGNED NOT NULL;";
	$resultat2=$db->exec($sql);
	if($resultat2!==false) {
		echo "<p>Modification du champ 'chi_id_responsable' dans la table 'chimiste'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'chi_id_responsable' dans la table 'chimiste'</p>";
	
	$sql="ALTER TABLE `produit` CHANGE `pro_qrcode` `pro_qrcode` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
	$resultat3=$db->exec($sql);
	if($resultat3!==false) {
		echo "<p>Modification du champ 'pro_qrcode' dans la table 'produit'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_qrcode' dans la table 'produit'</p>";
	
	$sql="ALTER TABLE `plaque` ADD `pla_identifiant_externe` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;";
	$resultat3=$db->exec($sql);
	if($resultat3!==false) {
		echo "<p>Ajout du champ 'pla_identifiant_externe' dans la table 'plaque'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de l'ajout du champ 'pla_identifiant_externe' dans la table 'plaque'</p>";
	
	$sql="ALTER TABLE `structure` CHANGE `str_nom` `str_nom` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
	$resultat4=$db->exec($sql);
	if($resultat4!==false) {
		echo "<p>modification du champ 'str_nom' dans la table 'structure'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'str_nom' dans la table 'structure'</p>";
	
	$sql="ALTER TABLE `produit` CHANGE `pro_doi` `pro_doi` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
	$resultat5=$db->exec($sql);
	if($resultat5!==false) {
		echo "<p>modification du champ 'pro_doi' dans la table 'produit'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_doi' dans la table 'produit'</p>";
	
	$sql="ALTER TABLE `parametres` ADD `para_id_parametre` INT(1) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;";
	$resultat6=$db->exec($sql);
	if($resultat6!==false) {
		echo "<p>Ajout du champ 'para_id_parametre' en clès primaire dans la table 'parametres'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de l'ajout du champ 'para_id_parametre' dans la table 'parametres'</p>";
	
	if ($i==7) {
		echo "<br/><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.3.1</i> vers la version 1.3.2 a réussi</h3>";
		$sql="UPDATE `parametres` SET `para_version` = '1.3.2'";
		$resultat7=$db->exec($sql);
		if($resultat7!==false) {
			echo "<p>Modification du champ 'para_version' dans la table 'parametres'</p>";
			$i++;
		}
		else echo "<p class=\"h3erreur\">Echec de la modification du champ 'para_version' dans la table 'parametres'</p>";
		echo "<form method=\"post\" action=\"sessionpdo.php\"><input type=\"submit\" value=\"Retour\"></form>";	
	}
	else {
		echo "<br/><br/><br/><h3 class=\"h3erreur\">Echec de la mise à jour 1.3.2</h3>";
		echo "<form method=\"post\" action=\"sessionpdo.php\"><input type=\"submit\" value=\"Retour\"></form>";	
	}
		
	
}
else 
{
	session_destroy();
	unset($_SESSION);
	include_once 'index.php';
}
unset($db);
?>