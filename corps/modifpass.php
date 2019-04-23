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
include_once 'autoload.php';
include_once 'protection.php';
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_id_chimiste,chi_nom FROM chimiste WHERE chi_email='".$_POST['email']."' and chi_passif='0'";
$result=$dbh->query($sql);
if ($result->rowCount()==0)
	print"<br/><br/><p align=\"center\"><font color=\"#CC0000\"><strong>". NOEXISTE."</strong></font></p>";
else {
	$row=$result->fetch(PDO::FETCH_NUM);
	$pass=subStr(md5($row[1].$row[0].date("j-m-Y H:i:s")),1,12);
	$sql="UPDATE chimiste SET chi_password='".password_hash($pass, PASSWORD_BCRYPT)."' WHERE chi_id_chimiste='$row[0]'";
	$updat=$dbh->exec($sql);
	if($updat==1) {
		print"<br/><br/><p align=\"center\"><strong>".UPDAT."</strong></p>";
		$envoicourriel=new envoi_pass ($row[0],$pass);
		$envoicourriel->envoi();
	}
	else print"<br/><br/><p align=\"center\"><strong>".ECHECUP."</strong></p>";
	}
unset($dbh);
?>
