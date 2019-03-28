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
include_once 'langues/'.$_SESSION['langue'].'/lang_plaque.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"plaques.php\">".CREA."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"gestionplaque.php\">".GESTION."</a></td>
			</tr>
			</table><br/>";

	print"<table width=\"246\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr><td>";
	echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
	print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
		  <tr>
			<th scope=\"col\">&nbsp;</th>
			<th scope=\"col\" width=\"80\">2</th>
			<th scope=\"col\" width=\"80\">3</th>
			<th scope=\"col\" width=\"80\">4</th>
			<th scope=\"col\" width=\"80\">5</th>
			<th scope=\"col\" width=\"80\">6</th>
			<th scope=\"col\" width=\"80\">7</th>
			<th scope=\"col\" width=\"80\">8</th>
			<th scope=\"col\" width=\"80\">9</th>
			<th scope=\"col\" width=\"80\">10</th>
			<th scope=\"col\" width=\"80\">11</th>
		  </tr>";

	$sql="SELECT str_mol,pos_coordonnees FROM position,produit,structure WHERE pos_id_plaque='".$_GET['pltmere']."' and position.pos_id_produit=produit.pro_id_produit and produit.pro_id_structure=structure.str_id_structure ORDER BY pos_coordonnees";
	$resultat=$dbh->query($sql);
	while($row=$resultat->fetch(PDO::FETCH_NUM)) {
			$tab[$row[1]]=$row[0];
	}
	$i=0;
	$u="a";
	$uu=2;
	while($i<80) {
		if ($uu==2) echo "<tr>
			<th scope=\"row\" height=\"75\">".mb_strtoupper($u)."</th>";
		print"<td>";
		if (!empty($tab[$u.$uu])) {
			$jme=new visualisationmoleculejme (80,80,$tab[$u.$uu]);
			$jme->imprime();
		}
		else {
			if (!empty($tab[$u.'0'.$uu])) {
				$jme=new visualisationmoleculejme (80,80,$tab[$u.'0'.$uu]);
				$jme->imprime();
			}
		}
		print"</td>";
		$uu++;
		if ($uu==12) {
			$uu=2;
			$u++;
			echo "</tr>";
		}
		$i++;
	}
  print"</td>";
print"</table>";

print"</td><td valign=\"top\"><a href=\"gestionplaque.php?id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=".$_GET["id2"]."&i=".$_GET["i"]."&j=".$_GET["j"]."&k=".$_GET["k"]."&m=".$_GET["m"]."\"><img src=\"images/retour.gif\" alt=\"".RETOUR."\" title=\"".RETOUR."\" border=\"0\"/></a><br/><br/><a href=\"visuplaimprime.php?pltmere=".$_GET['pltmere']."\" target=\"_blank\"><img src=\"images/imprime.gif\" alt=\"".IMPRIME."\" title=\"".IMPRIME."\" border=\"0\"/></a><br/><br/><a href=\"visuplaquesdf.php?pltmere=".$_GET['pltmere']."\" target=\"_blank\"><img src=\"images/charge.gif\" alt=\"".TELECHARGESDF."\" title=\"".TELECHARGESDF."\" border=\"0\"/></a><br/><br/><a href=\"exportcsv.php?pltmere=".$_GET['pltmere']."\" target=\"_blank\"><img src=\"images/liste.gif\" alt=\"".LISTE."\" title=\"".LISTE."\" border=\"0\"/></a></td></tr></table>";
}
else require 'deconnexion.php';
unset($dbh);
?>
