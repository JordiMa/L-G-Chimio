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

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	$sql="SELECT para_num_exportation,para_acronyme FROM parametres";
    $resultat1=$dbh->query($sql);
    $row1=$resultat1->fetch(PDO::FETCH_NUM);
    if ($row1[0]>0) $num="pro_num_constant";
    else $num="pro_numero";
	$sql="SELECT pos_coordonnees,str_mol,$num,pla_identifiant_local,pos_mass_prod,pla_volume,pla_unite_volume,pla_masse,pla_volume_preleve,pla_unite_vol_preleve,str_masse_molaire,pla_concentration FROM position,produit,structure,plaque WHERE pos_id_plaque='".$_GET['pltmere']."' and position.pos_id_produit=produit.pro_id_produit and produit.pro_id_structure=structure.str_id_structure and position.pos_id_plaque=plaque.pla_id_plaque ORDER BY LEFT(pos_coordonnees,1) ASC, CAST(RIGHT(pos_coordonnees,CHAR_LENGTH(pos_coordonnees)-1) AS int) ASC";
	$resultat=$dbh->query($sql);
	$op="";
	while($row=$resultat->fetch(PDO::FETCH_NUM)) {
		$op.=$row[1];
		$op.="> <plaque>\n".$row[3]."\n\n> <identifier>\n".$row[2]."\n\n> <position>\n".strtoupper($row[0])."\n\n";
		if ($row[7]>0)	$op.="> <mass_mg>\n".$row[7]."\n\n> <exact_molecular_weight_g/mol>\n".$row[10]."\n\n> <average_concentration_mol/L>\n".$row[11]."\n\n";
		elseif($row[4]>0) {
			$n=($row[4]*0.001)/$row[10];
			switch($row[6]){
				case "{ML}": $uv=0.001;
				break;
				case "{MIL}": $uv=0.000001;
				break;
			}
			$c=$n/($row[5]*$uv);
			$op.="> <exact_mass_mg>\n".$row[4]."\n\n> <exact_molecular_weight_g/mol>\n".$row[10]."\n\n> <exact_concentration_mol/L>\n".$c."\n\n";
		}
		$op.="$$$$\n";
		$nomfichier="$row[3].sdf";
	}
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$nomfichier);
	header("Content-Length: ".strlen($op));
	echo $op;
}
else require 'deconnexion.php';
unset($dbh);
?>