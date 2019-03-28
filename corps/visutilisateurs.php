<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011
Distributeur : UGCN - http://chimiotheque-nationale.org

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

include_once 'script/secure.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"ttip.js\"></script>";
	if (empty($_GET['order'])) $order="chi_nom,chi_prenom";
	else {
		switch($_GET['order']) {
			case "nomasc": $order="chi_nom ASC";
			break;
			case "nomdesc": $order="chi_nom DESC";
			break;
			case "prenomasc": $order="chi_prenom ASC";
			break;
			case "prenomdesc": $order="chi_prenom DESC";
			break;
			case "langasc": $order="chi_langue ASC";
			break;
			case "langdesc": $order="chi_langue DESC";
			break;
			case "responasc": $order="chi_id_responsable ASC";
			break;
			case "respondesc": $order="chi_id_responsable DESC";
			break;
			case "autoasc": $order="chi_statut ASC";
			break;
			case "autodesc": $order="chi_statut DESC";
			break;
			case "equipasc": $order="equi_nom_equipe ASC";
			break;
			case "equipdesc": $order="equi_nom_equipe DESC";
			break;
			case "statasc": $order="chi_passif ASC";
			break;
			case "statdesc": $order="chi_passif DESC";
			break;
		}
	}
	print"<table width=\"492\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
			</tr>
			</table><br/>";
    print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"nomdesc" and $_GET['order']<>"nomasc")) print"<a href='utilisateurs.php?order=nomdesc'>".NOM." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="nomdesc" ) print"<a href='utilisateurs.php?order=nomasc'>".NOM." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="nomasc") print"<a href='utilisateurs.php?order=nomdesc'>".NOM." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print"</td><td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"prenomdesc" and $_GET['order']<>"prenomasc")) print"<a href='utilisateurs.php?order=prenomdesc'>".PRENOM." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="prenomdesc" ) print"<a href='utilisateurs.php?order=prenomasc'>".PRENOM." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="prenomasc") print"<a href='utilisateurs.php?order=prenomdesc'>".PRENOM." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print"</td>
	<td class=\"entete\">".COURRIEL."</td>
	<td class=\"entete\">".RECEP."</td>
	<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"langdesc" and $_GET['order']<>"langasc")) print"<a href='utilisateurs.php?order=langdesc'>".LANG." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="langdesc" ) print"<a href='utilisateurs.php?order=langasc'>".LANG." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="langasc") print"<a href='utilisateurs.php?order=langdesc'>".LANG." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print "</td>
	<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"autodesc" and $_GET['order']<>"autoasc")) print"<a href='utilisateurs.php?order=autodesc'>".AUTO." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="autodesc" ) print"<a href='utilisateurs.php?order=autoasc'>".AUTO." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="autoasc") print"<a href='utilisateurs.php?order=autodesc'>".AUTO." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print"</td>
	<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"respondesc" and $_GET['order']<>"responasc")) print"<a href='utilisateurs.php?order=respondesc'>".RESPON." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="respondesc" ) print"<a href='utilisateurs.php?order=responasc'>".RESPON." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="responasc") print"<a href='utilisateurs.php?order=respondesc'>".RESPON." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print"</td>
	<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"equipdesc" and $_GET['order']<>"equipasc")) print"<a href='utilisateurs.php?order=equipdesc'>".EQUIPE." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="equipdesc" ) print"<a href='utilisateurs.php?order=equipasc'>".EQUIPE." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="equipasc") print"<a href='utilisateurs.php?order=equipdesc'>".EQUIPE." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print"</td>
	<td class=\"entete\">";
	if (!isset($_GET['order']) or ($_GET['order']<>"statdesc" and $_GET['order']<>"statasc")) print"<a href='utilisateurs.php?order=statdesc'>".STATUT." <img border=\"0\" src=\"images/desc.gif\" /></a>";
	elseif ( $_GET['order']=="statdesc" ) print"<a href='utilisateurs.php?order=statasc'>".STATUT." <img border=\"0\" src=\"images/ascv.gif\" /></a>";
	elseif ($_GET['order']=="statasc") print"<a href='utilisateurs.php?order=statdesc'>".STATUT." <img border=\"0\" src=\"images/descv.gif\" /></a>";
	print "</td>
		</tr>";
	$sql="SELECT chi_nom,chi_prenom,chi_email,chi_recevoir,chi_langue,chi_statut,chi_id_responsable,chi_id_equipe,chi_passif FROM chimiste ORDER BY $order";
	$resultat=$dbh->query($sql);
	$col=1;
    while ($row=$resultat->fetch(PDO::FETCH_NUM)) {
		if ($row[7]>0){
			$sql="SELECT equi_nom_equipe FROM equipe WHERE equi_id_equipe='".$row[7]."'";
			$resultat2=$dbh->query($sql);
			$row2=$resultat2->fetch(PDO::FETCH_NUM);
		}
		else $row2[0]='';

		if ($row[5]!="{CHIMISTE}") {
			switch ($row[3]) {
				case 0: $row[3]=NON;
				break;
				case 1: $row[3]=OUI;
				break;
			}
		}
		else $row[3]="-";
		switch ($row[8]) {
			case 0: $row[8]="<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>".ACTIF."</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/pointv.gif\" /></a>";
			break;
			case 1: $row[8]="<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>".PASSIF."</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/point.gif\" /></a>";
			break;
		}
		if ($row[6]==0) $row[6]="-";
		else {
			$sql="SELECT chi_nom,chi_prenom FROM chimiste WHERE chi_id_chimiste=$row[6]";
			$resultat1=$dbh->query($sql);
			$row1=$resultat1->fetch(PDO::FETCH_NUM);
			$row[6]=$row1[1]." ".$row1[0];
		}
		print"<tr";
		if (!is_integer($col/2)) print" class=\"ligneutil\"";
		else print" class=\"ligneutil1\"";
		$search= array('{','}');
		$row[5]=str_replace($search,'',$row[5]);
		print"><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>".constant($row[5])."</td><td>$row[6]</td><td>$row2[0]</td><td>$row[8]</td></tr>";
		$col++;
	}
    print"</table>";

}
else require 'deconnexion.php';
unset($dbh);
?>
