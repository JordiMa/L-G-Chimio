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

include_once 'autoload.php';
include_once 'protection.php';
include_once 'langues/fr/presentation.php';
include("simple-php-captcha\simple-php-captcha.php");

$_SESSION['captcha'] = simple_php_captcha();

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
print"<br/><br/><div align=\"center\">";
//initialisation du formulaire
$formulaire=new formulaire ("passordoulie","transfertpass.php","POST",true);
$formulaire->affiche_formulaire();
//recherche des informations sur le champ pro_hal
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_email'";
//les résultats sont retournées dans la variable $result
$result1=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$rop=$result1->fetch(PDO::FETCH_NUM);
$formulaire->ajout_text ($rop[0]+1,'',$rop[0],"email",EMAIL."<br/>","","");
echo "<br><br>";
echo '<img src="'.$_SESSION['captcha']['image_src'].'">';
$formulaire->ajout_text ("",'',"","captcha","<br/> saisissez les caractères que vous voyez dans l'image<br/>","","");
$formulaire->ajout_cache($_SESSION['captcha']['code'], "captcha_code");
echo "<br><br>";
$formulaire->ajout_button (SUBMIT,"","submit","");
//fin du formulaire
$formulaire->fin();
unset ($dbh);
print"</div>";
?>
