<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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
//include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_export.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';

if ($row[0]=='{ADMINISTRATEUR}') {

// [JM - 01/02/2019] Si l'administrateur a effectuée une recherche sur un produit
if (isset($_GET['produit'])){
	// [JM - 01/02/2019] on recherche les information du produit
	$sql_produit="SELECT pro_id_produit, pro_numero, pro_id_equipe, pro_id_responsable, pro_id_chimiste FROM produit WHERE pro_numero='".$_GET['produit']."'";
	$result_produit = $dbh->query($sql_produit);
	$row1=$result_produit->fetch(PDO::FETCH_NUM);

	// [JM - 01/02/2019] l'equipe correspondante
	$sql_equipe="SELECT equi_id_equipe, equi_nom_equipe FROM equipe";
	$result_equipe = $dbh->query($sql_equipe);

	// [JM - 01/02/2019] le responsable
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe = -1";
	if (!empty($row1[2]))
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe =".$row1[2];
	if (isset($_GET['equipe']) && !empty($_GET['equipe']))
		// [JM - 01/02/2019] Si l'utilisateur selectionne une autre equipe, on recherche les responsables correspondant
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe =".$_GET['equipe'];
	$result_responsable = $dbh->query($sql_responsable);

	// [JM - 01/02/2019] et le chimiste
	$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste";
	if (!empty($row1[3]))
		$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_responsable =".$row1[3];
	if (isset($_GET['responsable']) && !empty($_GET['responsable']))
		// [JM - 01/02/2019] Si l'utilisateur selectionne un autre responsable, on recherche les chimistes correspondant
		$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_responsable =".$_GET['responsable'];
	$result_chimiste = $dbh->query($sql_chimiste);
}

?>

<form id="myForm" action="attributionstructures.php" method="get">
	<!-- [JM - 01/02/2019] Recherche du produit -->
	<br>Veuillez saisir l'identifiant local du produit : <input type="text" name="produit" <?php if (isset($_GET['produit'])) echo "value='".$_GET['produit']."'"; ?>> 	<input type="submit" name="Rechercher" value="Rechercher">	<br><br>

	<?php
	if (isset($row1) && $row1 && isset($_GET['produit'])) {
		//  [JM - 01/02/2019] sauvegarde les valeur de la recherche
		if (isset($_GET['Rechercher'])){
			echo "<input type='hidden' name='equipe' value='".$row1[2]."'>";
			echo "<input type='hidden' name='responsable' value='".$row1[3]."'>";
			echo "<input type='hidden' name='chimiste' value='".$row1[4]."'>";
			echo '<script>document.forms.myForm.submit();</script>';
		}
		?>

		Sélectionnez une equipe :<br>
		<select name="equipe" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les equipes dans une liste box -->
			<?php
					foreach ($result_equipe as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['equipe']) && $_GET['equipe'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
					}
			?>
		</select><br><br>

		Sélectionnez un responsable :<br>
		<select name="responsable" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les responsables dans une liste box -->
			<?php
					foreach ($result_responsable as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['responsable']) && $_GET['responsable'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]." ".$value[2]."</option>";
					}
			?>
		</select><br><br>

		Sélectionnez un chimiste :<br>
		<select name="chimiste" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les chimistes dans une liste box -->
			<?php
					foreach ($result_chimiste as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['chimiste']) && $_GET['chimiste'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]." ".$value[2]."</option>";
					}
			?>
		</select><br><br>

		<input type="image" name="save" value="download" src="images/charge.gif" alt="Sauvegarder" title="Sauvegarder">
	<?php }  ?>

</form>

<?php

	if (isset($_GET['save_x']))
	{
		// [JM - 01/02/2019] demande de confirmation
		echo '<script language="javascript">';
		// [JM - 01/02/2019] Si l'utilisateur annule, l'opération est stoppé
		echo 'if(confirm("Voulez vous enregistrer les modifications apportées ?")){

		}
		else{
			window.stop();
			history.back();
		}';
		echo '</script>';
		// [JM - 01/02/2019] effectue la modification dans la base de donnée
		$update = "UPDATE produit SET pro_id_equipe = ".$_GET['equipe'].", pro_id_responsable = ".$_GET['responsable'].", pro_id_chimiste = ".$_GET['chimiste']." WHERE pro_numero = '".$_GET['produit']."'";
		$update1=$dbh->exec($update);

		if ($update1)
			echo "Sauvegarde effectuée";
		else
			echo "Echec de la sauvegarde";
	}

}
else require 'deconnexion.php';
unset($dbh);
?>
