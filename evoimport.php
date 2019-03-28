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

//traitement du retour de la valeur maximun du fichier uploader souvent exprimé en Mo
$maxup=ini_get('upload_max_filesize');
if (preg_match("/G|g/",$maxup)) {
	$maxup=str_replace("/G|g/","",$maxup);
	$maxup=$maxup*1024*1024*1024;
	}
if (preg_match("/M|m/",$maxup)) {
	$maxup=str_replace("/M|m/","",$maxup);
	$maxup=$maxup*1024*1024;
	}
if (preg_match("/K|k/",$maxup)) {
	$maxup=str_replace("/K|k/","",$maxup);
	$maxup=$maxup*1024;
	}

if (($_FILES['filevo']['type']=="application/vnd.ms-excel" or $_FILES['filevo']['type']=="text/csv" or $_FILES['filevo']['type']=="text/x-comma-separated-values" or $_FILES['filevo']['type']=="application/force-download" or $_FILES['filevo']['type']=="text/comma-separated-values") and $_FILES['filevo']['size']<=$maxup) $transfert=true;
$menu=9;
$ssmenu=12;
include_once 'presentation/entete.php';
include_once 'presentation/gauche.php';
if (($_FILES['filevo']['type']=="application/vnd.ms-excel" or $_FILES['filevo']['type']=="text/csv" or $_FILES['filevo']['type']=="text/x-comma-separated-values" or $_FILES['filevo']['type']=="application/force-download" or $_FILES['filevo']['type']=="text/comma-separated-values") and $_FILES['filevo']['size']<=$maxup) include_once 'corps/insertcsvevo.php';
else {
  $erreur="ERREFILECSV";
  include_once 'corps/importevo.php';
}
include_once 'presentation/pied.php';
?>