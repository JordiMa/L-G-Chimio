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
	$sql="ALTER TABLE `parametres` CHANGE `para_version` `para_version` VARCHAR( 7 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
	$resultat1=$db->exec($sql);
	if($resultat1!==false) {
		echo "<p>Modification du champ 'para_version`' dans la table 'parametres'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'para_version' dans la table 'parametres'</p>";
	if ($i==1) {
		$sql="UPDATE `parametres` SET `para_version` = '1.3.2.1'";
		$resultat7=$db->exec($sql);
		if($resultat7!==false) {
			echo "<p>Modification de la valeur du champ 'para_version' dans la table 'parametres'</p>";
			echo "<br/><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.3.2</i> vers la version 1.3.2.1 a réussi</h3>";
		}
		else echo "<p class=\"h3erreur\">Echec de la modification de la valeur du champ 'para_version' dans la table 'parametres'</p>";
		echo "<form method=\"post\" action=\"sessionpdo.php\"><input type=\"submit\" value=\"Retour\"></form>";	
	}
	else {
		echo "<br/><br/><br/><h3 class=\"h3erreur\">Echec de la mise à jour 1.3.2.1</h3>";
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