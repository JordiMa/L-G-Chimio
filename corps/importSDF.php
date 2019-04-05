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
include_once 'langues/'.$_SESSION['langue'].'/lang_import.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."' AND chi_passif = FALSE";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"importation.php\">".IMPORTCN."</a></td>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"importationtare.php\">".IMPORTTARE."</a></td>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"importationevo.php\">".IMPORTEVO."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"importationSDF.php\">SDF/RDF</a></td>
		  </tr>
		  </table>";
	print"<div id=\"dhtmltooltip\"></div>
		<script language=\"javascript\" src=\"ttip.js\"></script>";

	echo "<br/><h3 align=\"center\">".TITREIMPORTSDFRDF."</h3>";

	if (!empty($erreur)) echo "<p align=\"center\" class=\"erreur\">".constant($erreur)."</p>";
	//formulaire d'importatio du fichier


	$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom, chi_id_responsable, chi_id_equipe FROM chimiste WHERE chi_statut='{CHIMISTE}'";
	$result_chimiste = $dbh->query($sql_chimiste);

	suppression("files");
	suppression("files/sdf");
	suppression("files/rdf");

?>

<form action="importationSDF_enregistrement.php" method="post" enctype="multipart/form-data">
		<label><?php echo SDFRDF;?></br></br></label>
		<input type="file" id="fichier" name="file_name" required/><br>
		<br>
		<br>
		<label>&nbsp;<?php echo CORRECTIONSTRUCTURE;?></label><br>
		<br>
		<input type="radio" name="correctionOnLive" value="true" ><?php echo PENDANTIMPORT;?><br>
  	<input type="radio" name="correctionOnLive" value="false" checked><?php echo APRESIMPORT;?><br>
		<br>
		<input type="submit" value="Envoyer" id="envoyer" class="centre"/>
</form>

<?php
}
else require 'deconnexion.php';
unset($dbh);
?>
