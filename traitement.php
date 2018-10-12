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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';

if(!isset($_POST['contrat'])) $_POST['contrat']="";
if(!isset($_POST['numbrevet'])) $_POST['numbrevet']="";
if(!isset($_POST['duree'])) $_POST['duree']="";
if(!isset($_POST['numerique'])) $_POST['numerique']="";
if(!isset($_POST['sansmasse'])) $_POST['sansmasse']="";
if(!isset($_POST['boite'])) $_POST['boite']=0;
if(!isset($_POST['coordonnee'])) $_POST['coordonnee']="";
if(!isset($_POST['precaution'])) $_POST['precaution']=NULL;

if(!isset($_POST['logp'])) $_POST['logp']="0";
if(!isset($_POST['acceptorcount'])) $_POST['acceptorcount']="0";
if(!isset($_POST['rotatablebondcount'])) $_POST['rotatablebondcount']="0";
if(!isset($_POST['aromaticatomcount'])) $_POST['aromaticatomcount']="0";
if(!isset($_POST['aromaticbondcount'])) $_POST['aromaticbondcount']="0";
if(!isset($_POST['donorcount'])) $_POST['donorcount']="0";
if(!isset($_POST['asymmetricatomcount'])) $_POST['asymmetricatomcount']="0";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
  
$date=date("Y-m-d H:i:s");
$erreur='';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
$result5=$dbh->query($sql);
$row5 =$result5->fetch(PDO::FETCH_NUM);
  
if ($row5[0]=="{ADMINISTRATEUR}" or $row5[0]=="{CHEF}") {
    $id_equipe=$_POST['equipe'];
    $nam=$row5[1];
	$responsable=$_POST['responsable'];
}

else {
    $sql="SELECT chi_id_chimiste,chi_id_equipe,chi_id_responsable FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
    $result2=$dbh->query($sql);
    $rop=$result2->fetch(PDO::FETCH_NUM);
    $nam=$rop[0];
    $id_equipe=$rop[1];
	if ($row5[0]=="{RESPONSABLE}" and $rop[2]==0) $responsable =$rop[0];
	else $responsable = $rop[2];
}

//recherche dans la base pour savoir si la structure existe
$sql="SELECT str_id_structure,str_nom FROM structure WHERE str_inchi_md5='".$_POST['inchikey']."'";
$result1 = $dbh->query($sql);
if($result1==false) {
			$erreur=1;
			echo "<p align=\"center\" class=\"erreur\">";
			print_r ($dbh->errorInfo());
			echo "</p>";
		}
$id_structure = $result1->fetch(PDO::FETCH_NUM);

