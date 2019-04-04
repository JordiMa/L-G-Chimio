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
echo "<html>
		<head>
		  <meta http-equiv=\"Content-Type\" content=\"text/html; UTF-8\"/>
		  <meta name=\"copyright\" content=\"Laurent ROBIN CNRS-Université d'Orléans 2011\">
		  <meta name=\"author\" content=\"Laurent Robin (ICOA, Orléans)- Fanny Bonachera (Institut Pasteur, Lille)- Denis Charapoff (LEDSS, Grenoble)- Nicolas Foulon (LMASO, Lyon)- Philippe Jauffret (UGCN, Montpellier)- Jean-Christophe Jullian (Laboratoire de Pharmacognosie - Biocis, Châtenay-Malabry)- Aurélien Lesnard (CERMN, Caen) - Alain Montagnac (ICSN, Gif-sur-Yvette) - Jean-Marc Paris (ENSCP, Paris)- Julien Peyre (ICSN, Gif-sur-Yvette)- Nicolas Saettel (CERMN, Caen)- Kiet Tran (UGCN, Montpellier)\">";
?>
  <title>Chimiothèque</title>
  <style type="text/css" media="all">
         @import url(../presentation/general.css);
  </style>
  <LINK REL="shortcut icon" HREF="../presentation/chimiotheque.ico">
</head>
<body>
<table width="98%" height="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10%" colspan="2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="132"><a href="http://chimiotheque-nationale.org" target="_blank">
    <img src="../images/logo_chimiotheque.png" width="132" alt="Chimioth&egrave;que nationale" border="0" /></a></td>
          <td align="center" valign="top" class=bandeau>chimioth&egrave;que<br/><br/>
<?php
//appel le fichier de connexion à la base de données
require '../script/connectionb.php';
//préparation de la requète SQL
$sql = "SELECT para_nom_labo,para_logo FROM parametres";
//les résultats sont retournées dans la variable $result

$result = $dbh->query($sql);

$row =$result->fetch(PDO::FETCH_NUM);
print $row[0]."</td>\n<td align=\"right\" width=\"132\">\n<img src=\"../".$row[1]."\" height=\"85\" border=\"0\" />";

unset($dbh)
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
