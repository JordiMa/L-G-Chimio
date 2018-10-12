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
echo "<tr>
    <td width=\"15%\" valign=\"top\"><br/>";
print"<table width=\"150\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr align=\"center\">\n<td class=cellulebleu>";
echo "Etapes";
print"</td>\n</tr>\n<tr>\n<td height=\"100\" valign=\"top\" class=celluleblanche>\n<br/>\n";

for ($i=1; $i<=$menu ; $i++) {
  switch($i) {
    case 1 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";	
		print" Pr&eacute;sentation";
		}
    break;
    case 2 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" R&eacute;pertoires";
		}
    break;
    case 3 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Connexion à PostgreSQL";
		}
    break;
    case 4 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Création des tables";
		}
    break;
    case 5 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Configuration";
		}
    break;
	case 6 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Droits d'accès";
		}
    break;
	case 7 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Paramètres";
		}
    break;
	case 8 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Compte";
		}
    break;
	case 9 : {
		if ($menu==$i) print"<img border=\"0\" src=\"../images/pucerouge.gif\" width=\"9\" height=\"9\">";
		else print"<img border=\"0\" src=\"../images/pucebleu.gif\" width=\"9\" height=\"9\">";
		print" Fin";
		}
    break;
  }
  print"\n<br/>\n<br/>\n";
}
print"</td>\n</tr>\n</table>";
?>
    </td>
    <td bgcolor="#FFFFFF" valign="top" width="85%" >