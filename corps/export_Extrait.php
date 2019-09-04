<script type="text/javascript" src="js/jquery.min.js"></script>
<script>
	document.getElementById('loader').style.visibility = 'visible';
	document.getElementById('table_principal').style.filter = 'blur(5px)';

	function ready() {
		document.getElementById('loader').style.visibility = 'hidden';
		document.getElementById('table_principal').style.filter = 'none';
	}
	document.addEventListener("DOMContentLoaded", ready);
</script>
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
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe, chi_password FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

set_time_limit(0);
?>
	<br/>
	<h3 align="center">Exportation pour la Chimiothèque Nationale</h3>


	<!-- [JM - 24/01/2019] Debut du formulaire -->
	<form action="exportation_Extrait.php" method="post">

		<div>
			<br><?php echo SELECTCRITERESEXPORT; ?><br/><br/>
			<input type="radio" name="rad_dispo" <?php if (isset($_POST['rad_dispo']) && $_POST['rad_dispo']=="oui") echo "checked";?> value="oui" onchange="this.form.submit()">Extrait Disponible<br>
  		<input type="radio" name="rad_dispo" <?php if (isset($_POST['rad_dispo']) && $_POST['rad_dispo']=="non") echo "checked";?> value="non" onchange="this.form.submit()">Extrait Non Disponible<br>
  		<input type="radio" name="rad_dispo" <?php if ((isset($_POST['rad_dispo']) && $_POST['rad_dispo']=="lesDeux") || !isset($_POST['rad_dispo'])) echo "checked";?> value="lesDeux" onchange="this.form.submit()">Tous les extraits<br>
			<br>
			<input type="checkbox" name="chx_liste" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_liste']) || isset($_GET['chx_liste'])) echo "checked"; ?> ><?php echo SELECT_LISTEID; ?><br>
		</div>
		<br>

		<?php if(isset($_POST['chx_liste']) || isset($_GET['chx_liste'])) { ?>
			<label><?php echo SELECTLISTEIDEXPORT; ?></label><br>
			<textarea name="listeID" rows="8" cols="80" onchange="this.form.submit()"><?php if(isset($_POST['listeID'])) echo $_POST['listeID']; ?><?php if(isset($_GET['listeID'])) echo $_GET['listeID']; ?></textarea><br>

			<label><?php echo SEPARATEUR; ?><br>
				<select name="listeID_separateur" size="1" onchange="this.form.submit()">
					<option value=";" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == ";") echo "selected='selected'"; ?>>;</option>
					<option value="," <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == ",") echo "selected='selected'"; ?>>,</option>
					<option value="espace" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == "espace") echo "selected='selected'"; ?>><?php echo ESPACE; ?></option>
					<option value="ligne" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == "ligne") echo "selected='selected'"; ?>><?php echo RLIGNE; ?></option>
				</select>
			</label><br><br>
		<?php } ?>

		<br><B style="color: red;"><?php echo MOTDEPASSE; ?> :</B><br>
		<input type="password" name="pass" placeholder="<?php echo MOTDEPASSE; ?>"><br>

	<br><br>
		<input type="image" name="download" value="download" src="images/charge.gif" alt="Télécharger le fichier" title="Télécharger le fichier" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">
	<input type="image" name="liste" value="liste" src="images/liste.gif" alt="Afficher les resultats" title="Afficher les resultats" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">

	</form>

<?php

