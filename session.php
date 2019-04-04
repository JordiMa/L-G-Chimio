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
//démarage de la session
session_start();

//vérification si les variables name_chimiste et reponse existe et si elles ne sont pas vide
if (isset($_POST['name_chimiste']) && isset($_POST['password_chimiste']) && !empty($_POST['name_chimiste']) && !empty($_POST['password_chimiste'])) {
	 $pass=$_POST['password_chimiste'];

	if (verification($_POST['name_chimiste'],$pass)) {
		session_regenerate_id();
		$_SESSION['nom']=$nom_chim;
		$_SESSION['langue']=$lang_chim;
		// unset($dbh);
		include_once 'entre.php';
	}
	//sinon redirection sur le fichier index.php avec un message d'erreur
	else {
		session_destroy();
		unset($_SESSION);
		$message = "LOGPASS";
		include_once 'index.php';
	}
}
//Si les variables n'existes pas alors l'utilisateur est redirigé sur index.php avec un message d'erreur
else {
	session_destroy();
	unset($_SESSION);
	$message = "LOGPASSVIDE";
	include_once 'index.php';
}

function verification($nom,$pass){
	//appel le fichier de connexion à la base de données
	require 'script/connectionb.php';

	$sql = "SELECT chi_id_chimiste, chi_password, chi_langue, chi_nom as nbres FROM chimiste WHERE chi_nom='$nom' and chi_passif='0'";

	foreach  ($dbh->query($sql) as $row) {
				if (password_verify($pass, $row['chi_password'])){
					global $id_chim, $nom_chim, $lang_chim;
					$id_chim = $row['chi_id_chimiste'];
					$nom_chim = $row['nbres'];
					$lang_chim = $row['chi_langue'];
					//fermeture de la connexion à la base de données
					unset($dbh);
					return TRUE;
				}
  }
	//fermeture de la connexion à la base de données
	unset($dbh);
	return FALSE;
}
?>
