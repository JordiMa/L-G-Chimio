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
include_once 'langues/'.$_SESSION['langue'].'/lang_plaque.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

	if(!isset($_POST["cible"])) $_POST["cible"]="";

	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"plaques.php\">".CREA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"gestionplaque.php\">".GESTION."</a></td>
		</tr>
		</table><br/>";
	if (isset ($_GET["idpl"])) $idpl=$_GET["idpl"];
	elseif (isset($_POST["idpl"])) $idpl=$_POST["idpl"];

	$sql="SELECT pla_identifiant_local FROM plaque WHERE pla_id_plaque='$idpl'";
	$resultat=$dbh->query($sql);
	$row=$resultat->fetch(PDO::FETCH_NUM);
	print "<p><strong>".NUMERO."</strong> $row[0]</p>";

	$sql="SELECT cib_nom,lab_concentration,lab_protocol,lab_laboratoire,plac_date,plac_id_cible FROM plaquecible,cible,labocible WHERE plac_id_plaque='$idpl' and plaquecible.plac_id_cible=cible.cib_id_cible and labocible.lab_id_cible=cible.cib_id_cible";
	$resultat1=$dbh->query($sql);
	if (!empty($resultat1)) {
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
		  <tr>";
		while ($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
			print"<td class=\"blocaffichage\"><p><i>".CIBLE."</i><blockquote>$row1[0]</blockquote></p><p><i>".LABO."</i><blockquote>".nl2br($row1[3])."</blockquote></p><p><i>".CONCEN."</i><blockquote>$row1[1] ".MOL."</blockquote></p><p><i>".PROTOCOL."</i><blockquote>".nl2br($row1[2])."</blockquote></p></td>";
			print"<td valign=\"top\"><i>".DATEENV."</i> ";
			list($annee,$mois,$jour)=explode("-",$row1[4]);
			print"$jour-$mois-$annee<br/><br/><br/><i>".RESULTAT."</i><br/><br/>";
			$sql="SELECT res_id_resultat FROM resultat WHERE res_id_cible='$row1[5]' and  res_id_plaque='$idpl'";
			$resultat5=$dbh->query($sql);
			if (empty($resultat5)) echo TESTCOUR;
			else echo TESTRESU;
			print"</td></tr>";
		}
		print"</table><hr>";
	}
	print"<br/><h3>".TEST."</h3>";

	if (isset($_POST["mois"]) and isset($_POST["jour"]) and isset($_POST["annee"])) $date=$_POST["jour"]."-".$_POST["mois"]."-".$_POST["annee"];
	else {
		$date=date("d-m-Y");
		$_POST["jour"]=date("d");
		$_POST["mois"]=date("m");
		$_POST["annee"]=date("Y");
	}
	$formulaire3=new formulaire ("datecible","statuspl.php","POST",true);
	$formulaire3->affiche_formulaire();
	list($jour,$mois,$annee)=explode("-",$date);
	$nb=nbjour($mois,$annee);
	for ($i=1;$i<=$nb;$i++) {
		if ($i<10) $i="0".$i;
		$tab5[$i]=$i;
	}
	$formulaire3->ajout_select (1,"jour",$tab5,false,$jour,"",DATEENV."<br/>",false,"onChange=submit()");
	for ($j=1;$j<13;$j++) {
		if ($j<10) $j="0".$j;
		$tab6[$j]=$j;
	}
	$formulaire3->ajout_select (1,"mois",$tab6,false,$mois,"","",false,"onChange=submit()");
	$anneedujour=date("Y");
	$fin=($anneedujour-7);
	for ($anneedujour;$anneedujour>=$fin;$anneedujour--) {
		$tab7[$anneedujour]=$anneedujour;
	}
	$formulaire3->ajout_select (1,"annee",$tab7,false,$annee,"","",false,"onChange=submit()");
	$formulaire3->ajout_cache ($idpl,"idpl");
	//fin du formulaire
	$formulaire3->fin();

	print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr>";
	$sql="SELECT cib_id_cible,cib_nom FROM cible";
	$resultat2=$dbh->query($sql);
	$numresultat2=$resultat2->rowCount();
	if ($numresultat2>0) {
		$i=1;
		while ($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
		  $tab[$row2[0]]=$row2[1];
		  $i++;
		}
		print"<td valign=\"top\">";
		$formulaire=new formulaire ("cible","statuspl.php","POST",true);
		$formulaire->affiche_formulaire();
		$formulaire->ajout_select (1,"cible",$tab,false,$_POST["cible"],SELECTCIBLE,ANCIBLE,false,"onChange=submit()");
		$formulaire->ajout_cache ($idpl,"idpl");
		$formulaire->fin();

		if (!empty($_POST["cible"])) {
			$sql="SELECT lab_id_cible,lab_concentration,lab_protocol,lab_laboratoire FROM labocible WHERE lab_id_cible='".$_POST["cible"]."'";
			echo $_POST["cible"];
			$resultat3=$dbh->query($sql);
			$formulaire1=new formulaire ("cible","statuspl.php","POST",true);
			$formulaire1->affiche_formulaire();
			print"<label>".DESCRIPT."</label><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr>";
			while($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
				print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[0]\" /></td>";
				print"<td><div style=\"width:500; height:200; overflow:auto; border:solid 1px black;\"><i>".LABO."</i><br/>$row3[3]<br/><i>".CONCEN."</i><br/>$row3[1] ".MOL."<br/><i>".PROTOCOL."</i><br/>$row3[2]</div></td></tr>";
			}
			print"</table>";
			$formulaire1->ajout_cache ($idpl,"idpl");
			$formulaire1->ajout_cache ($_POST['mois'],"mois");
			$formulaire1->ajout_cache ($_POST['jour'],"jour");
			$formulaire1->ajout_cache ($_POST['annee'],"annee");
			$formulaire1->ajout_button (SAUVEGARDE,"","submit","");
			$formulaire1->fin();
		}
		print"</td>";
		if (empty($_POST["cible"])) {
			if(!isset($_POST['nomcible'])) $_POST['nomcible']="";
			if(!isset($_POST['conccible'])) $_POST['conccible']="";
			if(!isset($_POST['protocible'])) $_POST['protocible']="";
			if(!isset($_POST['labocible'])) $_POST['labocible']="";
			print"<td>";
			$formulaire2=new formulaire ("cible","statuspl.php","POST",true);
			$formulaire2->affiche_formulaire();
			//recherche des informations sur le champ cib_nom
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='cib_nom'";
			//les résultats sont retournées dans la variable $result
			$result4=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row4=$result4->fetch(PDO::FETCH_NUM);
			$formulaire2->ajout_text ($row4[0]+1,$_POST['nomcible'],$row4[0],"nomcible",NVCIBLE."<br/>","","");
			print"<br/>";

			echo "<label for='conccible'>".CONCEN."</label>";
			print"<br/>";
			echo "<input type=\"number\" name=\"conccible\" min=\"0\" step=\"any\"";
			if (isset($_POST['conccible'])) echo "value='".$_POST['conccible']."'";
			echo "> ".MOL;

			print"<br/>";
			$formulaire2->ajout_textarea ("protocible",52,$_POST['protocible'],12,true,PROTOCOL."<br/>");
			print"<br/>";
			$formulaire2->ajout_textarea ("labocible",52,$_POST['labocible'],12,true,LABO."<br/>");
			$formulaire2->ajout_cache ($_POST['mois'],"mois");
			$formulaire2->ajout_cache ($_POST['jour'],"jour");
			$formulaire2->ajout_cache ($_POST['annee'],"annee");
			$formulaire2->ajout_cache ($idpl,"idpl");
			print"<br/>";
			$formulaire2->ajout_button (SAUVEGARDE,"","submit","");
			$formulaire2->fin();
			print"</td>";
		}
	}
  else {
		print"<td>";
		$formulaire2=new formulaire ("cible","statuspl.php","POST",true);
		$formulaire2->affiche_formulaire();
		//recherche des informations sur le champ cib_nom
		//$sql="SHOW COLUMNS FROM cible LIKE 'cib_nom'";
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='cib_nom'";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$row4=$result4->fetch(PDO::FETCH_NUM);

		if (!isset($_POST['nomcible'])) $_POST['nomcible'] = "";
		if (!isset($_POST['conccible'])) $_POST['conccible'] = "";
		if (!isset($_POST['protocible'])) $_POST['protocible'] = "";
		if (!isset($_POST['labocible'])) $_POST['labocible'] = "";

		$formulaire2->ajout_text ($row4[0]+1,$_POST['nomcible'],$row4[0],"nomcible",NVCIBLE."<br/>","","");
		print"<br/>";

		echo "<label for='conccible'>".CONCEN."</label>";
		print"<br/>";
		echo "<input type=\"number\" name=\"conccible\" min=\"0\" step=\"any\"";
		if (isset($_POST['conccible'])) echo "value='".$_POST['conccible']."'";
		echo "> ".MOL;

		print"<br/>";
		$formulaire2->ajout_textarea ("protocible",52,$_POST['protocible'],12,true,PROTOCOL."<br/>");
		print"<br/>";
		$formulaire2->ajout_textarea ("labocible",52,$_POST['labocible'],12,true,LABO."<br/>");
		$formulaire2->ajout_cache ($_POST['mois'],"mois");
		$formulaire2->ajout_cache ($_POST['jour'],"jour");
		$formulaire2->ajout_cache ($_POST['annee'],"annee");
		$formulaire2->ajout_cache ($idpl,"idpl");
		print"<br/>";
		$formulaire2->ajout_button (SAUVEGARDE,"","submit","");
		$formulaire2->fin();
		print"</td>";
	}
	print"</tr></table>";
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
