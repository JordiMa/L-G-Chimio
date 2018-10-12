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
//include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_export.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"exportation.php\">".SDF."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"exportationcsvpesee.php\">".CSV."</a></td>
		</tr>
		</table><br/>";
	if (!isset($_POST["typ1"])) $_POST["typ1"]="";
	if (!isset($_POST["sup1"])) $_POST["sup1"]="";
	if (!isset($_POST["equipe"])) $_POST["equipe"]="";
	if (!isset($_POST["sup"])) $_POST["sup"]="";
	if (!isset($_POST["masvrac"])) $_POST["masvrac"]="";
	
	print"<script language=\"JavaScript\">
		  function Postdown(theForm){
			  theForm.action=\"exportsdf.php\";
			  theForm.target=\"_blank\";
		  }
		  
		  function Emaildown(theForm,eval){
			  theForm.temail.value=eval;
			  theForm.action=\"emailsdf.php\";
			  theForm.target=\"_blank\";
		  }

		  function Retablir(theForm){
			theForm.action=\"exportation.php\";
			theForm.target=\"_self\";
			theForm.submit();
		  }
		  </script><br/>";
		  
	print"<h4>".EXPEQUIPE."</h4>";
	$formulaire=new formulaire ("datecible","exportation.php","POST",true);
	$formulaire->affiche_formulaire();
	print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>";
	$sql="SELECT equi_id_equipe,equi_nom_equipe FROM equipe";
	$resultat=$dbh->query($sql);
	$numresultat=$resultat->rowCount();
	if ($numresultat>0) {
		while($row=$resultat->fetch(PDO::FETCH_NUM)) {
			$tab[$row[0]]=$row[1];
		}
	}
	else $tab="";
	print"<td>";
	$formulaire->ajout_select (3,"equipe",$tab,true,$_POST["equipe"],SELECTEQUIP,EQUIPE."<br/>",false,"onChange=Retablir(form)");
	print"</td><td>";
	$sql="SELECT para_stock FROM parametres";
	$resultat4=$dbh->query($sql);
	$row4=$resultat4->fetch(PDO::FETCH_NUM);
	if (!isset($_POST['massemini'])) {
		if ($row4[0]==1) {
			$_POST['massemini']=1;
			$_POST["sup"]=rawurlencode(">=");
		}	
		else {
			$_POST['massemini']=$row4[0]-1;
			$_POST["sup"]=rawurlencode(">");
		}	
	}
	$tab4[rawurlencode(">")]=">";
	$tab4[rawurlencode(">=")]="&ge;";
	$tab4[rawurlencode("<")]="<";
	$tab4[rawurlencode("=")]="=";
	$formulaire->ajout_select (1,"sup",$tab4,false,$_POST["sup"],"",MASSE."<br/>",false,"");
	$formulaire->ajout_text (3,$_POST['massemini'],3,"massemini"," ",MG,"");
	print"</td>";
   if (isset($_POST["equipe"]) and !empty($_POST["equipe"]) and !isset($_POST["etudiant"]) and empty($_POST["etudiant"])) {
		print"<td>";
		$formulaire->ajout_cache ("","temail");
		$formulaire->ajout_buttonimage ("","download","image","onclick=Postdown(form)","images/charge.gif",TELE);
		print"&nbsp;&nbsp;";
		$formulaire->ajout_buttonimage ("","email","image","onclick=Emaildown(form,1)","images/arob.gif",MAIL);
		print"&nbsp;&nbsp;";
		$formulaire->ajout_buttonimage ("","email","image","onclick=Emaildown(form,2)","images/arogchimio.gif",MAIL1);
		print"</td>";
   }
   if (isset($_POST["equipe"]) and !empty($_POST["equipe"])) {
		if(!isset($_POST["etudiant"])) $_POST["etudiant"]="";
		$i=0;
		$equi="";
		foreach ($_POST["equipe"] as $elem) {
			if ($i>0 and $i<count($_POST["equipe"])) $equi.=" or ";
			$equi.="chi_id_equipe='$elem'";
			$i++;
		}
		$sql="SELECT chi_id_chimiste,chi_nom,chi_prenom FROM chimiste WHERE $equi";
		$resultat1=$dbh->query($sql);
		while($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
		   $tab1[$row1[0]]=$row1[2]." ".$row1[1];
		}
		print"<td>";
		$formulaire->ajout_select (3,"etudiant",$tab1,true,$_POST["etudiant"],SELECTPERS,PERSO."<br/>",false,"onChange=Retablir(form)");
		print"</td>";
   }
   if (isset($_POST["equipe"]) and !empty($_POST["equipe"]) and isset($_POST["etudiant"]) and !empty($_POST["etudiant"])) {
		if(!isset($_POST["typ"])) $_POST["typ"]="";
		$sql="SELECT typ_id_type,typ_type FROM type";
		$resultat2=$dbh->query($sql);
		while($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
			$tab2[$row2[0]]=constant($row2[1]);
		}
		print"<td>";
		$formulaire->ajout_select (3,"typ",$tab2,true,$_POST["typ"],"",TYPE."<br/>",false,"");
		print"&nbsp;&nbsp;";
		$formulaire->ajout_cache ("","temail");
		$formulaire->ajout_buttonimage ("","download","image","onclick=Postdown(form)","images/charge.gif",TELE);
		print"&nbsp;&nbsp;";
		$formulaire->ajout_buttonimage ("","email","image","onclick=Emaildown(form,1)","images/arob.gif",MAIL);
		print"&nbsp;&nbsp;";
		$formulaire->ajout_buttonimage ("","email","image","onclick=Emaildown(form,2)","images/arogchimio.gif",MAIL1);
		print"</td>";
	}
	print"</tr><tr><td>&nbsp;</td><td><input type=\"checkbox\" value=\"2\" name=\"masvrac\"";
	if ($_POST["masvrac"]==2) echo " checked ";
	print"><strong>".PLAQUE;
	if (isset($_POST["equipe"]) and !empty($_POST["equipe"])) print"<td>&nbsp;</td>";
	if (isset($_POST["equipe"]) and !empty($_POST["equipe"]) and isset($_POST["etudiant"]) and !empty($_POST["etudiant"])) print"<td>&nbsp;</td>";
	print"</tr></table>";
	//fin du formulaire
	$formulaire->fin();
	print"<hr><h4>".EXPTYPE."</h4>";
	$formulaire1=new formulaire ("test","exportsdf.php","POST",true);
	$formulaire1->affiche_formulaire();
	$sql="SELECT typ_id_type,typ_type FROM type";
    $resultat3=$dbh->query($sql);
    while($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
		$tab3[$row3[0]]=constant($row3[1]);
    }
    print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr><td>";
	$formulaire1->ajout_select (3,"typ1",$tab3,true,$_POST["typ1"],"",TYPE."<br/>",false,"");
    print"</td><td>";
	if (!isset($_POST['massemini1'])) {
		if ($row4[0]==1) {
			$_POST['massemini1']=1;
			$_POST["sup1"]=rawurlencode(">=");
		}	
		else {
			$_POST['massemini1']=$row4[0]-1;
			$_POST["sup1"]=rawurlencode(">");
		}	
	}
	//if (!isset($_POST['massemini1'])) $_POST['massemini1']=$row4[0]-1;
	$formulaire1->ajout_select (1,"sup1",$tab4,false,$_POST["sup1"],"",MASSE."<br/>",false,"");
	$formulaire1->ajout_text (3,$_POST['massemini1'],3,"massemini1"," ",MG,"");
	print"</td><td>";
	print"&nbsp;&nbsp;";
	$formulaire1->ajout_cache ("","temail");
	$formulaire1->ajout_buttonimage ("","download","image","onclick=Postdown(form)","images/charge.gif",TELE);
	print"&nbsp;&nbsp;";
	$formulaire1->ajout_buttonimage ("","email","image","onclick=Emaildown(form,1)","images/arob.gif",MAIL);
	print"&nbsp;&nbsp;";
	$formulaire1->ajout_buttonimage ("","email","image","onclick=Emaildown(form,2)","images/arogchimio.gif",MAIL1);
	print"</td></tr>";
	print"<tr><td>&nbsp;</td><td><input type=\"checkbox\" value=\"2\" name=\"masvrac\"><strong>".PLAQUE;   
	print"</strong></td></tr></table>";
	//fin du formulaire
	$formulaire1->fin();
}
else require 'deconnexion.php';
unset($dbh);
?>