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
$text="<?php\n";
$text.="if (!defined ('MYSQLSERVEURCHI')) define ('MYSQLSERVEURCHI','".$mysqlserveurchi."');\n";
$text.="if (!defined ('MYSQLLOGINCHI')) define ('MYSQLLOGINCHI','".$mysqlloginchi."');\n";
$text.="if (!defined ('MYSQLPASSCHI')) define ('MYSQLPASSCHI','".$mysqlpasschi."');\n";
$text.="if (!defined ('MYSQLBDCHI')) define ('MYSQLBDCHI','".$mysqlbdchi."');\n";
$text.="try {
		\$db = new PDO('mysql:host='.MYSQLSERVEURCHI.';dbname='.MYSQLBDCHI.'', MYSQLLOGINCHI, MYSQLPASSCHI);
		\$db->exec(\"SET CHARACTER SET utf8\");
		} catch (PDOException \$excep) {
			print \" Error! : \".\$excep->getMessage().\"<br />\";
			die();
		}";
$text.="\n?>";
if($fp=fopen(REPEPRINCIPAL."script/connectiona.php","w+")) {
	fwrite($fp,$text);
	fclose($fp); 
	print"<p align=\"center\"><br/><b>Le fichier connectiona.php a été modifié dans le répertoire ".REPEPRINCIPAL."script</b></p>";
	chmod(REPEPRINCIPAL."script/connectiona.php",0444);
	$okfichier1=1;
}
else {
	print"<p align=\"center\"><br/><b>fichier connectiona.php impossible à modifier dans le répertoire ".REPEPRINCIPAL."script</b></p>";
	$okfichier1=0;
}
unset($db);

require '../script/connectiona.php';


$sql="SELECT chi_statut FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournés dans la variable $result
$resulta =$db->query($sql);
$row =$resulta->fetch(PDO::FETCH_NUM);
$i=0;
$presenceindex=0;
if ($row[0]=='ADMINISTRATEUR') {
	
	$sql="SHOW INDEX FROM produit";
	$resultatindex=$db->query($sql);
	while ($rowindex=$resultatindex->fetch(PDO::FETCH_ASSOC)) {
		if ($rowindex['Key_name']=='pro_qrcode') $presenceindex=1;
	}
	if ($presenceindex==1) {
		$sql="ALTER TABLE `produit` DROP INDEX `pro_qrcode`;";
		$resultat=$db->exec($sql);
		if($resultat!==false) {
			echo "<p>Modification du champ 'pro_qrcode' dans la table 'produit' pour enlever l'index unique</p>";
			$i++;
		}
		else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_qrcode' dans la table 'produit'</p>";
	}
	else {
		$i++;
		echo "<p>Modification du champ 'pro_qrcode' dans la table 'produit' pour enlever l'index unique n'est pas nécessaire</p>";
	}
	 
	$sql="ALTER TABLE `produit` CHANGE `pro_num_brevet` `pro_num_brevet` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';";
	$resultat1=$db->exec($sql);
	if($resultat1!==false) {
		echo "<p>Modification du champ 'pro_num_brevet' dans la table 'produit'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_num_brevet' dans la table 'produit'</p>";
	
	$sql="ALTER TABLE `produit` CHANGE `pro_origine_substance` `pro_origine_substance` SET( 'SYNTHESE', 'HEMISYNTHESE', 'NATURELLE', 'INCONNU' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'INCONNU';";
	$resultat2=$db->exec($sql);
	if($resultat2!==false) {
		echo "<p>Modification du champ 'pro_origine_substance' dans la table 'produit' avec l'ajout d'une valeur par défaut</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_origine_substance' dans la table 'produit'</p>";
	
	$sql="SHOW COLUMNS FROM produit LIKE 'pro_unite_masse'";
	$resultat31=$db->query($sql);
	$nbresultat31=$resultat31->rowCount();
	if($nbresultat31==0) {
		$sql="ALTER TABLE `produit` ADD `pro_unite_masse` ENUM( 'MG', 'NMOL' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `pro_masse`;";
		$resultat3=$db->exec($sql);
		if($resultat3!==false) {
			echo "<p>Ajout du champ 'pro_unite_masse' dans la table 'produit' avec l'ajout d'une valeur par défaut</p>";
			$i++;
		}
		else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_unite_masse' dans la table 'produit'</p>";
	}
	else {
		$row31=$resultat31->fetch(PDO::FETCH_NUM);
		if ($row31[1]=="enum('MG','NMOL')") {
			echo "<p>Le champ 'pro_unite_masse' dans la table 'produit' existe déjà avec la bonne configuration</p>";
			$i++;
		}
		else {
			$sql="ALTER TABLE `produit` CHANGE `pro_unite_masse` ENUM( 'MG', 'NMOL' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `pro_masse`;";
			$resultat3=$db->exec($sql);
			if($resultat3!==false) {
				echo "<p>Ajout du champ 'pro_unite_masse' dans la table 'produit' avec l'ajout d'une valeur par défaut</p>";
				$i++;
			}
			else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_unite_masse' dans la table 'produit'</p>";
		}
	}		
	
	$sql="ALTER TABLE `produit` CHANGE `pro_qrcode` `pro_qrcode` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
	$resultat4=$db->exec($sql);
	if($resultat4!==false) {
		echo "<p>Modification du champ 'pro_qrcode' dans la table 'produit'</p>";
		$i++;
	}
	else echo "<p class=\"h3erreur\">Echec de la modification du champ 'pro_qrcode' dans la table 'produit' pour passer en Varchar 256</p>";
		
	if ($i==5) {
		echo "<br/><h3>La mise à jour de la base de données de L-g-<i>Chimio V1.3</i> vers la version 1.3.1 a réussi</h3><br/><br/><br/><br/><h3 class=\"h3erreur\">Vérifiez que le fichier ".REPEPRINCIPAL."script/connectiona.php est bien en lecture seule</h3>";
		$sql="UPDATE `parametres` SET `para_version` = '1.3.1'";
		$resultat5=$db->exec($sql);
		if($resultat5!==false) {
			echo "<p>Modification du champ 'para_version' dans la table 'parametres'</p>";
			$i++;
		}
		else echo "<p class=\"h3erreur\">Echec de la modification du champ 'para_version' dans la table 'parametres'</p>";
		echo "<form method=\"post\" action=\"sessionpdo.php\"><input type=\"submit\" value=\"Retour\"></form>";
	}
	else {
		echo "<br/><br/><br/><h3 class=\"h3erreur\">Echec de la mise à jour 1.3.1</h3>";
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