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
	if ($_POST["ver"]==1.1) {
		$sql="SHOW COLUMNS FROM parametres LIKE 'para_version'";
		$result=mysql_query($sql);
		if (!mysql_num_rows($result)>0) {
			$sql="ALTER TABLE `parametres` ADD `para_version` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat=mysql_query($sql);
			if(!$resultat) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'para_version' dans la table 'parametres'</p>";
			else {
				echo "<p>Intégration du champ 'para_version' dans la table 'parametres'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'para_version' dans la table 'parametres' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM parametres LIKE 'para_origin_defaut'";
		$result1=mysql_query($sql);
		if (!mysql_num_rows($result1)>0) {
			$sql="ALTER TABLE `parametres` ADD `para_origin_defaut` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat1=mysql_query($sql);
			if(!$resultat1) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'para_origin_defaut' dans la table 'parametres'</p>";
			else {
				echo "<p>Intégration du champ 'para_origin_defaut' dans la table 'parametres'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'para_origin_defaut' dans la table 'parametres' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_methode_purete'";
		$result2=mysql_query($sql);
		if (!mysql_num_rows($result2)>0) {
			$sql="ALTER TABLE `produit` ADD `pro_methode_purete` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2=mysql_query($sql);
			if(!$resultat2) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_methode_purete' dans la table 'produit'</p>";
			else {
				echo "<p>Intégration du champ 'pro_methode_purete' dans la table 'produit'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pro_methode_purete' dans la table 'produit' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_num_cn'";
		$result3=mysql_query($sql);
		if (!mysql_num_rows($result3)>0) {
			$sql="ALTER TABLE `produit` ADD `pro_num_cn` VARCHAR( 9 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat3=mysql_query($sql);
			if(!$resultat3) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_num_cn' dans la table 'produit'</p>";
			else {
				echo "<p>Intégration du champ 'pro_num_cn' dans la table 'produit'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pro_num_cn' dans la table 'produit' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_origine_substance'";
		$result4=mysql_query($sql);
		if (!mysql_num_rows($result4)>0) {	
			$sql="ALTER TABLE `produit` ADD `pro_origine_substance` SET( 'SYNTHESE', 'HEMISYNTHESE', 'NATURELLE', 'INCONNU' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat4=mysql_query($sql);
			if(!$resultat4) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_origine_substance' dans la table 'produit'</p>";
			else {
				echo "<p>Intégration du champ 'pro_origine_substance' dans la table 'produit'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pro_origine_substance' dans la table 'produit' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_tare_pilulier'";
		$result5=mysql_query($sql);
		if (!mysql_num_rows($result5)>0) {
			$sql="ALTER TABLE `produit` ADD `pro_tare_pilulier` DOUBLE NOT NULL";
			$resultat5=mysql_query($sql);
			if(!$resultat5) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_tare_pilulier' dans la table 'produit'</p>";
			else {
				echo "<p>Intégration du champ 'pro_tare_pilulier' dans la table 'produit'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pro_tare_pilulier' dans la table 'produit' existe déjà</p>";
			$i++;
		}
			
		$sql="ALTER TABLE `produit` CHANGE `pro_purete` `pro_purete` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''";
		$resultat6=mysql_query($sql);
		if(!$resultat6) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_purete' dans la table 'produit'</p>";
		else {
			echo "<p>Modification du champ 'pro_purete' dans la table 'produit'</p>";
			$i++;
		}

		$sql="SHOW COLUMNS FROM position LIKE 'pos_mass_prod'";
		$result6=mysql_query($sql);
		if (!mysql_num_rows($result6)>0) {
			$sql="ALTER TABLE `position` ADD `pos_mass_prod` DOUBLE NOT NULL";
			$resultat7=mysql_query($sql);
			if(!$resultat7) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pos_mass_prod' dans la table 'position'</p>";
			else {
				echo "<p>Intégration du champ 'pos_mass_prod' dans la table 'position'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pos_mass_prod' dans la table 'position' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM plaque LIKE 'pla_vol_preleve'";
		$result7=mysql_query($sql);
		if (!mysql_num_rows($result7)>0) {	
			$sql="ALTER TABLE `plaque` ADD `pla_vol_preleve` DOUBLE NOT NULL";
			$resultat8=mysql_query($sql);
			if(!$resultat8) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pla_vol_preleve' dans la table 'plaque'</p>";
			else {
				echo "<p>Intégration du champ 'pla_vol_preleve' dans la table 'plaque'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pla_vol_preleve' dans la table 'plaque' existe déjà</p>";
			$i++;
		}
			
		$sql="SHOW COLUMNS FROM plaque LIKE 'pla_unit_vol_preleve'";
		$result8=mysql_query($sql);
		if (!mysql_num_rows($result8)>0) {
			$sql="ALTER TABLE `plaque` ADD `pla_unit_vol_preleve` SET( 'ML', 'MIL' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat9=mysql_query($sql);
			if(!$resultat9) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pla_unit_vol_preleve' dans la table 'plaque'</p>";
			else {
				echo "<p>Intégration du champ 'pla_unit_vol_preleve' dans la table 'plaque'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'pla_unit_vol_preleve' dans la table 'plaque' existe déjà</p>";
			$i++;
		}
		
		$sql="SHOW COLUMNS FROM resultat LIKE 'res_resultat_pourcentageinhi'";
		$result9=mysql_query($sql);
		if (!mysql_num_rows($result9)>0) {
			$sql="ALTER TABLE `resultat` ADD `res_resultat_pourcentageinhi` DOUBLE NOT NULL";
			$resultat11=mysql_query($sql);
			if(!$resultat11) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'res_resultat_pourcentageinhi' dans la table 'resultat'</p>";
			else {
				echo "<p>Intégration du champ 'res_resultat_pourcentageinhi' dans la table 'resultat'</p>";
				$i++;
			}
		}
		else {
			echo "<p>Le champ 'res_resultat_pourcentageinhi' dans la table 'resultat' existe déjà</p>";
			$i++;
		}
			
		$sql="ALTER TABLE `equipe` CHANGE `equi_initiale_numero` `equi_initiale_numero` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''";
		$resultat10=mysql_query($sql);
		if(!$resultat10) echo "<p class=\"h3erreur\">Echec de la modification du champ 'equi_initiale_numero' dans la table 'equipe'</p>";
		else {
			echo "<p>Modification du champ 'equi_initiale_numero' dans la table 'equipe'</p>";
			$i++;
		}
		if ($i==12) echo "<br><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.1</i> vers la version 1.2 a réussi<br></h3>";
		else echo "<br><br><br><h3  class=\"h3erreur\">Echec de la mise à jour vers la version 1.2</h3>";
	}	
	if ($_POST["ver"]=='1.1' or $_POST["ver"]=='1.2') {
		$y=0;
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_apha_concentration'";
		$result2_1=mysql_query($sql);
		if (mysql_num_rows($result2_1)>0) {
			$sql="ALTER TABLE produit CHANGE `pro_apha_concentration` `pro_apha_concentration` DECIMAL( 5, 1 ) UNSIGNED NOT NULL";
			$resultat2_1_1=mysql_query($sql);
			if(!$resultat2_1_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_apha_concentration' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_apha_concentration' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_rmnh_fichier_nom'";
		$result2_2=mysql_query($sql);
		if (mysql_num_rows($result2_2)>0){
			$sql="ALTER TABLE produit CHANGE `pro_rmnh_fichier_nom` `pro_rmnh_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_2_1=mysql_query($sql);
			if(!$resultat2_2_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_rmnh_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_rmnh_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_rmnc_fichier_nom'";
		$result2_3=mysql_query($sql);
		if (mysql_num_rows($result2_3)>0){
			$sql="ALTER TABLE produit CHANGE `pro_rmnc_fichier_nom` `pro_rmnc_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_3_1=mysql_query($sql);
			if(!$resultat2_3_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_rmnc_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_rmnc_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_ir_fichier_nom'";
		$result2_4=mysql_query($sql);
		if (mysql_num_rows($result2_4)>0){
			$sql="ALTER TABLE produit CHANGE `pro_ir_fichier_nom` `pro_ir_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_4_1=mysql_query($sql);
			if(!$resultat2_4_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_ir_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_ir_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_sm_fichier_nom'";
		$result2_5=mysql_query($sql);
		if (mysql_num_rows($result2_5)>0){
			$sql="ALTER TABLE produit CHANGE `pro_sm_fichier_nom` `pro_sm_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_5_1=mysql_query($sql);
			if(!$resultat2_5_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_sm_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_sm_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_hrms_fichier_nom'";
		$result2_6=mysql_query($sql);
		if (mysql_num_rows($result2_6)>0){
			$sql="ALTER TABLE produit CHANGE `pro_hrms_fichier_nom` `pro_hrms_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_6_1=mysql_query($sql);
			if(!$resultat2_6_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_hrms_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_hrms_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_uv_fichier_nom'";
		$result2_7=mysql_query($sql);
		if (mysql_num_rows($result2_7)>0){
			$sql="ALTER TABLE produit CHANGE `pro_uv_fichier_nom` `pro_uv_fichier_nom` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat2_7_1=mysql_query($sql);
			if(!$resultat2_7_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_uv_fichier_nom' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_uv_fichier_nom' dans la table 'produit'</p>";
				$y++;
			}
		}
		
		$sql="ALTER TABLE `produit` ADD UNIQUE (`pro_num_constant`)";
		$resultat2_8=mysql_query($sql);
		if(!$resultat2_8) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_num_constant' dans la table 'produit'</p>";
		else {
			echo "<p>Modification du champ 'pro_num_constant' dans la table 'produit'</p>";
			$y++;
		}
		
		$sql="ALTER TABLE `produit` ADD UNIQUE (`pro_numero`)";
		$resultat2_9=mysql_query($sql);
		if(!$resultat2_9) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_numero' dans la table 'produit'</p>";
		else {
			echo "<p>Modification du champ 'pro_numero' dans la table 'produit'</p>";
			$y++;
		}
		
		$sql="SHOW COLUMNS FROM structure LIKE 'str_inchi_md5'";
		$resultat2_10=mysql_query($sql);
		$rowmd5=mysql_fetch_row($resultat2_10);
		if($rowmd5[3]=="UNI") {
			$y++;
			echo "<p>champ 'str_inchi_md5' dans la table 'structure' déjà à jour</p>";
		}	
		else {
			$sql="ALTER TABLE `structure` ADD UNIQUE (`str_inchi_md5`)";
			$resultat2_10_1=mysql_query($sql);
			if(!$resultat2_10_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'str_inchi_md5' dans la table 'structure'</p>";
			else {
				echo "<p>Modification du champ 'str_inchi_md5' dans la table 'structure'</p>";
				$y++;
			}
		}
		
		$sql="UPDATE `parametres` SET `para_version` = '1.2.1'";
		$resultat2_11=mysql_query($sql);
		if(!$resultat2_11) echo "<p class=\"h3erreur\">Echec de la modification du champ 'para_version' dans la table 'parametres'</p>";
		else {
			echo "<p>Modification du champ 'para_version' dans la table 'parametres'</p>";
			$y++;
		}
		
		if ($y==11) {
			echo "<br><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.2</i> vers la version 1.2.1 a réussi</h3>";
			echo "<form method=\"post\" action=\"session.php\"><input type=\"submit\" value=\"Retour\"></form>";
		}
		else {
			echo "<br><br><br><h3 class=\"h3erreur\">Echec de la mise à jour 1.2.1</h3>";
			echo "<form method=\"post\" action=\"session.php\"><input type=\"submit\" value=\"Retour\"></form>";
		}
	}
	if ($_POST["ver"]=='1.2.1') {
		$j=0;
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_cas'";
		$result3_1=mysql_query($sql);
		if (mysql_num_rows($result3_1)>0){
			$sql="ALTER TABLE produit CHANGE `pro_cas` `pro_cas` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat3_1_1=mysql_query($sql);
			if(!$resultat3_1_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_cas' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_cas' dans la table 'produit'</p>";
				$j++;
			}
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_doi'";
		$result3_2=mysql_query($sql);
		if (mysql_num_rows($result3_2)>0){
			$sql="ALTER TABLE produit CHANGE `pro_doi` `pro_doi` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
			$resultat3_2_1=mysql_query($sql);
			if(!$resultat3_2_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_doi' dans la table 'produit'</p>";
			else {
				echo "<p>Modification du champ 'pro_doi' dans la table 'produit'</p>";
				$j++;
			}
		}
		
		$sql="SHOW COLUMNS FROM chimiste LIKE 'chi_id_responsable'";
		$result3_3=mysql_query($sql);
		if (mysql_num_rows($result3_2)>0){
			$sql="ALTER TABLE chimiste CHANGE `chi_id_responsable` `chi_id_responsable` SMALLINT(6) UNSIGNED NOT NULL";
			$resultat3_3_1=mysql_query($sql);
			if(!$resultat3_3_1) echo "<p class=\"h3erreur\">Echec de la modification du champ 'chi_id_responsable' dans la table 'chimiste'</p>";
			else {
				echo "<p>Modification du champ 'chi_id_responsable' dans la table 'chimiste'</p>";
				$j++;
			}
		}
		
		$sql="SHOW COLUMNS FROM produit LIKE 'pro_id_responsable'";
		$result3_4=mysql_query($sql);
		if (!mysql_num_rows($result3_4)>0) {
			$sql="ALTER TABLE `produit` ADD `pro_id_responsable` SMALLINT(6) UNSIGNED NOT NULL AFTER `pro_id_equipe`";
			$resultat3_4_1=mysql_query($sql);
			if(!$resultat3_4_1) echo "<p class=\"h3erreur\">Echec de l'intégration du champ 'pro_id_responsable' dans la table 'produit'</p>";
			else {
				echo "<p>Intégration du champ 'pro_id_responsable' dans la table 'produit'</p>";
				$j++;
			}
		}
		else {
			echo "<p>Le champ 'pro_id_responsable' dans la table 'produit' existe déjà</p>";
			$j++;
		}
		
		do {
			$u=0;
			$sql="SELECT pro_id_equipe,pro_id_chimiste,pro_id_produit FROM produit where pro_id_responsable='0'";
			$result3_5=mysql_query($sql);
			$nbresult3_5=mysql_num_rows($result3_5);
			if ($nbresult3_5>0) {
				while ($row3_5=mysql_fetch_row($result3_5)) {
					$sql="SELECT chi_statut FROM chimiste WHERE chi_id_chimiste='".$row3_5[1]."'";
					$result3_5_1=mysql_query($sql);
					while ($row3_5_1=mysql_fetch_row($result3_5_1)) {
						$sql="SELECT chi_id_responsable FROM chimiste WHERE chi_id_chimiste='".$row3_5[1]."' and chi_id_equipe='".$row3_5[0]."' and chi_statut='".$row3_5_1[0]."'";
						$result3_5_2=mysql_query($sql);
						if (!mysql_num_rows($result3_5_2)>0) {
							while ($row3_5_2=mysql_fetch_row($result3_5_2)) {
								$sql="UPDATE produit SET pro_id_responsable='".$row3_5_2[0]."' WHERE pro_id_produit='".$row3_5[2]."'";
								$resultat3_5_3=mysql_query($sql);
								$u++;
							}	
						}
						else {
							$sql="SELECT chi_id_chimiste FROM chimiste WHERE chi_id_equipe='".$row3_5[0]."' and chi_statut='RESPONSABLE'";
							$result3_5_4=mysql_query($sql);
							if (!mysql_num_rows($result3_5_4)>0) {
								$row3_5_4=mysql_fetch_row($result3_5_4);
								$sql="UPDATE produit SET pro_id_responsable='".$row3_5_4[0]."' WHERE pro_id_produit='".$row3_5[2]."'";
								$resultat3_5_5=mysql_query($sql);
								$u++;
							}
						}			
					}
				}
			}
		}
		while ($nbresult3_5>0);
		if ($u==$nbresult3_5) {
			echo "<p>Modification du champ 'pro_id_responsable' dans la table 'produit' pour chaque entrée</p>";
			$j++;
		}
		if ($y==5) {
			echo "<br><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.2.1</i> vers la version 1.2.2 a réussi</h3>";
			echo "<form method=\"post\" action=\"session.php\"><input type=\"submit\" value=\"Retour\"></form>";
		}
		else {
			echo "<br><br><br><h3 class=\"h3erreur\">Echec de la mise à jour 1.2.2</h3>";
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