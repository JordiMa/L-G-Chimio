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
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<?php
//retourne les noms des fichiers dans la variable $fichiers du répertoire /images/fond
$fichiers =glob('images/fond/*.jpg');
//compte le nombre de fichiers dans le répertoire
$nbrs=sizeof($fichiers);
//initialisation du générateur de nombre alléatoire basé sur le tampon horaire unix
mt_srand(microtime()*10000);
print "<p class=presentation>&nbsp;".VALO."</p><br/><br/><p align=\"center\" class=presentation>".REF."</p><br/><br/><p align=\"right\" class=presentation>".SAUV."&nbsp;</p></td>\n<td align=\"right\" width=\"266\" height=\"270\">";
//choisi une valeur alléatoire basée entre 1 et $nbrs: le nombre de fichiers dans le répertoire
$affimage=mt_rand(1,$nbrs);
print"<img src=\"images/fond/image".$affimage.".jpg\">\n</td>\n</tr>";
print"<tr>\n<td colspan=\"2\" align= \"center\"><p class=presentation1>".PAT."</p></td>\n</tr>\n";
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
//préparation de la requète SQL, on recherche le nombres de strucutres dans la table structure
$sql = "SELECT count(*) FROM produit";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
$sql = "SELECT count(distinct(pro_id_structure)) FROM produit";
//les résultats sont retournées dans la variable $result
$result1 =$dbh->query($sql);
$row1 =$result1->fetch(PDO::FETCH_NUM);

$sql = "SELECT count(*) FROM Extraits";
//les résultats sont retournées dans la variable $result
$result2 =$dbh->query($sql);
$row2 =$result2->fetch(PDO::FETCH_NUM);

//fermeture de la connexion à la base de données
unset($dbh);
print"<tr>\n<td colspan=\"2\" align= \"center\"><p class=presentation1><br/>".NB." ".$row[0]." ".NB1."</p><p class=presentation1>".DON." ".$row1[0]." ".STR."</p></td>\n</tr>\n";
if ($row2[0] > 0) {
  print"<tr>\n<td colspan=\"2\" align= \"center\"><p class=presentation1><br/><br/>Extractothèque :</p><p class=presentation1>".NB." ".$row2[0]." Extraits</p></td>\n</tr>\n";
}

?>
  </tr>
</table>
