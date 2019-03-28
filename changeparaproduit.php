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
include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_parametre.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	if ($_POST['formu']==1) {
		$sql="SELECT para_stock,para_origin_defaut FROM parametres";
		$parabd=$dbh->query($sql);
		$row =$parabd->fetch(PDO::FETCH_NUM);
		if ($_POST['massestock']>=1) {
			if ($row[0]!=$_POST['massestock']) {
				$sql="UPDATE parametres SET para_stock='".$_POST['massestock']."'";
				$update =$dbh->exec($sql);
			}
        }
		else $erreur=MASSENOT;
		if (isset($_POST['origmol']) and $_POST['origmol']!=$row[1]) {
			$sql="UPDATE parametres SET para_origin_defaut='".$_POST['origmol']."'";
			$update =$dbh->exec($sql);
		}
	}
	if ($_POST['formu']==2) {
		if ($_POST['choixnum']=="AUTO") {

			$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_numtype'";
			//les résultats sont retournées dans la variable $result3
			$result3=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row3=$result3->fetch(PDO::FETCH_NUM);
			$traitement=new traitement_requete_sql($row3[0]);
			$tab3=$traitement->imprime();
      
			$sql="DELETE FROM numerotation";
			$delete=$dbh->exec($sql);
      
			for($i=1;$i<=(count($tab3))*2;$i++) {
				if (!isset($_POST["num$i"])) $_POST["num$i"]="";
				if ($_POST["num$i"]!="") {
					if ($_POST["num$i"]=="-" or $_POST["num$i"]=="/" or $_POST["num$i"]=="(" or $_POST["num$i"]==")") $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type,num_valeur) VALUES ('$i','1','{FIXE}','".$_POST["num$i"]."')";
					elseif ($_POST["num$i"]=="FIXE") $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type,num_valeur) VALUES ('$i','1','{".$_POST["num$i"]."}','".$_POST["textfixe$i"]."')";
					else $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type) VALUES ('$i','1','{".$_POST["num$i"]."}')";
					$update=$dbh->exec($sql);
				}
			}
      
			//copie du tab3 en enlevant les valeurs : Numéro de la boite, Coordonnées dans la boite
			foreach ($tab3 as $key=>$elem) {
				if ($key!="COORDONEE" and $key!="BOITE" ) $tab5[$key]=$elem;
			}
      
			for($i=1;$i<=(count($tab5))*2;$i++) {
				if (!isset($_POST["numvir$i"])) $_POST["numvir$i"]="";
				if ($_POST["numvir$i"]!="") {
					if ($_POST["numvir$i"]=="-" or $_POST["numvir$i"]=="/" or $_POST["numvir$i"]=="(" or $_POST["numvir$i"]==")") $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type,num_valeur) VALUES ('$i','2','{FIXE}','".$_POST["numvir$i"]."')";
					elseif ($_POST["numvir$i"]=="FIXE") $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type,num_valeur) VALUES ('$i','2','{".$_POST["numvir$i"]."}','".$_POST["textfixevir$i"]."')";
					else $sql="INSERT INTO numerotation (num_id_numero,num_parametre,num_type) VALUES ('$i','2','{".$_POST["numvir$i"]."}')";
					$update=$dbh->exec($sql);
				}
			}
		}
	}
	$menu=11;
	$ssmenu=12;
	if (!isset($erreur)) $transfert=true;
	include_once 'presentation/entete.php';
	include_once 'presentation/gauche.php';
	if (isset($erreur)) include_once 'corps/formulaireparaproduit.php';
	elseif (isset($update) and $update>0) print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
	elseif (isset($_POST['vide'])) print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".VIDETEMP."</p>";
}
else require 'deconnexion.php';
unset($dbh);
include_once 'presentation/pied.php';
?>