if (empty($id_structure[0])) {
    //requête d'insertion des données dans la table "structure" pour une nouvelle structure
    $sql="INSERT INTO structure (str_nom, str_mol, str_inchi, str_formule_brute, str_masse_molaire, str_analyse_elem, str_date, str_inchi_md5, str_logp, str_acceptorcount, str_rotatablebondcount, str_aromaticatomcount, str_donorcount, str_asymmetricatomcount, str_aromaticbondcount) VALUES ('".AddSlashes($_POST['nomiupac'])."','".rawurldecode($_POST['mol'])."','".rawurldecode($_POST['inchi'])."','".$_POST['formulebrute']."','".$_POST['massemol']."', '".$_POST['composition']."', '".$date."', '".rawurldecode($_POST['inchikey'])."', '".$_POST['logp']."', '".$_POST['acceptorcount']."', '".$_POST['rotatablebondcount']."', '".$_POST['aromaticatomcount']."', '".$_POST['donorcount']."', '".$_POST['asymmetricatomcount']."', '".$_POST['aromaticbondcount']."')";
    $insertion=$dbh->exec($sql);	
    if($insertion==false) print_r ($dbh->errorInfo());
    $lastId = $dbh->lastInsertId('structure_str_id_structure_seq');
    //entrée des précautions éventuelles
    if (!empty($_POST['precaution'])) {
		foreach($_POST['precaution'] as $elem) {
			$sql="INSERT INTO liste_precaution (lis_id_precaution,lis_id_structure) VALUES ('$elem','$lastId')";
			$insertion=$dbh->exec($sql);
			$error=$dbh->errorInfo();
			if($insertion==false) {
				$erreur=1;
				echo "<p align=\"center\" class=\"erreur\">";
				print_r ($dbh->errorInfo());
				echo "</p>";
		}
		}  
    }
}
else {
    $lastId=$id_structure[0];
    //si la structure est déjà connue et que le nom entré est différent que celui de la base celui-ci est modifié
    if ($id_structure[1]!=$_POST['nomiupac']) {
		$sql="UPDATE structure SET str_nom='".AddSlashes($_POST['nomiupac'])."' WHERE str_inchi_md5='".$_POST['inchikey']."'";
		$update=$dbh->exec($sql);
		if($update==false) {
			$erreur=1;
			echo "<p align=\"center\" class=\"erreur\">";
			print_r ($dbh->errorInfo());
			echo "</p>";
		}
    }
    if (sizeof($_POST['precaution'])>0) {
		$sql="DELETE FROM liste_precaution where lis_id_structure='$lastId'";
		$delete=$dbh->exec($sql);
		if($delete==false) {
			$erreur=1;
			echo "<p align=\"center\" class=\"erreur\">";
			print_r ($dbh->errorInfo());
			echo "</p>";
		}
        
		foreach($_POST['precaution'] as $elem) {
			$sql="INSERT INTO liste_precaution (lis_id_precaution,lis_id_structure) VALUES ('$elem','$lastId')";
			$insertion=$dbh->exec($sql);
			if($insertion==false) {
				$erreur=1;
				echo "<p align=\"center\" class=\"erreur\">";
				print_r ($dbh->errorInfo());
				echo "</p>";
			}
		}
    }
}
//traitement des données
if (empty($_POST['alphasolvant'])) $_POST['alphasolvant']='Null';
else  $_POST['alphasolvant']="'".$_POST['alphasolvant']."'";
if (empty ($_POST['alphaconc'])) $_POST['alphaconc']='0.00';
$_POST['alphaconc']=str_replace(",",".",$_POST['alphaconc']);
if (empty ($_POST['rf'])) $_POST['rf']='0.00';
$_POST['rf']=str_replace(",",".",$_POST['rf']);
if (empty ($_POST['alphatemp'])) $_POST['alphatemp']='0.00';
$_POST['alphatemp']=str_replace(",",".",$_POST['alphatemp']);
if (empty ($_POST['alpha'])) $_POST['alpha']='0.00';
$_POST['alpha']=str_replace(",",".",$_POST['alpha']);
$_POST['alpha']=str_replace("+","",$_POST['alpha']);
if (!isset($_POST['duree']) or empty($_POST['duree'])) $_POST['duree']='0';
if (empty ($_POST['numerique'])) $_POST['numerique']='0';
if (empty ($_POST['sansmasse'])) $_POST['sansmasse']='0';

  
//génère un chiffre aléatoire entre 10000000 et 9999999
mt_srand(microtime()*10000);
$o=0;
while ($o!=1) {
    $chiffre=mt_rand(10000000,99999999);
    $sql="SELECT pro_num_constant FROM produit WHERE pro_num_constant='$chiffre'";
    $resultnb=$dbh->query($sql);
    $nbresultnb=$resultnb->rowCount();
    if ($nbresultnb>0) $o=0;
	else $o=1;
} 

//traitement du Qrcode ou code-barre multiple
if (!empty($_POST['qrcode']) and preg_match("/\r/",$_POST['qrcode'])) {
	$_POST['qrcode']=chop($_POST['qrcode']);
	$_POST['qrcode']=preg_replace("/\r/","¤",$_POST['qrcode']);
}

//update de la dernière entrée avec insertion éventuelle des fichiers
$filetype = array ("ir","uv","sm","hrms","rmnh","rmnc");
for ($ifile=0; $ifile<count($filetype); $ifile++) {
    $fichiertmp="file".$filetype[$ifile];
    if (!empty($_FILES[$fichiertmp]['tmp_name']) and !$_FILES[$fichiertmp]['error']) {
		$extension_fichier=preg_split("/\./",$_FILES[$fichiertmp]['name']);
		$fichier=file_get_contents($_FILES[$fichiertmp]['tmp_name']);
		$fichier=Base64_encode($fichier);
		
		$fichiertype=$filetype[$ifile].'_fichier';
		$extensiontype=$filetype[$ifile].'_nom_fichier';
		$text=$filetype[$ifile].'_text';
		$sequence= $filetype[$ifile]."_".$filetype[$ifile]."_id_".$filetype[$ifile]."_seq";
		if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $typesm=$filetype[$ifile].'_type';
		
		if (!empty($_POST['donnees'.$filetype[$ifile]])) $_POST['donnees'.$filetype[$ifile]]=preg_replace("/\'/", "''",$_POST['donnees'.$filetype[$ifile]]);		
		
		$sql="INSERT INTO ".$filetype[$ifile]." ($fichiertype,$extensiontype,$text";
		if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $sql.=",$typesm";
		$sql.=") VALUES ('$fichier','$extension_fichier[1]','".$_POST['donnees'.$filetype[$ifile]]."'";
		if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $sql.=",'{".$_POST[$filetype[$ifile].'type']."}'";
		$sql.=")";
		$fichierinsert=$dbh->exec($sql);	
		if($fichierinsert==false) {
			$erreur=1;
			echo "<p align=\"center\" class=\"erreur\">";
			print_r ($dbh->errorInfo());
			echo "</p>";
		}
		if ($fichierinsert==1) $lastIdinsertionfichier{$filetype[$ifile]} = $dbh->lastInsertId($sequence);
    }
	else $lastIdinsertionfichier{$filetype[$ifile]} =NULL;
}

