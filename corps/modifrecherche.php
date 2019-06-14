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

if(!isset($_POST['type'])) $_POST['type']="";
if(!isset($_POST['equipechi'])) $_POST['equipechi']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['supinf'])) $_POST['supinf']="";
if(!isset($_POST['massemol'])) $_POST['massemol']="";
if(!isset($_POST['massexact'])) $_POST['massexact']="";
if(!isset($_POST['formbrute'])) $_POST['formbrute']="";
if(!isset($_POST['forbrutexact'])) $_POST['forbrutexact']="";
if(!isset($_POST['numero'])) $_POST['numero']="";
if(!isset($_POST['refcahier'])) $_POST['refcahier']="";

print"\n<script language=\"JavaScript\">
		function GetSmiles(theForm){
			if (!document.JME.smiles()=='') {
				document.consultation1.mol.value=document.JME.molFile();
				theForm.submit();
			}
			else {
				if (document.consultation1.supinf.value!=\"".rawurlencode("=")."\" && document.consultation1.massexact.checked==true) {alert(\"".CHAMP."\");}
				else {theForm.submit();}
			}
		  }
		</script>\n";

print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"ttip.js\"></script>";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
//initialisation du formulaire
$formulaire=new formulaire ("consultation","consultation.php","POST",true);
$formulaire->affiche_formulaire();
//sélection des types dans la base de données
$sql = "SELECT typ_id_type,typ_type FROM type";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
//Les résultats son mis sous forme de tableau
while($row =$result->fetch(PDO::FETCH_NUM)) {
	$tab[$row[0]]=constant ($row[1]);
}
print"<br/><br/><table width=\"100%\" border=\"0\" cellspacing=\"3\" cellpadding=\"2\">
	  <tr>
		<td>";
$formulaire->ajout_select (1,"type",$tab,false,$_POST['type'],SELECTYPE,TYPE."<br/>",false,"");
print"</td>";
$sql= "SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result1
$result1 =$dbh->query($sql);
$rop =$result1->fetch(PDO::FETCH_NUM);

if ($rop[0]=="{CHEF}") {
	$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe WHERE equi_id_equipe in(SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$rop[1]."') order by equi_nom_equipe";
	//les résultats sont retournées dans la variable $result4
	$result4 =$dbh->query($sql);
	$nbrow4=$result4->rowCount();
	if (!empty($nbrow4)) {
		while($row4 =$result4->fetch(PDO::FETCH_NUM)) {
			$tab4[$row4[0]]=$row4[1];
		}
	}
	print"<td>";
	if (!empty($nbrow4)) print"<strong>".ETOU."</strong>";
	print"</td><td>";
	if (!empty($nbrow4)) $formulaire->ajout_select (1,"equipechi",$tab4,false,$_POST['equipechi'],SELECTEQUIPE,EQUIPE."<br/>",false,"");
	$sql="(SELECT pro_id_chimiste, chi_nom, chi_prenom FROM chimiste, produit WHERE produit.pro_id_chimiste = chimiste.chi_id_chimiste AND pro_id_chimiste IN (SELECT DISTINCT (pro_id_chimiste) FROM produit WHERE pro_id_equipe IN (SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable = '".$rop[1]."')) GROUP BY pro_id_chimiste,chi_nom,chi_prenom) UNION (SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_equipe IN (SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$rop[1]."')) UNION (SELECT pro_id_chimiste, chi_nom, chi_prenom FROM chimiste, produit WHERE produit.pro_id_chimiste = chimiste.chi_id_chimiste AND chi_nom='".$_SESSION['nom']."') ORDER BY chi_nom, chi_prenom";
	//les résultats sont retournées dans la variable $result2
	$result2 =$dbh->query($sql);
	$nbrow2=$result2->rowCount();
	if (!empty($nbrow2)) {
		while($row =$result2->fetch(PDO::FETCH_NUM)) {
			//$tab1['tous']=TOUS;
			$tab1[$row[0]]=$row[1]." ".$row[2] ;
		}
	}
	if (!empty($nbrow4)) print"<br/><strong>".OU."</strong><br/>";
	if (!empty($nbrow4)) $formulaire->ajout_select (1,"chimiste",$tab1,false,$_POST['chimiste'],SELECTCHIMISTE,CHIMISTE."<br/>",false,"");
	print"</td>";
}

if($rop[0]=="{RESPONSABLE}") {

	$sql="(SELECT pro_id_chimiste, chi_nom, chi_prenom FROM chimiste, produit WHERE produit.pro_id_chimiste = chimiste.chi_id_chimiste AND pro_id_chimiste IN ( SELECT DISTINCT (pro_id_chimiste) FROM produit WHERE pro_id_equipe='".$rop[2]."' and pro_id_responsable='".$rop[1]."') GROUP BY pro_id_chimiste,chi_nom,chi_prenom) UNION (SELECT pro_id_chimiste, chi_nom, chi_prenom FROM chimiste, produit WHERE produit.pro_id_chimiste = chimiste.chi_id_chimiste AND chi_nom='".$_SESSION['nom']."') ORDER BY chi_nom, chi_prenom";
	//les résultats sont retournées dans la variable $result2
	$result2 =$dbh->query($sql);
	$nbrow2=$result2->rowCount();
	if (!empty($nbrow2)) {
		while($row =$result2->fetch(PDO::FETCH_NUM)) {
			$tab1[$row[0]]=$row[1]." ".$row[2] ;
		}
	}
	print"<td>";
	if (!empty($nbrow2)) print"<strong>et/ou</strong>";
	print"</td><td>";
	if (!empty($nbrow2)) $formulaire->ajout_select (1,"chimiste",$tab1,false,$_POST['chimiste'],SELECTCHIMISTE,CHIMISTE."<br/>",false,"");
	print"</td>";
}
if($rop[0]=="{ADMINISTRATEUR}") {
	$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe ORDER BY equi_nom_equipe";
	//les résultats sont retournées dans la variable $result4
	$result4 =$dbh->query($sql);
	$nbrow4=$result4->rowCount();
	if (!empty($nbrow4)) {
		while($row4 =$result4->fetch(PDO::FETCH_NUM)) {
			$tab4[$row4[0]]=$row4[1];
		}
	}
	print"<td>";
	if (!empty($nbrow4)) print"<strong>".ETOU."</strong>";
	print"</td><td>";
	if (!empty($nbrow4)) $formulaire->ajout_select (1,"equipechi",$tab4,false,$_POST['equipechi'],SELECTEQUIPE,EQUIPE."<br/>",false,"");
	$sql="SELECT chi_id_chimiste,chi_nom,chi_prenom FROM chimiste ORDER BY chi_nom,chi_prenom";
	//les résultats sont retournées dans la variable $result2
	$result2 =$dbh->query($sql);
	$nbrow2=$result2->rowCount();
	if (!empty($nbrow2)) {
		while($row =$result2->fetch(PDO::FETCH_NUM)) {
			$tab1[$row[0]]=$row[1]." ".$row[2] ;
		}
	}
	if (!empty($nbrow4)) print"<br/><strong>".OU."</strong><br/>";
	if (!empty($nbrow4)) $formulaire->ajout_select (1,"chimiste",$tab1,false,$_POST['chimiste'],SELECTCHIMISTE,CHIMISTE."<br/>",false,"");
	print"</td>";
}

print"<td align=\"right\">";
$formulaire->ajout_button (SUBMITRE,"","submit","");
print"  </td></tr>
</table>";
//fin du formulaire
$formulaire->fin();
print"<hr>\n";
$formulaire1=new formulaire ("consultation1","consultation.php","POST",true);
$formulaire1->affiche_formulaire();
print"<table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\">\n<tr>\n<td width=\"400\" height=\"400\">";
$jsme=new dessinmoleculejsme(460,450,'');
$jsme->imprime();
print"<td>";
$tab2[rawurlencode("=")]="=";
$tab2[rawurlencode(">")]=">";
$tab2[rawurlencode("<")]="<";
$formulaire1->ajout_select (1,"supinf",$tab2,false,$_POST['supinf'],"",MASSMOL."<br/>",false,"");
$formulaire1->ajout_text (22, $_POST['massemol'], 22, "massemol", "","","");
$formulaire1->ajout_checkbox ("massexact",EXACTE,$_POST['massexact'],"",true,"");
print"<br/><br/>";
//recherche des informations sur le champ str_formule_brute
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='str_formule_brute'";
//les résultats sont retournées dans la variable $result3
$result3=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row=$result3->fetch(PDO::FETCH_NUM);
//$traitement=new traitement_requete_sql($row[1]);
//$tab=$traitement->imprime();
//$formulaire1->ajout_text (intval($tab/1.5), $_POST['formbrute'], $tab, "formbrute", FORMULEBRUTE."<br/>","","");
$formulaire1->ajout_text (intval($row[0]/1.5), $_POST['formbrute'], $row[0], "formbrute", FORMULEBRUTE."<br/>","","");
$formulaire1->ajout_checkbox ("forbrutexact",EXACTE,$_POST['forbrutexact'],"",true,"");
print"<br/><br/>";
$formulaire1->ajout_text (31, $_POST['numero'], 30, "numero", NUMERO."<br/>","","");
print"<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>".AddSlashes(AIDENUM)."</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/aide.gif\" /></a>";
print"<br/><br/><br/>";
$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_ref_cahier_labo'";
//les résultats sont retournées dans la variable $result4
$result4=$dbh->query($sql);
//Les résultats son mis sous forme de tableau
$row4=$result4->fetch(PDO::FETCH_NUM);
$formulaire1->ajout_text (intval($row4[0]/1.5), $_POST['refcahier'], $row4[0], "refcahier", REFCAH."<br/>","","");
print"</td>\n</tr>\n<tr>\n<td colspan=\"2\" align=\"left\">\n";
$tab=array(EXACTSTRUC,SOUSSTRUC,SIMILARITE);
$formulaire1->ajout_radio ('recherche',$tab,EXACTSTRUC,TYPERECHERCHE,true,'');
echo COESIMILARITE." min 0 <input type=\"range\" name=\"valtanimoto\" value=\"0.6\" max=\"1\" min=\"0\" step=\"0.05\" oninput=\"resultat4.value=parseFloat(valtanimoto.value)\"> max 1\n";
echo "  ".VALUE.": <output name=\"resultat4\">0.6</output>";
echo"</td>\n</tr>\n<tr>\n<td colspan=\"2\" align=\"right\">\n";
$formulaire1->ajout_cache ("","mol");
$formulaire1->ajout_button (SUBMITRE,"","button","onClick=\"GetSmiles(form)\"");
print"</td>\n</tr>\n</table>";
$formulaire1->fin();
//fermeture de la connexion à la base de données
unset($dbh);
?>
