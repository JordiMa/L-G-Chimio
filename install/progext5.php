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
print"<table width=\"900\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n<tr>\n<td><br/>";	
if ($_POST["win"]==1) {
	$permscrip=fileperms($_POST["repp"]."script\\administrateur.php");
	$permscrip1=fileperms($_POST["repp"]."script\\.htaccess");
	$permscrip2=fileperms($_POST["repp"]."script\\connectionb.php");
	$permscrip3=fileperms($_POST["repp"]."script\\secure.php");
	print"<b>Les fichiers contenus dans le répertoire ".$_POST["repp"]."script doivent être en lecture seule.</b><br/>";
	if (decoct($permscrip)==100444 and decoct($permscrip1)==100444 and decoct($permscrip2)==100444 and decoct($permscrip3)==100444) $okscript=1;
	else $okscript=0;
}
else {
	$permscrip=fileperms($_POST["repp"]."script");
	print"<b>Répertoire contenant les paramètres de l'application (les droits d'accès doivent être 555 en production) :</b><br/>";
	echo $_POST["repp"]."script";
	if (decoct($permscrip)==40555) $okscript=1;
	else $okscript=0;
}
print"</td>\n<td align=\"left\">";

if ($okscript==1) print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>les droits d\'accès du répertoire sont bien paramétrés</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
else print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Il faut que le répertoire est les droits d\'accès initialisées à 777 le temps de l\'installation. Maintenant, il faut que ce répertoire soit en lecture seulement.</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
print"</td></tr></table>";
if ($okscript==1) {
	$formulaire=new formulaire ("install","etape7.php","POST",true);
	$formulaire->affiche_formulaire();
	echo "<p align=\"center\">";
	$formulaire->ajout_button ("Etape suivante","","submit","");
	echo "</p>";
	}
else {
	$formulaire=new formulaire ("install","etape6.php","POST",true);
	$formulaire->affiche_formulaire();
	if ($_POST["win"]==1) $formulaire->ajout_cache ($_POST["win"],"win");
	$formulaire->ajout_button ("Recharger","","submit","");
	}
$formulaire->ajout_cache ($_POST["repp"],"repp");	
$formulaire->fin();
?>