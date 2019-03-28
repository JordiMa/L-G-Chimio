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
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
//test des variables obligatoire pour voir si elles sont pas vides
if ($_POST['masse']=="" || (empty($_POST['couleur']) && $_POST['config_couleur'] == '1') || (empty($_POST['purification']) && $_POST['config_typePurif'] == '1') || (empty($_POST['aspect']) && $_POST['config_aspect'] == '1') || (empty($_POST['ref']) && $_POST['config_refCahier'] == '1') || (empty($_POST['nomiupac']) && $_POST['config_nomenclature'] == '1')) {
	include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';
	$erreur="<p align=\"center\" class=\"erreur\">";
	if ($_POST['masse']=="") {
		$erreur.=CHAMP." ".MASS." ".RENSEIGNE."<br/>";
		$_POST['masse']=" ";
	}
	if ($_POST['couleur']=="-- ".SELECCOULEUR." --" && $_POST['config_couleur'] == '1') $erreur.=CHAMP." ".COULEUR." ".RENSEIGNE."<br/>";
	if ($_POST['purification']=="-- ".SELECPURIFICATION." --" && $_POST['config_typePurif'] == '1') $erreur.=CHAMP." ".PURIFICATION." ".RENSEIGNE."<br/>";
	if ($_POST['aspect']=="-- ".SELECASPECT." --" && $_POST['config_aspect'] == '1') $erreur.=CHAMP." ".ASPECT." ".RENSEIGNE."<br/>";
	if (empty($_POST['ref']) && $_POST['config_refCahier'] == '1') $erreur.=CHAMP." ".REFERENCECAHIER." ".RENSEIGNE."<br/>";
	if (empty($_POST['nomiupac']) && $_POST['config_nomenclature'] == '1') $erreur.=CHAMP." ".NOM." ".RENSEIGNE."<br/>";
	//appelle le fichier de connexion à la base de données
	require 'script/connectionb.php';
	//recherche de solvants sur la table solvant
	$sql="SELECT count(sol_id_solvant) FROM solvant";
	//les résultats sont retournées dans la variable $result
	$result=$dbh->query($sql);
	$pv=0; //variable pour vérifier l"existance de solvant
	while($count=$result->fetch(PDO::FETCH_NUM)) {
		for ($i=0; $i<$count[0]; $i++) {
			if (!empty ($_POST["solvant$i"])) {
				$pv=1;
			}
		}
	}
	//reconcatène la variable équipe dans le cas d'un administrateur ou chef
	$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	$result22=$dbh->query($sql);
	$row22 =$result22->fetch(PDO::FETCH_NUM);
	if ($row22[0]=="{ADMINISTRATEUR}" or $row22[0]=="{CHEF}") {
		$_POST['equipe']=$_POST['equipe']."/".$_POST['responsable']."/".$_POST['chimiste'];
	}
	unset($dbh);
	if ($pv==0 && $_POST['config_solvantsDeSolubilisation'] == '1') $erreur.=CHAMP." ".SOLVANTS." ".RENSEIGNE."<br/>";
	$erreur.="</p>";
	$menu=1;
	include_once 'presentation/entete.php';
	include_once 'presentation/gauche.php';
	include_once 'corps/saisieformulaire2.php';
}
else {
  $transfert=1;
  $menu=1;
  include_once 'presentation/entete.php';
  include_once 'presentation/gauche.php';
  include_once 'corps/traitement.php';
}
include_once 'presentation/pied.php';
?>
