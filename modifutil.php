<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

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
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

if (!isset($etape)) $etape="";
if (!isset($_POST['nomequi'])) $_POST['nomequi']="";
if (!isset($_POST['iniequi'])) $_POST['iniequi']="";

//appelle le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table width=\"492\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
	</tr>
    </table><br/>";
	print"<script language=\"JavaScript\">
	  <!--
	  function Changer(theForm) {
		theForm.action=\"utilisateurmodif.php\";
		theForm.param.value=true;
		theForm.submit();
	  }
	  function Verif(theForm) {
		if (document.utilisateur.nom.value==\"\") {alert(\"".CHAMP." \'".NOM."\' ".RENSEIGNE."\");}
		  else {
				if (document.utilisateur.prenom.value==\"\") {alert(\"".CHAMP." \'".PRENOM."\' ".RENSEIGNE."\");}
				else {
					  if (document.utilisateur.email.value==\"\") {alert(\"".CHAMP." \'".COURRIEL."\' ".RENSEIGNE."\");}
					  else {theForm.submit();}
					  }
				}
		 }
	  function Verif1(theForm) {
		if (document.utilisateur1.equipe.value==\"\" && document.utilisateur1.nomequi.value==\"\") {alert(\"".CHAMP." \'".EQUIPE."\' ".RENSEIGNE."\");}
		else {
		   if ((document.utilisateur1.equipe.value==\"\" && document.utilisateur1.nomequi.value!=\"\" && document.utilisateur1.iniequi.value==\"\") || (document.utilisateur1.equipe.value==\"\" && document.utilisateur1.nomequi.value==\"\" && document.utilisateur1.iniequi.value!=\"\")) {alert(\"".CHAMP." \'".EQUIPE."\' ".RENSEIGNE."\");}
		   else { theForm.submit();}
		}
	  }
	  
	  function Verif4(theForm) {
			if (document.utilisateur1.nomequi.value==\"\" || document.utilisateur1.iniequi.value==\"\") {alert(\"".CHAMP." \'".EQUIPE."\' ".RENSEIGNE."\");} 
			else {
				theForm.submit();
				}
	  }
	  </script>";
	if (!empty($_GET["idutil"])) $utilid=$_GET["idutil"];
	if (!empty($_POST["idutil"])) $utilid=$_POST["idutil"];
	$sql="SELECT * FROM chimiste WHERE chi_id_chimiste='".$utilid."'";
	$resultat=$dbh->query($sql);
	$row=$resultat->fetch(PDO::FETCH_NUM);
  
	$formulaire=new formulaire ("utilisateur","utilisateurmodif.php","POST",true);
	$formulaire->affiche_formulaire();
	//recherche des informations sur le champ chi_nom
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_nom'";
	//les résultats sont retournées dans la variable $result1
	$result1=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row1=$result1->fetch(PDO::FETCH_NUM);
	if (!empty($_POST["nom"])) $nomutil=$_POST["nom"];
	else $nomutil=$row[1];
	$formulaire->ajout_text ($row1[0]+1,$nomutil,$row1[0],"nom",NOM.DEUX."<br/>","","onBlur=\"Verif(form)\"");
	print"<br/><br/>";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_prenom'";
	//les résultats sont retournées dans la variable $result2
	$result2=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row2=$result2->fetch(PDO::FETCH_NUM);
	if (!empty($_POST["prenom"])) $prenomutil=$_POST["prenom"];
	else $prenomutil=$row[2];
	$formulaire->ajout_text ($row2[0]+1,$prenomutil,$row2[0],"prenom",PRENOM.DEUX."<br/>","","onBlur=\"Verif(form)\"");
	print"<br/><br/>";

	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_email'";
	//les résultats sont retournées dans la variable $result3
	$result3=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row3=$result3->fetch(PDO::FETCH_NUM);
	if (!empty($_POST["email"])) $emailutil=$_POST["email"];
	else $emailutil=$row[4];
	$formulaire->ajout_text ($row3[0]+1,$emailutil,$row3[0],"email",COURRIEL.DEUX."<br/>","","onBlur=\"Verif(form)\"");
	print"<br/><br/>";
	if (!empty($_POST["langue"])) $lang=$_POST["langue"];
	else $lang=$row[6];
	$folder = dir(REPEPRINCIPAL."langues/");
	while($rept=$folder->read()) {
		if ($rept!='.' and $rept!='..' and $rept!='index.php')  $tab1[$rept]=$rept;
	}
	$formulaire->ajout_select (1,"langue",$tab1,false,$lang,"",LANGUE.DEUX."<br/>",false,"onChange=\"Verif(form)\"");
	print"<br/><br/>";
  
	//recherche le nombre d'équipes
	$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe order by equi_nom_equipe";
	$result4=$dbh->query($sql);
	$num4=$result4->fetch(PDO::FETCH_NUM);
	if ($num4>0) {
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_statut';";
		$result8=$dbh->query($sql);
		$row8=$result8->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row8[0]);
		
		if ($row[7]=="{RESPONSABLE}") {
			$sql="SELECT count(*) FROM chimiste WHERE chi_id_equipe='$row[9]' and chi_statut='$row[7]'";
			$result7=$dbh->query($sql);
			$row7=$result7->fetch(PDO::FETCH_NUM);
			if ($row7[0]<=1) {
				$tab8=array("RESPONSABLE"=>RESPONSABLE);
				$message=MESSMODIFUTIL;
			}
			else $tab8=$traitement->imprime();
		}
		else $tab8=$traitement->imprime();
	}
	else $tab8=array("ADMINISTRATEUR"=>ADMINISTRATEUR,"RESPONSABLE"=>RESPONSABLE);
	
	if (!empty($_POST["statut"])) $statututil=$_POST["statut"];
	else {
		$search= array('{','}');
		$row[7]=str_replace($search,'',$row[7]);
		$statututil=$row[7];
	}
	
	if (isset($message)) print"<p class=\"messagederreur\">$message</p>";
  
	$formulaire->ajout_select (1,"statut",$tab8,false,$statututil,"",STATUT.DEUX."<br/>",false,"onChange=\"Verif(form)\"");
	print"<br/><br/>";
	$formulaire->ajout_cache ($utilid,"idutil");
	$formulaire->ajout_cache (true,"param");
	$formulaire->ajout_cache ($row[7],"exstatu");
	//fin du formulaire
	$formulaire->fin();
  
	if (!empty($_POST['statut'])) $statut=$_POST['statut'];
	else {
		$search= array('{','}');
		$row[7]=str_replace($search,'',$row[7]);
		$statut=$row[7];
	}

	if ($statut=="RESPONSABLE" or $statut=="CHIMISTE") {
		$formulaire1=new formulaire ("utilisateur1","utilisateurmodif.php","POST",true);
		$formulaire1->affiche_formulaire();

		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td valign=\"top\">";
		$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe order by equi_nom_equipe";
		$result4=$dbh->query($sql);
		$num4=$result4->rowCount();
		if ($num4>0) {
			if (isset($_POST['equipe'])) {
				if (!empty($_POST['equipe'])) $equi=$_POST['equipe'];
				else $equi="";
			}
			else $equi=$row[9];
			while($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab4[$row4[0]]=$row4[1];
			}
			$formulaire1->ajout_select (1,"equipe",$tab4,false,$equi,SELECEQUIPE,EQUIPE.DEUX."<br/>",false,"onChange=\"form.submit()\"");
		}
		if ($statut=="RESPONSABLE") {
			if ($num4>0) print"</td><td>".OU."</td><td>";
			else print"</td><td>";
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_nom_equipe'";
			//les résultats sont retournées dans la variable $result
			$result6=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row6=$result6->fetch(PDO::FETCH_NUM);
			$formulaire1->ajout_text ($row6[0]+1,$_POST['nomequi'],$row6[0],"nomequi",NEWEQUIPE.DEUX."<br/>","","");
			print"<br/>";
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_initiale_numero'";
			//les résultats sont retournées dans la variable $result
			$result7=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row7=$result7->fetch(PDO::FETCH_NUM);
			$formulaire1->ajout_text ($row7[0]+1,$_POST['iniequi'],$row7[0],"iniequi",INIEQUIPE.DEUX."<br/>","","");
		}
		print"</td></tr></table>";
		print"<br/><br/>";
		$formulaire1->ajout_cache ($nomutil,"nom");
		$formulaire1->ajout_cache ($prenomutil,"prenom");
		$formulaire1->ajout_cache ($emailutil,"email");
		$formulaire1->ajout_cache ($lang,"langue");
		$formulaire1->ajout_cache ($statututil,"statut");
		$formulaire1->ajout_cache ($utilid,"idutil");
		$formulaire1->ajout_cache ($row[7],"exstatu");
      
		if (!$num4>0 and $statut=="RESPONSABLE")  {
			$etape=ETAPE2;
			$onclic="onClick=\"Verif4(form)\"";
			$formulaire1->ajout_cache ("false","param");
			$formulaire1->ajout_button ($etape,"","button",$onclic);
		}
		elseif($statut=="RESPONSABLE" and empty($equi) and empty($_POST['nomequi']) and empty($_POST['iniequi'])) {
			$etape=ETAPE1;
			$onclic="onClick=\"Verif1(form)\"";
			$formulaire1->ajout_cache (true,"param");
			$formulaire1->ajout_button ($etape,"","button",$onclic);
		}
		else $formulaire1->ajout_cache (true,"param");
		//fin du formulaire1
		$formulaire1->fin();

		if ($etape!=ETAPE1 and (!empty($equi) or !empty($row[8]) or (!empty($_POST['nomequi']) and !empty($_POST['iniequi'])))) {
        
			if (!empty($_POST["responsable"])) $responsableutil=$_POST["responsable"];
			else $responsableutil=$row[8];
        
			$formulaire2=new formulaire ("utilisateur2","utilisateurmodif.php","POST",true);
			$formulaire2->affiche_formulaire();
			switch ($statut) {
				case "CHIMISTE" : {
					$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_id_chimiste IN (SELECT chi_id_chimiste FROM chimiste WHERE chi_id_equipe='".$equi."' and chi_id_chimiste<>'$utilid' and chi_statut='{RESPONSABLE}')";
					$result5=$dbh->query($sql);
					$nbresult5=$result5->rowCount();
					while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
						$tab5[$row5[2]]=$row5[0]." ".$row5[1];
					}
					$selectres=SELECRESPON;
					$respons=RESPONSABLE;
					break;
				}
				case "RESPONSABLE" : {
					$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_statut='{CHEF}' and chi_id_chimiste<>'$utilid'";
					$result5=$dbh->query($sql);
					$nbresult5=$result5->rowCount();
					while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
						$tab5[$row5[2]]=$row5[0]." ".$row5[1];
					}
					$selectres=SELECHEF;
					$respons=CHEF;
					break;
				}
			}
			$formulaire2->ajout_select (1,"responsable",$tab5,false,$responsableutil,$selectres,$respons.DEUX."<br/>",false,"");
			print"<br/><br/>";

			if (!empty($equi)) $formulaire2->ajout_cache ($equi,"equipe");
			if (!empty($_POST["nomequi"])) $formulaire2->ajout_cache ($_POST["nomequi"],"nomequi");
			if (!empty($_POST["iniequi"])) $formulaire2->ajout_cache ($_POST["iniequi"],"iniequi");
			$formulaire2->ajout_cache ($nomutil,"nom");
			$formulaire2->ajout_cache ($prenomutil,"prenom");
			$formulaire2->ajout_cache ($emailutil,"email");
			$formulaire2->ajout_cache ($lang,"langue");
			$formulaire2->ajout_cache ($statututil,"statut");
			$formulaire2->ajout_cache ($utilid,"idutil");
			$formulaire2->ajout_cache ("false","param");
			$formulaire2->ajout_cache ($row[7],"exstatu");
			print"<br/><br/>";
			if ($nbresult5>0 or $statututil=="RESPONSABLE") $formulaire2->ajout_button (ETAPE2,"sauv","submit","");
			//fin du formulaire
			$formulaire2->fin();
		}
    }
    elseif ($statut=="CHEF") {
       
		$formulaire2=new formulaire ("utilisateur2","utilisateurmodif.php","POST",true);
		$formulaire2->affiche_formulaire();
		$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_statut='{RESPONSABLE}' and chi_id_chimiste<>'$utilid'";
		$result5=$dbh->query($sql);
		$num5=$result5->rowCount();
		if ($num5>0) {
			while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
				$tab5[$row5[2]]=$row5[0]." ".$row5[1];
			}
			if (empty($_POST['srespo'])) {
				$sql="SELECT chi_id_chimiste FROM chimiste WHERE chi_id_responsable='$utilid'";
				$result6=$dbh->query($sql);
				$num6=$result6->rowCount();
				if ($num6>0) {
					while ($row6=$result6->fetch(PDO::FETCH_NUM)) {
						$srespo[]=$row6[0];
					}
				}
				else $srespo="";
			}
			else $srespo=$_POST['srespo'];
			$formulaire2->ajout_select (3,"srespo",$tab5,true,$srespo,"",RESPONSABLE.DEUX."<br/>",false,"");
		}
		print"<br/><br/>\n";
		$formulaire2->ajout_cache ($nomutil,"nom");
		$formulaire2->ajout_cache ($prenomutil,"prenom");
		$formulaire2->ajout_cache ($emailutil,"email");
		$formulaire2->ajout_cache ($lang,"langue");
		$formulaire2->ajout_cache ($statututil,"statut");
		$formulaire2->ajout_cache ($row[7],"exstatu");
		$formulaire2->ajout_cache ($utilid,"idutil");
		$formulaire2->ajout_cache ("false","param");
		print"<br/><br/>";
		$formulaire2->ajout_button (ETAPE2,"sauv","submit","");
		//fin du formulaire
		$formulaire2->fin();
    }
    elseif ($statut=="ADMINISTRATEUR") {
		$formulaire2=new formulaire ("utilisateur2","utilisateurmodif.php","POST",true);
		$formulaire2->affiche_formulaire();
		$formulaire2->ajout_cache ($nomutil,"nom");
		$formulaire2->ajout_cache ($prenomutil,"prenom");
		$formulaire2->ajout_cache ($emailutil,"email");
		$formulaire2->ajout_cache ($lang,"langue");
		$formulaire2->ajout_cache ($statututil,"statut");
		$formulaire2->ajout_cache ($row[7],"exstatu");
		$formulaire2->ajout_cache ($utilid,"idutil");
		$formulaire2->ajout_cache ("false","param");
		print"<br/><br/>";
		$formulaire2->ajout_button (ETAPE2,"sauv","submit","");
		//fin du formulaire
		$formulaire2->fin();
    }
}
else require 'deconnexion.php';
unset($dbh);
?>