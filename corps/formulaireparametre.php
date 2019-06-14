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
	print"</td><td><img src=\"$row1[3]\" style=\"width: 150px;\"></td></tr></table>";
	print"<br/><br/>";
	$formulaire->ajout_text (50, $row1[8], 50, "email", MAILLOC."<br/>","","");
	print"<br/><br/>";
	$formulaire->ajout_cache ("1","form1");
	$formulaire->ajout_button (SUBMIT,"","submit","");
	//fin du formulaire
	$formulaire->fin();
?>

	<hr>
	<h3 align="center">Champs obligatoires</h3>

	<form>
		<?php
		if ($config_data['etapeSynthese'] == 1)
	  	print "<input type='checkbox' name='etapeSynthese' value='1' checked>Etape de synthèse de la molécule<br>";
		else
			print "<input type='checkbox' name='etapeSynthese' value='1'>Etape de synthèse de la molécule<br>";

		if ($config_data['couleur'] == 1)
	  	print "<input type='checkbox' name='couleur' value='1' checked>Couleur du produit<br>";
		else
		print "<input type='checkbox' name='couleur' value='1' >Couleur du produit<br>";

		if ($config_data['typePurif'] == 1)
		print "<input type='checkbox' name='typePurif' value='1' checked>Type de purification<br>";
		else
			print "<input type='checkbox' name='typePurif' value='1'>Type de purification<br>";

		if ($config_data['aspect'] == 1)
			print "<input type='checkbox' name='aspect' value='1' checked>Aspect<br>";
		 else
			 print"<input type='checkbox' name='aspect' value='1'>Aspect<br>";

		if ($config_data['refCahier'] == 1)
			print "<input type='checkbox' name='refCahier' value='1' checked>Référence cahier de laboratoire ou thèse<br>";
		else
			print "<input type='checkbox' name='refCahier' value='1'>Référence cahier de laboratoire ou thèse<br>";

		if ($config_data['nomenclature'] == 1)
			print "<input type='checkbox' name='nomenclature' value='1' checked>Nom en nomenclature IUPAC (anglaise)<br>";
		else
			print "<input type='checkbox' name='nomenclature' value='1'>Nom en nomenclature IUPAC (anglaise)<br>";

		if ($config_data['solvantsDeSolubilisation'] == 1)
			print "<input type='checkbox' name='solvantsDeSolubilisation' value='1' checked>Solvants de solubilisation<br>";
		else
			print "<input type='checkbox' name='solvantsDeSolubilisation' value='1'>Solvants de solubilisation<br>";
			?>
		<br>
		<input type="hidden" name="valid" value="valid">
		<input type="submit">
	</form>

	<?php

	// initialisation de la variable $configJSON pour chaque champ
	// valeur = 0 car par défaut les champs sont facultatifs
	$configJSON = array();
	$configJSON["etapeSynthese"] = 0;
	$configJSON["couleur"] = 0;
	$configJSON["typePurif"] = 0;
	$configJSON["aspect"] = 0;
	$configJSON["refCahier"] = 0;
	$configJSON["nomenclature"] = 0;
	$configJSON["solvantsDeSolubilisation"] = 0;

// on regarde les cases qui ont été cocher
// et on met valeur = 1 pour les rendre obligatoires
	if (isset($_GET['etapeSynthese']))
		$configJSON["etapeSynthese"] = 1;
	if (isset($_GET['couleur']))
		$configJSON["couleur"] = 1;
	if (isset($_GET['typePurif']))
		$configJSON["typePurif"] = 1;
	if (isset($_GET['aspect']))
		$configJSON["aspect"] = 1;
	if (isset($_GET['refCahier']))
		$configJSON["refCahier"] = 1;
	if (isset($_GET['nomenclature']))
		$configJSON["nomenclature"] = 1;
	if (isset($_GET['solvantsDeSolubilisation']))
		$configJSON["solvantsDeSolubilisation"] = 1;

		// valid = bouton du formulaire
	if (isset($_GET['valid'])){

		// on encode la variable $configJSON au format JSON,
		// puis on écrit dans le fichier config.json
		$myJSON = json_encode($configJSON);
		file_put_contents('script/config.json', $myJSON);

		// on recharge la page des configurations
	 	echo " <script> location.replace('parametres.php'); </script>";
	}
?>

	<hr>
	<h3 align="center">Champs personnalisés</h3>
<center>
	En cliquant sur le bouton ci-dessous, vous accéderez à une page où vous pourrez ajouter des champs à la fenêtre de saisie de produit.<br>


	<br><input type="button" value="Continuer" onClick="javascript:document.location.href='gestionChamps1.php'" /><br>
</center>

<h3></h3>

<?php
}
else require 'deconnexion.php';
unset($dbh);
?>
