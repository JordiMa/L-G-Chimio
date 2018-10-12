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
include_once 'script/secure.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_compte.php';

//javascript de vérification des champs obligatoires
echo"<script language=\"JavaScript\">
  <!--
  function GetSmiles(theForm) {
    if (document.modifcompte.email.value==\"\") {alert(\"".CHAMP." \'".EMAIL."\' ".RENSEIGNE."\");} 
    else {
      if (document.modifcompte.langue.value==\"\") {alert(\"".CHAMP." \'".LANGUE."\' ".RENSEIGNE."\");}
      else {
		if (document.modifcompte.password.value!=\"\" || document.modifcompte.password2.value!=\"\") {
			if (document.modifcompte.password.value!=\"\" && document.modifcompte.password2.value==\"\") {alert(\"".CHAMP." \'".PASSWORDVER."\' ".RENSEIGNE."\");}
			else{
			    if (document.modifcompte.password.value!=document.modifcompte.password2.value) {alert(\"".VERIF."\");}
				else {
					if (document.modifcompte.password.value.length<12) {alert(\"".NBPASSWORD."\");}
					else {theForm.submit();}
					}
				}
			}
		else {theForm.submit();}		
		}
    }
 }
 </script>";
 //fin du javascript
if (isset($erreur)) print"<p><strong><font color=\"red\">$erreur</font></strong></p>";
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT * FROM chimiste where chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);

print "<p><br/><strong>$row[1] $row[2]</strong></p>";

//initialisation du formulaire
$formulaire=new formulaire ("modifcompte","modifcompte.php","POST",true);
$formulaire->affiche_formulaire();
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_email'";
//les résultats sont retournées dans la variable $result1
$result1 =$dbh->query($sql);
$row1 =$result1->fetch(PDO::FETCH_NUM);
$formulaire->ajout_text ($row1[0]+1, rawurldecode($row[4]),$row1[0],"email",EMAIL."<br/>",false,"");
print"<br/><br/>";
if ($row[7]=="{RESPONSABLE}" or $row[7]=="{ADMINISTRATEUR}" or $row[7]=="{CHEF}") {
  $tab3=array(1=>OUI,0=>NON);
  $formulaire->ajout_radio ("envoi",$tab3,$row[5],RECEVOIR."<br/>",true,"");
  print"<br/><br/>";
}
$folder = dir(REPEPRINCIPAL."langues/");
while($rept=$folder->read()) {
  if ($rept!='.' and $rept!='..' and $rept!='index.php')  $tab1[$rept]=$rept;
}
$formulaire->ajout_select (1,"langue",$tab1,false,$_SESSION['langue'],"",LANGUE."<br/>",false,"");
print"<br/><br/><br/>";
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_password'";
//les résultats sont retournées dans la variable $result1
$result1 =$dbh->query($sql);
$row1 =$result1->fetch(PDO::FETCH_NUM);
print "<strong>".PASSWORDCOMP."</strong><br/><br/>";
$formulaire->ajout_password ($row1[0]+1, "", $row1[0],"password",PASSWORDMOD."<br/>","");
print"<br/>";
$formulaire->ajout_password ($row1[0]+1, "", $row1[0],"password2",PASSWORDVER."<br/>","");
print"<br/><br/>";
$formulaire->ajout_button (SUBMIT,"","button","onClick=\"GetSmiles(form)\"");
//fin du formulaire
$formulaire->fin();
unset($dbh);
?>