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
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

if(!isset($_POST['nom'])) $_POST['nom']="";
if(!isset($_POST['prenom'])) $_POST['prenom']="";
if(!isset($_POST['email'])) $_POST['email']="";
if(!isset($_POST['langue'])) $_POST['langue']="";
if(!isset($_POST['statut'])) $_POST['statut']="";
if(!isset($_POST['equipe'])) $_POST['equipe']="";
if(!isset($_POST['responsable'])) $_POST['responsable']="";
if(!isset($_POST['nomequi'])) $_POST['nomequi']="";
if(!isset($_POST['iniequi'])) $_POST['iniequi']="";
if(!isset($_POST['srespo'])) $_POST['srespo']="";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
  print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"ttip.js\"></script>";
  print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
  	<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>

    </tr>
    </table><br/>";
   print"<script language=\"JavaScript\">
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

  function Verif2(theForm) {
       if (document.utilisateur2.responsable.value!=\"\")  {
         theForm.action=\"incritutil.php\";
         theForm.submit();
         }
       else {alert(\"".CHAMP." \'".RESPONSABLE."\' ".RENSEIGNE."\");}
  }
  function Verif3(theForm) {
         theForm.action=\"incritutil.php\";
         theForm.submit();
  }
  function Verif4(theForm) {
        if (document.utilisateur1.nomequi.value==\"\" || document.utilisateur1.iniequi.value==\"\") {alert(\"".CHAMP." \'".EQUIPE."\' ".RENSEIGNE."\");}
		else {
			theForm.action=\"incritutil.php\";
			theForm.submit();
			}
  }
  </script>";
   //initialisation du formulaire
	$formulaire=new formulaire ("utilisateur","utilisateurajout.php","POST",true);
	$formulaire->affiche_formulaire();
	//recherche des informations sur le champ chi_nom
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_nom'";
	//les résultats sont retournées dans la variable $result1
	$result1=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row1=$result1->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row1[0]+1,$_POST['nom'],$row1[0],"nom",NOM.DEUX."<br/>","","");
	print"<br/>";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_prenom'";
	//les résultats sont retournées dans la variable $result2
	$result2=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row2=$result2->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row2[0]+1,$_POST['prenom'],$row2[0],"prenom",PRENOM.DEUX."<br/>","","");
	print"<br/>";

	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='chi_email'";
	//les résultats sont retournées dans la variable $result3
	$result3=$dbh->query($sql);
	//Les résultats son mis sous forme de tableau
	$row3=$result3->fetch(PDO::FETCH_NUM);
	$formulaire->ajout_text ($row3[0]+1,$_POST['email'],$row3[0],"email",COURRIEL.DEUX."<br/>","","");
	print"<br/>";
	$folder = dir(REPEPRINCIPAL."langues/");
	while($rept=$folder->read()) {
		if ($rept!='.' and $rept!='..' and $rept!='index.php')  $tab1[$rept]=$rept;
	}
	$formulaire->ajout_select (1,"langue",$tab1,false,$_POST['langue'],SELECTLANGUE,LANGUE.DEUX."<br/>",false,"");
	print"<br/>";
	//recherche le nombre d'équipes
	$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe order by equi_nom_equipe";
	$result4=$dbh->query($sql);
	$num4=$result4->rowCount();
	if ($num4>0) {
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_statut'";
		$result8=$dbh->query($sql);
		$row8=$result8->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$traitement=new traitement_requete_sql($row8[0]);
		$tab8=$traitement->imprime();
	}
	else $tab8=array("{ADMINISTRATEUR}"=>ADMINISTRATEUR,"{RESPONSABLE}"=>RESPONSABLE);
	$formulaire->ajout_select (1,"statut",$tab8,false,$_POST['statut'],SELECTSTATUT,STATUT.DEUX."<br/>",false,"onChange=\"Verif(form)\"");
	print"&nbsp;<a href=\"#\" onmouseover=\"ddrivetip('<p align=\'center\'>".AddSlashes(AIDESTATU)."</p><p align=\'center\'><img src=\'images/hierachie".$_SESSION['langue'].".gif\'></p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/aide.gif\" /></a>";
	print"<br/><br/>";
	//fin du formulaire
	$formulaire->fin();

	if (!empty($_POST['statut'])) {
		if ($_POST['statut']=="RESPONSABLE" or $_POST['statut']=="CHIMISTE") {
			$formulaire1=new formulaire ("utilisateur1","utilisateurajout.php","POST",true);
			$formulaire1->affiche_formulaire();
			$etape=ETAPE1;
			$onclic="onClick=\"Verif1(form)\"";
			print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
				<td valign=\"top\">";
			$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe order by equi_nom_equipe";
			$result4=$dbh->query($sql);
			$num4=$result4->rowCount();
			if ($num4>0) {
				while($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab4[$row4[0]]=$row4[1];
				}
				if ($_POST['statut']=="CHIMISTE") $change="onChange=\"Verif1(form)\"";
				else $change="";
				$formulaire1->ajout_select (1,"equipe",$tab4,false,$_POST['equipe'],SELECEQUIPE,EQUIPE.DEUX."<br/>",false,$change);
			}
			if ($_POST['statut']=="RESPONSABLE") {
				if ($num4>0) print"</td><td>".OU."</td><td>";
				else print"</td><td>";
				$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_nom_equipe'";
				//les résultats sont retournées dans la variable $result6
				$result6=$dbh->query($sql);
				//Les résultats son mis sous forme de tableau
				$row6=$result6->fetch(PDO::FETCH_NUM);
				$formulaire1->ajout_text ($row6[0]+1,$_POST['nomequi'],$row6[0],"nomequi",NEWEQUIPE.DEUX."<br/>","","");
				print"<br/>";
				$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_initiale_numero'";
				//les résultats sont retournées dans la variable $result7
				$result7=$dbh->query($sql);
				//Les résultats son mis sous forme de tableau
				$row7=$result7->fetch(PDO::FETCH_NUM);
				$formulaire1->ajout_text ($row7[0]+1,$_POST['iniequi'],$row7[0],"iniequi",INIEQUIPE.DEUX."<br/>","","");
			}

			print"</td></tr></table>";
			print"<br/><br/>";
			$formulaire1->ajout_cache ($_POST['nom'],"nom");
			$formulaire1->ajout_cache ($_POST['prenom'],"prenom");
			$formulaire1->ajout_cache ($_POST['email'],"email");
			$formulaire1->ajout_cache ($_POST['langue'],"langue");
			$formulaire1->ajout_cache ($_POST['statut'],"statut");
			if (!$num4>0 and $_POST['statut']=="RESPONSABLE")  {
				$etape=ETAPE2;
				$onclic="onClick=\"Verif4(form)\"";
				$formulaire1->ajout_button ($etape,"","button",$onclic);
			}
			elseif ($_POST['statut']=="RESPONSABLE" and empty($_POST["equipe"]) and empty($_POST['nomequi']) and empty($_POST['iniequi'])) $formulaire1->ajout_button ($etape,"","button",$onclic);
			//fin du formulaire1
			$formulaire1->fin();


			if (!empty($_POST["equipe"]) or (!empty($_POST['nomequi']) and !empty($_POST['iniequi']))) {
				$formulaire2=new formulaire ("utilisateur2","utilisateurajout.php","POST",true);
				$formulaire2->affiche_formulaire();
				switch ($_POST['statut']) {
					case "CHIMISTE" : {
						$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_id_chimiste IN (SELECT chi_id_chimiste FROM chimiste WHERE chi_id_equipe='".$_POST['equipe']."' and chi_statut='{RESPONSABLE}')";
						$result5=$dbh->query($sql);
						$num5=$result5->rowCount();
						while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
							$tab5[$row5[2]]=$row5[0]." ".$row5[1];
						}
						$onclic="onClick=\"Verif2(form)\"";
						$selectres=SELECRESPON;
						$respons=RESPONSABLE;
						break;
					}
					case "RESPONSABLE" : {
						$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_statut='{CHEF}'";
						$result5=$dbh->query($sql);
						$num5=$result5->rowCount();
						while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
							$tab5[$row5[2]]=$row5[0]." ".$row5[1];
						}
						$onclic="onClick=\"Verif3(form)\"";
						$selectres=SELECHEF;
						$respons=CHEF;
						break;
					}
				}
				if ($num5>0) $formulaire1->ajout_select (1,"responsable",$tab5,false,$_POST['responsable'],$selectres,$respons.DEUX."<br/>",false,"");
				print"<br/><br/>";
				$etape=ETAPE2;
				if (!empty($_POST["equipe"])) $formulaire2->ajout_cache ($_POST["equipe"],"equipe");
				if (!empty($_POST["nomequi"])) $formulaire2->ajout_cache ($_POST["nomequi"],"nomequi");
				if (!empty($_POST["iniequi"])) $formulaire2->ajout_cache ($_POST["iniequi"],"iniequi");
				$formulaire2->ajout_cache ($_POST['nom'],"nom");
				$formulaire2->ajout_cache ($_POST['prenom'],"prenom");
				$formulaire2->ajout_cache ($_POST['email'],"email");
				$formulaire2->ajout_cache ($_POST['langue'],"langue");
				$formulaire2->ajout_cache ($_POST['statut'],"statut");
				$formulaire2->ajout_button ($etape,"","button",$onclic);
				//fin du formulaire2
				$formulaire2->fin();
			}
		}
		elseif ($_POST['statut']=="CHEF") {
			$formulaire1=new formulaire ("utilisateur1","incritutil.php","POST",true);
			$formulaire1->affiche_formulaire();
			$sql="SELECT chi_prenom,chi_nom,chi_id_chimiste FROM chimiste WHERE chi_statut='{RESPONSABLE}'";
			$result5=$dbh->query($sql);
			$num5=$result5->rowCount();
			if ($num5>0) {
				while ($row5=$result5->fetch(PDO::FETCH_NUM)) {
					$tab5[$row5[2]]=$row5[0]." ".$row5[1];
				}
				$formulaire1->ajout_select (3,"srespo",$tab5,true,$_POST['srespo'],"",RESPONSABLE.DEUX."<br/>",false,"");
			}
			print"<br/><br/>\n";
			$etape=ETAPE2;
			$onclic="onClick=\"Verif(form)\"";
			$formulaire1->ajout_cache ($_POST['nom'],"nom");
			$formulaire1->ajout_cache ($_POST['prenom'],"prenom");
			$formulaire1->ajout_cache ($_POST['email'],"email");
			$formulaire1->ajout_cache ($_POST['langue'],"langue");
			$formulaire1->ajout_cache ($_POST['statut'],"statut");
			$formulaire1->ajout_button ($etape,"","button",$onclic);
			//fin du formulaire1
			$formulaire1->fin();
		}
		elseif ($_POST['statut']=="ADMINISTRATEUR") {
			$formulaire1=new formulaire ("utilisateur1","incritutil.php","POST",true);
			$formulaire1->affiche_formulaire();
			$etape=ETAPE2;
			$onclic="onClick=\"Verif(form)\"";
			$formulaire1->ajout_cache ($_POST['nom'],"nom");
			$formulaire1->ajout_cache ($_POST['prenom'],"prenom");
			$formulaire1->ajout_cache ($_POST['email'],"email");
			$formulaire1->ajout_cache ($_POST['langue'],"langue");
			$formulaire1->ajout_cache ($_POST['statut'],"statut");
			$formulaire1->ajout_button ($etape,"","button",$onclic);
			//fin du formulaire1
			$formulaire1->fin();
		}
	}
}
else require 'deconnexion.php';
unset($dbh);
?>