//insertion des données du produit
$sql="INSERT INTO produit
  (pro_purification, pro_masse, pro_unite_masse, pro_aspect, pro_id_couleur, pro_date_entree, pro_ref_cahier_labo, pro_modop, pro_id_structure, pro_analyse_elem_trouve, pro_point_fusion, pro_point_ebullition, pro_pression_pb, pro_alpha, pro_alpha_temperature, pro_alpha_concentration, pro_alpha_solvant, pro_rf, pro_rf_solvant, pro_doi, pro_hal, pro_cas, pro_id_type, pro_num_brevet, pro_ref_contrat, pro_date_contrat, pro_id_chimiste, pro_id_equipe, pro_configuration, pro_observation, pro_num_boite, pro_num_position, pro_num_incremental, pro_numero, pro_num_constant, pro_num_sansmasse, pro_origine_substance, pro_purete, pro_methode_purete, pro_id_responsable, pro_etape_mol, pro_qrcode";
 if ($lastIdinsertionfichier{'rmnh'}!=NULL) $sql.=", pro_id_rmnh";
 if ($lastIdinsertionfichier{'rmnc'}!=NULL) $sql.=", pro_id_rmnc";
 if ($lastIdinsertionfichier{'ir'}!=NULL) $sql.=", pro_id_ir";
 if ($lastIdinsertionfichier{'uv'}!=NULL) $sql.=", pro_id_uv";
 if ($lastIdinsertionfichier{'sm'}!=NULL) $sql.=", pro_id_sm";
 if ($lastIdinsertionfichier{'hrms'}!=NULL) $sql.=", pro_id_hrms";
 $sql.=") VALUES ('{".$_POST['purification']."}','".$_POST['masse']."','{".$_POST['unitmass']."}','{".$_POST['aspect']."}','".$_POST['couleur']."','$date','".preg_replace("/\'/", "''",$_POST['ref'])."','".preg_replace("/\'/", "''",$_POST['modop'])."','$lastId','".preg_replace("/\'/", "''",$_POST['anaelem'])."','".preg_replace("/\'/", "''",$_POST['pfusion'])."','".preg_replace("/\'/", "''",$_POST['pebulition'])."','".preg_replace("/\'/", "''",$_POST['pressionpb'])."','".preg_replace("/\'/", "''",$_POST['alpha'])."','".preg_replace("/\'/", "''",$_POST['alphatemp'])."','".preg_replace("/\'/", "''",$_POST['alphaconc'])."',".$_POST['alphasolvant'].",'".preg_replace("/\'/", "''",$_POST['rf'])."','".preg_replace("/\'/", "''",$_POST['ccmsolvant'])."','".preg_replace("/\'/", "''",$_POST['doi'])."','".preg_replace("/\'/", "''",$_POST['hal'])."','".preg_replace("/\'/", "''",$_POST['cas'])."','".preg_replace("/\'/", "''",$_POST['type'])."','".preg_replace("/\'/", "''",$_POST['numbrevet'])."','".preg_replace("/\'/", "''",$_POST['contrat'])."','".preg_replace("/\'/", "''",$_POST['duree'])."','$nam','$id_equipe','".preg_replace("/\'/", "''",$_POST['config'])."','".preg_replace("/\'/", "''",$_POST['observation'])."','".preg_replace("/\'/", "''",$_POST['boite'])."','".preg_replace("/\'/", "''",$_POST['coordonnee'])."','".preg_replace("/\'/", "''",$_POST['numerique'])."','".preg_replace("/\'/", "''",$_POST['numerocomplet'])."','".$chiffre."','".preg_replace("/\'/", "''",$_POST['sansmasse'])."','{".$_POST['origimol']."}','".preg_replace("/\'/", "''",$_POST['purete'])."','".preg_replace("/\'/", "''",$_POST['methopurete'])."','$responsable','{".$_POST['etapmol']."}','".$_POST['qrcode']."'";
 if ($lastIdinsertionfichier{'rmnh'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'rmnh'}."'";
 if ($lastIdinsertionfichier{'rmnc'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'rmnc'}."'";
 if ($lastIdinsertionfichier{'ir'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'ir'}."'";
 if ($lastIdinsertionfichier{'uv'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'uv'}."'";
 if ($lastIdinsertionfichier{'sm'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'sm'}."'";
 if ($lastIdinsertionfichier{'hrms'}!=NULL) $sql.=",'".$lastIdinsertionfichier{'hrms'}."'";
$sql.=" )";
$insertion=$dbh->exec($sql);
if($insertion==false)  {
			$erreur=1;
			echo "<p align=\"center\" class=\"erreur\">";
			print_r ($dbh->errorInfo());
			echo "</p>";
		}
$lastIdinsertion = $dbh->lastInsertId('produit_pro_id_produit_seq');
//insertion des solvants de solubilisation du prosuit


  
//recherche de solvants sur la table solvant
$sql="SELECT count(sol_id_solvant) FROM solvant";
//les résultats sont retournées dans la variable $result3
$result3=$dbh->query($sql);
while($countsol=$result3->fetch(PDO::FETCH_NUM)) {
    for ($i=0; $i<$countsol[0]; $i++) {
		if (!empty ($_POST["solvant$i"])) {
			$sol="solvant".$i;
			//insertion des solvants de solubilisation du produit
			$sql="INSERT INTO solubilite (sol_id_solvant,sol_id_produit) VALUES ('".$_POST[$sol]."','$lastIdinsertion')";
			${"insert$i"}=$dbh->exec($sql);
			if(${"insert$i"}==false)  {
				$erreur=1;
				echo "<p align=\"center\" class=\"erreur\">";
				print_r ($dbh->errorInfo());
				echo "</p>";
		}
        }
    }
}
 
 //'".AddSlashes($_POST['donneesrmnh'])."','".AddSlashes($_POST['donneesrmnc'])."','".AddSlashes($_POST['donneesir'])."','".AddSlashes($_POST['sm'])."','".AddSlashes($_POST['smtype'])."','".AddSlashes($_POST['hsm'])."','".AddSlashes($_POST['hsmtype'])."','".AddSlashes($_POST['donneesuv'])."'
 


if ($erreur=='') {
    //envoie d'un email au responsable
    $sql="SELECT chi_id_chimiste FROM chimiste WHERE chi_recevoir='1' and chi_passif='0' and chi_id_chimiste IN (SELECT chi_id_responsable FROM chimiste WHERE chi_nom='".$_SESSION['nom']."')";
    $result4=$dbh->query($sql);
	$num4=$result4->rowCount();
	if ($num4>0) {
		$row4=$result4->fetch(PDO::FETCH_NUM);
        $envoicourriel=new envoi_mail_admin ($row4[0],$_POST['numerocomplet'],$chiffre);
		$envoicourriel->envoi();
    }
    
	//envoie d'un email au chef 
    $sql="SELECT chi_id_responsable FROM chimiste WHERE chi_recevoir='1' and chi_passif='0' and chi_id_chimiste IN (SELECT chi_id_responsable FROM chimiste WHERE chi_nom='".$_SESSION['nom']."')";
    $result6=$dbh->query($sql);
    $num6=$result6->rowCount();
	if ($num6>0) {
		$row6=$result6->fetch(PDO::FETCH_NUM);    
        $envoicourriel=new envoi_mail_admin ($row6[0],$_POST['numerocomplet'],$chiffre);
        $envoicourriel->envoi();
    }

    //envoie d'un email à chaque administrateur
    $sql="SELECT chi_id_chimiste FROM chimiste WHERE chi_statut='{ADMINISTRATEUR}' and chi_recevoir='1' and chi_passif='0'";
    $result5=$dbh->query($sql);
	$num5=$result5->rowCount();
	if ($num5>0) {
		while($row5=$result5->fetch(PDO::FETCH_NUM)) {
			$envoicourriel=new envoi_mail_admin ($row5[0],$_POST['numerocomplet'],$chiffre);
			$envoicourriel->envoi();
		}
	}	
    print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
}
//fermeture de la connexion à la base de données
unset($dbh);
?>