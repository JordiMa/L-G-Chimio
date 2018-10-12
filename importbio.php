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
include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'presentation/entete.php';
$menu=7;
include_once 'presentation/gauche.php';

if (isset($_POST['nomcible']) and !empty($_POST['nomcible']) and isset($_POST['conccible']) and !empty($_POST['conccible']) and isset($_POST['protocible']) and !empty($_POST['protocible'])  and isset($_POST['labocible']) and !empty($_POST['labocible']) and isset($_POST['uniprot']) and !empty($_POST['uniprot'])) {
	require 'script/connectionb.php';
	$_POST['conccible']=str_replace(",",".",$_POST['conccible']);
	$sql="SELECT * FROM cible WHERE cib_nom='".$_POST['nomcible']."' or cib_uniprot='".$_POST['uniprot']."'";
	$result=$dbh->query($sql);
	$num=$result->rowCount();
	if ($num>0) $erreur='ERREURCIBLE';
	else {
		require 'script/connectionb.php';
		$sql="INSERT INTO cible (cib_nom,cib_uniprot) VALUES ('".$_POST['nomcible']."','".$_POST['uniprot']."')";
		$insert1=$dbh->exec($sql);
		$_POST['cible'] =$dbh->lastInsertId('cible_cib_id_cible_seq');
		$sql="INSERT INTO labocible (lab_concentration,lab_protocol,lab_laboratoire,lab_id_cible) VALUES('".$_POST['conccible']."','".$_POST['protocible']."','".$_POST['labocible']."','".$_POST['cible']."')";
		$insert2=$dbh->exec($sql);
		$_POST['labo'] = $dbh->lastInsertId('labocible_lab_id_labocible_seq');
	}
}
 elseif (!empty($_POST['cible']) and isset($_POST['conccible']) and !empty($_POST['conccible']) and isset($_POST['protocible']) and !empty($_POST['protocible'])  and isset($_POST['labocible']) and !empty($_POST['labocible'])) {
	require 'script/connectionb.php';
	$_POST['conccible']=str_replace(",",".",$_POST['conccible']);
	$sql="INSERT INTO labocible (lab_concentration,lab_protocol,lab_laboratoire,lab_id_cible) VALUES('".$_POST['conccible']."','".$_POST['protocible']."','".$_POST['labocible']."','".$_POST['cible']."')";
	$insert2=$dbh->exec($sql);
	$_POST['labo'] = $dbh->lastInsertId('labocible_lab_id_labocible_seq');
 } 
unset ($dbh);
include_once 'importresbio.php';
include_once 'presentation/pied.php';
?>