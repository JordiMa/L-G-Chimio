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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';

if(!isset($_POST["mol"])) $_POST["mol"]="";
if(!isset($_POST['equipe'])) $_POST['equipe']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['masse'])) $_POST['masse']="";
if(!isset($_POST['type'])) $_POST['type']="";
if(!isset($_POST['config'])) $_POST['config']="";
if(!isset($_POST['origimol'])) $_POST['origimol']="";
if(!isset($_POST['etapmol'])) $_POST['etapmol']="";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);

print"\n<script language=\"JavaScript\">
function GetSmiles(theForm){
  if(document.saisie.masse.value==\"\"){alert(\"".MASSABS."\");}
  else {
	if (document.saisie.origimol.value==\"\"){alert(\"".ORIGABS."\");}
	else {
		if (document.saisie.etapmol.value==\"\"){alert(\"".ETAPGABS."\");}";
if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}")  print"else {
                                         if (document.saisie.equipe.value==\"\"){alert(\"".EQUIPEABS."\");}";
print"  else {
    if (document.JME.smiles()=='') {alert(\"".DESSINSTRUC."\");}
    else {
			document.saisie.mol.value=document.JME.molFile();
			var resultat=value=document.JME.molFile().indexOf('STY');
			var resultat1=value=document.JME.molFile().indexOf('\$RXN');
			if (resultat==-1 && resultat1==-1) {
				theForm.submit();
			}
			else {
				alert(\"".DESSINSTRUC1."\");
			}";
if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}")  print"}";
print"    }
		}
	}
  }
}

</script>\n";

$formulaire1=new formulaire ("saisie","saisie2.php","POST",true);
$formulaire1->affiche_formulaire();
print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td width=\"500\" height=\"500\">";
if(isset($erreur)) print"<p class=\"erreur\">$erreur</p>";

$jsme=new dessinmoleculejsme(460,450,$_POST['mol']);
$jsme->imprime();

print"<td>".OBLIGATOIRE."<br/><br/><br/>";

if ($row[0]=="{CHEF}") {
	$sql="SELECT equi_id_equipe, equi_nom_equipe, chi_nom, chi_prenom, chi_id_chimiste FROM equipe, chimiste WHERE equi_id_equipe IN (SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable = '".$row[1]."' GROUP BY chi_id_equipe) AND chi_statut = '{RESPONSABLE}' AND chi_id_equipe = equi_id_equipe AND chi_id_responsable = '".$row[1]."' ORDER BY equi_nom_equipe";
	$result1=$dbh->query($sql);
	while($row1 = $result1->fetch(PDO::FETCH_NUM)) {
		$tab1[$row1[0]."/".$row1[4]]=$row1[1]." --- ".$row1[3]." ".$row1[2];
    }
	$formulaire1->ajout_select (1,"equipe",$tab1,false,$_POST['equipe'],SELECTEQUIPE,EQUIPE,false,"");
	print"<br/>\n<br/>\n";
}

if ($row[0]=="{ADMINISTRATEUR}") {
	$sql='SELECT equi_id_equipe, equi_nom_equipe, res.chi_id_chimiste, res.chi_nom, res.chi_prenom, chim.chi_id_chimiste ,chim.chi_nom, chim.chi_prenom FROM chimiste AS "chim" Inner Join chimiste AS "res" on res.chi_id_chimiste = chim.chi_id_responsable Inner Join equipe on chim.chi_id_equipe = equipe.equi_id_equipe WHERE res.chi_statut=\'{RESPONSABLE}\'';
	$result1=$dbh->query($sql);
	$nbresult1=$result1->rowCount();
	if ($nbresult1>0) {
		while($row1 = $result1->fetch(PDO::FETCH_NUM)) {
			$tab1[$row1[0]."/".$row1[2]."/".$row1[5]]=$row1[1]." --- ".$row1[3]." ".$row1[4]." --- ".$row1[6]." ".$row1[7];
		}
	}
	else $tab1="";

	$formulaire1->ajout_select (1,"equipe",$tab1,false,$_POST['equipe'],"Equipe - Responsable - Chimiste","* Equipe - Responsable - Chimiste :",false,"");
	print"<br/>\n<br/>\n";
}

//recherche des informations sur le champ pro_origine_substance
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_originesubstance';";
//les résultats sont retournées dans la variable $result
$result3=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row3=$result3->fetch(PDO::FETCH_NUM);
$traitement=new traitement_requete_sql($row3[0]);
$tab3=$traitement->imprime();
$sql="SELECT para_origin_defaut FROM parametres";
$resultpara =$dbh->query($sql);
$rowpara=$resultpara->fetch(PDO::FETCH_NUM);
$formulaire1->ajout_select (1,"origimol",$tab3,false,$rowpara[0],SELECTORIGINEMOL,ORIGINEMOL,false,"");
print"<br/>\n<br/>\n";

//recherche des informations sur le champ pro_etape_mol
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_etapemol';";
//les résultats sont retournées dans la variable $result
$result4=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row4=$result4->fetch(PDO::FETCH_NUM);
$traitement4=new traitement_requete_sql($row4[0]);
$tab4=$traitement4->imprime();

$formulaire1->ajout_select (1,"etapmol",$tab4,false,$_POST['etapmol'],SELECTETAPMOL,ETAPMOL,false,"");
print"<br/>\n<br/>\n";

$formulaire1->ajout_text (5, $_POST['masse'], 5, "masse", MASS,"","");
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_unitemasse';";
//les résultats sont retournées dans la variable $result
$result4=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row4=$result4->fetch(PDO::FETCH_NUM);
$traitement=new traitement_requete_sql($row4[0]);
$tab4=$traitement->imprime();
$formulaire1->ajout_select (1,"unitmass",$tab4,false,"MG","","",false,"");
$sql="SELECT typ_id_type,typ_type FROM type";
$result2 = $dbh->query($sql);
while($row2= $result2->fetch(PDO::FETCH_NUM)) {
	$tab2[$row2[0]]=constant ($row2[1]);
	}
unset($dbh);
print"<br/>\n<br/>\n";
$formulaire1->ajout_select (1,"type",$tab2,false,$_POST['type'],"",TYPE,false,"");
$formulaire1->ajout_cache ("","mol");
//$formulaire1->ajout_cache ("","inchikey");
// $formulaire1->ajout_cache ("","inchimd5");
// $formulaire1->ajout_cache ("","massemol");
// $formulaire1->ajout_cache ("","formulebrute");
// $formulaire1->ajout_cache ("","nom");
// $formulaire1->ajout_cache ("","logp");
// $formulaire1->ajout_cache ("","donorcount");
// $formulaire1->ajout_cache ("","acceptorcount");
// $formulaire1->ajout_cache ("","composition");
// $formulaire1->ajout_cache ("","aromaticatomcount");
// $formulaire1->ajout_cache ("","aromaticbondcount");
// $formulaire1->ajout_cache ("","rotatablebondcount");
// $formulaire1->ajout_cache ("","asymmetricatomcount");
print"</td>\n</tr>\n<tr>\n<td>\n";
print"<a href=\"images/CNBrochure.pdf\" target=\"_blank\"><strong>".RECOMMANDATION."</strong></a><br/><br/>";
$formulaire1->ajout_text (45, $_POST['config'], 256, "config", CONFIG,"","");
print"</td><td  align=\"right\">\n";
$formulaire1->ajout_button (SUBMIT,"","button","onClick=\"GetSmiles(form)\"");
print"</td>\n</tr>\n</table>";
$formulaire1->fin();
?>
