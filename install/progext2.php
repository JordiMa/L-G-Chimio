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
include_once 'autoload.php';

print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"../ttip.js\"></script>";
	
if (!empty($_POST["servmysql"]) and !empty($_POST["loginmysql"]) and !empty($_POST["passmysql"]) and !empty($_POST["namebase"])) {
	try {
			$db = new PDO('pgsql:host='.$_POST["servmysql"].';dbname='.$_POST["namebase"].'', $_POST["loginmysql"], $_POST["passmysql"]);
			$db->exec("SET CHARACTER SET utf8");
			$ok=1;
			if($db->exec("use ".$_POST["namebase"])) {
				$okb=0;
				$loc="etape3.php";
				$text="Vérification";
			}
			else {
				$okb=1;
				$loc="etape4.php";
				$text="Etape suivante";
			}
		} catch (PDOException $excep) {
			print "<p class=\"erreur\"> Error! : ".$excep->getMessage()."</p>";
			//connexion au serveur de base de données PostgreSQL échouée
			$ok=0;
			$loc="etape3.php";
			$text="Vérification";			
		}
}
else {
	if(!isset($_POST["servmysql"])) $_POST["servmysql"]="localhost";
	if(!isset($_POST["loginmysql"])) $_POST["loginmysql"]="";
	if(!isset($_POST["passmysql"])) $_POST["passmysql"]="";
	if (empty($_POST["namebase"])) $_POST["namebase"]="chimiotheque";
	$loc="etape3.php";
	$text="Vérification";
}


$formulaire=new formulaire ("install2",$loc,"POST",true);
$formulaire->affiche_formulaire();
print"<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n<tr>\n<td>";
$formulaire->ajout_text (30,$_POST["servmysql"],"","servmysql","Serveur PostgreSQL :<br/>","","");
print"</td>";
if (isset($ok)) {
	print"<td>";
	if ($ok==0) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Vérifiez les paramètres de connexion au serveur PostgreSQL</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	elseif($ok==1) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Connexion au serveur PostgreSQL réussie</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	print"</td>";
}
print"</tr><tr><td>";
$formulaire->ajout_text (30,$_POST["loginmysql"],"","loginmysql","Nom d'utilisateur (avec les droits administrateur) :<br/>","","");
print"</td>";
if (isset($ok)) {
	print"<td>";
	if ($ok==0) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Vérifiez les paramètres de connexion au serveur PostgreSQL</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	elseif($ok==1) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Connexion au serveur PostgreSQL réussie</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	print"</td>";
}
print"</tr><tr><td>";
$formulaire->ajout_text (30,$_POST["passmysql"],"","passmysql","Mot de passe :<br/>","","");
print"</td>";
if (isset($ok)) {
	print"<td>";
	if ($ok==0) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Vérifiez les paramètres de connexion au serveur PostgreSQL</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	elseif($ok==1) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Connexion au serveur PostgreSQL réussie</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	print"</td>";
}
print"</tr><tr><td>";

$formulaire->ajout_text (30,$_POST["namebase"],"","namebase","Nom de la base à créer :<br/>","","");
print"</td>";
if (isset($okb)) {
	print"<td>";
	if ($okb==0) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Ce nom de base de données existe déjà</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	elseif($okb==1) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Ce nom de base de données est valide</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	print"</td>";
}
print"</tr></table>";
$formulaire->ajout_cache ($_POST["web"],"web");
$formulaire->ajout_cache ($_POST["reptemp"],"reptemp");
$formulaire->ajout_cache ($_POST["repp"],"repp");
if ($_POST["win"]==1) $formulaire->ajout_cache ($_POST["win"],"win");
print"<br/>";
$formulaire->ajout_button ($text,"","submit","");
$formulaire->fin();
?>