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
include_once '../script/administrateur.php';
include_once '../autoload.php';
include_once '../langues/'.$_SESSION['langue'].'/presentation.php';
if(file_exists(REPEPRINCIPAL."/script/connectiona.php"))
{
	$fichier=file_get_contents(REPEPRINCIPAL."/script/connectiona.php");
	if (preg_match("/PDO/",$fichier)) {
		include_once 'entetepdo.php';
	}
	else {
		include_once 'entete.php';
	}
}
if ($_POST["ver"]=='1.3.2') {
	include_once 'upbd32.php';
}
elseif ($_POST["ver"]=='1.3.1') {
	include_once 'upbd31.php';
}
elseif ($_POST["ver"]=='1.3') {
	include_once 'upbd3.php';
}
elseif ($_POST["ver"]=='1.2.1') {
	if (isset($_GET["etape"])) $_POST["etape"]=$_GET["etape"];
	include_once 'upbd2.php';
}
else include_once 'upbd.php';
include_once 'pied.htm';
?>