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

	if(!isset($_GET["id"])) $_GET["id"]="";

	if (isset($_GET["idsup"]) and $_GET["idsup"]>0) {
		$sqlsup1="DELETE FROM position WHERE pos_id_plaque='".$_GET["idsup"]."';";
		$sqlsup2="DELETE FROM lotplaque WHERE lopla_id_plaque='".$_GET["idsup"]."';";
		$sqlsup3="DELETE FROM plaquecible WHERE plac_id='".$_GET["idsup"]."';";
		$sqlsup4="DELETE FROM plaque WHERE pla_id_plaque='".$_GET["idsup"]."';";
		for ($i=1;$i<=4;$i++) {
			$supression=$dbh->exec(${"sqlsup".$i});
		}
	}

	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"plaques.php\">".CREA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"gestionplaque.php\">".GESTION."</a></td>
		</tr>
		</table>";
	print"<div id=\"dhtmltooltip\"></div>
		<script language=\"javascript\" src=\"ttip.js\"></script>";
	$sql="SELECT pla_id_plaque,pla_concentration,pla_nb_decongelation,pla_date,pla_volume,pla_unite_volume,pla_identifiant_local,pla_id_plaque_mere,sol_solvant,pla_masse FROM plaque,solvant WHERE pla_id_plaque_mere=0 and plaque.pla_id_solvant=solvant.sol_id_solvant";
	$resultat=$dbh->query($sql);
	print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
	while($row=$resultat->fetch(PDO::FETCH_NUM)) {
		$sql="SELECT pla_id_plaque FROM plaque WHERE pla_id_plaque_mere=$row[0]";
		$resultat1=$dbh->query($sql);
		$nbresultat1=$resultat1->rowCount();
		print"<tr><td width=\"15%\">";
		if ($nbresultat1>0 and $row[0]<>$_GET["id"]) print"&nbsp;&nbsp;<a href=\"gestionplaque.php?id=$row[0]\"><img src=\"images/plus.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".DEVELOPPE."\" title=\"".DEVELOPPE."\"/></a>";
		elseif ($nbresultat1>0 and $row[0]==$_GET["id"]) print"&nbsp;&nbsp;<a href=\"gestionplaque.php\"><img src=\"images/moins.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".REDUIRE."\" title=\"".REDUIRE."\"/></a>";
		else print"&nbsp;&nbsp;&nbsp;&nbsp;";
		$tabdate=explode("-",$row[3]);
		$search= array('{','}');
		$row[5]=str_replace($search,'',$row[5]);
		if ($row[9]==0) print"&nbsp;<a href=\"statuspl.php?idpl=$row[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row[8]<br/>- ".VOLUME." $row[4] ".constant($row[5])."<br/>- ".DATE." $tabdate[2]-$tabdate[1]-$tabdate[0]')\">$row[6]</a>";
		else print"&nbsp;<a href=\"statuspl.php?idpl=$row[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row[8]<br/>- ".CONCENTRATION." $row[1] ".MOL."<br/>- ".VOLUME." $row[4] ".constant($row[5])."<br/>- ".MASSE." $row[9]".MG."<br/>- ".DATE." $tabdate[2]-$tabdate[1]-$tabdate[0]')\">$row[6]</a>";
		if (!isset($_GET["id1"])) $_GET["id1"]="";
		if (!isset($_GET["id2"])) $_GET["id2"]="";
		if (!isset($_GET["m"])) $_GET["m"]="";
		if (!isset($_GET["k"])) $_GET["k"]="";
		if (!isset($j)) $j="";
		if (!isset($i)) $i="";
		print"&nbsp;&nbsp;<a href=\"visuplt.php?pltmere=$row[0]&id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=".$_GET["id2"]."&i=$i&j=$j&k=".$_GET["k"]."&m=".$_GET["m"]."\"><img src=\"images/lire.gif\" width=\"15\" border=\"0\" alt=\"".VISU."\" title=\"".VISU."\"/></a><a href=\"gestionplaque.php?idmodif=".$row[0]."\"><img src=\"images/modifier.gif\" width=\"15\" border=\"0\" alt=\"".MOD."\" title=\"".MOD."\"/></a>";
		if ($nbresultat1>0 and $row[0]==$_GET["id"]) print"&nbsp;&rarr;";

		$sql="SELECT count(*) FROM position WHERE pos_id_plaque='".$row[0]."'";
		$resultnbproduit=$dbh->query($sql);
		$rownbprod=$resultnbproduit->fetch(PDO::FETCH_NUM);
		if ($row[9]>0) $massety=1;
		else $massety=2;
		$sql="SELECT * FROM plaquecible WHERE plac_id_plaque='".$row[0]."'";
		$plaquecible=$dbh->query($sql);
		if ($nbresultat1==0 and $plaquecible->rowCount()==0) echo "<a href=\"gestionplaque.php?idsup=".$row[0]."\" onclick=\"return(confirm('".SUPCONFIRM.$row[6]."'));\")\"><img src=\"images/pasok.gif\" width=\"15\" border=\"0\" alt=\"".SUP."\" title=\"".SUP."\"/></a>";


		//plaques mères
		if (isset($_GET["id"]) and $_GET["id"]==$row[0]) {
			if(!isset($_GET["id1"])) $_GET["id1"]="";
			print"</td><td width=\"20%\">";
			$sql="SELECT pla_id_plaque,pla_concentration,pla_nb_decongelation,pla_date,pla_volume,pla_unite_volume,pla_identifiant_local,pla_id_plaque_mere,sol_solvant,pla_masse,pla_volume_preleve,pla_unite_vol_preleve FROM plaque,solvant WHERE pla_id_plaque_mere=$row[0] and plaque.pla_id_solvant=solvant.sol_id_solvant";
			$resultat2=$dbh->query($sql);
			$nbresultat2=$resultat2->rowCount();
			$k=0;
			while($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT pla_id_plaque FROM plaque WHERE pla_id_plaque_mere=$row2[0]";
				$resultat3=$dbh->query($sql);
				$nbresultat3=$resultat3->rowCount();
				if(!isset($_GET["j"])) $_GET["j"]="";
				if (isset($_GET["i"])) $i=$_GET["i"]+$_GET["j"];
				else $i=$nbresultat3;

				if ($nbresultat2==1) print"&#9472;&#9472;&nbsp;";
				elseif ($nbresultat2==2) {
					if ($k==0) print"&#9484;&#9472;&nbsp;";
					if ($k==1) print"&#9492;&#9472;&nbsp;";
				}
				elseif ($nbresultat2>=3) {
					if ($k==0) print"&#9484;&#9472;&nbsp;";
					if ($k>0 and $k<($nbresultat2-1)) print"&#9500;&#9472;&nbsp;";
					if ($k==($nbresultat2-1)) print"&#9492;&#9472;&nbsp;";
				}

				if ($nbresultat3>0 and $row2[0]<>$_GET["id1"]) print"<a href=\"gestionplaque.php?id=$row[0]&id1=$row2[0]&i=$i&k=$k\"><img src=\"images/plus.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".DEVELOPPE."\" title=\"".DEVELOPPE."\"/></a>";
				elseif ($nbresultat3>0 and $row2[0]==$_GET["id1"]) print"<a href=\"gestionplaque.php?id=$row[0]\"><img src=\"images/moins.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".REDUIRE."\" title=\"".REDUIRE."\"/></a>";
				else print"&nbsp;&nbsp;";
				$search= array('{','}');
				$row2[5]=str_replace($search,'',$row2[5]);
				$tabdate2=explode("-",$row2[3]);
				if ($row2[9]==0) {
					print"&nbsp;<a href=\"statuspl.php?idpl=$row2[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row2[8]<br/>- ".VOLUME." $row2[4] ".constant($row2[5])."<br/>";
					if ($row[6]>0) print "- ".VOLUMEPRE.$row[6].DPOINT." $row2[10]".constant($row2[11])."<br/>";
					print"- ".DATE." $tabdate2[2]-$tabdate2[1]-$tabdate2[0]')\">$row2[6]</a>";
				}
				else {
					print"&nbsp;<a href=\"statuspl.php?idpl=$row2[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row2[8]<br/>- ".CONCENTRATION." $row2[1] ".MOL."<br/>- ".VOLUME." $row2[4] ".constant($row2[5])."<br/>- ".MASSE." $row2[9]".MG."<br/>";
					if ($row[6]>0) print "- ".VOLUMEPRE.$row[6].DPOINT." $row2[10]".constant($row2[11])."<br/>";
					print "- ".DATE." $tabdate2[2]-$tabdate2[1]-$tabdate2[0]')\">$row2[6]</a>";
				}
				print"&nbsp;&nbsp;<a href=\"visuplt.php?pltmere=$row2[0]&id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=".$_GET["id2"]."&i=$i&j=$j&k=".$_GET["k"]."&m=".$_GET["m"]."\"><img src=\"images/lire.gif\" width=\"15\" border=\"0\" alt=\"".VISU."\" title=\"".VISU."\"/></a><a href=\"gestionplaque.php?idmodif=".$row2[0]."\"><img src=\"images/modifier.gif\" width=\"15\" border=\"0\" alt=\"".MOD."\" title=\"".MOD."\"/></a>";
				$sql="SELECT * FROM plaquecible WHERE plac_id_plaque='".$row[0]."'";
				$plaquecible=$dbh->query($sql);
				if ($nbresultat3==0 and $plaquecible->rowCount()==0) echo "<a href=\"gestionplaque.php?idsup=".$row2[0]."\" onclick=\"return(confirm('".SUPCONFIRM.$row[6]."'));\")\"><img src=\"images/pasok.gif\" width=\"15\" border=\"0\" alt=\"".SUP."\" title=\"".SUP."\"/></a>";
				if ($nbresultat3>0 and $row2[0]==$_GET["id1"]) {
					print"&nbsp;&rarr;<br/>";
					for ($l=0; $l<$i; $l++) {
						if ($k==($nbresultat2-1)) print"<br/>";
						else print"&#9474;<br/>";
					}
				}
				else print"<br/>";
				$k++;
			}
		    //plaques filles
			if (isset($_GET["id1"])) {

				if(!isset($_GET["k"])) $_GET["k"]="";
				if(!isset($_GET["id2"])) $_GET["id2"]="";

				print"</td>\n<td width=\"20%\" valign=\"top\">";
				for ($n=0; $n<$_GET["k"]; $n++) {
					print"<br/>";
				}
				$sql="SELECT pla_id_plaque,pla_concentration,pla_nb_decongelation,pla_date,pla_volume,pla_unite_volume,pla_identifiant_local,pla_id_plaque_mere,sol_solvant,pla_masse,pla_volume_preleve,pla_unite_vol_preleve FROM plaque,solvant WHERE pla_id_plaque_mere=".$_GET["id1"]." and plaque.pla_id_solvant=solvant.sol_id_solvant";
				$resultat4=$dbh->query($sql);
				if(!empty($resultat4)) {
					$nbresultat4=$resultat4->rowCount();
					$m=0;
					while($row4=$resultat4->fetch(PDO::FETCH_NUM)) {

						$sql="SELECT pla_id_plaque,pla_identifiant_local FROM plaque WHERE pla_id_plaque_mere=$row4[0]";
						$resultat5=$dbh->query($sql);
						$nbresultat5=$resultat5->rowCount();
						if (isset($_GET["j"])) $j=$_GET["j"];
						else $j=$nbresultat5;

						if ($nbresultat4==1) print"&#9472;&#9472;&nbsp;";
						elseif ($nbresultat4==2) {
							if ($m==0) print"&#9484;&#9472;&nbsp;";
							if ($m==1) print"&#9492;&#9472;&nbsp;";
						}
						elseif ($nbresultat4>=3) {
							if ($m==0) print"&#9484;&#9472;&nbsp;";
							if ($m>0 and $m<($nbresultat4-1)) print"&#9500;&#9472;&nbsp;";
							if ($m==($nbresultat4-1)) print"&#9492;&#9472;&nbsp;";
						}
						$sql="SELECT pla_identifiant_local FROM plaque WHERE pla_id_plaque='".$_GET["id1"]."'";
						$resultat5b=$dbh->query($sql);
						$row5b=$resultat5b->fetch(PDO::FETCH_NUM);
						if ($nbresultat5>0 and $row4[0]<>$_GET["id2"]) print"<a href=\"gestionplaque.php?id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=$row4[0]&i=$i&j=$j&k=".$_GET["k"]."&m=$m\"><img src=\"images/plus.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".DEVELOPPE."\" title=\"".DEVELOPPE."\"/></a>";
						elseif ($nbresultat5>0 and $row4[0]==$_GET["id2"]) print"<a href=\"gestionplaque.php?id=".$_GET["id"]."&id1=".$_GET["id1"]."&k=".$_GET["k"]."&i=".($i-$j)."\"><img src=\"images/moins.gif\" width=\"10\" height=\"11\" border=\"0\" alt=\"".REDUIRE."\" title=\"".REDUIRE."\"/></a>";
						else print"&nbsp;&nbsp;";
						$search= array('{','}');
						$row4[5]=str_replace($search,'',$row4[5]);
						$tabdate4=explode("-",$row4[3]);
						if ($row4[9]==0) {
							print"&nbsp;<a href=\"statuspl.php?idpl=$row4[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row4[8]<br/>- ".VOLUME." $row4[4] ".constant($row4[5])."<br/>";
							if ($row[6]>0) print "- ".VOLUMEPRE.$row5b[0].DPOINT." $row4[10]".constant($row4[11])."<br/>";
							print"- ".DATE." $tabdate4[2]-$tabdate4[1]-$tabdate4[0]')\">$row4[6]</a>";
						}
						else {
							print"&nbsp;<a href=\"statuspl.php?idpl=$row4[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row4[8]<br/>- ".CONCENTRATION." $row4[1] ".MOL."<br/>- ".VOLUME." $row4[4] ".constant($row4[5])."<br/>- ".MASSE." $row4[9]".MG."<br/>";
							if ($row[6]>0) print "- ".VOLUMEPRE.$row5b[0].DPOINT." $row4[10]".constant($row4[11])."<br/>";
							print"- ".DATE." $tabdate4[2]-$tabdate4[1]-$tabdate4[0]')\">$row4[6]</a>";
						}
						print"&nbsp;&nbsp;<a href=\"visuplt.php?pltmere=$row4[0]&id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=".$_GET["id2"]."&i=$i&j=$j&k=".$_GET["k"]."&m=".$_GET["m"]."\"><img src=\"images/lire.gif\" width=\"15\" border=\"0\" alt=\"".VISU."\" title=\"".VISU."\"/></a><a href=\"gestionplaque.php?idmodif=".$row4[0]."\"><img src=\"images/modifier.gif\" width=\"15\" border=\"0\" alt=\"".MOD."\" title=\"".MOD."\"/></a>";
						$sql="SELECT * FROM plaquecible WHERE plac_id_plaque='".$row[0]."'";
						$plaquecible=$dbh->query($sql);
						if ($nbresultat5==0 and $plaquecible->rowCount()==0) echo "<a href=\"gestionplaque.php?idsup=".$row4[0]."\" onclick=\"return(confirm('".SUPCONFIRM.$row[6]."'));\")\"><img src=\"images/pasok.gif\" width=\"15\" border=\"0\" alt=\"".SUP."\" title=\"".SUP."\"/></a>";
						if ($nbresultat4>0 and $row4[0]==$_GET["id2"]) {
							print"&nbsp;&rarr;<br/>";
							for ($l=0; $l<$j; $l++) {
								if ($m==($nbresultat4-1)) print"<br/>";
								else print"&#9474;<br/>";
							}
						}
						else print"<br/>";
						$m++;
					}
					//plaques petites filles
					if (isset($_GET["id2"])) {

						if (!isset($_GET["k"])) $_GET["k"]="";
						if (!isset($_GET["m"])) $_GET["m"]="";

						print"</td>\n<td width=\"40%\" valign=\"top\">";
						for ($n=0; $n<($_GET["m"]+$_GET["k"]); $n++) {
							print"<br/>";
						}
						$sql="SELECT pla_id_plaque,pla_concentration,pla_nb_decongelation,pla_date,pla_volume,pla_unite_volume,pla_identifiant_local,pla_id_plaque_mere,sol_solvant,pla_masse,pla_volume_preleve,pla_unite_vol_preleve FROM plaque,solvant WHERE pla_id_plaque_mere=".$_GET["id2"]." and plaque.pla_id_solvant=solvant.sol_id_solvant";
						$resultat6=$dbh->query($sql);
						if (!empty($resultat6)) {
							$nbresultat6=$resultat6->rowCount();
							$o=0;
							while($row6=$resultat6->fetch(PDO::FETCH_NUM)) {
								$sql="SELECT pla_id_plaque FROM plaque WHERE pla_id_plaque_mere=$row6[0]";
								$resultat7=$dbh->query($sql);
								if ($nbresultat6==1) print"&#9472;&#9472;&nbsp;";
								elseif ($nbresultat6==2) {
									if ($o==0) print"&#9484;&#9472;&nbsp;";
									if ($o==1) print"&#9492;&#9472;&nbsp;";
								}
								elseif ($nbresultat6>=3) {
									if ($o==0) print"&#9484;&#9472;&nbsp;";
									if ($o>0 and $o<($nbresultat6-1)) print"&#9500;&#9472;&nbsp;";
									if ($o==($nbresultat6-1)) print"&#9492;&#9472;&nbsp;";
								}
								$sql="SELECT pla_identifiant_local FROM plaque WHERE pla_id_plaque='".$_GET["id2"]."'";
								$resultat5t=$dbh->query($sql);
								$row5t=$resultat5t->fetch(PDO::FETCH_NUM);
								print"&nbsp;&nbsp;";
								$tabdate6=explode("-",$row6[3]);
								$search= array('{','}');
								$row6[5]=str_replace($search,'',$row6[5]);
								if ($row6[9]==0) print"&nbsp;<a href=\"statuspl.php?idpl=$row6[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row6[8]<br/>- ".VOLUME." $row6[4] ".constant($row6[5])."<br/>- ".VOLUMEPRE.$row5t[0].DPOINT." $row6[10]".constant($row6[11])."<br/>- ".DATE." $tabdate6[2]-$tabdate6[1]-$tabdate6[0]')\">$row6[6]</a>";
								else print"&nbsp;<a href=\"statuspl.php?idpl=$row6[0]\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('- ".SOLVANT." $row6[8]<br/>- ".CONCENTRATION." $row6[1] ".MOL."<br/>- ".VOLUME." $row6[4] ".constant($row6[5])."<br/>- ".MASSE." $row[9]".MG."<br/>- ".DATE." $tabdate6[2]-$tabdate6[1]-$tabdate6[0]')\">$row6[6]</a><br/>";
								print"&nbsp;&nbsp;<a href=\"visuplt.php?pltmere=$row6[0]&id=".$_GET["id"]."&id1=".$_GET["id1"]."&id2=".$_GET["id2"]."&i=$i&j=$j&k=".$_GET["k"]."&m=".$_GET["m"]."\"><img src=\"images/lire.gif\" width=\"15\" border=\"0\" alt=\"".VISU."\" title=\"".VISU."\"/></a><a href=\"gestionplaque.php?idmodif=".$row6[0]."\"><img src=\"images/modifier.gif\" width=\"15\" border=\"0\" alt=\"".MOD."\" title=\"".MOD."\"/></a>";
								$sql="SELECT * FROM plaquecible WHERE plac_id_plaque='".$row[0]."'";
								$plaquecible=$dbh->query($sql);
								if ($nbresultat6==0 and $plaquecible->rowCount()==0) echo "<a href=\"gestionplaque.php?idsup=".$row6[0]."\" onclick=\"return(confirm('".SUPCONFIRM.$row[6]."'));\")\"><img src=\"images/pasok.gif\" width=\"15\" border=\"0\" alt=\"".SUP."\" title=\"".SUP."\"/></a>";
								$o++;
							}
							print"</td>\n</tr>";
						}
					}
					else print"</td>\n<td width=\"40%\">&nbsp;</td>\n</tr>";
				}
			}
			else print"</td>\n<td width=\"20%\">&nbsp;</td>\n<td width=\"40%\">&nbsp;</td>\n</tr>";
		}
		else print"</td>\n<td width=\"20%\">&nbsp;</td>\n<td width=\"20%\">&nbsp;</td>\n<td width=\"40%\">&nbsp;</td>\n</tr>";
	}
	print"</table>";
}
else require 'deconnexion.php';
unset($dbh);
?>
