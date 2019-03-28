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

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	//entre les résultats de la référence et récupère l'id de celle-ci
	if (!empty($_POST["molref"]) or !empty($_POST["resulref"]) or !empty($_POST["uniteref"])) {
		$date=$_POST["annee"]."-".$_POST["mois"]."-".$_POST["jour"];
		$sql="INSERT INTO reference_resultat (ref_unite_resultat,ref_date,ref_molecule_reference,ref_resultat_reference) VALUES ('".$_POST["uniteref"]."','".$date."','".$_POST["molref"]."','".$_POST["resulref"]."')";
		$insertion=$dbh->exec($sql);
		$id=$dbh->lastInsertId('reference_resultat_ref_id_ref_seq');
	}
	else $id=0;

    $o=0;
    for ($y=0; $y<count($_SESSION['tabval0']); $y++) {
        
		for ($i=0; $i<$_POST['nbcol']; $i++) {
			$colonne{$i}=$_SESSION['tabval'.$i];
			switch ($_POST["col".$i]) {
				case 1 : {
							$sql="SELECT pro_id_produit FROM produit WHERE pro_numero='".$colonne{$i}[$y]."' or pro_num_constant='".$colonne{$i}[$y]."'";
							$resultat=$dbh->query($sql);
							$idpro=$resultat->fetch(PDO::FETCH_NUM);
						}
						break;
				case 2 : $ic50=$colonne{$i}[$y];
						break;
				case 3 : {
							$element=$_SESSION['elem'];
							$elem0=str_replace ("elem","",$element[0]);
							$elem1=str_replace ("elem","",$element[1]);
							switch($colonne{$i}[$y]) {
								case $elem0 : $activite="'".($_POST[$element[0]])."'";
								break;
								case $elem1 : $activite="'".($_POST[$element[1]])."'";
								break;
								default : $activite="0";
								break;
							}
						}
						break;
				case 4 : $pouract=$colonne{$i}[$y];
						$pouract=str_replace (",",".",$pouract);
						break;
				case 5 : $ec50=$colonne{$i}[$y];
						$ec50=str_replace (",",".",$ec50);
						break;
				case 6 : $inhi=$colonne{$i}[$y];
						$inhi=str_replace (",",".",$inhi);
						break;
				case 7 : $autre=$colonne{$i}[$y];
						break;		
			}
		}
	    
		if (!isset($activite))  $activite=0;
		if (!isset($ic50))  $ic50='';
		if (!isset($autre))  $autre='';
		if (!isset($ec50))  $ec50='';
	    if (!isset($inhi))  $inhi='0.00';
		if (!isset($pouract))  $pouract='0.00';
	    $sql="INSERT INTO resultat (res_actif,res_resultat_ic50,res_resultat_pourcentactivite,res_resultat_ec50,res_resultat_autre";
		if ($id>0) $sql.=",res_id_reference";
		$sql.=",res_id_labocible,res_id_produit,res_resultat_pourcentageinhi) VALUES ($activite,'$ic50','$pouract','$ec50','$autre'";
		if ($id>0) $sql.=",'$id'";
		$sql.=",'".$_POST['labo']."','$idpro[0]','$inhi')";
		$insertion1=$dbh->exec($sql);
	    if ($insertion1==true) $o++;
    }
    if ($o==count($_SESSION['tabval0'])) $transfert=true;
}
else require 'deconnexion.php';
unset($dbh);
?>