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
include_once '../langues/fr/lang_utilisateurs.php';
include_once 'autoload.php';
require '../script/connectionb.php';

if(!isset($_POST['chinom'])) $_POST['chinom']="";
if(!isset($_POST['chiprenom'])) $_POST['chiprenom']="";
if(!isset($_POST['chiemail'])) $_POST['chiemail']="";
if(!isset($_POST['langue'])) $_POST['langue']="";

$formulaire=new formulaire ("utilisateur","etape9.php","POST",true);
$formulaire->affiche_formulaire();

print"<br/>";
print"<h2>Compte du Chimiothécaire</h2>";
print"<br/>";
if (!empty($erreur)) print'<p class="messagederreur">'.$erreur.'</p>';
//recherche des informations sur le champ pro_cas
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_nom'";
//les résultats sont retournées dans la variable $result
$result1=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row1=$result1->fetch(PDO::FETCH_NUM);
$formulaire->ajout_text ($row1[0]+1,$_POST['chinom'],$row1[0],"chinom",NOM.DEUX."<br/>","","");
print"<br/><br/>";

$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_prenom'";
//les résultats sont retournées dans la variable $result
$result2=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row2=$result2->fetch(PDO::FETCH_NUM);
$formulaire->ajout_text ($row2[0]+1,$_POST['chiprenom'],$row2[0],"chiprenom",PRENOM.DEUX."<br/>","","");
print"<br/><br/>";

$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_email'";
//les résultats sont retournées dans la variable $result
$result3=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row3=$result3->fetch(PDO::FETCH_NUM);
$formulaire->ajout_text ($row3[0]+1,$_POST['chiemail'],$row3[0],"chiemail",COURRIEL.DEUX."<br/>","","");
print"<br/><br/>";

$folder = dir(REPEPRINCIPAL."langues/");
while($rept=$folder->read()) {
    if ($rept!='.' and $rept!='..' and $rept!='index.php')  $tab1[$rept]=$rept;
  }
$formulaire->ajout_select (1,"langue",$tab1,false,$_POST['langue'],SELECTLANGUE,LANGUE.DEUX."<br/>",false,"");
print"<br/><br/>";
print "<b>Saisisez votre mot de passe (12 caractères minimum) :</b><br/><br/>";
echo "<input type=\"password\" name=\"password\" placeholder=\"Password\" id=\"password\" minlength=\"12\" required>
        <input type=\"password\" name=\"password2\" placeholder=\"Confirm Password\" id=\"confirm_password\" minlength=\"12\" required>";
        echo "<script type=\"text/javascript\">
        var password = document.getElementById(\"password\")
        , confirm_password = document.getElementById(\"confirm_password\");

        function validatePassword(){
          if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity(\"Passwords Don't Match\");
          } else {
            confirm_password.setCustomValidity('');
          }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
        </script>";
// $formulaire->ajout_password (21, "", 20,"password","tapez votre mot de passe :"."<br/>","");
// print"<br/>";
// $formulaire->ajout_password (21, "", 20,"password2","retapez votre mot de passe :"."<br/>","");
print"<br/><br/>";

$formulaire->ajout_button (SUBMIT,"","submit","");

//fin du formulaire
$formulaire->fin();
unset($dbh);
?>
