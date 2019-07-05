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
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	if(!isset($_POST['responsable'])) $_POST['responsable']="";
	print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"institutgestion.php\">Institut</a></td>
			</tr>
			</table><br/>";
	if (!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['email']) and !empty($_POST['statut']) and !empty($_POST["langue"])) {
		if ($_POST['statut']=="RESPONSABLE") {
			if (empty($_POST['equipe']) and !empty($_POST['nomequi']) and !empty($_POST['iniequi'])) {
				$sql="SELECT * FROM equipe WHERE equi_nom_equipe='".$_POST['nomequi']."' or equi_initiale_numero='".$_POST['iniequi']."'";
				$selectequipe=$dbh->query($sql);
				$nbequipe=$selectequipe->rowCount();
				if ($nbequipe>0) print"<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"messagederreur\">".ERREUREQUIPE."</p>";
				else {
					$sql="INSERT INTO equipe (equi_nom_equipe,equi_initiale_numero) VALUES ('".$_POST['nomequi']."','".$_POST['iniequi']."')";
					$insert=$dbh->exec($sql);
					$idequi=$dbh->lastInsertId();
				}
			}
		}
		if (!empty($_POST["equipe"])) $idequi=$_POST["equipe"];
		//création du mot de passe aléatoire
		$pass=subStr(md5($_POST['nom'].$_POST['prenom'].$_POST['statut'].date("j-m-Y H:i:s")),1,12);

		$sql="SELECT * FROM chimiste where chi_nom='".$_POST['nom']."'";
		$selectnom=$dbh->query($sql);
		$nbnom=$selectnom->rowCount();
		if ($nbnom>0) print"<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"messagederreur\">".ERREURNOM."</p>";
		else {
			$sql="INSERT INTO chimiste (chi_nom,chi_prenom,chi_password,chi_email,chi_statut,";
			if (isset($_POST['responsable']) and $_POST['responsable']>0) $sql.="chi_id_responsable,";
			if (!($_POST['statut']=='CHEF' or $_POST['statut']=='ADMINISTRATEUR')) $sql.="chi_id_equipe,";
			$sql.="chi_langue) VALUES ('".$_POST['nom']."','".$_POST['prenom']."','".password_hash($pass, PASSWORD_BCRYPT)."','".$_POST['email']."','{".$_POST['statut']."}',";
			if (isset($_POST['responsable']) and $_POST['responsable']>0) $sql.="'".$_POST['responsable']."',";
			if (!($_POST['statut']=='CHEF' or $_POST['statut']=='ADMINISTRATEUR')) $sql.="'".$idequi."',";
			$sql.="'".$_POST["langue"]."')";
			$insert1=$dbh->exec($sql);
			$idchimi=$dbh->lastInsertId('chimiste_chi_id_chimiste_seq');
			if ($_POST['statut']=="CHEF") {
				$nb=count($_POST['srespo']);
				if ($nb>0) {
					$req="";
					$i=1;
					foreach ($_POST['srespo'] as $elem) {
						$req.="chi_id_chimiste='".$elem."'";
						if ($i<$nb) $req.=" or ";
						$i++;
					}
					$sql="UPDATE chimiste SET chi_id_responsable='$idchimi' WHERE ($req)";
					$updat=$dbh->exec($sql);
				}
			}
			if ($insert1==true) {
				$envoicourriel=new envoi_pass ($idchimi,$pass);
				$envoicourriel->envoi();
				print"<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
			}
			else print"<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".ECHECSAUVDONNE."</p>";
		}
	}
	else require 'ajouutilisateurs.php';
}
else require 'deconnexion.php';
unset($dbh);
?>
