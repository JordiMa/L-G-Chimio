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
include_once 'langues/'.$_SESSION['langue'].'/lang_modifications.php';

if(!isset($_POST['masseexact'])) $_POST['masseexact']="";
if(!isset($_POST['supinf'])) $_POST['supinf']="";
if(!isset($_POST['massemol'])) $_POST['massemol']="";
if(!isset($_POST['formbrute'])) $_POST['formbrute']="";
if(!isset($_POST['forbrutexact'])) $_POST['forbrutexact']="";
if(!isset($_POST['supinflog'])) $_POST['supinflog']="";
if(!isset($_POST['logp'])) $_POST['logp']="";
if(!isset($_POST['logpexact'])) $_POST['logpexact']="";
if(!isset($_POST['numero'])) $_POST['numero']="";

print"\n<script language=\"JavaScript\">
function GetSmiles(theForm){
	if (!document.JME.smiles()=='') {
		document.consultation1.mol.value=document.JME.molFile();
		theForm.submit();
	}
    else {
      if (document.consultation1.supinf.value!=\"%3D\" && document.consultation1.masseexact.checked==true) {alert(\"".CHAMP."\");}
      else {theForm.submit();}
    }
  }
</script>\n";

print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"ttip.js\"></script>";


//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
if (!empty($erreur)) print"$erreur";
print"<br/>";
$formulaire1=new formulaire ("consultation1","consultation1.php","POST",true);
$formulaire1->affiche_formulaire();
print"<table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\">\n<tr>\n<td width=\"400\" height=\"400\">";
$jsme=new dessinmoleculejsme(460,450,'');
$jsme->imprime();
print"<td>";
$tab1[rawurlencode("=")]="=";
$tab1[rawurlencode(">")]=">";
$tab1[rawurlencode("<")]="<";
$formulaire1->ajout_select (1,"supinf",$tab1,false,$_POST['supinf'],"",MASSMOL."<br/>",false,"");
$formulaire1->ajout_text (22, $_POST['massemol'], 22, "massemol","","","");
$formulaire1->ajout_checkbox ("masseexact",EXACTE,$_POST['masseexact'],"",true,"");
print"<br/><br/>";
//recherche des informations sur le champ pro_purification
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='str_formule_brute'";
//les résultats sont retournées dans la variable $result
$result3=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row=$result3->fetch(PDO::FETCH_NUM);
$formulaire1->ajout_text (intval($row[0]/1.5), $_POST['formbrute'], $row[0], "formbrute", FORMULEBRUTE."<br/>","","");
$formulaire1->ajout_checkbox ("forbrutexact",EXACTE,$_POST['forbrutexact'],"",true,"");
print"<br/><br/>";
$formulaire1->ajout_text (31, $_POST['numero'], 30, "numero", NUMERO."<br/>","","");
print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>".AddSlashes(AIDENUM)."</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/aide.gif\" /></a>";
print"</td>\n</tr>\n<tr>\n<td colspan=\"2\" align=\"left\">\n";
$tab=array(EXACTSTRUC,SOUSSTRUC,SIMILARITE);
$formulaire1->ajout_radio ('recherche',$tab,EXACTSTRUC,TYPERECHERCHE,true,'');
echo COESIMILARITE." min 0 <input type=\"range\" name=\"valtanimoto\" value=\"0.6\" max=\"1\" min=\"0\" step=\"0.05\" oninput=\"resultat4.value=parseFloat(valtanimoto.value)\"> max 1\n";
echo "  ".VALUE.": <output name=\"resultat4\">0.6</output>";
echo"</td>\n</tr>\n<tr>\n<td colspan=\"2\" align=\"right\">\n";
 $formulaire1->ajout_cache ("","mol");
//echo "<input type=\"hidden\" id=\"mol\">";

$formulaire1->ajout_button (SUBMITRE,"","button","onClick=\"GetSmiles(form)\"");
print"</td>\n</tr>\n</table>";
$formulaire1->fin();
//fermeture de la connexion à la base de données
unset($dbh);
?>