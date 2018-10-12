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

$erreur="";
if (empty($_POST['chinom'])) $erreur.="Le champ 'Nom' de l'utilisateur est vide !<br/>";
if (empty($_POST['chiprenom'])) $erreur.="Le champ 'Prénom' de l'utilisateur est vide !<br/>";
if (empty($_POST['chiemail'])) $erreur.="Le champ 'courriel' de l'utilisateur est vide !<br/>";
if (empty($_POST['langue'])) $erreur.="Le champ 'langue' est vide !<br/>";
if (empty($_POST['password'])) $erreur.="Le premier champ 'mot de passe' est vide !<br/>";
if (empty($_POST['password2'])) $erreur.="Le deuxième champ 'mot de passe' est vide !<br/>";
if ($_POST['password']!=$_POST['password2']) $erreur.="Les deux champs mot de passe ne sont pas identique! <br/>";
if (strlen($_POST['password'])<10) $erreur.="le mot de passe est inférieur à 10 caractères! <br/>";

if (empty($erreur)) {
	require '../script/connectionb.php';
	$sql="INSERT INTO chimiste (chi_nom ,chi_prenom,chi_password,chi_email ,chi_recevoir, chi_langue ,chi_statut) VALUES ('".$_POST['chinom']."','".$_POST['chiprenom']."','".md5($_POST['password'])."','".$_POST['chiemail']."','1','".$_POST['langue']."','{ADMINISTRATEUR}')";

	if($insert=$dbh->query($sql)) {
		$menu=9;
		unset($db);
		include_once 'gauche.php';
		include_once 'fin.php';
	}
	else {
		$menu=8;
		include_once 'gauche.php';
		include_once 'progext7.php';
	}
}

else {
	$menu=8;
	include_once 'gauche.php';
	include_once 'progext7.php';
	}
include_once 'pied.htm';

?>