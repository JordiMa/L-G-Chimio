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
session_cache_limiter('public');
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
    //définition du numéro utilisé pour l'exportation
    $sql="SELECT para_num_exportation,para_acronyme FROM parametres";
    $resultat1=$dbh->query($sql);
    $row1=$resultat1->fetch(PDO::FETCH_NUM);
    if ($row1[0]>0) $num="pro_num_constant";
    else $num="pro_numero";
	switch($_POST["colactiv"]) {
				case 1: $requete="res_resultat_ic50,res_resultat_ec50,res_actif,res_resultat_pourcentactivite,res_resultat_autre,res_resultat_pourcentageinhi";
				break;
				case 2: {
					$requete="res_resultat_ic50";
					$champ="IC50";
					}
				break;
				case 3: {
					$requete="res_actif";
					$champ="ACT";
					}
				break;
				case 4: {
					$requete="res_resultat_pourcentactivite";
					$champ="POURACT";
					}
				break;
				case 5: {
					$requete="res_resultat_ec50";
					$champ="EC50";
					}
				break;
				case 6: {
					$requete="res_resultat_pourcentageinhi";
					$champ="POURINHI";
					}
				break;
				case 7: {
					$requete="res_resultat_autre";
					$champ="AUTRE";
					}
				break;
			}			

    $sql="SELECT res_id_produit,$requete FROM resultat WHERE res_id_labocible='".$_POST["labo"]."'";
    $resultat2=$dbh->query($sql);
	$op="";
    while($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
      $sql="SELECT str_mol,$num FROM produit,structure WHERE pro_id_produit=$row2[0] and produit.pro_id_structure=structure.str_id_structure";
      $resultat3=$dbh->query($sql);
      while ($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
        $op.=$row3[0];
        $op.="> <identificateur>\n".$row3[1]."\n\n";
		if ($_POST["colactiv"]<>1) {
			$op.="> <";
			if ($champ=="IC50" or $champ=="EC50") $op.=$champ; 
			else $op.=constant($champ);
			if ($requete=="res_actif") {
				if ($row2[1]==2) $row2[1]=INACTIF;
				elseif($row2[1]==1) $row2[1]=ACTIF;
				else $row2[1]="";
			}		
			$op.=">\n".$row2[1]."\n\n";
		}
		else $op.="> <IC50>\n".$row2[1]."\n\n> <EC50>\n".$row2[2]."\n\n> <".ACT.">\n".$row2[3]."\n\n> <".POURACT.">\n".$row2[4]."\n\n> <".POURINHI.">\n".$row2[6]."\n\n> <".AUTRE.">\n".$row2[5]."\n\n";
		$op.="$$$$\n";
      }
    }
  $nomfichier="sdf-$row1[1].sdf";
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=".$nomfichier);
  header("Content-Length: ".strlen($op));
  echo $op;
}
else require 'deconnexion.php';
unset($dbh);
?>