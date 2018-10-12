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

if(!isset($_POST["marvin"])) $_POST["marvin"]="";

print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"../ttip.js\"></script>";
$formulaire=new formulaire ("install","etape1.php","POST",true);
$formulaire->affiche_formulaire();
$repprincipal=getcwd();
//vérifie si on est sous un système windows
if(preg_match("/:/",$repprincipal)) $win=1;
$repprincipal=str_replace("install","",$repprincipal);
print"<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n<tr>\n<td><br/>";
$formulaire->ajout_text (60,$repprincipal,"","repp","Répertoire principal de l'application sur le serveur :"."<br/>","","");
print"</td>\n<td align=\"left\">";
if(file_exists($repprincipal."menu.php")) {
	$okrep=1;
	print"<a href=\"#\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('<p align=\'center\'>Le répertoire est bien présent</p>')\"><img border=\"0\" src=\"../images/ok.gif\" id=\"Image\"/></a>";
	}
else {
	$okrep=0;
	print"<a href=\"#\" onmouseout=\"hideddrivetip()\" onmouseover=\"ddrivetip('<p align=\'center\'>l\'application ne trouve pas le répertoire</p>')\"><img border=\"0\" src=\"../images/pasok.gif\"/></a>";
	}
print"</td>\n</tr>\n<tr>\n<td><br/>";
$permtemp=fileperms($repprincipal."temp");
if ($win==1) print"<b>Répertoire temporaire de l'application (les droits d'accès doivent autoriser la lecture et la modification) :</b><br/>";
else print"<b>Répertoire temporaire de l'application (les droits d'accès doivent être 777) :</b><br/>";
if ($win==1) {
	echo $repprincipal."temp\\";
	$formulaire->ajout_cache ($repprincipal."temp\\","reptemp");
	}
else {
	echo $repprincipal."temp/";
	$formulaire->ajout_cache ($repprincipal."temp/","reptemp");
	}

//suppresion des fichiers de test résiduels
if(file_exists($repprincipal."temp/test.txt")) unlink($repprincipal."temp/test.txt");
if(file_exists($repprincipal."temp/test.inchi")) unlink($repprincipal."temp/test.inchi");

print"</td>\n<td align=\"left\">";
if (decoct($permtemp)==40777) {
	$oktemp=1;
	print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>les droits d\'accès du répertoire sont bien paramétrés</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	}
else {
	$oktemp=0;
	print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Il faut que le répertoire ait les droits d\'accès initialisés à 777</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	}
print"</td>\n</tr>\n<tr>\n<td><br/>";
$permscrip=fileperms($repprincipal."script");
if ($win==1) print"<b>Répertoire contenant les paramètres de l'application (les droits d'accès doivent autoriser la lecture et la modification le temps de l'installation) :</b><br/>";
else print"<b>Répertoire contenant les paramètres de l'application (les droits d'accès doivent être 777 le temps de l'installation) :</b><br/>";
echo $repprincipal."script";
print"</td>\n<td align=\"left\">";
if (decoct($permscrip)==40777) {
	$okscript=1;
	print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>les droits d\'accès du répertoire sont bien paramétrés</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/ok.gif\" /></a>";
	}
else {
	$okscript=0;
	print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>Il faut que le répertoire ait les droits d\'accès initialisés à 777 le temps de l'installation</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"../images/pasok.gif\" /></a>";
	}
print"</td>\n</tr>\n<tr>\n<td colspan=\"2\"><br/>";
$web=str_replace("/install/","",$_SERVER["HTTP_REFERER"]);
$web=str_replace("etape.php","",$web);
$web=str_replace("etape1.php","",$web);
$web=str_replace("etape2.php","",$web);
$formulaire->ajout_text (60,$web,"","web","Adresse du site :"."<br/>","","");
print"</td>\n</tr>\n<tr>\n";

print"</tr>\n</table>\n<br/>";
print"<script language=\"JavaScript\">

      function GetSmiles(theForm, valeurformu) {
        if (valeurformu==1) {
			theForm.action=\"etape2.php\"
			theForm.submit();
		}
		else {
			if (valeurformu==2) {
				theForm.action=\"etape3.php\"
				theForm.submit();
			}
			else {
				theForm.submit();
			}
		}
	  }

		</script>";
	
if ($okrep==1 and $oktemp==1 and $okscript==1) {
	$ok=2;
	$text="Etape suivante";
}
else $ok=0;	
if ($win==1) $formulaire->ajout_cache ("1","win");	
$formulaire->ajout_button ($text,"","button","onClick=\"GetSmiles(form,$ok)\"");
$formulaire->fin();
?>