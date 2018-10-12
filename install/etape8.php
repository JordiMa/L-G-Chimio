<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique - ICOA UMR7311
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
include_once '../langues/fr/presentation.php';
include_once 'entete.html';
require '../script/administrateur.php';
require '../script/connectionb.php';

$erreur="";

if (empty($_POST['nom'])) $erreur.="Le champ 'Nom du laboratoire' est vide !<br/>";
if (empty($_POST['acronyme'])) $erreur.="Le champ 'Acronyme du laboratoire' est vide !<br/>";
if (empty($_POST['email'])) $erreur.="Le champ 'Adresse à utiliser par l'application pour envoyer les courriels' est vide !<br/>";
if (empty($_FILES['logo']['tmp_name'])) $erreur.="Le champ 'Charger le logo du laboratoire' est vide !<br/>";

if (!empty($_FILES['logo']['tmp_name']) and !$_FILES['logo']['error']) {
	if ($_FILES['logo']['type']!='image/pjpeg' and $_FILES['logo']['type']!='image/jpeg' and $_FILES['logo']['type']!='image/jpg' and $_FILES['logo']['type']!='image/gif' and $_FILES['logo']['type']!='image/x-xbitmap' and $_FILES['logo']['type']!='image/x-png' and $_FILES['logo']['type']!='image/png') $erreur="Le type de fichier du logo n'est pas le bon (types de fichier admis : JPG, GIF, PNG<br/>";
    elseif ($_FILES['logo']['size']>20000) $erreur="Le fichier est trop volumineux le maximun autorisé est 19.53Ko<br/>";
}
if (empty($erreur)) {
    $sql="INSERT INTO parametres (para_nom_labo,para_acronyme,para_email_envoie,para_numerotation,para_num_exportation,para_email_national,para_version) VALUES ('".$_POST['nom']."', '".$_POST['acronyme']."', '".$_POST['email']."', 'MANU','1','dba@chimiotheque-nationale.enscm.fr','1.4')";
	$insert=$dbh->exec($sql);
   
   if (!empty($_FILES['logo']['tmp_name']) and !$_FILES['logo']['error']) {
		$extension_fichier=preg_split("/\./",$_FILES['logo']['name']);
		$path="../temp/logo.".$extension_fichier[1];
		//sauvegarde du logo dans le repertoire /temp
		if (file_exists($path)) unlink($path);
		move_uploaded_file($_FILES['logo']['tmp_name'], $path);
		$path1="temp/logo.".$extension_fichier[1];
		$sql="UPDATE parametres SET para_logo ='$path1'";
		$update=$dbh->exec($sql);
		if ($insert>0 and $update>0) {
		  $menu=8;
		  include_once 'gauche.php';
		  include_once 'progext7.php';
		}
		else {
			$sql="DELETE FROM parametres where para_nom_labo='".$_POST['nom']."'";
			$dbh->exec($sql);
			$menu=7;
			include_once 'gauche.php';
			include 'progext6.php';
		}
    }
  }
else {
	$menu=7;
	include_once 'gauche.php';
	include 'progext6.php';
}
include_once 'pied.htm';
unset($dbh);
?>