if (isset($_POST["pass"]) && password_verify($_POST["pass"],$row[3])){

	if (isset($_POST['download_x']) || isset($_POST['liste_x'])){
		$sql_sdf = "SELECT tax_famille, tax_genre, tax_espece, tax_sous_espece, tax_variete, pay_pays, spe_date_recolte, Echantillon.spe_code_specimen, ext_code_extraits, par_fr, typ_tax_type FROM Extraits
								INNER JOIN Echantillon on Echantillon.ech_code_echantillon = Extraits.ech_code_echantillon
						    INNER JOIN specimen on specimen.spe_code_specimen = echantillon.spe_code_specimen
						    INNER JOIN expedition on expedition.exp_id = specimen.exp_id
						    INNER JOIN pays on pays.pay_code_pays = expedition.pay_code_pays
						    INNER JOIN taxonomie on taxonomie.tax_ID = specimen.tax_ID
						    INNER JOIN type_taxonomie on type_taxonomie.typ_tax_id = taxonomie.typ_tax_id
						    INNER JOIN partie_organisme on partie_organisme.par_id = echantillon.par_id
								WHERE 1=1";

		if(isset($_POST['rad_dispo'])){
			if($_POST['rad_dispo'] == "oui"){
				$sql_sdf .= " AND ext_disponibilite = TRUE";
			}
			else
				if($_POST['rad_dispo'] == "non"){
					$sql_sdf .= " AND ext_disponibilite = FALSE";
				}
			}

		if(isset($_POST['chx_liste'])){
			switch ($_POST['listeID_separateur']) {
				case ';':
						$listeID_value = str_replace(';', '|:|', $_POST['listeID']);
					break;
				case ',':
						$listeID_value = str_replace(',', '|:|', $_POST['listeID']);
					break;
				case 'espace':
							$listeID_value = str_replace(' ', '|:|', $_POST['listeID']);
					break;
				case 'ligne':
								$listeID_value = str_replace(array("\r\n","\n"), '|:|', $_POST['listeID']);
					break;
			}

			$listeID_array = explode("|:|",$listeID_value);
			$listeID_value = "";
			$listeID_value_num = "";
			foreach ($listeID_array as $key => $value) {
				$listeID_value.= "'".trim($value)."',";

				if (is_numeric(trim($value))){
					$listeID_value_num.= trim($value) . ",";
				}
			}
			$listeID_value = substr($listeID_value,0,-1);


			$sql_sdf .= " and (ext_Code_Extraits IN (".$listeID_value.")";

			$sql_sdf .= ")";
		}

		$sql_sdf .= " ORDER BY ext_Code_Extraits";
			// [JM - 24/01/2019] Preparation du contenue du fichier SDF
			$result_sdf = $dbh->query($sql_sdf);

			$timestamp = time();
			ini_set('memory_limit', '256M');

			if (isset($_POST['liste_x'])){
				$array_afficheListe = array();
				foreach ($result_sdf as $key => $value) {
					$array_afficheListe[] = $value['ext_code_extraits'];
					unset($value);
				}

				if (sizeof($array_afficheListe) == 0)
					echo ZERORESULTAT."<br>";
				else
					if (sizeof($array_afficheListe) == 1)
						echo UNRESULTAT."<br>";
				else
					echo sizeof($array_afficheListe)." ".XRESULTAT."<br>";

				if (sizeof($array_afficheListe) >= 1){
					echo "<br>";
					echo LISTERESULTAT."<br>";
					echo "<textarea rows='10' cols='29' disabled>";
					foreach ($array_afficheListe as $key => $value) {
						echo $value. "\n";
					}
					echo "</textarea>";
				}
			}
			else
				if (isset($_POST['download_x'])){
								$contenuFichier_csv[0][0] = 'famille';
								$contenuFichier_csv[0][1] = 'nom_genre';
								$contenuFichier_csv[0][2] = 'nom_espece';
								$contenuFichier_csv[0][3] = 'sub_espece';
								$contenuFichier_csv[0][4] = 'variété';
								$contenuFichier_csv[0][5] = "pays";
								$contenuFichier_csv[0][6] = 'date';
								$contenuFichier_csv[0][7] = 'code_bot';
								$contenuFichier_csv[0][8] = 'id_stock';
								$contenuFichier_csv[0][9] = 'par_id_partie';
								$contenuFichier_csv[0][10] = 'type organisme';

							foreach ($result_sdf as $key => $value) {
								/*foreach ($contenuFichier_csv[0] as $key1 => $value1) {
									// [JM - 24/01/2019] Remplissage du fichier
									$contenuFichier_csv[$key+1][$key1] = " ";
								}*/


								$contenuFichier_csv[$key+1][0] = $value['tax_famille'];
								unset($value['tax_famille']);

								$contenuFichier_csv[$key+1][1] = $value['tax_genre'];
								unset($value['tax_genre']);

								$contenuFichier_csv[$key+1][2] = $value['tax_espece'];
								unset($value['tax_espece']);

								$contenuFichier_csv[$key+1][3] = $value['tax_sous_espece'];
								unset($value['tax_sous_espece']);

								$contenuFichier_csv[$key+1][4] = $value['tax_variete'];
								unset($value['tax_variete']);

								$contenuFichier_csv[$key+1][5] = $value['pay_pays'];
								unset($value['pay_pays']);

								$contenuFichier_csv[$key+1][6] = $value['spe_date_recolte'];
								unset($value['spe_date_recolte']);

								$contenuFichier_csv[$key+1][7] = $value['spe_code_specimen'];
								unset($value['spe_code_specimen']);

								$contenuFichier_csv[$key+1][8] = $value['ext_code_extraits'];
								unset($value['ext_code_extraits']);

								$contenuFichier_csv[$key+1][9] = $value['par_fr'];
								unset($value['par_fr']);

								$contenuFichier_csv[$key+1][10] = $value['typ_tax_type'];
								unset($value['typ_tax_type']);


								// [JM - 24/01/2019] création du fichier SDF
								$fichier_csv = fopen('temp/'.$timestamp.'.csv', 'w+');
								// [JM - 24/01/2019] Remplissage du fichier
								fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
								foreach($contenuFichier_csv as $ligne){
									if(!empty($ligne[0]))
										fputcsv($fichier_csv, $ligne, ";");
							}
							echo "<a class='download-file' href='temp/".$timestamp.".csv' download='Export_E_CSV_".date("Y-m-d").".csv'></a>";
				}
			}
		}
	}
	else{
		if (isset($_POST['download_x']) || isset($_POST['liste_x'])){
			echo "<script>alert('Mot de passe invalide');</script>";
		}
	}
}

else require 'deconnexion.php';
unset($dbh);
set_time_limit(120);
?>
<!-- Auto click sur la balise <a class='download-file'> ci dessus -->
<script type="text/javascript">
	$('.download-file').get(0).click();
</script>

<script>
	function download(text, name, type) {
	  var a = document.getElementById("a");
	  var file = new Blob([text], {type: type});
	  a.href = URL.createObjectURL(file);
	  a.download = name;
	}
</script>
