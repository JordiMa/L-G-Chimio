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
if (!empty($_GET['id']) and !empty($_GET['rank'])) {
	//vérification que la session a le droit de visualiser la fiche demandée

	  //appel le fichier de connexion à la base de données
	  require 'script/connectionb.php';
	  $sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	  //les résultats sont retournées dans la variable $result
	  $result =$dbh->query($sql);
	  $row =$result->fetch(PDO::FETCH_NUM);
	  $sql="SELECT pro_id_equipe,pro_id_chimiste FROM produit WHERE pro_id_produit='".$_GET['id']."'";
	  //les résultats sont retournées dans la variable $result1
	  $result1 =$dbh->query($sql);
	  $row1 =$result1->fetch(PDO::FETCH_NUM);
	if (($row[1]==$row1[1]) or ($row[0]=="{RESPONSABLE}" and $row[2]==$row1[0]) or $row[0]=="{ADMINISTRATEUR}") {
		$champfile=$_GET['rank']."_fichier";
		$chamextension=$_GET['rank']."_nom_fichier";
		$sql="SELECT $chamextension,$champfile FROM produit P INNER JOIN ".$_GET['rank']." U ON P.pro_id_".$_GET['rank']."=U.".$_GET['rank']."_id_".$_GET['rank']." WHERE pro_id_produit='".$_GET['id']."';";
		$result2 =$dbh->query($sql);
		$row2 =$result2->fetch(PDO::FETCH_NUM);
		$nom_fichier_complet="fichier_".$_SESSION['nom']."_".$_GET['rank'].".".$row2[0];
		$donne=stream_get_contents ($row2[1]);
		$donne=base64_decode($donne);
		
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$nom_fichier_complet);
		header("Content-Length: ".strlen($donne));
		echo $donne;
	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>