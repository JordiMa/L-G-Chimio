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
	
	if (!isset($_POST['numlot'])) $_POST['numlot']="";
	if (!isset($_POST['numplaque'])) $_POST['numplaque']="";
	if (!isset($_POST['numextplaque'])) $_POST['numextplaque']="";
	if (!isset($_POST['conplaque'])) $_POST['conplaque']="";
	if (!isset($_POST['massplaque'])) $_POST['massplaque']="";
	if (!isset($_POST['massetran'])) $_POST['massetran']="";
	if (!isset($_POST['volplaque'])) $_POST['volplaque']="";
	if (!isset($_POST['unitevol'])) $_POST['unitevol']="";
	if (!isset($_POST['ancienlot'])) $_POST['ancienlot']="";
	if (!isset($_POST['massety'])) $_POST['massety']="";
	if(!isset($_POST['solvantplaque'])) $_POST['solvantplaque']="8";

	echo"<script language=\"JavaScript\">
		  <!--
		  function Getcreation(theForm) {
			if (document.plaquecrea.numplaque.value==\"\") {alert(\"".CHAMPNUM."\");}
			else {
					if (document.plaquecrea.volplaque.value==\"\") {alert(\"".CHAMPVOL."\");}
					else {
						if(document.plaquecrea.massety.value==1) {
							if (document.plaquemasse.massplaque.value==\"\") {alert(\"".CHAMPMASS."\");}
							else {
									if (document.plaquemasse.conplaque.value==\"\") {alert(\"".CHAMPCON."\");}
									else {theForm.submit();}
							}	
						}
						else {
							if(document.plaquecrea.massety.value==2) {theForm.submit();}
						}
					}
				}	
			}
			function Creaplaquefille (theForm) {
				if (document.plaquecrea.numplaque.value==\"\") {alert(\"".CHAMPNUM."\");}
				else {
					if (document.plaquecrea.volplaque.value==\"\") {alert(\"".CHAMPVOL."\");}
					else { 
							document.plaquecrea.action =\"creationplaque.php\";
							theForm.submit();
						}
					}
			}	
		</script>";
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"plaques.php\">".CREA."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"gestionplaque.php\">".GESTION."</a></td>
			</tr>
			</table>";
	//initialisation du formulaire
	$formulaire1=new formulaire ("datecrea","plaques.php","POST",true);
	$formulaire1->affiche_formulaire();
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
	$formulaire1->ajout_select (1,"mois",$tab4,false,$mois,"","",false,"onChange=submit()");
	$anneedujour=date("Y");
	$fin=($anneedujour-7);
	for ($anneedujour;$anneedujour>=$fin;$anneedujour--) {
		$tab5[$anneedujour]=$anneedujour;
	}
	$formulaire1->ajout_select (1,"annee",$tab5,false,$annee,"","",false,"onChange=submit()");
	//fin du formulaire
	$formulaire1->fin();
	
	echo "<br>";
	
	if (!isset($_POST['plaquera'])) {
		//Selection d'une boite à mettre en plaque
		$sql="SELECT num_type FROM numerotation WHERE num_parametre=1 and num_type<>'{FIXE}' and num_type<>'{COORDONEE}' and num_type<>'{NUMERIC}' ORDER BY num_id_numero";
		$result4=$dbh->query($sql);
		$resultrow4="";
		$nubresultat4=$result4->rowCount();
		$y=0;
		while($row4=$result4->fetch(PDO::FETCH_NUM)) {
			switch ($row4[0]) {
				case "{TYPE}": $resultrow4.="pro_id_type";
				break;
				case "{EQUIPE}": $resultrow4.="pro_id_equipe";
				break;
				case  "{BOITE}": $resultrow4.="pro_num_boite";
				break;
				case "{NUMERIC}": $resultrow4.="pro_num_incremental";
				break;
			}
			$y++;
			if ($y<$nubresultat4) $resultrow4.=",";
		}
		$resultrow4=str_replace(",$","",$resultrow4);
		$sql="SELECT $resultrow4 FROM produit WHERE pro_num_boite<>0 GROUP BY $resultrow4";
		$result5=$dbh->query($sql);
		if (!empty($result5)) {
			while ($row5=$result5->fetch(PDO::FETCH_OBJ)) {
				$requetesql="";
				if (isset($row5->pro_id_equipe)) $requetesql.="pro_id_equipe='".$row5->pro_id_equipe."' and ";
				if (isset($row5->pro_id_type)) $requetesql.="pro_id_type='".$row5->pro_id_type."' and ";
				if (isset($row5->pro_num_boite)) $requetesql.="pro_num_boite='".$row5->pro_num_boite."' and ";
				if (isset($row5->pro_num_incremental)) $requetesql.="pro_num_incremental='".$row5->pro_num_incremental."' and ";
				$requetesql=rtrim($requetesql);
				$requetesql=preg_replace("/and$/","",$requetesql);
				$sql="SELECT count(pro_id_produit) FROM produit WHERE $requetesql";
				$result9=$dbh->query($sql);
				$row9=$result9->fetch(PDO::FETCH_NUM);
				if ($row9[0]==80) {
					$varrequete="";
					$numerocomplet="";
					$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre=1 ORDER BY num_id_numero";
					$result6=$dbh->query($sql);
					while ($row6=$result6->fetch(PDO::FETCH_NUM)) {
						if ($row6[0]=="{FIXE}") $numerocomplet.=$row6[1];
						elseif ($row6[0]=="{EQUIPE}") {
							$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe=".$row5->pro_id_equipe."";
							$result7=$dbh->query($sql);
							$row7=$result7->fetch(PDO::FETCH_NUM);
							$numerocomplet.=$row7[0];
							$varrequete.="&equipe=".$row5->pro_id_equipe;
						}
						elseif ($row6[0]=="{TYPE}") {
							$sql="SELECT typ_initiale FROM type where typ_id_type=".$row5->pro_id_type."";
							$resultat8=$dbh->query($sql);
							$row8=$resultat8->fetch(PDO::FETCH_NUM);
							$numerocomplet.=$row8[0];
							$varrequete.="&type=".$row5->pro_id_type;
						}
						elseif ($row6[0]=="{BOITE}") {
							$numerocomplet.=$row5->pro_num_boite;
							$varrequete.="&boite=".$row5->pro_num_boite;
						}
						elseif ($row6[0]=="{NUMERIC}") {
							$numerocomplet.=$row5->pro_num_incremental;
							$varrequete.="&incremental=".$row5->pro_num_incremental;
						}
						$varrequete=str_replace("^&","",$varrequete);
					}
					$tab6[$varrequete]=$numerocomplet;
				}
			}
		}
		if (isset($tab6) and count($tab6)>0) {
			if(!isset($_POST['boitetotal'])) $boitetotal="";
			else $boitetotal=$_POST['boitetotal'];
			//initialisation du formulaire insertion à partir d'une boite
			$formulaire3=new formulaire ("plaquefille","plaques.php","POST",true);
			$formulaire3->affiche_formulaire();
			$formulaire3->ajout_select (1,"boitetotal",$tab6,false,$boitetotal,SELECTBOITE,BOITETO."<br/>",false,"onChange=submit()");
			$formulaire3->ajout_cache ($_POST['mois'],"mois");
			$formulaire3->ajout_cache ($_POST['jour'],"jour");
			$formulaire3->ajout_cache ($_POST['annee'],"annee");
			//fin du formulaire
			$formulaire3->fin();
		}
	}

	
	if (!isset($_POST['boitetotal'])) {
		//initialisation du formulaire
		$formulaire2=new formulaire ("plaquefille","plaques.php","POST",true);
		$formulaire2->affiche_formulaire();
		//recherche des plaques mères dans la table plaque
		$sql="SELECT pla_identifiant_local,pla_id_plaque FROM plaque WHERE pla_id_plaque_mere='0'";
		$resultat=$dbh->query($sql);
		$nbresultat=$resultat->rowCount();
		if ($nbresultat>0) {
			while($row=$resultat->fetch(PDO::FETCH_NUM)) {
				$tab[$row[1]]=$row[0];
			}
			if (!isset($_POST['plaquera'])) $_POST['plaquera']="";  
			$formulaire2->ajout_select (1,"plaquera",$tab,false,$_POST['plaquera'],SELECPLA,PLAQUERA."<br/>",false,"onChange=submit()");
		}
		if (!empty($_POST['plaquera'])) {
			$sql="SELECT pla_identifiant_local,pla_id_plaque FROM plaque WHERE pla_id_plaque_mere='".$_POST['plaquera']."'";
			$resultat9=$dbh->query($sql);
			$nbresultat9=$resultat9->rowCount();
			if ($nbresultat9>0) {
				while($row9=$resultat9->fetch(PDO::FETCH_NUM)) {
					$tab9[$row9[1]]=$row9[0];
				}
				if (!isset($_POST['plaquera1'])) $_POST['plaquera1']="";
				$formulaire2->ajout_select (1,"plaquera1",$tab9,false,$_POST['plaquera1'],SELECPLA,""."<br/>",false,"onChange=submit()");
			}
			else unset ($_POST['plaquera1']);
		}
		
		if (!empty($_POST['plaquera1']) and !empty($_POST['plaquera'])) {
			$sql="SELECT pla_identifiant_local,pla_id_plaque FROM plaque WHERE pla_id_plaque_mere='".$_POST['plaquera1']."'";
			$resultat10=$dbh->query($sql);
			$nbresultat10=$resultat10->rowCount();
			if ($nbresultat10>0) {
				while($row10=$resultat10->fetch(PDO::FETCH_NUM)) {
					$tab10[$row10[1]]=$row10[0];
				}
				if (!isset($_POST['plaquera2'])) $_POST['plaquera2']="";
				$formulaire2->ajout_select (1,"plaquera2",$tab10,false,$_POST['plaquera2'],SELECPLA,""."<br/>",false,"onChange=submit()");
			}
			else unset ($_POST['plaquera2']);
		}
		$formulaire2->ajout_cache ($_POST['mois'],"mois");
		$formulaire2->ajout_cache ($_POST['jour'],"jour");
		$formulaire2->ajout_cache ($_POST['annee'],"annee");
		//fin du formulaire
		$formulaire2->fin();
	}
	
	echo "<br>";
  
	//initialisation du formulaire
	$formulaire=new formulaire ("plaquecrea","plaques.php","POST",true);
	$formulaire->affiche_formulaire();
	if (!empty($_POST['plaquera'])) {
		if (isset($_POST['plaquera2']) and !empty($_POST['plaquera2'])) $plaid=$_POST['plaquera2'];
		elseif (isset($_POST['plaquera1']) and !empty($_POST['plaquera1'])) $plaid=$_POST['plaquera1'];
		elseif (isset($_POST['plaquera']) and !empty($_POST['plaquera'])) $plaid=$_POST['plaquera'];
		$sql="SELECT pla_identifiant_local FROM plaque WHERE pla_id_plaque='$plaid'";
		$resultatpla=$dbh->query($sql);
		$rowpla=$resultatpla->fetch(PDO::FETCH_NUM);
		if(!isset($_POST['volprel'])) $_POST['volprel']="";
		$formulaire->ajout_text (5, $_POST['volprel'], 5, "volprel", VOLUMEPRE.$rowpla[0].DPOINT."<br/>","","");
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_unite_volume'";
		//les résultats sont retournées dans la variable $result8
		$result8=$dbh->query($sql);
		$row8=$result8->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$traitement=new traitement_requete_sql($row8[0]);
		$tab8=$traitement->imprime();
		if(!isset($_POST['unitevolprel'])) $_POST['unitevolprel']="";
		$formulaire->ajout_select (1,"unitevolprel",$tab8,false,$_POST['unitevolprel'],"","",false,"");
		print"<br/><br/>";
	}
	if ($_POST['massety']>0) $jscript="onChange=submit()";
	else $jscript="";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='lot_num_lot'";
	//les résultats sont retournées dans la variable $result1
	$result1=$dbh->query($sql);
	$row1=$result1->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row1[0]+1, $_POST['numlot'], $row1[0], "numlot", NUMEROLOT."<br/>","",$jscript);

	$sql="SELECT * FROM lot";
	$result6=$dbh->query($sql);
	$nbresult6=$result6->rowCount();
	if ($nbresult6>0) {
		while($row6=$result6->fetch(PDO::FETCH_NUM)) {
			$tab6[$row6[0]]=$row6[1];
		}
		print"&nbsp;&nbsp;".OU."&nbsp;&nbsp;";
		$formulaire->ajout_select (1,"ancienlot",$tab6,false,$_POST['ancienlot'],SELECPLA,LOTANCIEN,false,$jscript);
	}

	print"<br/><br/>";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pla_identifiant_local'";
	//les résultats sont retournées dans la variable $result2
	$result2=$dbh->query($sql);
	$row2=$result2->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row2[0]+1, $_POST['numplaque'], $row2[0], "numplaque", NUMERO."<br/>","",$jscript);
	
	print"<br/><br/>";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pla_identifiant_externe'";
	//les résultats sont retournées dans la variable $result2
	$result2=$dbh->query($sql);
	$row2=$result2->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row2[0]+1, $_POST['numextplaque'], $row2[0], "numextplaque", NUMEROEVOTEC."<br/>","",$jscript);
	
	print"<br/><br/>";
	//recherche de la liste des solvants sur la table solvant
	$sql="SELECT * FROM solvant ORDER BY sol_solvant ASC";
	//les résultats sont retournées dans la variable $result7
	$result7=$dbh->query($sql);
	while($row7 =$result7->fetch(PDO::FETCH_NUM)) {
		$tab7[$row7[0]]=constant($row7[1]);
	}
	$formulaire->ajout_select (1,"solvantplaque",$tab7,false,$_POST["solvantplaque"],"",SOLVANT."<br/>",false,$jscript);
	print"<br/><br/>";
	$formulaire->ajout_text (5, $_POST['volplaque'], 5, "volplaque", VOLUME."<br/>","",$jscript);
	$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_unite_volume'";
	//les résultats sont retournées dans la variable $result8
	$result8=$dbh->query($sql);
	$row8=$result8->fetch(PDO::FETCH_NUM);
	//traitement du resultat afin de retourner la taille maximale du champ
	$traitement=new traitement_requete_sql($row8[0]);
	$tab8=$traitement->imprime();
	$formulaire->ajout_select (1,"unitevol",$tab8,false,$_POST['unitevol'],"","",false,$jscript);	
	if (isset($_POST['plaquera2']) and !empty($_POST['plaquera2'])) $formulaire->ajout_cache ($_POST['plaquera2'],"plaquefil");
	elseif (isset($_POST['plaquera1']) and !empty($_POST['plaquera1'])) $formulaire->ajout_cache ($_POST['plaquera1'],"plaquefil");
	elseif (isset($_POST['plaquera']) and !empty($_POST['plaquera'])) $formulaire->ajout_cache ($_POST['plaquera'],"plaquefil");
	if (isset($_POST['boitetotal'])) $formulaire->ajout_cache ($_POST['boitetotal'],"boitetotal");
		
	$formulaire->ajout_cache ($_POST['mois'],"mois");
	$formulaire->ajout_cache ($_POST['jour'],"jour");
	$formulaire->ajout_cache ($_POST['annee'],"annee");
	print"<br/><br/>";
	if (empty($_POST['plaquera'])) {
		$tabmass[1]=MASSEMOY;
		$tabmass[2]=MASSEEXA;
		$formulaire->ajout_select (1,"massety",$tabmass,false,$_POST['massety'],SELECTMASSE,MASSE."<br/>",false,"onChange=\"submit()\"");	
	}
	else $formulaire->ajout_button (SUBMIT,"","button","onClick=\"Creaplaquefille(form)\"");
	$formulaire->fin();
	
	if ($_POST['massety']>0) {
		//initialisation du formulaire
		$formulaire5=new formulaire ("plaquemasse","creationplaque.php","POST",true);
		$formulaire5->affiche_formulaire();
		if($_POST['massety']==1) {
			print"<br/><br/>";
			$formulaire5->ajout_text (5, $_POST['conplaque'], 5, "conplaque", CONCENTRATION."<br/>",MOL,"");
			print"<br/><br/><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n<tr>\n<td width=\"20%\">";
			$formulaire5->ajout_text (5, $_POST['massplaque'], 5, "massplaque", MASSE."<br/>",MG,"");
			print"</td><td>";
			if (empty($_POST['plaquera'])) {
				$tab9[1]=DEFALK;
				$formulaire5->ajout_checkbox ("massetran",$tab9,$_POST['massetran'],"","false");
				echo "<p><img src=\"images/att.gif\" width=\"13\" height=\"10\">&nbsp;".ATTDEFALK."</p>";
			}
			print"</td>\n</tr>\n</table>\n<br/><br/>";
		}
		if($_POST['massety']==2) {
			$tab9[1]=DEFALK;
			$formulaire5->ajout_checkbox ("massetran",$tab9,$_POST['massetran'],"","false");
			echo "<p><img src=\"images/att.gif\" width=\"13\" height=\"10\">&nbsp;".ATTDEFALK."</p>";
		}
		if (isset($_POST['plaquera2']) and !empty($_POST['plaquera2'])) $formulaire5->ajout_cache ($_POST['plaquera2'],"plaquefil");
		elseif (isset($_POST['plaquera1']) and !empty($_POST['plaquera1'])) $formulaire5->ajout_cache ($_POST['plaquera1'],"plaquefil");
		elseif (isset($_POST['plaquera']) and !empty($_POST['plaquera'])) $formulaire5->ajout_cache ($_POST['plaquera'],"plaquefil");
		if (isset($_POST['boitetotal'])) $formulaire5->ajout_cache ($_POST['boitetotal'],"boitetotal");
		
		$formulaire5->ajout_cache ($_POST['mois'],"mois");
		$formulaire5->ajout_cache ($_POST['jour'],"jour");
		$formulaire5->ajout_cache ($_POST['annee'],"annee");
		$formulaire5->ajout_cache ($_POST['massety'],"massety");
		$formulaire5->ajout_cache ($_POST['unitevol'],"unitevol");
		$formulaire5->ajout_cache ($_POST['volplaque'],"volplaque");
		$formulaire5->ajout_cache ($_POST['solvantplaque'],"solvantplaque");
		$formulaire5->ajout_cache ($_POST['numplaque'],"numplaque");
		$formulaire5->ajout_cache ($_POST['numlot'],"numlot");
		$formulaire5->ajout_cache ($_POST['numextplaque'],"numextplaque");
		
		print"<br/><br/>";
		$formulaire5->ajout_button (SUBMIT,"","button","onClick=\"Getcreation(form)\"");
		//fin du formulaire
		$formulaire5->fin();
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