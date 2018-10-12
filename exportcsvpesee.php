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
	
	if (!empty($_POST['boitetotal'])) 
	{
		$op="code barre du flacon de destination;identificateur local du composé;numéro unique;Poids (mg);MW (g/mol)\n";
		$i=0;
		foreach ($_POST['boitetotal'] as $elem) {
			parse_str($elem);
			if (isset ($_POST['doublon0']) and $_POST['doublon0']==1) $sql="SELECT str_masse_molaire,pro_numero,pro_num_constant FROM produit,structure WHERE pro_id_equipe='$equipe' and pro_id_type='$type' and pro_num_boite='$boite' and pro_num_incremental=0 and produit.pro_id_structure=structure.str_id_structure and pro_num_constant not in (SELECT evo_numero_permanent FROM evotec) order by pro_num_position";
			else $sql="SELECT str_masse_molaire,pro_numero,pro_num_constant FROM produit,structure WHERE pro_id_equipe='$equipe' and pro_id_type='$type' and pro_num_boite='$boite' and pro_num_incremental=0 and produit.pro_id_structure=structure.str_id_structure order by pro_num_position";
			$resultat=$dbh->query($sql);
			
			while($row=$resultat->fetch(PDO::FETCH_NUM)) {
				if($_POST['alternative0']==1) {
					$sql="SELECT str_masse_molaire,pro_numero,pro_num_constant FROM produit,structure WHERE produit.pro_id_structure=structure.str_id_structure and str_inchi_md5 in (SELECT str_inchi_md5 FROM produit,structure WHERE pro_num_constant='".$row[2]."' and produit.pro_id_structure=structure.str_id_structure);";
					$resultat1=$dbh->query($sql);
					$numlocal='';
					$numconstant='';
					$nbresultat1=$resultat1->rowCount();
					$o=0;
					while ($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
						$o++;
						$numlocal.=$row1[1];
						if ($nbresultat1>1 and $o<$nbresultat1) $numlocal.=" / ";
						$numconstant.=$row1[2];
						if ($nbresultat1>1 and $o<$nbresultat1) $numconstant.=" / ";
						$tab[$i][3]=$row1[0];
					}
					$tab[$i][1]=$numlocal;
					$tab[$i][2]=$numconstant;
				}
				else {
					$tab[$i][1]=$row[1];
					$tab[$i][2]=$row[2];
					$tab[$i][3]=$row[0];
				}
				$i++;
				
			}
		}
		if (isset($_POST['aleatoire0']) and $_POST['aleatoire0']==1) shuffle($tab);
		for ($y=0; $y<$i ; $y++) {
			$op.=";".$tab[$y][1].";".$tab[$y][2].";;".$tab[$y][3]."\n";
		}	
	}
	elseif(!empty($_POST['listetotal'])) 
	{
		$op="code barre du flacon de destination;identificateur local du composé;numéro unique;Poids (mg);MW (g/mol)\n";
		if ($_POST['separateur']=='ligne') $_POST['separateur']="\n";
		if ($_POST['separateur']=='espace') $_POST['separateur']=" ";
		$tabliste=explode($_POST['separateur'],$_POST['listetotal']);
		
		foreach ($tabliste as $elem) {
			if (is_int($elem)) $sql="SELECT str_masse_molaire,pro_numero,pro_num_constant FROM produit,structure WHERE pro_num_constant='$elem' and produit.pro_id_structure=structure.str_id_structure order by pro_num_position";
			else $sql="SELECT str_masse_molaire,pro_numero,pro_num_constant FROM produit,structure WHERE pro_numero='$elem' and produit.pro_id_structure=structure.str_id_structure order by pro_num_position";
			$resultat=$dbh->query($sql);
			while($row=$resultat->fetch(PDO::FETCH_NUM)) {
				$op.=";".$row[1].";".$row[2].";;".$row[0]."\n";
			}
		}
	}
	$sql="SELECT para_acronyme FROM parametres";
    $resultat1=$dbh->query($sql);
    $row1=$resultat1->fetch(PDO::FETCH_NUM);
	$nomfichier=$row1[0]."vrac".date('Ymj').".csv";
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$nomfichier);
	header("Content-Length: ".strlen($op));
	echo $op;
}
else require 'deconnexion.php';
unset($dbh);
?>