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
include_once 'langues/'.$_SESSION['langue'].'/lang_compte.php';

if (empty($_POST['OLDPASS'])) {
	$erreur=PASSERR;
	require 'parametrecompte.php';
}
elseif (empty($_POST['email'])) {
	$erreur=CHAMP." ".EMAIL." ".RENSEIGNE;
	require 'parametrecompte.php';
}
elseif (empty($_POST['langue'])) {
	$erreur=CHAMP." ".LANGUE." ".RENSEIGNE;
	require 'parametrecompte.php';
}
elseif (!empty($_POST['password']) and !empty($_POST['password2'])) {
	if ($_POST['password']!=$_POST['password2']) {
		$erreur=VERIF;
		require 'parametrecompte.php';
	}
	elseif (strlen($_POST['password'])<12) {
		$erreur=NBPASSWORD;
		require 'parametrecompte.php';
	}
	else modification ();
}
else modification ();

function modification () {
	//appel le fichier de connexion à la base de données
	require 'script/connectionb.php';
	$update = 0;
	$passactu = 0;
	$sql = "SELECT chi_password FROM chimiste WHERE chi_nom='".$_SESSION["nom"]."' and chi_passif='0'";

	foreach  ($dbh->query($sql) as $row) {
		if (password_verify($_POST['OLDPASS'], $row['chi_password'])){
			$passactu = 1;
			$set="";
			if (isset($_POST['envoi'])){
				if ($_POST['envoi']==0 or $_POST['envoi']==1) $set.="chi_recevoir='".$_POST['envoi']."'";
				if (($_POST['envoi']==0 or $_POST['envoi']==1) and !empty($_POST['email'])) $set.=" , ";
			}
			if (!empty($_POST['email'])) $set.="chi_email='".$_POST['email']."'";
			if (!empty($_POST['email']) and !empty($_POST['langue'])) $set.=" , ";
			if (!empty($_POST['langue'])) $set.="chi_langue='".$_POST['langue']."'";
			if (!empty($_POST['langue']) and !empty($_POST['password'])) $set.=" , ";
			if (!empty($_POST['password'])) $set.="chi_password=('".password_hash($_POST['password'],PASSWORD_BCRYPT)."')";
			$sql="UPDATE chimiste SET $set WHERE chi_nom='".$_SESSION['nom']."'";
			$update=$dbh->exec($sql);
			unset($_SESSION['langue']);
			$_SESSION['langue']=$_POST['langue'];
			}
		}

		if($update==1)
			print "<br/><br/><p align=\"center\"><strong>".SAUVDONNE."</strong></p>";
		elseif ($passactu==0)
			print "<br/><br/><p align=\"center\"><strong style='color: red';>".SAUVEPASS."</strong></p>";
		else
			print "<br/><br/><p align=\"center\"><strong>".SAUVECHEC."</strong></p>";
		unset($dbh);
}
?>
