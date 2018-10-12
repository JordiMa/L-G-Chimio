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
include_once 'langues/'.$_SESSION['langue'].'/lang_parametre.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table width=\"328\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"parametres.php\">".GENE."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"parametreproduit.php\">".PROD."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"exportparametre.php\">".EXPOR."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"parametremaintenance.php\">".MAINT."</a></td>
			</tr>
			</table>";
	if (isset($erreur)) print"<p class=\"erreur\">".$erreur."</p>";
	echo "<h3 align=\"center\">".FORMULAIREPARA."</h3>";
	$sql='SELECT * FROM parametres';
	$result1=$dbh->query($sql);
	$row1 =$result1->fetch(PDO::FETCH_NUM);
	//initialisation du formulaire
	print"<br/>";
	$formulaire=new formulaire ("parametrage","changeparametre.php","POST",true);
	$formulaire->affiche_formulaire();
	if (isset($_POST['nom'])) $row1[1]=$_POST['nom'];
	$formulaire->ajout_text (50, $row1[1], 256, "nom", NOMLAB."<br/>","","");
	print"<br/><br/>";
	if (isset($_POST['acronyme'])) $row1[4]=$_POST['acronyme'];
	$formulaire->ajout_text (7, $row1[4], 7, "acronyme", ACRO."<br/>","","");
	print"<br/>";
	print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			<tr>
			<td>";
	$formulaire->ajout_file (30, "logo",true,LOGO."<br/>","");
	print"</td><td><img src=\"$row1[3]\"></td></tr></table>";
	print"<br/><br/>";
	$formulaire->ajout_text (50, $row1[8], 50, "email", MAILLOC."<br/>","","");
	print"<br/><br/>";
	$formulaire->ajout_cache ("1","form1");
	$formulaire->ajout_button (SUBMIT,"","submit","");
	//fin du formulaire
	$formulaire->fin();
}
else require 'deconnexion.php';
unset($dbh);
?>