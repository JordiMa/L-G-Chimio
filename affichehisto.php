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
include_once 'langues/'.$_SESSION['langue'].'/lang_fiche.php';
if (!empty($_POST['id'])) $id_sql=$_POST['id'];
if (!empty($_GET['id']))  $id_sql=$_GET['id'];
if (!empty($id_sql)) {
	//vérification que la session a le droit de visualiser la fiche demandée

	//appel le fichier de connexion à la base de données
	require 'script/connectionb.php';
	$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	//les résultats sont retournées dans la variable $result
	$result =$dbh->query($sql);
	$row =$result->fetch(PDO::FETCH_NUM);
	if ($row[0]=="{ADMINISTRATEUR}") {
		$sql="SELECT pro_suivi_modification FROM produit WHERE pro_id_produit='".$id_sql."'";
		$result1 =$dbh->query($sql);
		$row1=$result1->fetch(PDO::FETCH_NUM);
		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">
			<table width=\"328\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fiche.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".STRUCTURE."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"ficheana.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".ANALYSE."</a></td>";
		if (($row[0]=="{RESPONSABLE}" and $menu==2 and $row[2]==$row1[0]) or ($row[0]=="{ADMINISTRATEUR}" and $menu==2)) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fichebio.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".ANABIO."</a></td>";
		else print"<td width=\"82\" height=\"23\"></td>";
		if ($row[0]=="{ADMINISTRATEUR}" and $menu==2) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"fichehistorique.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".CHANGEMENT."</a></td>";
		else print"<td width=\"82\" height=\"23\"></td>";
		print"</tr>
				</table></td><td><div align=\"center\">
				<form method=\"post\" action=\"consultation.php\">
				<input type=\"image\" src=\"images/retour.gif\">
				<input type=\"hidden\" name=\"menu\" value=\"2\">
				<input type=\"hidden\" name=\"type\" value=\"".$_GET['type']."\">
				<input type=\"hidden\" name=\"mol\" value=\"".Base64_decode($_GET['mol'])."\">
				<input type=\"hidden\" name=\"formbrute\" value=\"".$_GET['formbrute']."\">
				<input type=\"hidden\" name=\"massemol\" value=\"".$_GET['massemol']."\">
				<input type=\"hidden\" name=\"supinf\" value=\"".$_GET['supinf']."\">
				<input type=\"hidden\" name=\"massexac\" value=\"".$_GET['massexact']."\">
				<input type=\"hidden\" name=\"forbrutexact\" value=\"".$_GET['forbrutexact']."\">
				<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">
				<input type=\"hidden\" name=\"nbrs\" value=\"".$_GET['nbrs']."\">
				<input type=\"hidden\" name=\"nbpage\" value=\"".$_GET['nbpage']."\">
				<input type=\"hidden\" name=\"typechimiste\" value=\"".$_GET['typechimiste']."\">
				<input type=\"hidden\" name=\"chimiste\" value=\"".$_GET['chimiste']."\">
				<input type=\"hidden\" name=\"equipechi\" value=\"".$_GET['equipechi']."\">
				<input type=\"hidden\" name=\"numero\" value=\"".$_GET['numero']."\">
				<input type=\"hidden\" name=\"refcahier\" value=\"".$_GET['refcahier']."\">
				<input type=\"hidden\" name=\"recherche\" value=\"".$_GET['recherche']."\">
				<input type=\"hidden\" name=\"valtanimoto\" value=\"".$_GET['valtanimoto']."\">
				</form>
				</div>
				</td></tr></table>";
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
				  <tr>
				  <td  colspan=\"4\" align=\"center\"><h3>".HISTORIQUE."</h3></td>
				  </tr>
				  <tr>
					<td><strong>".QUI."</strong></td>
					<td><strong>".QUAND."</strong></td>
					<td><strong>".CHAMPS."</strong></td>
					<td><strong>".ANCIEN."</strong></td>
				  </tr>";
		$tab=explode("\n",$row1[0]);
		for ($y=0; $y<(count($tab)-1); $y++) {
			print"<tr>";
			$tab1=explode ("@",$tab[$y]);
			$i=0;
			foreach ($tab1 as $cham) {
				$i++;
				if ($i==3) {
					if (preg_match("/./",$cham)) {
						$tab3=explode (".",$cham);
						print "<td>";
						foreach ($tab3 as $cham1) {
							echo constant($cham1)." ";
						}
						print"</td>";
					}
					else print"<td>".constant($cham)."</td>";
				}
				elseif ($i==4) {
					if (empty($cham)) print"<td>".AUCUNEVAL."</td>";
					elseif (preg_match("/#/",$cham)) print"<td bgcolor=\"$cham\">&nbsp;</td>";
					elseif (!preg_match("/[a-z0-9]/",$cham)) {
						if (preg_match("/,/",$cham)) {
							$tabcham=explode (",",$cham);
							print"<td>";
							foreach ($tabcham as $elemcham) {
								if (!empty ($elemcham)) print constant($elemcham).", ";
							}
						}
						elseif (preg_match("/[A-Z]/",$cham)) print "<td>$cham</td>";
						else print"<td>".constant($cham)."</td>";
					}
					else print "<td>$cham</td>";
				}
				else  print"<td>$cham</td>";
			}
			print"</tr>";
		}
		print"</table>";
		//fermeture de la connexion à la base de données
		unset($dbh);
	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>