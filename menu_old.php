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
print"<div class=\"divmenu\"><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr align=\"center\">\n<td class=cellulebleu>";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
echo MENU;
print"</td>\n</tr>\n<tr align=\"left\">\n<td height=\"100%\" valign=\"top\" class=celluleblanche>\n<br/>\n";

if ($row[0]=='{RESPONSABLE}' or $row[0]=='{CHEF}') {
	for ($i=1; $i<=6 ; $i++) {
		print"&nbsp;<a class=\"mnu\" href=\"";
		switch($i) {
			case 1 : {
					print"saisie.php";
					$y=1;
				}
			break;
			case 2 : {
					print"modification.php";
					$y=2;
				}
			break;
			case 3 : {
					print"resultatbiorespon.php";
					$y=6;
				}
			break;
			case 4 : {
					print"rechercher.php";
					$y=3;
				}
			break;
			case 5 : {
					print"compte.php";
					$y=4;
				}
			break;
			case 6 : {
					print"deconnexion.php";
					$y=5;
				}
			break;
		}
		if(isset($menu) and $menu==$y) print"\"><img border=\"0\" src=\"images/pucerouge.gif\" width=\"9\" height=\"9\">&nbsp;";
		else print"\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image$y','','images/pucerouge.gif',1)\">\n<img name=\"Image$y\" border=\"0\" src=\"images/pucebleu.gif\" width=\"9\" height=\"9\">&nbsp;";
		switch($i) {
			case 1 : echo SAISIE;
			break;
			case 2 : echo MODIF;
			break;
			case 3 : echo RESULTBIO;
			break;
			case 4 : echo RECHERCHE;
			break;
			case 5 : echo COMPTE;
			break;
			case 6 : echo DECONNEXION;
			break;
		}
		print"</a>\n<br/>\n<br/>\n";
	}
}
else {
	for ($i=1; $i<=5 ; $i++) {
		print"&nbsp;<a class=\"mnu\" href=\"";
		switch($i) {
			case 1 : print"saisie.php";
			break;
			case 2 : print"modification.php";
			break;
			case 3 : print"rechercher.php";
			break;
			case 4 : print"compte.php";
			break;
			case 5 : print"deconnexion.php";
			break;
		}
		if(isset($menu) and $menu==$i) print"\"><img border=\"0\" src=\"images/pucerouge.gif\" width=\"9\" height=\"9\">&nbsp;";
		else print"\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image$i','','images/pucerouge.gif',1)\">\n<img name=\"Image$i\" border=\"0\" src=\"images/pucebleu.gif\" width=\"9\" height=\"9\">&nbsp;";
		switch($i) {
			case 1 : echo SAISIE;
			break;
			case 2 : echo MODIF;
			break;
			case 3 : echo RECHERCHE;
			break;
			case 4 : echo COMPTE;
			break;
			case 5 : echo DECONNEXION;
			break;
		}
		print"</a>\n<br/>\n<br/>\n";
	}
}
print"</td>\n</tr>\n</table></div>";

if ($row[0]=='{ADMINISTRATEUR}') {
	print"<br/><br/><div class=\"divmenu1\"><table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr align=\"center\">\n<td class=cellulebleu>";
	echo ADMINISTRATION;
	print"</td>\n</tr>\n<tr align=\"left\">\n<td height=\"100%\" valign=\"top\" class=celluleblanche>\n<br/>\n";
	for ($y=6; $y<=13 ; $y++) {
		print"&nbsp;<a class=\"mnu\" href=\"";
		switch($y) {
			case 6 : print"plaques.php";
			break;
			case 7 : print"resultatbio.php";
			break;
			case 8 : print"exportation.php";
			break;
			case 9 : print"importation.php";
			break;
			case 10 : print"utilisateurs.php";
			break;
			case 11 : print"attributionstructures.php";
			break;
			case 12 : print"parametres.php";
			break;
			case 13 : print"param_extra.php";
		}
		if(isset($menu) and $menu==$y) print"\"><img border=\"0\" src=\"images/pucerouge.gif\" width=\"9\" height=\"9\">&nbsp;";
		else print"\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('Image$y','','images/pucerouge.gif',1)\">\n<img name=\"Image$y\" border=\"0\" src=\"images/pucebleu.gif\" width=\"9\" height=\"9\">&nbsp;";
		switch($y) {
			case 6 : echo GESTIONPLAQUE;
			break;
			case 7 : echo RESULTBIO;
			break;
			case 8 : echo EXPORTATION;
			break;
			case 9 : echo IMPORTATION;
			break;
			case 10 : echo UTILISATEUR;
			break;
			case 11 : echo ATRIB_STRUCTURE;
			break;
			case 12 : echo PARAMETRES;
			break;
			case 13 : echo "Extractothèque";
        }
		print"</a>\n<br/>\n<br/>\n";
    }
	print"</td>\n</tr>\n</table></div>";
	//print"<br/><a href=\"presentation/l-g-chimio_documentation.pdf\" target=\"_blank\"><img border=\"0\" src=\"images/aide1.gif\" width=\"20\" height=\"20\" alt=\"Documentation\"></a> <a href=\"./wiki\" target=\"_blank\"><img border=\"0\" src=\"images/wiki.gif\" width=\"20\" height=\"20\" alt=\"Wiki\"></a> <a href=\"presentation/credit.html\" target=\"_blank\"><img border=\"0\" src=\"images/credit.gif\" width=\"20\" height=\"20\" alt=\"Crédits\"></a>";
}
else
{
	//print"<br/><a href=\"presentation/l-g-chimio_utilisateur.pdf\" target=\"_blank\"><img border=\"0\" src=\"images/aide1.gif\" width=\"20\" height=\"20\" alt=\"Documentation\"></a> <a href=\"./wiki\" target=\"_blank\"><img border=\"0\" src=\"images/wiki.gif\" width=\"20\" height=\"20\" alt=\"Wiki\"></a> <a href=\"presentation/credit.html\" target=\"_blank\"><img border=\"0\" src=\"images/credit.gif\" width=\"20\" height=\"20\" alt=\"Crédits\"></a>";
}
unset($dbh);
?>
