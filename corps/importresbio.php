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
include_once 'langues/'.$_SESSION['langue'].'/lang_bio.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

	if (!isset($_POST["precible"])) $_POST["precible"]="";
	if (!isset($_POST['nomcible'])) $_POST['nomcible']="";
	if (!isset($_POST['uniprot'])) $_POST['uniprot']="";
	if (!isset($_POST['conccible'])) $_POST['conccible']="";
	if (!isset($_POST['protocible'])) $_POST['protocible']="";
	if (!isset($_POST['labocible'])) $_POST['labocible']="";
	
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"resultatbio.php\">".CONSULTER."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"importbio.php\">".IMPORTER."</a></td>
			</tr>
			</table><br/>";  
	if (!empty($erreur)) echo "<p align=\"center\" class=\"erreur\">".constant($erreur)."</p>";
	$sql="SELECT * FROM cible ORDER BY cib_nom";
	$resultat1=$dbh->query($sql);
	$numresultat1=$resultat1->rowCount();
	print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
	if ($numresultat1>0) {
		print"<tr><td valign=\"top\"";
		if (!empty($_POST["precible"])) print" colspan=\"2\" ";
		print">";
		while ($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
			$tab[$row1[0]]=$row1[2]." - ".$row1[1];
		}
		$formulaire=new formulaire ("bio","importbio.php","POST",true);
		$formulaire->affiche_formulaire();
		if (isset($_POST["cible"]) and !empty($_POST["cible"])) $formulaire->ajout_select (1,"cible",$tab,false,$_POST["cible"],SELECTCIBLE,ANCIBLE."<br/>",false,"onChange=submit()");
		else $formulaire->ajout_select (1,"precible",$tab,false,$_POST["precible"],SELECTCIBLE,ANCIBLE."<br/>",false,"onChange=submit()");
		$formulaire->fin();
		print"</td>";
	}
	if ((empty($_POST["precible"]) and !isset ($_POST["cible"])) or (empty($_POST["cible"]) and empty($_POST["precible"]))) {
		print"<td valign=\"top\">";
		$formulaire3=new formulaire ("cible","importbio.php","POST",true);
		$formulaire3->affiche_formulaire();
		//recherche des informations sur le champ cib_nom
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='cib_nom'";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$row4=$result4->fetch(PDO::FETCH_NUM);
		$formulaire3->ajout_text ($row4[0]+1,$_POST['nomcible'],$row4[0],"nomcible",NVCIBLE."<br/>","","");
		print"<br/>";
		$formulaire3->ajout_text (7,$_POST['uniprot'],6,"uniprot","<a href=\"http://www.uniprot.org/\" target=\"_blank\">".UNIPROT."</a><br/>","","");
		print"<br/>";

		echo "<label for='conccible'>".CONCEN."</label>";
		print"<br/>";
		echo "<input type=\"number\" name=\"conccible\" min=\"0\" step=\"any\"";
		if (isset($_POST['conccible'])) echo "value='".$_POST['conccible']."'";
		echo "> ".MOL;

		print"<br/>";
		$formulaire3->ajout_textarea ("protocible",52,$_POST['protocible'],12,true,PROTOCOL."<br/>");
		print"<br/>";
		$formulaire3->ajout_textarea ("labocible",52,$_POST['labocible'],12,true,LABO."<br/>");
		print"<br/>";
		$formulaire3->ajout_button (SAUVEGARDE,"","submit","");
		$formulaire3->fin();
		print"</td></tr></table>";
	}
  
	if (isset($_POST["precible"]) and !empty($_POST["precible"])) {
	
		if (!isset($_POST["labo"])) $_POST["labo"]="";
	
		print"</tr><tr><td valign=\"top\"><strong>".EXISTE;
		$sql="SELECT cib_id_cible,lab_concentration,lab_protocol,lab_laboratoire,cib_uniprot,lab_id_labocible FROM cible,labocible WHERE cib_id_cible='".$_POST["precible"]."' and cible.cib_id_cible=labocible.lab_id_cible";
		$resultat3=$dbh->query($sql);
		print"</strong><div style=\"width:400; height:200; overflow:auto; border:solid 1px black;\">";
		$formulaire4=new formulaire ("cible","importbio.php","POST",true);
		$formulaire4->affiche_formulaire();
		$formulaire4->ajout_cache ($_POST["precible"],"cible");
		print "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr>";
		while($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
			if ($row3[5]==$_POST["labo"]) print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[5]\" checked onClick=\"submit()\"/></td>";
			else print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[5]\" onClick=\"submit()\"/></td>";
			print"<td><i>".LABO."</i><br/>$row3[3]<br/><i>".CONCEN."</i><br/>$row3[1] ".MOL."<br/><i>".PROTOCOL."</i><br/>$row3[2]</td></tr>";
		}
		print "</table>";
		$formulaire4->fin();
		print"</div>";
		print"</td><td><strong>".NEWTEST."</strong>";
		$formulaire3=new formulaire ("cible","importbio.php","POST",true);
		$formulaire3->affiche_formulaire();

		echo "<label for='conccible'>".CONCEN."</label>";
		print"<br/>";
		echo "<input type=\"number\" name=\"conccible\" min=\"0\" step=\"any\"";
		if (isset($_POST['conccible'])) echo "value='".$_POST['conccible']."'";
		echo "> ".MOL;

		print"<br/>";
		$formulaire3->ajout_textarea ("protocible",52,$_POST['protocible'],12,true,PROTOCOL."<br/>");
		print"<br/>";
		$formulaire3->ajout_textarea ("labocible",52,$_POST['labocible'],12,true,LABO."<br/>");
		print"<br/>";
		$formulaire3->ajout_cache ($_POST["precible"],"cible");
		$formulaire3->ajout_button (SAUVEGARDE,"","submit","");
		$formulaire3->fin();
	}
	if(!isset($_POST["precible"]) and !isset ($_POST["cible"]) or !empty($_POST["precible"])) print"</td></tr></table>";
  
	if (isset($_POST["cible"]) and !empty($_POST["cible"])) {
	
		if (!isset($_POST['molref'])) $_POST['molref']="";
		if (!isset($_POST['resulref'])) $_POST['resulref']="";
		if (!isset($_POST['uniteref'])) $_POST['uniteref']="";
	
		$sql="SELECT cib_id_cible,lab_concentration,lab_protocol,lab_laboratoire,cib_uniprot,lab_id_labocible FROM cible,labocible WHERE cib_id_cible='".$_POST["cible"]."' and cible.cib_id_cible=labocible.lab_id_cible";
		$resultat3=$dbh->query($sql);
		print"</td><td>";
		$formulaire1=new formulaire ("bio1","importbio.php","POST",true);
		$formulaire1->affiche_formulaire();
		print "<label>".DESCRIPT."</label><div style=\"width:500; height:200; overflow:auto; border:solid 1px black;\">";
		print "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr>";
		while($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
			if ($row3[5]==$_POST["labo"]) print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[5]\" checked onClick=\"submit()\"/></td>";
			else print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[5]\" onClick=\"submit()\"/></td>";
			print"<td><i>".LABO."</i><br/>$row3[3]<br/><i>".CONCEN."</i><br/>$row3[1] ".MOL."<br/><i>".PROTOCOL."</i><br/>$row3[2]</td></tr>";
		}
		print "</table>";
		$formulaire1->ajout_cache ($_POST["cible"],"cible");
		if (isset($_POST["mois"]) and isset($_POST["jour"]) and isset($_POST["annee"])) {
			$formulaire1->ajout_cache ($_POST["jour"],"jour");
			$formulaire1->ajout_cache ($_POST["mois"],"mois");
			$formulaire1->ajout_cache ($_POST["annee"],"annee");
		}
		$formulaire1->fin();
		print "</div>";
		print"</td></tr></table><hr>";
		//initialisation du formulaire
		$formulaire2=new formulaire ("bio2","importbio.php","POST",true);
		$formulaire2->affiche_formulaire();
		if (isset($_POST["mois"]) and isset($_POST["jour"]) and isset($_POST["annee"])) $date=$_POST["jour"]."-".$_POST["mois"]."-".$_POST["annee"];
		else {
			$date=date("d-m-Y");
			$_POST["jour"]=date("d");
			$_POST["mois"]=date("m");
			$_POST["annee"]=date("Y");
		}
		list($jour,$mois,$annee)=explode("-",$date);
		$nb=nbjour($mois,$annee);
		for ($i=1;$i<=$nb;$i++) {
			if ($i<10) $i="0".$i;
			$tab3[$i]=$i;
		}
		$formulaire1->ajout_select (1,"jour",$tab3,false,$jour,"",DATE."<br/>",false,"onChange=submit()");
		for ($j=1;$j<13;$j++) {
			if ($j<10) $j="0".$j;
			$tab4[$j]=$j;
		}
		$formulaire2->ajout_select (1,"mois",$tab4,false,$mois,"","",false,"onChange=submit()");
		$anneedujour=date("Y");
		$fin=($anneedujour-7);
		for ($anneedujour;$anneedujour>=$fin;$anneedujour--) {
			$tab5[$anneedujour]=$anneedujour;
		}
		$formulaire2->ajout_select (1,"annee",$tab5,false,$annee,"","",false,"onChange=submit()");
		$formulaire2->ajout_cache ($_POST["cible"],"cible");
		$formulaire2->ajout_cache ($_POST["labo"],"labo");
		//fin du formulaire
		$formulaire2->fin();
		
		$formulaire3=new formulaire ("bio4","insertcsv.php","POST",true);
		$formulaire3->affiche_formulaire();
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
		print"<tr><td>";
		$formulaire3->ajout_textarea ("molref",47,$_POST['molref'],14,true,MOLREF."<br/>");
		print"</td><td>";
		$formulaire3->ajout_textarea ("resulref",47,$_POST['resulref'],14,true,RESULREF."<br/>");
		print"</td></tr></table>";
		//recherche des informations sur le champ pro_apha_concentration
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='ref_unite_resultat'";
		//les résultats sont retournées dans la variable $result
		$result11=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result11->fetch(PDO::FETCH_NUM);
		$formulaire3->ajout_text ($rop[0]+2,$_POST['uniteref'],$rop[0],"uniteref",UNITEREF."<br/>","","");

		if (isset($_POST["cible"]) and !empty($_POST["cible"]) and isset($_POST["labo"]) and !empty($_POST["labo"])) {
			print"<hr>";
			$formulaire3->ajout_file (30, "filebio",true,IMPORTBIO."<br/>","");
			$formulaire3->ajout_cache ($_POST["cible"],"cible");
			$formulaire3->ajout_cache ($_POST["labo"],"labo");
			$formulaire3->ajout_cache ($_POST["jour"],"jour");
			$formulaire3->ajout_cache ($_POST["mois"],"mois");
			$formulaire3->ajout_cache ($_POST["annee"],"annee");
			$formulaire3->ajout_button (SUBMIT,"","submit","");
		}
		$formulaire3->fin();
	}

}
else require 'deconnexion.php';
unset($dbh);

function nbjour ($x, $annee)
{
	$bis=0;
	if ($x<10) str_replace ("0","",$x);
	if(($annee%4==0 && $annee%100!=0)||$annee%400==0) $bis=1;
	if ($x==1|| $x==3 || $x==5 || $x==7 || $x==8 || $x==10 || $x==12) $j=31;
	elseif ($x==4 || $x==6 ||$x==9 || $x==11) $j=30;
	elseif ($x==2 && $bis==0) $j=28;
	elseif ($x==2 && $bis==1) $j=29;
	return $j;
}
?>
