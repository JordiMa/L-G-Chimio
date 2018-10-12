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
	if (empty($_POST['nom'])) $erreur=NOMMANQUE;
	if (empty($_POST['acronyme'])) $erreur=ACROMANQUE;
	if (empty($_POST['email'])) $erreur=MAILMANQUE;
	if (!empty($_FILES['logo']['tmp_name']) and !$_FILES['logo']['error']) {
		if ($_FILES['logo']['type']!='image/pjpeg' and $_FILES['logo']['type']!='image/jpeg' and $_FILES['logo']['type']!='image/jpg' and $_FILES['logo']['type']!='image/gif' and $_FILES['logo']['type']!='image/x-xbitmap' and $_FILES['logo']['type']!='image/x-png' and $_FILES['logo']['type']!='image/png') $erreur=MIMEERREUR;
		elseif ($_FILES['logo']['size']>10000) $erreur=TAILLEERREUR;
	}
	if (!isset($erreur)) {
		$sql="UPDATE parametres SET para_nom_labo='".$_POST['nom']."', para_acronyme='".$_POST['acronyme']."', para_email_envoie='".$_POST['email']."'";
		$update=$dbh->exec($sql);
		if (!empty($_FILES['logo']['tmp_name']) and !$_FILES['logo']['error']) {
			//Suppression de l'ancien fichier logo
			$mask = REPTEMP."logo.*";
			array_map( "unlink", glob( $mask ) );
			
			$extension_fichier=preg_split("/\./",$_FILES['logo']['name']);
			$path="temp/logo.".$extension_fichier[1];
			//sauvegarde du logo dans le repertoire /temp
			move_uploaded_file($_FILES['logo']['tmp_name'], $path);
			$sql="UPDATE parametres SET para_logo ='$path'";
			$update1=$dbh->exec($sql);
		}
	}

	$menu=11;
	$ssmenu=10;
	if (!isset($erreur)) $transfert=true;
	include_once 'presentation/entete.php';
	include_once 'presentation/gauche.php';
	if (isset($erreur)) include_once 'formulaireparametre.php';
	elseif (isset($_POST['vide'])) print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".VIDETEMP."</p>";
	else print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
}
else require 'deconnexion.php';
if (!isset($erreur)) unset($dbh);
include_once 'presentation/pied.php';
?>