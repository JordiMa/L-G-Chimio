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
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
if ($_POST['masse']=="" || empty($_POST['couleur']) || empty($_POST['purification']) || empty($_POST['aspect']) || empty($_POST['ref']) || empty($_POST['nomiupac'])) {
	$erreur="<p align=\"center\" class=\"erreur\">";
	if ($_POST['masse']=="") $erreur.=CHAMP." ".MASS." ".RENSEIGNE."<br/>";
	if ($_POST['couleur']=="-- ".SELECCOULEUR." --") $erreur.=CHAMP." ".COULEUR." ".RENSEIGNE."<br/>";
	if ($_POST['purification']=="-- ".SELECPURIFICATION." --") $erreur.=CHAMP." ".PURIFICATION." ".RENSEIGNE."<br/>";
	if ($_POST['aspect']=="-- ".SELECASPECT." --") $erreur.=CHAMP." ".ASPECT." ".RENSEIGNE."<br/>";
	if (empty($_POST['ref'])) $erreur.=CHAMP." ".REFERENCECAHIER." ".RENSEIGNE."<br/>";
	if (empty($_POST['nomiupac'])) $erreur.=CHAMP." ".NOM." ".RENSEIGNE."<br/>";

	//recherche de solvants sur la table solvant
	$sql="SELECT count(sol_id_solvant) FROM solvant";
	//les résultats sont retournées dans la variable $result
	$result=$dbh->query($sql);
	$pv=0; //variable pour vérifier l"existance de solvant
	while($count=$result->fetch(PDO::FETCH_NUM)) {
		for ($i=0; $i<$count[0]; $i++) {
			if (!empty ($_POST["solvant$i"])) {
				$pv=1;
			}
		}
	}
	if ($pv==0) $erreur.=CHAMP." ".SOLVANTS." ".RENSEIGNE."<br/>";
	$erreur.="</p>";
	unset($dbh);
	include_once('formulsaisiemodif1.php');
}
else {
	$sql="SELECT pro_suivi_modification FROM produit WHERE pro_id_produit='".$_POST['id']."'";
	$resultchangement=$dbh->query($sql);
	$rowchangement=$resultchangement->fetch(PDO::FETCH_NUM);
	$date=date("Y-m-d H:i:s");
	$erreur=0;
	$change=$rowchangement[0]; //variable pour le stockage des changements effectués par l'utilisateur



	$sql="SELECT chi_id_chimiste,chi_id_equipe,chi_prenom FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	$result2=$dbh->query($sql);
	$rop=$result2->fetch(PDO::FETCH_NUM);

	//recherche dans la base pour savoir si la structure existe
	$sql="SELECT str_id_structure,str_nom,str_mol FROM structure WHERE str_inchi_md5='".$_POST['inchikey']."'";;
	$result1 = $dbh->query($sql);
	$nbstructure=$result1->rowCount();
	$id_structure = $result1->fetch(PDO::FETCH_NUM);

	if ($nbstructure==0) {
		if(!isset($_POST['logp'])) $_POST['logp']="0";
		if(!isset($_POST['acceptorcount'])) $_POST['acceptorcount']="0";
		if(!isset($_POST['rotatablebondcount'])) $_POST['rotatablebondcount']="0";
		if(!isset($_POST['aromaticatomcount'])) $_POST['aromaticatomcount']="0";
		if(!isset($_POST['aromaticbondcount'])) $_POST['aromaticbondcount']="0";
		if(!isset($_POST['donorcount'])) $_POST['donorcount']="0";
		if(!isset($_POST['asymmetricatomcount'])) $_POST['asymmetricatomcount']="0";
		//requête d'insertion des données dans la table "structure" pour une nouvelle structure
		$sql="INSERT INTO structure (str_nom, str_mol, str_inchi, str_formule_brute, str_masse_molaire, str_analyse_elem, str_date, str_inchi_md5, str_logp, str_acceptorcount, str_rotatablebondcount, str_aromaticatomcount, str_donorcount, str_asymmetricatomcount, str_aromaticbondcount) VALUES ('".$_POST['nomiupac']."','".$_POST['mol']."','".$_POST['inchi']."','".$_POST['formulebrute']."','".$_POST['massemol']."','".$_POST['composition']."', '".$date."', '".$_POST['inchikey']."', '".$_POST['logp']."', '".$_POST['acceptorcount']."', '".$_POST['rotatablebondcount']."', '".$_POST['aromaticatomcount']."', '".$_POST['donorcount']."', '".$_POST['asymmetricatomcount']."', '".$_POST['aromaticbondcount']."');";
		$insertion=$dbh->exec($sql);
		if ($insertion==false) {
			print"<li class=\"rouge\">".ERREUR_STRUCTURE.MESSAGEERREUR;
			print_r ($dbh->errorInfo());
			print"</li>";
			$erreur++;
		}
		$lastId = $dbh->lastInsertId('structure_str_id_structure_seq');
		//entre dans la variable $change le changement de structure
		$sql="SELECT str_inchi_md5 FROM structure,produit WHERE pro_id_produit='".$_POST['id']."' and produit.pro_id_structure=structure.str_id_structure";
		$result3=$dbh->query($sql);
		$rop1=$result3->fetch(PDO::FETCH_NUM);
		$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@STRUCTURE@".$rop1[0]."\n";
	}
	else {
		$lastId=$id_structure[0];
		if ($id_structure[1]!=$_POST['nomiupac']) {
			$sql="UPDATE structure set str_nom='".$_POST['nomiupac']."' WHERE str_inchi_md5='".$_POST['inchikey']."'";
			$update1=$dbh->exec($sql);
			//entre dans la variable $change le changement de nom de la structure
			$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@NOM@".$id_structure[1]."\n";
		}

		if ( transformemol ($id_structure[2])!=transformemol ($_POST['mol'])) {
			$sql="UPDATE structure set str_mol='".$_POST['mol']."' WHERE str_inchi_md5='".$_POST['inchikey']."'";
			$update2=$dbh->exec($sql);
			//entre dans la variable $change le changement de nom de la structure
			$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@STRUCTURE@".$_POST['inchikey']."\n";
		}
	}

	if (empty($_POST['alphasolvant'])) $_POST['alphasolvant']='Null';

	if (isset($_POST['boite']) or isset($_POST['coordonnee']) or isset($_POST['numerique']) or isset($_POST['sansmasse']) or isset($_POST['numerocomplet'])) {
		if (!isset($_POST['boite'])) $_POST['boite']=0;
		if (!isset($_POST['coordonnee'])) $_POST['coordonnee']="";
		if (!isset($_POST['numerique'])) $_POST['numerique']=0;
		if (!isset($_POST['sansmasse'])) $_POST['sansmasse']=0;
		if (!isset($_POST['numerocomplet'])) $_POST['numerocomplet']="";
		$changenumero=", pro_num_boite='".$_POST['boite']."',pro_num_position='".$_POST['coordonnee']."',pro_num_incremental='".$_POST['numerique']."',pro_numero='".$_POST['numerocomplet']."',pro_num_sansmasse='".$_POST['sansmasse']."'";
	}
	else $changenumero="";

	$changement=new changement_donnees($_POST['id'],$rop[2],$changenumero);
	$change.=$changement->imprime();

	$sql="SELECT pro_id_rmnh, pro_id_rmnc, pro_id_ir, pro_id_uv, pro_id_sm, pro_id_hrms FROM produit WHERE pro_id_produit='".$_POST['id']."'";
	$fichierselect=$dbh->query($sql);
	$row =$fichierselect->fetch(PDO::FETCH_ASSOC);

	//update de la dernière entrée avec insertion éventuelle des fichiers
	$filetype = array ("ir","uv","sm","hrms","rmnh","rmnc");
	for ($ifile=0; $ifile<count($filetype); $ifile++) {

		if (!isset($_POST["sup$filetype[$ifile]0"])) $_POST["sup$filetype[$ifile]0"]='';
		$fichiertmp="file".$filetype[$ifile];
		if (!empty($_FILES[$fichiertmp]['tmp_name']) and !$_FILES[$fichiertmp]['error']) {
			$extension_fichier=preg_split("/\./",$_FILES[$fichiertmp]['name']);
			$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@FICHIER.".strtoupper($filetype[$ifile])."@MODIFIE\n";
			$fichier=file_get_contents($_FILES[$fichiertmp]['tmp_name']);
			$fichier=Base64_encode($fichier);
		}
		else {
			$sql="SELECT ".$filetype[$ifile]."_fichier, ".$filetype[$ifile]."_nom_fichier FROM ".$filetype[$ifile]." WHERE ".$filetype[$ifile]."_id_".$filetype[$ifile]."=".$row['pro_id_'.$filetype[$ifile]].";";
			$lefichier=$dbh->query($sql);
			if ($lefichier!=False) ${"fichier$filetype[$ifile]"}=$lefichier->fetch(PDO::FETCH_NUM);

			if ($_POST["sup$filetype[$ifile]0"]==1) {
				$fichier='';
				$extension_fichier[1]='';
			}
			elseif ($lefichier!=False) {

				$fichier=stream_get_contents (${"fichier$filetype[$ifile]"}[0]);
				$extension_fichier[1]=${"fichier$filetype[$ifile]"}[1];
			}
			else {
				$fichier='';
				$extension_fichier[1]='';
			}
		}

		$fichiertype=$filetype[$ifile].'_fichier';
		$extensiontype=$filetype[$ifile].'_nom_fichier';
		$text=$filetype[$ifile].'_text';
		$sequence= $filetype[$ifile]."_".$filetype[$ifile]."_id_".$filetype[$ifile]."_seq";
		if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $typesm=$filetype[$ifile].'_type';

		if (!empty($_POST['donnees'.$filetype[$ifile]])) $_POST['donnees'.$filetype[$ifile]]=preg_replace("/\'/", "''",$_POST['donnees'.$filetype[$ifile]]);

		if (!empty ($row['pro_id_'.$filetype[$ifile]])) {

			if ((!empty($fichier) or !empty ($extension_fichier[1]) or !empty ($_POST['donnees'.$filetype[$ifile]])) or (isset($_POST[$filetype[$ifile].'type']) and !empty($_POST[$filetype[$ifile].'type']))) {
				$sql="UPDATE ".$filetype[$ifile]." SET $fichiertype='$fichier', $extensiontype='$extension_fichier[1]', $text='".$_POST['donnees'.$filetype[$ifile]]."'";
				if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $sql.=",$typesm='{".$_POST[$filetype[$ifile].'type']."}'";
				$sql.=" WHERE ".$filetype[$ifile]."_id_".$filetype[$ifile]."=".$row['pro_id_'.$filetype[$ifile]].";";
				$id{$filetype[$ifile]}=$row['pro_id_'.$filetype[$ifile]];
			}
			else {
				$sql="DELETE FROM ".$filetype[$ifile]." WHERE ".$filetype[$ifile]."_id_".$filetype[$ifile]."=".$row['pro_id_'.$filetype[$ifile]].";";
				$id{$filetype[$ifile]}=0;
			}
			$fichierinsert=$dbh->exec($sql);
			if($fichierinsert==false) {
				$erreur=1;
				echo "<p align=\"center\" class=\"erreur\">";
				print_r ($dbh->errorInfo());
				echo "</p>";
			}
		}
		//Probleme ici ??? // TODO:
		elseif ((!empty($fichier) or !empty ($extension_fichier[1]) or !empty ($_POST['donnees'.$filetype[$ifile]])) or (isset($_POST[$filetype[$ifile].'type']) and !empty($_POST[$filetype[$ifile].'type']))) {
			$sql="INSERT INTO ".$filetype[$ifile]." ($fichiertype,$extensiontype,$text";
			if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $sql.=",$typesm";
			$sql.=") VALUES ('$fichier','$extension_fichier[1]','".$_POST['donnees'.$filetype[$ifile]]."'";
			if ($filetype[$ifile]=="sm" or $filetype[$ifile]=="hrms") $sql.=",'{".$_POST[$filetype[$ifile].'type']."}'";
			$sql.=")";
			$fichierinsert=$dbh->exec($sql);
			print_r ($dbh->errorInfo());
			if($fichierinsert==false) {
				$erreur=1;
				echo "<p align=\"center\" class=\"erreur\">";
				print_r ($dbh->errorInfo());
				echo "</p>";
			}
			if ($fichierinsert==1) $id{$filetype[$ifile]} = $dbh->lastInsertId($sequence);
		}
		else $id{$filetype[$ifile]} =0;
	}

	if(!isset($_POST['numbrevet'])) $_POST['numbrevet']="";
	if(!isset($_POST['contrat'])) $_POST['contrat']="";
	if(!isset($_POST['duree'])) $_POST['duree']="";
	if(!isset($_POST['precaution'])) $_POST['precaution']="";
	if (empty($_POST['alphasolvant'])) $_POST['alphasolvant']=NULL;
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

// supprimer pour requête préparée
// TODO
	$sql="UPDATE produit SET
	pro_purification='{".$_POST['purification']."}', pro_masse='".$_POST['masse']."', pro_aspect='{".$_POST['aspect']."}', pro_id_couleur='".$_POST['couleur']."', pro_ref_cahier_labo='".preg_replace("/\'/", "''",$_POST['ref'])."', pro_modop='".preg_replace("/\'/", "''",$_POST['modop'])."',
	pro_id_structure='$lastId', pro_analyse_elem_trouve='".preg_replace("/\'/", "''",$_POST['anaelem'])."', pro_point_fusion='".preg_replace("/\'/", "''",$_POST['pfusion'])."', pro_point_ebullition='".preg_replace("/\'/", "''",$_POST['pebulition'])."', pro_pression_pb='".preg_replace("/\'/", "''",$_POST['pressionpb'])."', pro_alpha='".preg_replace("/\'/", "''",$_POST['alpha'])."', pro_alpha_temperature='".preg_replace("/\'/", "''",$_POST['alphatemp'])."', pro_alpha_concentration='".preg_replace("/\'/", "''",$_POST['alphaconc'])."'";
	if ($_POST['alphasolvant']!=NULL) $sql.=", pro_alpha_solvant=".preg_replace("/\'/", "''",$_POST['alphasolvant']);
	$sql.=", pro_rf='".preg_replace("/\'/", "''",$_POST['rf'])."', pro_rf_solvant='".preg_replace("/\'/", "''",$_POST['ccmsolvant'])."', pro_doi='".preg_replace("/\'/", "''",$_POST['doi'])."',
	pro_hal='".preg_replace("/\'/", "''",$_POST['hal'])."', pro_cas='".preg_replace("/\'/", "''",$_POST['cas'])."', pro_id_type='".preg_replace("/\'/", "''",$_POST['type'])."', pro_num_brevet='".preg_replace("/\'/", "''",$_POST['numbrevet'])."', pro_ref_contrat='".preg_replace("/\'/", "''",$_POST['contrat'])."', pro_date_contrat='".preg_replace("/\'/", "''",$_POST['duree'])."', pro_configuration='".preg_replace("/\'/", "''",$_POST['config'])."'";
if (isset($_POST['purete']) && $_POST['purete'] != ''){
	$sql.=", pro_purete='".preg_replace("/\'/", "''",$_POST['purete'])."'";
}
	$sql .=", pro_methode_purete='".preg_replace("/\'/", "''",$_POST['methopurete'])."', pro_origine_substance='{".$_POST['origimol']."}', pro_observation='".preg_replace("/\'/", "''",$_POST['observation'])."'".$changenumero.",pro_etape_mol='{".$_POST['etapmol']."}',pro_qrcode='".$_POST['qrcode']."'";
	if($id{'rmnh'}>0) $sql.=", pro_id_rmnh='".$id{'rmnh'}."'";
	if($id{'rmnc'}>0) $sql.=", pro_id_rmnc='".$id{'rmnc'}."'";
	if($id{'ir'}>0) $sql.=", pro_id_ir='".$id{'ir'}."'";
	if($id{'uv'}>0) $sql.=", pro_id_uv='".$id{'uv'}."'";
	if($id{'sm'}>0) $sql.=", pro_id_sm='".$id{'sm'}."'";
	if($id{'hrms'}>0) $sql.=", pro_id_hrms='".$id{'hrms'}."'";
	if (isset($_POST["chx_purete"]) && $_POST["chx_purete"] == "chx_purete"){
		$sql.=", pro_controle_purete = TRUE";
		//$sql.=", pro_date_controle_purete = now()";
	}
	else {
		$sql.=", pro_controle_purete = FALSE";
	}
	if (isset($_POST["chx_structure"]) && $_POST["chx_structure"] == "chx_structure"){
			//$stmt->bindValue(':pro_controle_structure',TRUE);
			$sql.=", pro_controle_structure = TRUE";
	}
	else {
		$sql.=", pro_controle_structure = FALSE";
	}

	$sql.=" where pro_id_produit='".$_POST['id']."'";
	$insert=$dbh->exec($sql);

	/*désactivé passage à la version 1.3.2.1
	if ($insert==false) {
		print"<li class=\"rouge\">".ERREUR_PRODUIT.MESSAGEERREUR;
		print_r ($dbh->errorInfo());
		print"</li>";
		$erreur++;
	}*/
/*
// requête préparée
	try {
	$stmt = $dbh->prepare("
		UPDATE produit SET
		pro_purification = :pro_purification,
		pro_masse = :pro_masse,
		pro_aspect = :pro_aspect,
		pro_id_couleur = :pro_id_couleur,
		pro_ref_cahier_labo = :pro_ref_cahier_labo,
		pro_modop = :pro_modop,
		pro_id_structure = :pro_id_structure,
		pro_analyse_elem_trouve = :pro_analyse_elem_trouve,
		pro_point_fusion = :pro_point_fusion,
		pro_point_ebullition = :pro_point_ebullition,
		pro_pression_pb = :pro_pression_pb,
		pro_alpha = :pro_alpha,
		pro_alpha_temperature = :pro_alpha_temperature,
		pro_alpha_concentration = :pro_alpha_concentration,
		pro_alpha_solvant = :pro_alpha_solvant,
		pro_rf = :pro_rf,
		pro_rf_solvant = :pro_rf_solvant,
		pro_doi = :pro_doi,
		pro_hal = :pro_hal,
		pro_cas = :pro_cas,
		pro_id_type = :pro_id_type,
		pro_num_brevet = :pro_num_brevet,
		pro_ref_contrat = :pro_ref_contrat,
		pro_date_contrat = :pro_date_contrat,
		pro_configuration = :pro_configuration,
		pro_purete = :pro_purete,
		pro_methode_purete = :pro_methode_purete,
		pro_origine_substance = :pro_origine_substance,
		pro_observation = :pro_observation,
		pro_num_boite = :pro_num_boite,
		pro_num_position = :pro_num_position,
		pro_num_incremental = :pro_num_incremental,
		pro_numero = :pro_numero,
		pro_num_sansmasse = :pro_num_sansmasse,
		pro_etape_mol = :pro_etape_mol,
		pro_qrcode = :pro_qrcode,
		pro_id_rmnh = :pro_id_rmnh,
		pro_id_rmnc = :pro_id_rmnc,
		pro_id_ir = :pro_id_ir,
		pro_id_uv = :pro_id_uv,
		pro_id_sm = :pro_id_sm,
		pro_id_hrms = :pro_id_hrms,
		pro_controle_purete = :pro_controle_purete,
		pro_date_controle_purete = :pro_date_controle_purete,
		pro_controle_structure = :pro_controle_structure
		where pro_id_produit = :pro_id_produit;");

	$param_purification = "{".$_POST['purification']."}";
	$stmt->bindParam(':pro_purification',$param_purification);

	$stmt->bindParam(':pro_masse',$_POST['masse']);

	$param_aspect = "{".$_POST['aspect']."}";
	$stmt->bindParam(':pro_aspect',$param_aspect);

	$stmt->bindParam(':pro_id_couleur',$_POST['couleur']);
	$stmt->bindParam(':pro_ref_cahier_labo',$_POST['ref']);
	$stmt->bindParam(':pro_modop',$_POST['modop']);
	$stmt->bindParam(':pro_id_structure',$lastId);
	$stmt->bindParam(':pro_analyse_elem_trouve',$_POST['anaelem']);
	$stmt->bindParam(':pro_point_fusion',$_POST['pfusion']);
	$stmt->bindParam(':pro_point_ebullition',$_POST['pebulition']);
	$stmt->bindParam(':pro_pression_pb',$_POST['pressionpb']);
	$stmt->bindParam(':pro_alpha',$_POST['alpha']);
	$stmt->bindParam(':pro_alpha_temperature',$_POST['alphatemp']);
	$stmt->bindParam(':pro_alpha_concentration',$_POST['alphaconc']);
	$stmt->bindParam(':pro_alpha_solvant',$_POST['alphasolvant']);
	$stmt->bindParam(':pro_rf',$_POST['rf']);
	$stmt->bindParam(':pro_rf_solvant',$_POST['ccmsolvant']);
	$stmt->bindParam(':pro_doi',$_POST['doi']);
	$stmt->bindParam(':pro_hal',$_POST['hal']);
	$stmt->bindParam(':pro_cas',$_POST['cas']);
	$stmt->bindParam(':pro_id_type',$_POST['type']);
	$stmt->bindParam(':pro_num_brevet',$_POST['numbrevet']);
	$stmt->bindParam(':pro_ref_contrat',$_POST['contrat']);
	$stmt->bindParam(':pro_date_contrat',$_POST['duree']);
	$stmt->bindParam(':pro_configuration',$_POST['config']);
	$stmt->bindParam(':pro_purete',$_POST['purete']);
	$stmt->bindParam(':pro_methode_purete',$_POST['methopurete']);

	$param_origimol = "{".$_POST['origimol']."}";
	$stmt->bindParam(':pro_origine_substance',$param_origimol);

	$param_pro_observation = NULL;
	$param_pro_num_boite = NULL;
	$param_pro_num_position = NULL;
	$param_pro_num_incremental = NULL;
	$param_pro_numero = NULL;
	$param_pro_num_sansmasse = NULL;

	if(isset($_POST['observation']))
		$param_pro_observation = $_POST['observation'];

	if(isset($_POST['boite']))
		$param_pro_num_boite = $_POST['boite'];

	if(isset($_POST['coordonnee']))
		$param_pro_num_position = $_POST['coordonnee'];

	if(isset($_POST['numerique']))
		$param_pro_num_incremental = $_POST['numerique'];

	if(isset($_POST['numerocomplet']))
		$param_pro_numero = $_POST['numerocomplet'];

	if(isset($_POST['sansmasse']))
		$param_pro_num_sansmasse = $_POST['sansmasse'];

	$stmt->bindParam(':pro_observation',$param_pro_observation);
	$stmt->bindParam(':pro_num_boite',$param_pro_num_boite);
	$stmt->bindParam(':pro_num_position',$param_pro_num_position);
	$stmt->bindParam(':pro_num_incremental',$param_pro_num_incremental);
	$stmt->bindParam(':pro_numero',$param_pro_numero);
	$stmt->bindParam(':pro_num_sansmasse',$param_pro_num_sansmasse);

	$param_etapmol = "{".$_POST['etapmol']."}";
	$stmt->bindParam(':pro_etape_mol',$param_etapmol);

	$stmt->bindParam(':pro_qrcode',$_POST['qrcode']);

	$stmt->bindParam(':pro_id_rmnh',$id{'rmnh'});
	$stmt->bindParam(':pro_id_rmnc',$id{'rmnc'});
	$stmt->bindParam(':pro_id_ir',$id{'ir'});
	$stmt->bindParam(':pro_id_uv',$id{'uv'});
	$stmt->bindParam(':pro_id_sm',$id{'sm'});
	$stmt->bindParam(':pro_id_hrms',$id{'hrms'});

	if (isset($_POST["chx_purete"]) && $_POST["chx_purete"] == "chx_purete"){
		$stmt->bindValue(':pro_controle_purete',1);
		$stmt->bindValue(':pro_date_controle_purete',date('Y-m-d'));
	}
	else {
		$stmt->bindValue(':pro_controle_purete',FALSE);
		$stmt->bindValue(':pro_date_controle_purete',NULL);
	}

	if (isset($_POST["chx_structure"]) && $_POST["chx_structure"] == "chx_structure"){
			$stmt->bindValue(':pro_controle_structure',TRUE);
	}
	else {
		$stmt->bindValue(':pro_controle_structure',FALSE);
	}

	$stmt->bindParam(':pro_id_produit',$_POST['id']);

	$stmt->execute();

}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
}
*/

	//recherche de solvants sur la table solvant
	$sql="SELECT count(sol_id_solvant) FROM solvant";
	//les résultats sont retournées dans la variable $result3
	$result3=$dbh->query($sql);
	$countsol=$result3->fetch(PDO::FETCH_NUM);
	$sql="SELECT solubilite.sol_id_solvant,sol_solvant FROM solubilite,solvant WHERE sol_id_produit='".$_POST['id']."' and solubilite.sol_id_solvant=solvant.sol_id_solvant";
	$resultsolubi=$dbh->query($sql);

	$nbrsolvantanciens=$resultsolubi->rowCount();
	$i=1;
	$y=0;
	$listesolubi="";
	while($rowsolubi=$resultsolubi->fetch(PDO::FETCH_NUM)) {
		$tabsolubi[$i]=$rowsolubi[0];
		$listesolubi.=$rowsolubi[1].",";
		$i++;
	}

	$changeok=false;
	if(isset($tabsolubi)){		
		for ($i=0; $i<$countsol[0]; $i++) {
			if (isset($_POST["solvant$i"]) and !empty ($_POST["solvant$i"])) {
				$y++;
				if (in_array($_POST["solvant$i"],$tabsolubi)) $changeok=false;
				else {
					$changeok=true;
					break;
				}
			}
		}
	}

	if ($y!=$nbrsolvantanciens) {
		$changeok=true;
	}
	if ($changeok==true) $change.=$_SESSION['nom']." ".$rop[2]."@".$date."@SOLVANT@".$listesolubi."\n";

	$sql="DELETE FROM solubilite where sol_id_produit='".$_POST['id']."'";
	$del=$dbh->exec($sql);
	$error=$dbh->errorInfo();
	for ($i=0; $i<$countsol[0]; $i++) {
		if (!empty ($_POST["solvant$i"])) {
			$sol="solvant".$i;
			$sql="INSERT INTO solubilite (sol_id_solvant,sol_id_produit) VALUES ('".$_POST[$sol]."','".$_POST['id']."')";
			${"insert$i"}=$dbh->exec($sql);
			if (${"insert$i"}==false) {
				print"<li class=\"rouge\">".ERREUR_SOLUBILITE.MESSAGEERREUR;
				print_r ($dbh->errorInfo());
				print"</li>";
				$erreur++;
			}
		}
	}

	//**********************************
	//Section traitement des précautions
	if (!empty($_POST['precaution']))  $nbprecau=count($_POST['precaution']);
	else $nbprecau=0;
	$sql="SELECT lis_id_precaution, pre_precaution FROM liste_precaution, produit, precaution WHERE pro_id_produit ='".$_POST['id']."' AND produit.pro_id_structure = liste_precaution.lis_id_structure AND liste_precaution.lis_id_precaution = precaution.pre_id_precaution";
	$resultprecaution=$dbh->query($sql);
	$nbprecautions=$resultprecaution->rowCount();
	$ipre=0;
	$listeprecaution="";
	if ($nbprecautions>0) {
		while ($rowprecaution=$resultprecaution->fetch(PDO::FETCH_NUM)) {
			$tabprecaution[$ipre]=$rowprecaution[0];
			$listeprecaution.=$rowprecaution[1].",";
			$ipre++;
		}
		// si données dans BD et données dans POST
		if ($nbprecau>0){
			$compareresultplus=array_diff($_POST['precaution'],$tabprecaution);
			$compareresultmoins=array_diff($tabprecaution,$_POST['precaution']);

			if (!empty($compareresultplus) or !empty($compareresultmoins)) {
				$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@PRECAUTION@".$listeprecaution."\n";

				if (!empty($compareresultmoins)) {
					foreach ($compareresultmoins as $elem) {
						$sql="DELETE FROM liste_precaution where lis_id_structure='$lastId' and lis_id_precaution='$elem'";
						$delete=$dbh->exec($sql);
						if ($delete==false) {
							print"<li class=\"rouge\">".ERREUR_SUP_PRECAUTION.MESSAGEERREUR;
							print_r ($dbh->errorInfo());
							print"</li>";
							$erreur++;
						}
					}
				}

				if (!empty($compareresultplus)) {
					foreach($compareresultplus as $elem) {
						$sql="INSERT INTO liste_precaution (lis_id_precaution,lis_id_structure) VALUES ('$elem','$lastId')";
						$insertion=$dbh->exec($sql);
						if ($insertion==false) {
							print"<li class=\"rouge\">".ERREUR_PRECAUTION.MESSAGEERREUR;
							print_r ($dbh->errorInfo());
							print"</li>";
							$erreur++;
						}
					}
				}
			}
		}
		// si données dans BD et pas de données dans POST : suppression dans la BD
		if ($nbprecau==0){
			$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@PRECAUTION@".$listeprecaution."\n";
			$sql="DELETE FROM liste_precaution where lis_id_structure='$lastId'";
			$delete=$dbh->exec($sql);
			if ($delete==false) {
				print"<li class=\"rouge\">".ERREUR_SUP_PRECAUTION.MESSAGEERREUR;
				print_r ($dbh->errorInfo());
				print"</li>";
				$erreur++;
			}
		}
	}
	if ($nbprecautions==0) {
		$listeprecaution="AUCUNEVAL";
		if ($nbprecau>0){
			$change.=$_SESSION['nom']." ".$rop[2]."@".$date."@PRECAUTION@".$listeprecaution."\n";
			foreach($_POST['precaution'] as $elem) {
				$sql="INSERT INTO liste_precaution (lis_id_precaution,lis_id_structure) VALUES ('$elem','$lastId')";
				$insertion=$dbh->exec($sql);
				if ($insertion==false) {
					print"<li class=\"rouge\">".ERREUR_PRECAUTION.MESSAGEERREUR;
					print_r ($dbh->errorInfo());
					print"</li>";
					$erreur++;
				}
			}
		}
	}
	//fin Section traitement des précautions
	//**********************************


	if (!empty($change)) {
		$sql="UPDATE produit SET pro_suivi_modification='".AddSlashes($change)."' WHERE pro_id_produit='".$_POST['id']."'";
		$updatechangement=$dbh->exec($sql);
	}
	//fermeture de la connexion à la base de données
	unset($dbh);
	//if ($erreur>0) print"<p align=\"center\" class=\"erreur\">".$erreur.ERREURATTENTION."</p>";
	//else print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
	print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
}

function transformemol ($filemol) {
	$arr=explode("\n",$filemol);
	array_shift($arr); //suppression du premier élèment du tableau
	$mol="";
	$i=0;
	foreach($arr as $elem) {
        $elem=str_replace("\r","",$elem);
        if ($elem=="M  END") {
         $mol.="M  END";
          break;
          }
        elseif($i>2) $mol.="$elem\n";
		$i++;
    }
	return md5($mol);
}
?>
