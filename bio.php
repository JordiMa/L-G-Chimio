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
	if (isset($_GET["cible"])) $_POST["cible"]=$_GET["cible"];
	if(!isset($_POST["cible"])) $_POST["cible"]="";
	if (isset($_GET["labo"])) $_POST["labo"]=$_GET["labo"];
	if(!isset($_POST["labo"])) $_POST["labo"]="";
	if (isset($_GET["colactiv"])) $_POST["colactiv"]=$_GET["colactiv"];
	if(!isset($_POST["colactiv"])) $_POST["colactiv"]="";
	if (isset($_GET["ordre"])) $_POST["ordre"]=$_GET["ordre"];
	if(!isset($_POST["ordre"])) $_POST["ordre"]="";
	if (isset($_GET["champ"])) $_POST["champ"]=$_GET["champ"];
	if(!isset($_POST["champ"])) $_POST["champ"]="";
	if (isset($_GET["page"])) $_POST["page"]=$_GET["page"];
	if(!isset($_POST["page"])) $_POST["page"]="";
	if(!isset($_POST["modif"])) $_POST["modif"]="";

	if (isset($_POST["colactiv"])) {
		switch($_POST["colactiv"]) {
			case 1: {
				$requete="res_resultat_ic50,res_resultat_ec50,res_actif,res_resultat_pourcentactivite,res_resultat_pourcentageinhi,res_resultat_autre";
				switch ($_POST["champ"]) {
					case 1: $champ="res_resultat_ic50";
					break;
					case 2: $champ="res_resultat_ec50";
					break;
					case 3: $champ="res_actif";
					break;
					case 4: $champ="res_resultat_pourcentactivite";
					break;
					case 5: $champ="res_resultat_autre";
					break;
					case 6 : $champ="res_resultat_pourcentageinhi";
					break;
				}
			}
			break;
			case 2: $requete=$champ="res_resultat_ic50";
			break;
			case 3: $requete=$champ="res_actif";
			break;
			case 4: $requete=$champ="res_resultat_pourcentactivite";
			break;
			case 5: $requete=$champ="res_resultat_ec50";
			break;
			case 6: $requete=$champ="res_resultat_pourcentageinhi";
			break;
			case 7: $requete=$champ="res_resultat_autre";
			break;
		}			
	}
	//modification des données
	if (isset($_POST["mcol"]) and isset($_POST["mcomm"])) {
		$sql="UPDATE resultat SET res_commentaire='".$_POST["mcomm"]."', $champ='".$_POST["mcol"]."' WHERE res_id_produit='".$_POST["produit"]."' and res_id_labocible='".$_POST["labo"]."'";
		$up=$dbh->query($sql);
	}
	elseif (isset($_POST["mic50"]) and isset($_POST["mec50"]) and isset($_POST["mactin"]) and isset($_POST["mpourcent"]) and isset($_POST["mautre"]) and isset($_POST["mcomm"]) and isset($_POST["minhi"])) {
		$sql="UPDATE resultat SET res_commentaire='".$_POST["mcomm"]."', res_resultat_ic50='".$_POST["mic50"]."',res_actif='".$_POST["mactin"]."',res_resultat_pourcentactivite='".$_POST["mpourcent"]."',res_resultat_ec50='".$_POST["mec50"]."',res_resultat_autre='".$_POST["mautre"]."',res_resultat_pourcentageinhi='".$_POST["minhi"]."'  WHERE res_id_produit='".$_POST["produit"]."' and res_id_labocible='".$_POST["labo"]."'";
		$up=$dbh->query($sql);
	}
	
	//fin de la modification des données 
	
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"resultatbio.php\">".CONSULTER."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"importbio.php\">".IMPORTER."</a></td>
		</tr>
		</table><br/>";
	$sql="SELECT * FROM cible order by cib_nom";
	$resultat1=$dbh->query($sql);
	$numresultat1=$resultat1->rowCount();
	if ($numresultat1>0) {
		while ($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
			$tab[$row1[0]]=$row1[2]." - ".$row1[1];
		}
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
		print"<tr><td>";
		$formulaire=new formulaire ("cible","resultatbio.php","POST",true);
		$formulaire->affiche_formulaire();
		$formulaire->ajout_select (1,"cible",$tab,false,$_POST["cible"],SELECTCIBLE,ANCIBLE."<br/>",false,"onChange=submit()");
		$formulaire->fin();
		if(empty($_POST["cible"])) print"</td></tr></table>";
	}
  
	if (isset($_POST["cible"]) and !empty($_POST["cible"])) {
		$formulaire1=new formulaire ("cible1","resultatbio.php","POST",true);
		$formulaire1->affiche_formulaire();
    
	
		if (isset($_POST["labo"]) and !empty($_POST["labo"])) {
			$tab[1]=ALL;
			$tab[2]=IC50;
			$tab[3]=ACT;
			$tab[4]=POURACT;
			$tab[5]=EC50;
			$tab[6]=POURINHI;
			$tab[7]=AUTRE;
			$formulaire1->ajout_select (1,"colactiv",$tab,false,$_POST["colactiv"],SELEC,TYP."<br/>",false,"onChange=submit()");
			$formulaire1->ajout_cache ($_POST["cible"],"cible");
			$formulaire1->ajout_cache ($_POST["labo"],"labo");
			$formulaire1->fin();
		}
		print"</td><td>";
		$sql="SELECT lab_id_labocible,lab_concentration,lab_protocol,lab_laboratoire,cib_uniprot FROM cible,labocible WHERE cib_id_cible='".$_POST["cible"]."' and cible.cib_id_cible=labocible.lab_id_cible";
		$resultat3=$dbh->query($sql);

		print "<label>".DESCRIPT."</label><div style=\"width:500; height:200; overflow:auto; border:solid 1px black;\">";
		$formulaire2=new formulaire ("cible2","resultatbio.php","POST",true);
		$formulaire2->affiche_formulaire();
		print "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr>";
		while($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
			if ($row3[0]==$_POST["labo"]) print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[0]\" checked onClick=\"submit()\"/></td>";
			else print"<td valign=\"top\"><input type=\"radio\" name=\"labo\" value=\"$row3[0]\" onClick=\"submit()\"/></td>";
			print"<td><i>".LABO."</i><br/>$row3[3]<br/><i>".CONCEN."</i><br/>$row3[1]<br/><i>".PROTOCOL."</i><br/>$row3[2]";
			if (!empty($row3[4])) print "<br/><i>".UNIPROT."</i><br/><a href=\"http://www.uniprot.org/uniprot/$row3[4]\" target=\"_blank\">$row3[4]</a>";
			print"</td></tr>";
		}
		print "</table>";
		$formulaire2->ajout_cache ($_POST["cible"],"cible");
		$formulaire2->fin();
		print "</div>";

		print"</td></tr></table>";
	}
	
    print"<hr>";
    
	if (!empty($_POST["cible"]) and !empty($_POST["labo"]) and !empty($_POST["colactiv"])) {
		
		//nombre limite de molécules à afficher par page
		$limitepage=10;
		
		//calcul du nombre de pages
		if (empty($_POST['page'])) {
		  $_POST['page']=1;
		  $nbrequete=0;
		}
		else {
		  if ($_POST['page']==1) $nbrequete=0;
		  else  $nbrequete=(($_POST['page']-1)*$limitepage);
		}
		
		$sql="SELECT res_id_produit FROM resultat WHERE res_id_labocible='".$_POST["labo"]."'";
		$resultat2=$dbh->query($sql);
		$nbrs=$resultat2->rowCount();
		$nbpage=ceil($nbrs/$limitepage);
		unset($resultat2);
		
		if ($nbpage>1) {
			print"<p align=\"center\">".PAGE;
			for ($i=1; $i<=$nbpage; $i++) {
				if ($i==$_POST["page"]) print "<font color=\"#FF0000\"><strong>$i</strong></font>";
				else print "<a href=\"resultatbio.php?cible=".$_POST["cible"]."&labo=".$_POST["labo"]."&colactiv=".$_POST["colactiv"]."&ordre=".$_POST["ordre"]."&page=$i&champ=".$_POST["champ"]."\"><strong>$i</strong></a>";
				if ($i<$nbpage) print", ";
			}	
			print"</p>";
		}
		
		$sql="SELECT res_id_produit FROM resultat WHERE res_id_labocible='".$_POST["labo"]."'";
		if (!empty($champ) and !empty($_POST["ordre"]))	{
			//if ($champ=="res_resultat_ic50" or $champ=="res_resultat_ec50") $sql.=" ORDER BY CAST($champ AS integer) ".$_POST["ordre"]."";
			//else 
			$sql.=" ORDER BY $champ ".$_POST["ordre"]."";
		}
		$sql.=" LIMIT $limitepage OFFSET $nbrequete";	
		$resultat2=$dbh->query($sql);
		$nbresultat2=$resultat2->rowCount();

		print"<table width=\"100%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">";
		if ($_POST["colactiv"]<>1) {
			switch($_POST["colactiv"]) {
				case 2: $val="IC50";
				break;
				case 3: $val="ACT";
				break;
				case 4: $val="POURACT";
				break;
				case 5: $val="EC50";
				break;
				case 6: $val="POURINHI";
				break;
				case 7: $val="AUTRE";
			}
			
			print"<tr><th scope=\"col\">".STRUCTURE."</th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".constant($val)."</th><th>";
			$formulaire4=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire4->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc") $formulaire4->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire4->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire4->ajout_cache ($_POST["cible"],"cible");
			$formulaire4->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire4->ajout_cache ($_POST["labo"],"labo");
			$formulaire4->ajout_cache ($_POST["page"],"page");
			$formulaire4->ajout_cache ("desc","ordre");
			$formulaire4->fin();
			print"</th><th>";
			$formulaire4=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire4->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc") $formulaire4->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire4->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire4->ajout_cache ($_POST["cible"],"cible");
			$formulaire4->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire4->ajout_cache ($_POST["labo"],"labo");
			$formulaire4->ajout_cache ($_POST["page"],"page");
			$formulaire4->ajout_cache ("asc","ordre");
			$formulaire4->fin();
			print"</th></tr></table></th><th scope=\"col\" width=\"40%\">".COMMENTAIRES."</th><th scope=\"col\" width=\"10%\">";
			if ($nbresultat2>0) {
				 print"<script language=\"JavaScript\">
				  function Postdown(theForm){
					  theForm.target=\"_blank\";
				  }
				  </script>";
				$formulaire3=new formulaire ("cible1","biosdf.php","POST",true);
				$formulaire3->affiche_formulaire();
				$formulaire3->ajout_buttonimage ("","download","image","onclick=Postdown(form)","images/charge.gif",TELE);
				$formulaire3->ajout_cache ($_POST["cible"],"cible");
				$formulaire3->ajout_cache ($_POST["colactiv"],"colactiv");
				$formulaire3->ajout_cache ($_POST["labo"],"labo");
				$formulaire3->fin();
			}
			print"</th></tr>";
		}
		else {
			print"<tr><th scope=\"col\">".STRUCTURE."</th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".IC50."</th><th>";
			$formulaire4=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire4->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc" and $_POST["champ"]==1) $formulaire4->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire4->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire4->ajout_cache ($_POST["cible"],"cible");
			$formulaire4->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire4->ajout_cache ($_POST["labo"],"labo");
			$formulaire4->ajout_cache ($_POST["page"],"page");
			$formulaire4->ajout_cache (1,"champ");
			$formulaire4->ajout_cache ("desc","ordre");
			$formulaire4->fin();
			print"</th><th>";
			$formulaire5=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire5->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc" and $_POST["champ"]==1) $formulaire5->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire5->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire5->ajout_cache ($_POST["cible"],"cible");
			$formulaire5->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire5->ajout_cache ($_POST["labo"],"labo");
			$formulaire5->ajout_cache ($_POST["page"],"page");
			$formulaire5->ajout_cache (1,"champ");
			$formulaire5->ajout_cache ("asc","ordre");
			$formulaire5->fin();
			print"</th></tr></table></th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".EC50."</th><th>";
			$formulaire6=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire6->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc" and $_POST["champ"]==2) $formulaire6->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire6->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire6->ajout_cache ($_POST["cible"],"cible");
			$formulaire6->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire6->ajout_cache ($_POST["labo"],"labo");
			$formulaire6->ajout_cache ($_POST["page"],"page");
			$formulaire6->ajout_cache (2,"champ");
			$formulaire6->ajout_cache ("desc","ordre");
			$formulaire6->fin();
			print"</th><th>";
			$formulaire7=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire7->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc" and $_POST["champ"]==2) $formulaire7->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire7->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire7->ajout_cache ($_POST["cible"],"cible");
			$formulaire7->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire7->ajout_cache ($_POST["labo"],"labo");
			$formulaire7->ajout_cache ($_POST["page"],"page");
			$formulaire7->ajout_cache (2,"champ");
			$formulaire7->ajout_cache ("asc","ordre");
			$formulaire7->fin();
			print"</th></tr></table></th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".ACT."</th><th>";
			$formulaire8=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire8->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc" and $_POST["champ"]==3) $formulaire8->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire8->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire8->ajout_cache ($_POST["cible"],"cible");
			$formulaire8->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire8->ajout_cache ($_POST["labo"],"labo");
			$formulaire8->ajout_cache ($_POST["page"],"page");
			$formulaire8->ajout_cache (3,"champ");
			$formulaire8->ajout_cache ("desc","ordre");
			$formulaire8->fin();
			print"</th><th>";
			$formulaire9=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire9->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc" and $_POST["champ"]==3) $formulaire9->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire9->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire9->ajout_cache ($_POST["cible"],"cible");
			$formulaire9->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire9->ajout_cache ($_POST["labo"],"labo");
			$formulaire9->ajout_cache ($_POST["page"],"page");
			$formulaire9->ajout_cache (3,"champ");
			$formulaire9->ajout_cache ("asc","ordre");
			$formulaire9->fin();
			print"</th></tr></table></th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".POURACT."</th><th>";
			$formulaire10=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire10->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc" and $_POST["champ"]==4) $formulaire10->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire10->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire10->ajout_cache ($_POST["cible"],"cible");
			$formulaire10->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire10->ajout_cache ($_POST["labo"],"labo");
			$formulaire10->ajout_cache ($_POST["page"],"page");
			$formulaire10->ajout_cache (4,"champ");
			$formulaire10->ajout_cache ("desc","ordre");
			$formulaire10->fin();
			print"</th><th>";
			$formulaire11=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire11->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc" and $_POST["champ"]==4) $formulaire11->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire11->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire11->ajout_cache ($_POST["cible"],"cible");
			$formulaire11->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire11->ajout_cache ($_POST["labo"],"labo");
			$formulaire11->ajout_cache ($_POST["page"],"page");
			$formulaire11->ajout_cache (4,"champ");
			$formulaire11->ajout_cache ("asc","ordre");
			$formulaire11->fin();
			print"</th></tr></table></th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".POURINHI."</th><th>";
			$formulaire12=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire12->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc" and $_POST["champ"]==6) $formulaire12->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire12->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire12->ajout_cache ($_POST["cible"],"cible");
			$formulaire12->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire12->ajout_cache ($_POST["labo"],"labo");
			$formulaire12->ajout_cache ($_POST["page"],"page");
			$formulaire12->ajout_cache (6,"champ");
			$formulaire12->ajout_cache ("desc","ordre");
			$formulaire12->fin();
			print"</th><th>";
			$formulaire13=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire13->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc" and $_POST["champ"]==6) $formulaire13->ajout_buttonimage ("","download","image","","images/descr.gif",ASC);
			else $formulaire13->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire13->ajout_cache ($_POST["cible"],"cible");
			$formulaire13->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire13->ajout_cache ($_POST["labo"],"labo");
			$formulaire13->ajout_cache ($_POST["page"],"page");
			$formulaire13->ajout_cache (6,"champ");
			$formulaire13->ajout_cache ("asc","ordre");
			$formulaire13->fin();
			print"</th></tr></table></th><th scope=\"col\"><table width=\"100%\" border=\"0\"><tr><th>".AUTRE."</th><th>";
			$formulaire14=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire14->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="desc"  and $_POST["champ"]==5) $formulaire14->ajout_buttonimage ("","download","image","","images/ascr.gif",DESC);
			else $formulaire14->ajout_buttonimage ("","download","image","","images/asc.gif",DESC);
			$formulaire14->ajout_cache ($_POST["cible"],"cible");
			$formulaire14->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire14->ajout_cache ($_POST["labo"],"labo");
			$formulaire14->ajout_cache ($_POST["page"],"page");
			$formulaire14->ajout_cache (5,"champ");
			$formulaire14->ajout_cache ("desc","ordre");
			$formulaire14->fin();
			print"</th><th>";
			$formulaire15=new formulaire ("cible1","resultatbio.php","POST",true);
			$formulaire15->affiche_formulaire();
			if (isset($_POST["ordre"]) and $_POST["ordre"]=="asc"  and $_POST["champ"]==5) $formulaire13->ajout_buttonimage ("","download","image","","images/desc.gif",ASC); 
			else $formulaire15->ajout_buttonimage ("","download","image","","images/desc.gif",ASC);
			$formulaire15->ajout_cache ($_POST["cible"],"cible");
			$formulaire15->ajout_cache ($_POST["colactiv"],"colactiv");
			$formulaire15->ajout_cache ($_POST["labo"],"labo");
			$formulaire15->ajout_cache ($_POST["page"],"page");
			$formulaire15->ajout_cache (5,"champ");
			$formulaire15->ajout_cache ("asc","ordre");
			$formulaire15->fin();
			print"</th></tr></table></th><th scope=\"col\" width=\"40%\">".COMMENTAIRES."</th><th scope=\"col\" width=\"10%\">";

			if ($nbresultat2>0) {
				 print"<script language=\"JavaScript\">
				  function Postdown(theForm){
					  theForm.target=\"_blank\";
				  }
				  </script>";
				$formulaire3=new formulaire ("cible1","biosdf.php","POST",true);
				$formulaire3->affiche_formulaire();
				$formulaire3->ajout_buttonimage ("","download","image","onclick=Postdown(form)","images/charge.gif",TELE);
				$formulaire3->ajout_cache ($_POST["cible"],"cible");
				$formulaire3->ajout_cache ($_POST["colactiv"],"colactiv");
				$formulaire3->ajout_cache ($_POST["labo"],"labo");
				$formulaire3->fin();
			}
			print"</th></tr>";
		}
		
		if ($nbresultat2>0) {
			echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
			while ($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT str_mol,pro_numero,pro_num_constant FROM produit,structure WHERE pro_id_produit='$row2[0]' and produit.pro_id_structure=structure.str_id_structure";
				$resultat3=$dbh->query($sql);
				$row3=$resultat3->fetch(PDO::FETCH_NUM);
				
				$sql="SELECT $requete,res_commentaire,res_id_resultat FROM resultat WHERE res_id_produit='$row2[0]' and res_id_labocible='".$_POST["labo"]."'";
				$resultat4=$dbh->query($sql);
				$nb4=$resultat4->rowCount();
				print"<tr align=\"center\"><td width=\"180\" height=\"200\"";
				if ($nb4>1) print " rowspan=\"$nb4\"";				
				print ">";
				print"<p>$row3[1]<br/>$row3[2]</p>";
				$jme=new visualisationmoleculejme (140,140,$row3[0]);
				$jme->imprime();
				print"</td>";
				$com=1;
				while($row4=$resultat4->fetch(PDO::FETCH_NUM)) {
					if ($_POST["colactiv"]<>1) {
						if ($nb4>1 and $com>1) print"<tr>";
						if ($_POST["modif"]==1 and $_POST["produit"]==$row2[0]) {
							$formulaire16=new formulaire ("cible1","resultatbio.php","POST",true);
							$formulaire16->affiche_formulaire();
							print"<td>";
							$formulaire16->ajout_text (6, $row4[0], 6, "mcol","","","");
							print"</td><td width=\"40%\"><div style=\"width:100%; height:200; overflow:auto; border:solid 1px black;\">";
							$formulaire16->ajout_textarea ("mcomm",55,$row4[1],12,true,"");
							print"</div></td><td width=\"10%\">";
							$formulaire16->ajout_button (MODIFIER,"","submit","");
							$formulaire16->ajout_cache ($_POST["cible"],"cible");
							$formulaire16->ajout_cache ($_POST["colactiv"],"colactiv");
							$formulaire16->ajout_cache ($_POST["labo"],"labo");
							$formulaire16->ajout_cache ($row2[0],"proid");
							$formulaire16->ajout_cache ($_POST["champ"],"champ");
							$formulaire16->ajout_cache ($_POST["ordre"],"ordre");
							$formulaire16->ajout_cache ($_POST["page"],"page");
							$formulaire16->ajout_cache ($row2[0],"produit");
							$formulaire16->fin();							
						}
						else {
							for ($i=0; $i<2; $i++) {
								if (empty($row4[$i])) $row4[$i]="&nbsp;";
							}
							
							if ($requete=="res_actif") {
								print"<td ";
								if ($row4[0]==1) print"bgcolor= \"#009900\"";
								elseif($row4[0]==2) print"bgcolor=\"#FF0000\"";
								elseif($row4[0]==0) print"bgcolor=\"#FFFFFF\"";
								print">&nbsp;";
							}
							else print"<td>$row4[0]";
							print"</td><td width=\"40%\"><div style=\"width:100%; height:200; overflow:auto; border:solid 1px black;\"><p align=\"left\">".nl2br($row4[1])."</p></div></td><td width=\"10%\">";
							$formulaire17=new formulaire ("cible1","resultatbio.php","POST",true);
							$formulaire17->affiche_formulaire();
							$formulaire17->ajout_buttonimage ("","download","image","","images/modifier.gif",MODIFIER);
							$formulaire17->ajout_cache ($_POST["cible"],"cible");
							$formulaire17->ajout_cache ($_POST["colactiv"],"colactiv");
							$formulaire17->ajout_cache ($_POST["labo"],"labo");
							$formulaire17->ajout_cache ($row2[0],"proid");
							$formulaire17->ajout_cache ($_POST["champ"],"champ");
							$formulaire17->ajout_cache ($_POST["ordre"],"ordre");
							$formulaire17->ajout_cache ($_POST["page"],"page");
							$formulaire17->ajout_cache (1,"modif");
							$formulaire17->ajout_cache ($row2[0],"produit");
							$formulaire17->fin();
						}
						print"</td></tr>";
					}
					else {
						if ($nb4>1 and $com>1) print"<tr>";
						if ($_POST["modif"]==1 and $_POST["produit"]==$row2[0]) {
						
							
							$formulaire18=new formulaire ("cible1","resultatbio.php","POST",true);
							$formulaire18->affiche_formulaire();
							print"<td>";
							$formulaire18->ajout_text (6,$row4[0],6,"mic50",IC50,"","");
							print"</td><td>";
							$formulaire18->ajout_text (6,$row4[1],6,"mec50",EC50,"","");
							print"</td><td>";
							
							$tab15[0]="";
							$tab15[1]=ACTIF;
							$tab15[2]=INACTIF;
	
							$formulaire18->ajout_select (1,"mactin",$tab15,false,$row4[2],"",ACT,false,"");
							
							print"</td><td>";
							$formulaire18->ajout_text (6,$row4[3],6, "mpourcent",POURACT,"","");
							print"</td><td>";
							$formulaire18->ajout_text (6,$row4[4],6, "minhi",POURINHI,"","");
							print"</td><td>";
							$formulaire18->ajout_text (6,$row4[5],6,"mautre",AUTRE,"","");
							print"</td><td width=\"40%\"><div style=\"width:100%; height:200; overflow:auto; border:solid 1px black;\">";
							$formulaire18->ajout_textarea ("mcomm",20,$row4[6],12,true,"");
							print"</div></td><td width=\"10%\">";
							$formulaire18->ajout_button (MODIFIER,"","submit","");
							$formulaire18->ajout_cache ($_POST["cible"],"cible");
							$formulaire18->ajout_cache ($_POST["colactiv"],"colactiv");
							$formulaire18->ajout_cache ($_POST["labo"],"labo");
							$formulaire18->ajout_cache ($_POST["champ"],"champ");
							$formulaire18->ajout_cache ($_POST["ordre"],"ordre");
							$formulaire18->ajout_cache ($_POST["page"],"page");
							$formulaire18->ajout_cache ($row2[0],"produit");
							$formulaire18->fin();							
						}
						else {
							for ($i=0; $i<6; $i++) {
								if (empty($row4[$i])) $row4[$i]="&nbsp;";
							}
							print"<td>$row4[0]</td><td>$row4[1]</td><td ";
							if ($row4[2]==1) print"bgcolor= \"#009900\"";
							elseif($row4[2]==2) print"bgcolor=\"#FF0000\"";
							elseif($row4[2]==0) print"bgcolor=\"#FFFFFF\"";
							print">&nbsp;</td><td>$row4[3]</td><td>$row4[4]</td><td>$row4[5]</td><td width=\"40%\"><div style=\"width:100%; height:200; overflow:auto; border:solid 1px black;\"><p align=\"left\">".nl2br($row4[6])."</p></div></td><td width=\"10%\">";
							$formulaire18=new formulaire ("cible1","resultatbio.php","POST",true);
							$formulaire18->affiche_formulaire();
							$formulaire18->ajout_buttonimage ("","download","image","","images/modifier.gif",MODIFIER);
							$formulaire18->ajout_cache ($_POST["cible"],"cible");
							$formulaire18->ajout_cache ($_POST["colactiv"],"colactiv");
							$formulaire18->ajout_cache ($_POST["labo"],"labo");
							$formulaire18->ajout_cache ($_POST["champ"],"champ");
							$formulaire18->ajout_cache ($_POST["ordre"],"ordre");
							$formulaire18->ajout_cache ($_POST["page"],"page");
							$formulaire18->ajout_cache (1,"modif");
							$formulaire18->ajout_cache ($row2[0],"produit");
							$formulaire18->fin();
							}
						print"</td></tr>";	
					}
					$com++;
				}
			}		
		}
    print"</td></tr></table>";
	}
}
else require 'deconnexion.php';
unset($dbh);
?>
