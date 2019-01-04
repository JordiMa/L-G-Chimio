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
require '../script/connectiona.php';

echo "<tr>
    <td bgcolor=\"#FFFFFF\" align=\"center\" valign=\"middle\" width=\"100%\" colspan=\"2\">";

$sql="SELECT chi_statut FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournés dans la variable $result
$resulta =mysql_query($sql);
$row =mysql_fetch_row($resulta);
$i=0;
if ($row[0]=='ADMINISTRATEUR') {
	if ($_POST["ver"]=='1.2.1') {
		switch ($_POST["etape"]) {
			case 0: {
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_cas'";
				$result3_1=mysql_query($sql);
				if (mysql_num_rows($result3_1)>0){
					$sql="ALTER TABLE produit CHANGE `pro_cas` `pro_cas` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
					$resultat3_1_1=mysql_query($sql);
					if(!$resultat3_1_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_cas' dans la table 'produit'</p>";
					else {
						echo "<p>Modification du champ 'pro_cas' dans la table 'produit' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}
				}
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("1","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_button ("Mettre à jour étape N°2","","submit","");
				$formulaire->fin();
			}
			break;	
			case 1: {
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_doi'";
				$result3_2=mysql_query($sql);
				if (mysql_num_rows($result3_2)>0){
					$sql="ALTER TABLE produit CHANGE `pro_doi` `pro_doi` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
					$resultat3_2_1=mysql_query($sql);
					if(!$resultat3_2_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_doi' dans la table 'produit'</p>";
					else {
						echo "<p>Modification du champ 'pro_doi' dans la table 'produit' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}	
				}
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("2","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_button ("Mettre à jour étape N°3","","submit","");
				$formulaire->fin();
			}
			break;
			case 2: {
				$sql="SHOW COLUMNS FROM chimiste LIKE 'chi_id_responsable'";
				$result3_3=mysql_query($sql);
				if (mysql_num_rows($result3_3)>0){
					$sql="ALTER TABLE chimiste CHANGE `chi_id_responsable` `chi_id_responsable` SMALLINT(6) UNSIGNED NOT NULL";
					$resultat3_3_1=mysql_query($sql);
					if(!$resultat3_3_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'chi_id_responsable' dans la table 'chimiste'</p>";
					else {
						echo "<p>Modification du champ 'chi_id_responsable' dans la table 'chimiste' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}
				}
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("3","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_button ("Mettre à jour étape N°4","","submit","");
				$formulaire->fin();
			}
			break;
			case 3: {
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_etape_mol'";
				$result4_1=mysql_query($sql);
				if (!mysql_num_rows($result4_1)>0) {
					$sql="ALTER TABLE `produit` ADD `pro_etape_mol` SET( 'INTERMEDIAIRE', 'FINALE', 'AUCUNE','INCONNUE' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'INCONNUE' AFTER `pro_id_structure`";
					$resultat4_1_1=mysql_query($sql);
					if(!$resultat4_1_1) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_etape_mol' dans la table 'produit'</p>";
					else {
						echo "<p>Intégration du champ 'pro_etape_mol' dans la table 'produit' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}
				}
				else {
					echo "<p>Le champ 'pro_etape_mol' dans la table 'produit' existe déjà</p>";
					$_POST["j"]=$_POST["j"]+1;
				}
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("4","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_button ("Mettre à jour étape N°5","","submit","");
				$formulaire->fin();
			}
			break;
			case 4: {
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_qrcode'";
				$result4_2=mysql_query($sql);
				if (!mysql_num_rows($result4_2)>0) {
					$sql="ALTER TABLE `produit` ADD `pro_qrcode` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `pro_num_constant`";
					$resultat4_2_1=mysql_query($sql);
					if(!$resultat4_2_1) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_qrcode' dans la table 'produit'</p>";
					else {
						echo "<p>Intégration du champ 'pro_qrcode' dans la table 'produit' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}
				}
				else {
					echo "<p>Le champ 'pro_qrcode' dans la table 'produit' existe déjà</p>";
					$_POST["j"]=$_POST["j"]+1;
				}
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("5","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_button ("Mettre à jour étape N°6","","submit","");
				$formulaire->fin();
			}
			break;
			case 5: {
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_id_responsable'";
				$result3_4=mysql_query($sql);
				if (!mysql_num_rows($result3_4)>0) {
					$sql="ALTER TABLE `produit` ADD `pro_id_responsable` SMALLINT(6) UNSIGNED NOT NULL AFTER `pro_id_equipe`";
					$resultat3_4_1=mysql_query($sql);
					if(!$resultat3_4_1) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_id_responsable' dans la table 'produit'</p>";
					else {
						echo "<p>Intégration du champ 'pro_id_responsable' dans la table 'produit' a été effectuée</p>";
						$_POST["j"]=$_POST["j"]+1;
					}
				}
				else {
					echo "<p>Le champ 'pro_id_responsable' dans la table 'produit' existe déjà</p>";
					$_POST["j"]=$_POST["j"]+1;
				}
				$sql="SELECT pro_id_equipe,pro_id_chimiste,pro_id_produit FROM produit where pro_id_responsable='0'";
				$result3_5=mysql_query($sql);
				$nbresult3_5=mysql_num_rows($result3_5);
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.2.1","ver");
				$formulaire->ajout_cache ("6","etape");
				$formulaire->ajout_cache ($_POST["j"],"j");
				$formulaire->ajout_cache ($nbresult3_5,"nb");
				$formulaire->ajout_cache ("0","debut");
				$formulaire->ajout_cache (round($nbresult3_5/10),"moitie");
				$formulaire->ajout_cache ("1","etapemod");
				$formulaire->ajout_button ("Mettre à jour étape N°7 sous partie N°1/10","","submit","");
				$formulaire->fin();
			}
			break;
			case 6: {	
				$u=0;
				$sql="SELECT pro_id_equipe,pro_id_chimiste,pro_id_produit FROM produit where pro_id_responsable='0' LIMIT ".$_POST["debut"]." , ".$_POST["moitie"]."";
				$result3_5=mysql_query($sql);
				$nbresult3_5=mysql_num_rows($result3_5);
				if ($nbresult3_5>0) {
					while ($row3_5=mysql_fetch_row($result3_5)) {
						$sql="SELECT chi_statut FROM chimiste WHERE chi_id_chimiste='".$row3_5[1]."'";
						$result3_5_1=mysql_query($sql);
						while ($row3_5_1=mysql_fetch_row($result3_5_1)) {
							$sql="SELECT chi_id_responsable FROM chimiste WHERE chi_id_chimiste='".$row3_5[1]."' and chi_id_equipe='".$row3_5[0]."' and chi_statut='".$row3_5_1[0]."'";
							$result3_5_2=mysql_query($sql);
							if (mysql_num_rows($result3_5_2)>0) {
								while ($row3_5_2=mysql_fetch_row($result3_5_2)) {
									$sql="UPDATE produit SET pro_id_responsable='".$row3_5_2[0]."' WHERE pro_id_produit='".$row3_5[2]."'";
									$resultat3_5_3=mysql_query($sql);
									$u++;
								}	
							}
							else {
								$sql="SELECT chi_id_chimiste FROM chimiste WHERE chi_id_equipe='".$row3_5[0]."' and chi_statut='RESPONSABLE'";
								$result3_5_4=mysql_query($sql);
								if (mysql_num_rows($result3_5_4)>0) {
									$row3_5_4=mysql_fetch_row($result3_5_4);
									$sql="UPDATE produit SET pro_id_responsable='".$row3_5_4[0]."' WHERE pro_id_produit='".$row3_5[2]."'";
									$resultat3_5_5=mysql_query($sql);
									$u++;
								}
							}			
						}
					}
				}
				if ($u==$nbresult3_5) {
					echo "<p>Modification du champ 'pro_id_responsable' dans la table 'produit' pour chaque entrée : étape N°".$_POST["etapemod"]." sur 10</p>";
					if ($_POST["etapemod"]==10) $_POST["j"]=$_POST["j"]+1;
				}
				if ($_POST["etapemod"] <10) {
					$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
					$formulaire->affiche_formulaire();
					$formulaire->ajout_cache ("1.2.1","ver");
					$formulaire->ajout_cache ("6","etape");
					$formulaire->ajout_cache ($_POST["j"],"j");
					$formulaire->ajout_cache ($_POST["nb"],"nb");
					$formulaire->ajout_cache ("0","debut");
					$formulaire->ajout_cache ($_POST["moitie"],"moitie");
					$formulaire->ajout_cache ($_POST["etapemod"]+1,"etapemod");
					$formulaire->ajout_button ("Mettre à jour étape N°7 sous partie N°".($_POST["etapemod"]+1)."/10","","submit","");
					$formulaire->fin();
				}
				if ($_POST["etapemod"]==10) {
					$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
					$formulaire->affiche_formulaire();
					$formulaire->ajout_cache ("1.2.1","ver");
					$formulaire->ajout_cache ("7","etape");
					$formulaire->ajout_cache ($_POST["j"],"j");
					$formulaire->ajout_button ("Mettre à jour étape N°8","","submit","");
					$formulaire->fin();
				}
			}
			break;
			case 7: {
				$sql="ALTER TABLE `produit` ADD INDEX ( `pro_id_responsable` )";
				$result5_1=mysql_query($sql);
				if(!$result5_1) echo "<p class=\"h3erreur\">Echec de l'indexation du champ 'pro_id_responsable' dans la table 'produit'</p>";
				else {
					echo "<p>Indexation du champ 'pro_id_responsable' dans la table 'produit' a été effectuée</p>";
					$_POST["j"]=$_POST["j"]+1;
				}
			}
			break;
		}
		
		if ($_POST["j"]==8 and $_POST["etape"]==7) {
			echo "<br><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.2.1</i> vers la version 1.3 a réussi</h3>";
			$sql="UPDATE parametres SET para_version='1.3'";
			$resultat5=mysql_query($sql);
			echo "<form method=\"post\" action=\"session.php\"><input type=\"submit\" value=\"Retour\"></form>";	
			}
		elseif ($_POST["j"]<>8 and $_POST["etape"]==7) {
			echo "<br><br><br><h3 class=\"h3erreur\">Echec de la mise à jour 1.3</h3>";
			echo "<form method=\"post\" action=\"session.php\"><input type=\"submit\" value=\"Retour\"></form>";	
		}
	}
}
else 
{
	session_destroy();
	unset($_SESSION);
	include_once 'index.php';
}
mysql_close();
?>