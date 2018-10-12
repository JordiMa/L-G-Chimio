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
class changement_donnees {

	public $id;

	function __construct ($id,$nom,$numpil) {
		$this->id=$id;
		$this->nom=$nom;
		$this->numeropillulier=$numpil;
	}
  
	function imprime() {
		require 'script/connectionb.php';
		$sql="SELECT pro_purification, pro_masse, pro_aspect, pro_id_couleur, pro_ref_cahier_labo, pro_modop, pro_analyse_elem_trouve, pro_point_fusion, pro_point_ebullition, pro_pression_pb, pro_alpha, pro_alpha_temperature, pro_alpha_concentration, pro_alpha_solvant, pro_rf, pro_rf_solvant, pro_doi,
			pro_hal, pro_cas, pro_id_type, pro_num_brevet, pro_ref_contrat, pro_date_contrat,cou_couleur,pro_observation,pro_origine_substance,pro_methode_purete,pro_purete,pro_numero,pro_etape_mol,pro_qrcode,pro_configuration FROM produit,couleur WHERE pro_id_produit='".$this->id."' and produit.pro_id_couleur=couleur.cou_id_couleur";
		$result=$dbh->query($sql);
		$row=$result->fetch(PDO::FETCH_ASSOC);
		$changement="";
		$date=date("Y-m-d H:i:s");
		if ($row['pro_purification']!=$_POST['purification']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@PURIFICATION@".$row['pro_purification']."\n";
		if ($row['pro_masse']!=$_POST['masse']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@MASS@".$row['pro_masse']."\n";
		if ($row['pro_aspect']!=$_POST['aspect']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ASPECT@".$row['pro_aspect']."\n";
		if ($row['pro_id_couleur']!=$_POST['couleur']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@COULEUR@#".$row['cou_couleur']."\n";
		if ($row['pro_ref_cahier_labo']!=$_POST['ref']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@REFE@".$row['pro_ref_cahier_labo']."\n";
		if ($row['pro_modop']!=$_POST['modop']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@MODOP@".str_replace("\n","",nl2br($row['pro_modop']))."\n";
		// if ($row[6]!=$_POST['donneesrmnh']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@RMNH@".str_replace("\n","",nl2br($row[6]))."\n";
		// if ($row[7]!=$_POST['donneesrmnc']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@RMNC@".str_replace("\n","",nl2br($row[7]))."\n";
		// if ($row[8]!=$_POST['donneesir']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@IR@".str_replace("\n","",nl2br($row[8]))."\n";
		// if ($row[9]!=$_POST['sm']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@SM@".str_replace("\n","",nl2br($row[9]))."\n";
		// if ($row[10]!=$_POST['smtype']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@SM.SMTYPE@".$row[10]."\n";
		// if ($row[11]!=$_POST['hsm']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@HRMS@".str_replace("\n","",nl2br($row[11]))."\n";
		// if ($row[12]!=$_POST['hsmtype']) $changement.=$_SESSION['nom']." ".$$this->nom."@".$date."@HRMS.SMTYPE@".$row[12]."\n";
		if ($row['pro_analyse_elem_trouve']!=$_POST['anaelem']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ANALYSEEXP@".$row['pro_analyse_elem_trouve']."\n";
		if ($row['pro_point_fusion']!=$_POST['pfusion']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@PF@".$row['pro_point_fusion']."\n";
		if ($row['pro_point_ebullition']!=$_POST['pebulition']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@PEB@".$row['pro_point_ebullition']."\n";
		if ($row['pro_pression_pb']!=$_POST['pressionpb']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@PEB.PRESSION@".$row['pro_pression_pb']."\n";
		if (!($row['pro_alpha']==0.0 and empty($_POST['alpha'])) and $row[17]!=$_POST['alpha']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ALPHA@".$row['pro_alpha']."\n";
		if (!($row['pro_alpha_temperature']==0.0 and empty($_POST['alphatemp'])) and $row['pro_alpha_temperature']!=$_POST['alphatemp']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ALPHA.ALPHATEMP@".$row['pro_alpha_temperature']."\n";
		if (!($row['pro_alpha_concentration']==0.0 and empty($_POST['alphaconc'])) and $row['pro_alpha_concentration']!=$_POST['alphaconc']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ALPHA.ALPHACONC@".$row['pro_alpha_concentration']."\n";
		if (!($row['pro_alpha_solvant']==NULL and empty($_POST['alphasolvant'])) and $row['pro_alpha_solvant']!=$_POST['alphasolvant']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ALPHASOLVANT@".$row['pro_alpha_solvant']."\n";
		if (!($row['pro_rf']==0.00 and empty($_POST['rf'])) and $row['pro_rf']!=$_POST['rf']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CCM.CCMRF@".$row['pro_rf']."\n";
		if ($row['pro_rf_solvant']!=$_POST['ccmsolvant']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CCM.CCMSOLVANT@".$row['pro_rf_solvant']."\n";
		// if ($row[23]!=$_POST['donneesuv']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@UV@".str_replace("\n","",nl2br($row[23]))."\n";
		if ($row['pro_doi']!=$_POST['doi']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@DOI@".$row['pro_doi']."\n";
		if ($row['pro_hal']!=$_POST['hal']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@HAL@".$row['pro_hal']."\n";
		if ($row['pro_cas']!=$_POST['cas']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CAS@".$row['pro_cas']."\n";
		if ($row['pro_id_type']!=$_POST['type']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@TYPE@".$row['pro_id_type']."\n";
		$search= array('{','}');
		$row['pro_origine_substance']=str_replace($search,'',$row['pro_origine_substance']);
		if ($row['pro_origine_substance']!=$_POST['origimol']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ORIGINEMOL@".$row['pro_origine_substance']."\n";
		if ($row['pro_methode_purete']!=$_POST['methopurete']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@METHOPURETE@".$row['pro_methode_purete']."\n";
		if (!($row['pro_purete']==0 and empty($_POST['purete'])) and $row['pro_purete']!=$_POST['purete']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@PURETE@".$row['pro_purete']."\n";
		if (!empty($this->numeropillulier) and $row['pro_numero']!=$this->numeropillulier) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@NUMERO@".$row['pro_numero']."\n";
		if ($row['pro_id_type']==2 and ($_POST['type']!=1 or $_POST['type']!=3)) {
			$changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CONTRATDESC@".$row['pro_ref_contrat']."\n";
			$changement.=$_SESSION['nom']." ".$this->nom."@".$date."@DUREE@".$row['pro_date_contrat']."\n";
		}
		if ($row['pro_id_type']==3 and ($_POST['type']!=1 or $_POST['type']!=2)) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@NUMBREVET@".$row['pro_num_brevet']."\n";
		if ($row['pro_id_type']==3 and $row['pro_num_brevet']!=$_POST['numbrevet'] and $_POST['type']==3) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@NUMBREVET@".$row['pro_num_brevet']."\n";
		if ($row['pro_id_type']==2 and $row['pro_ref_contrat']!=$_POST['contrat']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CONTRATDESC@".$row['pro_ref_contrat']."\n";
		if ($row['pro_id_type']==2 and $row['pro_date_contrat']!=$_POST['duree']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@DUREE@".$row['pro_date_contrat']."\n";
		if ($row['pro_observation']!=$_POST['observation']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@OBSERVATION@".str_replace("\n","",nl2br($row['pro_observation']))."\n";
		if ($row['pro_etape_mol']!=$_POST['etapmol']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ETAPESYNT@".$row['pro_etape_mol']."\n";
		if ($row['pro_qrcode']!=$_POST['qrcode']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@QRCODE@".$row['pro_qrcode']."\n";
		if ($row['pro_configuration']!=$_POST['config']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@CONFIG@".$row['pro_configuration']."\n";
	  
		$sql="SELECT sol_solvant,pro_alpha_solvant FROM produit,solvant WHERE pro_id_produit=$this->id and produit.pro_alpha_solvant=solvant.sol_id_solvant";
		$result1=$dbh->query($sql);
		$nbresult1=$result1->rowCount();
		if ($nbresult1>0) {
			$row1=$result1->fetch(PDO::FETCH_NUM);
			if ($row1[1]!=$_POST['alphasolvant']) $changement.=$_SESSION['nom']." ".$this->nom."@".$date."@ALPHA.ALPHASOLVANT@".$row1[0]."\n";
		}
		unset ($dbh);
		return $changement;
	}
}
?>