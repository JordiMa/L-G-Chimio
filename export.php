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
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

set_time_limit(0);

if(isset($_GET['chx_equipe'])){
	// [JM - 24/01/2019] Selectionne toute les equipes
	$sql_equipe="SELECT * FROM equipe;";
	$result_equipe = $dbh->query($sql_equipe);
}

if(isset($_GET['chx_utilisateur'])){
	// [JM - 24/01/2019] Selectionne les utilisateur
	$sql_utilisateur="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste ;";
	if(isset($_GET['chx_equipe'])){
		if (isset($_GET['equipe'])){
			$sql_utilisateur="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_equipe =".$_GET['equipe'].";";
		}
	}
	$result_utilisateur = $dbh->query($sql_utilisateur);
}

if(isset($_GET['chx_typeContrat'])){
	// [JM - 24/01/2019] Selectionne les type de contrat
 	$sql_type = "SELECT * FROM type;";
 	$result_type = $dbh->query($sql_type);
}
?>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="82" height="23" align="center" valign="middle" background="images/onglet1.gif"><a class="onglet" href="exportation.php"><?php echo "EXPORT" ?></a></td>
			<td width="82" height="23" align="center" valign="middle" background="images/onglet.gif"><a class="onglet" href="exportation.old.php"><?php echo SDF . " OLD" ?></a></td>
			<td width="82" height="23" align="center" valign="middle" background="images/onglet.gif"><a class="onglet" href="exportationcsvpesee.php"><?php echo CSV ?></a></td>
		</tr>
	</table>
	<br/>

	<!-- [JM - 24/01/2019] Debut du formulaire -->
	<form action="exportation.php" method="get">
		<div>
			<input type="radio" name="rad_format" value="SDF" onchange="this.form.submit()" <?php if(isset($_GET['rad_format']) && $_GET['rad_format'] == "SDF") echo "checked"; ?> >SDF<br>
			<input type="radio" name="rad_format" value="CSV" onchange="this.form.submit()" <?php if(isset($_GET['rad_format']) && $_GET['rad_format'] == "CSV") echo "checked"; ?> >CSV<br>
		</div>

		<div>
			<br>Sélectionnez les champs à exporter : (*non fonctionnel, en cours)<br>
				<?php
					$arrayChampsBDD = [
						"Type",
						"Equipe",
						"Responsable",
						"Chimiste",
						"Couleur",
						"Pureté",
						"Purification",
						"Masse",
						"Aspect",
						"Date de saisie",
						"Reference cahier de labo",
						"Observation",
						"Identificateur",
						"Numero constant",
						"Point de fusion",
						"Point d'ebullition",
						"Methode de messure de la purete",
						"Numero CN",
						"Origine de la substance",
						"QR code",
						"Pureté contrôlée",
						"Date de contrôle pureté",
						"Structure contrôlée"
					];
				?>

				<div id="multi-select-plugin" aria-labeledby="multi-select-plugin-label">
					<span class="toggle">
						<label>Sélectionnez les champs souhaité</label>
						<span class="chevron">&lt;</span>
					</span>
					<ul>

						<?php
							foreach ($arrayChampsBDD as $key => $value) {
								echo'
								<li>
									<label>
										<input type="checkbox" name="chx_ChampsBDD_'.$key.'" value="'.$value.'" ';if(isset($_GET['chx_ChampsBDD_'.$key])) echo "checked";echo'/>
										'.$value.'
									</label>
								</li>
								';
							}
						?>
					</ul>
				</div>
			<br>
		</div>

		<div>
			<br>Sélectionnez vos critères de sélection :<br>
			<input type="checkbox" name="chx_equipe" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_equipe'])) echo "checked"; ?> >Equipe<br>
			<input type="checkbox" name="chx_utilisateur" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_utilisateur'])) echo "checked"; ?> >Utilisateur<br>
			<input type="checkbox" name="chx_typeContrat" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_typeContrat'])) echo "checked"; ?> >Type de contrat<br>
			<input type="checkbox" name="chx_masseDispo" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_masseDispo'])) echo "checked"; ?> >Masse disponible<br>
			<input type="checkbox" name="chx_plaqueNonVrac" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_plaqueNonVrac'])) echo "checked"; ?> >Produits en plaque mais pas en vrac<br>
			<input type="checkbox" name="chx_evotec" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_evotec'])) echo "checked"; ?> >Chez Evotec<br>
			<input type="checkbox" name="chx_liste" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_liste'])) echo "checked"; ?> >Depuis une liste d'identificateurs<br>
		</div>

		<br>
		<?php if(isset($_GET['chx_equipe'])) { ?>
			<label>Sélectionnez une équipe :</label><br>
			<select name="equipe" size="4" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les equipes dans une liste box -->
				<?php
					foreach ($result_equipe as $key => $value) {
						echo "<option value='".$value[0]."'"; if(isset($_GET['equipe']) and $_GET['equipe'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
					}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_GET['chx_utilisateur'])) { ?>
			<label>Sélectionnez un utilisateur :</label><br>
			<select name="utilisateur" size="4" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les utilisateurs dans une liste box -->
				<?php
						foreach ($result_utilisateur as $key => $value) {
							echo "<option value='".$value[0]."'"; if(isset($_GET['utilisateur']) and $_GET['utilisateur'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]. " " .$value[2]."</option>";
						}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_GET['chx_typeContrat'])) { ?>
			<label>Sélectionnez un type de contrat :</label><br>
			<select name="typeContrat" size="3" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les utilisateurs dans une liste box -->
				<?php
						foreach ($result_type as $key => $value) {
							echo "<option value='".$value[0]."'"; if(isset($_GET['typeContrat']) and $_GET['typeContrat'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
						}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_GET['chx_masseDispo'])) { ?>
			<label>masse disponible : </label><br>
			<select name="masseOperateur" size="1"  onchange="this.form.submit()">
				<option value=">" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == ">") echo "selected='selected'"; ?> >&gt;</option>
				<option value=">=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == ">=") echo "selected='selected'"; ?> >≥</option>
				<option value="<" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "<") echo "selected='selected'"; ?> >&lt;</option>
				<option value="<=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "<=") echo "selected='selected'"; ?> >≤</option>
				<option value="=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "=") echo "selected='selected'"; ?> >=</option>
			</select>

			<label><input type="number" name="masse" value="-1" style="width: 110px;"> mg</label><br><br>
		<?php } ?>

		<?php if(isset($_GET['chx_liste'])) { ?>
			<label>Liste d'identificateurs :</label><br>
			<textarea name="listeID" rows="8" cols="80" onchange="this.form.submit()"><?php if(isset($_GET['listeID'])) echo $_GET['listeID']; ?></textarea><br>

			<label>Séparateur utilisé pour la liste :<br>
				<select name="listeID_separateur" size="1" onchange="this.form.submit()">
					<option value=";" <?php if(isset($_GET['listeID_separateur']) and $_GET['listeID_separateur'] == ";") echo "selected='selected'"; ?>>;</option>
					<option value="," <?php if(isset($_GET['listeID_separateur']) and $_GET['listeID_separateur'] == ",") echo "selected='selected'"; ?>>,</option>
					<option value="espace" <?php if(isset($_GET['listeID_separateur']) and $_GET['listeID_separateur'] == "espace") echo "selected='selected'"; ?>>Espace</option>
					<option value="ligne" <?php if(isset($_GET['listeID_separateur']) and $_GET['listeID_separateur'] == "ligne") echo "selected='selected'"; ?>>Retour à la ligne</option>
				</select>
			</label><br><br>
		<?php } ?>

	<br><br>
	<?php if(isset($_GET['rad_format'])) { ?>
		<input type="image" name="download" value="download" src="images/charge.gif" alt="Télécharger le fichier" title="Télécharger le fichier" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">
	<?php } ?>
	<input type="image" name="liste" value="liste" src="images/liste.gif" alt="Afficher les resultats" title="Afficher les resultats" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">

	</form>

<?php
	if (isset($_GET['download_x']) || isset($_GET['liste_x'])){
		$sql_sdf = "SELECT pro_id_produit, pro_num_constant, str_mol, pro_masse, pro_purete, pro_methode_purete, pro_origine_substance, pro_numero, str_inchi FROM produit INNER JOIN structure ON produit.pro_id_structure = structure.str_id_structure WHERE 1=1";

		if(isset($_GET['chx_equipe']))
			if(isset($_GET['equipe']))
				$sql_sdf .= " AND pro_id_equipe = ". $_GET['equipe'];

		if(isset($_GET['chx_utilisateur']))
			if(isset($_GET['utilisateur']))
				$sql_sdf .= " AND (pro_id_responsable = ".$_GET['utilisateur']." or pro_id_chimiste = ".$_GET['utilisateur'].")";

		if(isset($_GET['chx_typeContrat']))
			if(isset($_GET['typeContrat']))
				$sql_sdf .= " AND pro_id_type = ". $_GET['typeContrat'];

		if(isset($_GET['chx_masseDispo']))
			if(isset($_GET['masseOperateur']) && isset($_GET['masse']))
				$sql_sdf .= " AND pro_masse ". $_GET['masseOperateur'] . $_GET['masse'];

		if(isset($_GET['chx_evotec']))
			$sql_sdf .= " AND pro_num_constant IN (SELECT evo_numero_permanent FROM evotec)";

		if (isset($_GET['chx_plaqueNonVrac'])){
			$sql_stockParametre = "SELECT para_stock FROM parametres;";
			$result_stockParametre = $dbh->query($sql_stockParametre);
			$row_stockParametre = $result_stockParametre->fetch(PDO::FETCH_NUM);

			$sql_sdf .= " AND (pro_id_produit IN (SELECT pos_id_produit FROM position) AND pro_masse >".$row_stockParametre[0].")";
		}

		if(isset($_GET['chx_liste'])){
			switch ($_GET['listeID_separateur']) {
				case ';':
						$listeID_value = str_replace(';', ',', $_GET['listeID']);
					break;
				case ',':
						$listeID_value = $_GET['listeID'];
					break;
				case 'espace':
							$listeID_value = str_replace(' ', ',', $_GET['listeID']);
					break;
				case 'ligne':
								$listeID_value = str_replace(array("\r\n","\n"), ',', $_GET['listeID']);

					break;
			}

			$listeID_array = explode(",",$listeID_value);
			$listeID_value = "";
			$listeID_value_num = "";
			foreach ($listeID_array as $key => $value) {
				$listeID_value.= "'".trim($value)."'";
				if (count($listeID_array) != ($key+1)) {
					$listeID_value .= ",";
				}
				// TODO
				if (is_numeric(trim($value))){
					$listeID_value_num.= trim($value);
					if (count($listeID_array) != ($key+1)) {
						$listeID_value_num .= ",";
					}
				}
			}

			$sql_sdf .= " AND (pro_numero IN (".$listeID_value.")";

			if(!empty($listeID_value_num)){
				$sql_sdf .= " OR pro_num_constant IN (".$listeID_value_num.")";
			}

			$sql_sdf .= ")";
		}

			// [JM - 24/01/2019] Preparation du contenue du fichier SDF
			$contenuFichier_sdf = "";
			$result_sdf = $dbh->query($sql_sdf);

			// [JM - 24/01/2019] Récupération de la liste des produits en plaque
			$sql_plaque="SELECT pos_id_plaque, pos_id_produit FROM position;";
			$result_plaque =$dbh->query($sql_plaque);
			$row_plaque=$result_plaque->fetchAll(PDO::FETCH_NUM);

			// [JM - 24/01/2019] Récupération de la liste des produits chez Evotec
			$sql_evotec="SELECT evo_numero_permanent FROM evotec;";
			$result_evotec =$dbh->query($sql_evotec);
			$row_evotec=$result_evotec->fetchAll(PDO::FETCH_NUM);

			$array_afficheListe = array();

			$contenuFichier_csv[0][0] = 'Numéro local';
			$contenuFichier_csv[0][1] = 'Numéro constant';
			$contenuFichier_csv[0][2] = 'Inchi';
			$contenuFichier_csv[0][3] = 'Masse';
			$contenuFichier_csv[0][4] = 'Numéro de plaque';
			$contenuFichier_csv[0][5] = "Chez Evotec";
			$contenuFichier_csv[0][6] = 'Pureté';
			$contenuFichier_csv[0][7] = 'Méthode pureée';
			$contenuFichier_csv[0][8] = 'Origine substance';

			// [JM - 24/01/2019] Boucle sur chaque produit
			foreach ($result_sdf as $key => $value) {

				if (isset($_GET['download_x'])){

				$contenuFichier_csv[$key+1][0] = " ";
				$contenuFichier_csv[$key+1][1] = " ";
				$contenuFichier_csv[$key+1][2] = " ";
				$contenuFichier_csv[$key+1][3] = " ";
				$contenuFichier_csv[$key+1][4] = " ";
				$contenuFichier_csv[$key+1][5] = " ";
				$contenuFichier_csv[$key+1][6] = " ";
				$contenuFichier_csv[$key+1][7] = " ";
				$contenuFichier_csv[$key+1][8] = " ";

				$contenuFichier_csv[$key+1][0] = $value['pro_numero'];
				$contenuFichier_csv[$key+1][1] = $value['pro_num_constant'];
				$contenuFichier_csv[$key+1][2] = $value['str_inchi'];
				$contenuFichier_csv[$key+1][3] = $value['pro_masse'];

				// [JM - 24/01/2019] Imprime la strucure MOL dans le fichier SDF
				$contenuFichier_sdf .= $value['str_mol'];

				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <identificateur_local> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_numero'];

				// [JM - 24/01/2019] Imprime le numero permanent dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <identificateur> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_num_constant'];


				// [JM - 24/01/2019] Imprime la masse du produit dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <vrac> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_masse'];


				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <plaque> (".($key + 1) .")";
				// [JM - 24/01/2019] Boucle sur la liste des produits en plaque

				$key_arr = array_search($value[0], array_column($row_plaque, 0));
				if ($key_arr)
				{
					$contenuFichier_sdf .= "\n". $row_plaque[$key_arr][0];
					$contenuFichier_csv[$key+1][4] = $row_plaque[$key_arr][0];
				}

				//[JM - 24/01/2019] Si contrainte Evotec cocher
				if(isset($_GET['chx_evotec'])){
				// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
					$contenuFichier_sdf .= "\nEvotec";
					$contenuFichier_csv[$key+1][5] = "OUI";
				}
				else {
					// [JM - 24/01/2019] Boucle sur la liste des produits chez Evotec
					foreach ($row_evotec as $key_evotec => $value_evotec) {
						if(in_array($value['pro_num_constant'], $value_evotec)){
							// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
							$contenuFichier_sdf .= "\nEvotec";
							$contenuFichier_csv[$key+1][5] = "OUI";
							break;
						}
					}
				}

				// [JM - 24/01/2019] Imprime la purete dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <purete> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_purete'];
				$contenuFichier_csv[$key+1][6] = $value['pro_purete'];

				// [JM - 24/01/2019] Imprime la methode de mesure de la purete dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <methode_mesure_purete> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_methode_purete'];
				$contenuFichier_csv[$key+1][7] = $value['pro_methode_purete'];

				// [JM - 24/01/2019] Imprime l'origine de la substance dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n>  <origine> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_origine_substance'];
				$contenuFichier_csv[$key+1][8] = $value['pro_origine_substance'];

				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n$$$$\n";
			}
				if (isset($_GET['liste_x'])){
					$array_afficheListe[] = $value['pro_numero'];
				}
			}
			if (isset($_GET['download_x'])){
				$timestamp = time();

				if ($_GET['rad_format'] == "SDF"){
					// [JM - 24/01/2019] création du fichier SDF
					$fichier_sdf = fopen('temp/'.$timestamp.'.sdf', 'w+');
					// [JM - 24/01/2019] Remplissage du fichier
					fwrite($fichier_sdf, $contenuFichier_sdf);
					echo "<a class='download-file' href='temp/".$timestamp.".sdf' download='Export_SDF_".date("Y-m-d").".sdf'></a>";
				}

				if ($_GET['rad_format'] == "CSV"){
					// [JM - 24/01/2019] création du fichier SDF
					$fichier_csv = fopen('temp/'.$timestamp.'.csv', 'w+');
					// [JM - 24/01/2019] Remplissage du fichier
					fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
					foreach($contenuFichier_csv as $ligne){
						fputcsv($fichier_csv, $ligne, ";");
					}
					echo "<a class='download-file' href='temp/".$timestamp.".csv' download='Export_CSV_".date("Y-m-d").".csv'></a>";
				}

			}
			if (isset($_GET['liste_x'])){
				if (sizeof($array_afficheListe) == 0)
					echo "Aucun résultat trouvé<br>";
				else
					if (sizeof($array_afficheListe) == 1)
						echo "Un résultat trouvé<br>";
				else
					echo sizeof($array_afficheListe)." résultats trouvés<br>";

				if (sizeof($array_afficheListe) >= 1){
					echo "<br>";
					echo "Numero d'identification local des composés :<br>";
					echo "<textarea rows='10' cols='29' disabled>";
					foreach ($array_afficheListe as $key => $value) {
						echo $value. "\n";
					}
					echo "</textarea>";
				}
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
	(function($){
	'use strict';

	const DataStatePropertyName = 'multiselect';
	const EventNamespace = '.multiselect';
	const PluginName = 'MultiSelect';

	var old = $.fn[PluginName];
	$.fn[PluginName] = plugin;
	$.fn[PluginName].Constructor = MultiSelect;
	$.fn[PluginName].noConflict = function () {
	$.fn[PluginName] = old;
	return this;
	};

	// Defaults
	$.fn[PluginName].defaults = {

	};

	// Static members
	$.fn[PluginName].EventNamespace = function () {
	return EventNamespace.replace(/^\./ig, '');
	};
	$.fn[PluginName].GetNamespacedEvents = function (eventsArray) {
	return getNamespacedEvents(eventsArray);
	};

	function getNamespacedEvents(eventsArray) {
	var event;
	var namespacedEvents = "";
	while (event = eventsArray.shift()) {
			namespacedEvents += event + EventNamespace + " ";
	}
	return namespacedEvents.replace(/\s+$/g, '');
	}

	function plugin(option) {
	this.each(function () {
			var $target = $(this);
			var multiSelect = $target.data(DataStatePropertyName);
			var options = (typeof option === typeof {} && option) || {};

			if (!multiSelect) {
					$target.data(DataStatePropertyName, multiSelect = new MultiSelect(this, options));
			}

			if (typeof option === typeof "") {
					if (!(option in multiSelect)) {
							throw "MultiSelect does not contain a method named '" + option + "'";
					}
					return multiSelect[option]();
			}
	});
	}

	function MultiSelect(element, options) {
	this.$element = $(element);
	this.options = $.extend({}, $.fn[PluginName].defaults, options);
	this.destroyFns = [];

	this.$toggle = this.$element.children('.toggle');
	this.$toggle.attr('id', this.$element.attr('id') + 'multi-select-label');
	this.$backdrop = null;
	this.$allToggle = null;

	init.apply(this);
	}

	MultiSelect.prototype.open = open;
	MultiSelect.prototype.close = close;

	function init() {
	this.$element
	.addClass('multi-select')
	.attr('tabindex', 0);

	initAria.apply(this);
	initEvents.apply(this);
	updateLabel.apply(this);
	injectToggleAll.apply(this);

	this.destroyFns.push(function() {
	return '|'
	});
	}

	function injectToggleAll() {
	if(this.$allToggle && !this.$allToggle.parent()) {
	this.$allToggle = null;
	}

	this.$allToggle = $("<li><label><input type='checkbox'/>(Tout)</label><li>");

	this.$element
	.children('ul:first')
	.prepend(this.$allToggle);
	}

	function initAria() {
	this.$element
	.attr('role', 'combobox')
	.attr('aria-multiselect', true)
	.attr('aria-expanded', false)
	.attr('aria-haspopup', false)
	.attr('aria-labeledby', this.$element.attr("aria-labeledby") + " " + this.$toggle.attr('id'));

	this.$toggle
	.attr('aria-label', '');
	}

	function initEvents() {
	var that = this;
	this.$element
	.on(getNamespacedEvents(['click']), function($event) {
	if($event.target !== that.$toggle[0] && !that.$toggle.has($event.target).length) {
	return;
	}

	if($(this).hasClass('in')) {
	that.close();
	} else {
	that.open();
	}
	})
	.on(getNamespacedEvents(['keydown']), function($event) {
	var next = false;
	switch($event.keyCode) {
	case 13:
		if($(this).hasClass('in')) {
			that.close();
		} else {
			that.open();
		}
		break;
	case 9:
		if($event.target !== that.$element[0]	) {
			$event.preventDefault();
		}
	case 27:
		that.close();
		break;
	case 40:
		next = true;
	case 38:
		var $items = $(this)
		.children("ul:first")
		.find(":input, button, a");

		var foundAt = $.inArray(document.activeElement, $items);
		if(next && ++foundAt === $items.length) {
			foundAt = 0;
		} else if(!next && --foundAt < 0) {
			foundAt = $items.length - 1;
		}

		$($items[foundAt])
		.trigger('focus');
	}
	})
	.on(getNamespacedEvents(['focus']), 'a, button, :input', function() {
	$(this)
	.parents('li:last')
	.addClass('focused');
	})
	.on(getNamespacedEvents(['blur']), 'a, button, :input', function() {
	$(this)
	.parents('li:last')
	.removeClass('focused');
	})
	.on(getNamespacedEvents(['change']), ':checkbox', function() {
	if(that.$allToggle && $(this).is(that.$allToggle.find(':checkbox'))) {
	var allChecked = that.$allToggle
	.find(':checkbox')
	.prop("checked");

	that.$element
	.find(':checkbox')
	.not(that.$allToggle.find(":checkbox"))
	.each(function(){
		$(this).prop("checked", allChecked);
		$(this)
		.parents('li:last')
		.toggleClass('selected', $(this).prop('checked'));
	});

	updateLabel.apply(that);
	return;
	}

	$(this)
	.parents('li:last')
	.toggleClass('selected', $(this).prop('checked'));

	var checkboxes = that.$element
	.find(":checkbox")
	.not(that.$allToggle.find(":checkbox"))
	.filter(":checked");

	that.$allToggle.find(":checkbox").prop("checked", checkboxes.length === checkboxes.end().length);

	updateLabel.apply(that);
	})
	.on(getNamespacedEvents(['mouseover']), 'ul', function() {
	$(this)
	.children(".focused")
	.removeClass("focused");
	});
	}

	function updateLabel() {
	var pluralize = function(wordSingular, count) {
	if(count !== 1) {
	switch(true) {
		case /y$/.test(wordSingular):
			wordSingular = wordSingular.replace(/y$/, "ies");
		default:
			wordSingular = wordSingular + "s";
	}
	}
	return wordSingular;
	}

	var $checkboxes = this.$element
	.find('ul :checkbox');

	var allCount = $checkboxes.length;
	var checkedCount = $checkboxes.filter(":checked").length
	var label = checkedCount + " " + pluralize("champ", checkedCount) + pluralize(" sélectionné", checkedCount);

	this.$toggle
	.children("label")
	.text(checkedCount ? (checkedCount === allCount ? '(Tout)' : label) : 'Sélectionnez les champs souhaité');

	this.$element
	.children('ul')
	.attr("aria-label", label + " of " + allCount + " " + pluralize("champs", allCount));
	}

	function ensureFocus() {
	this.$element
	.children("ul:first")
	.find(":input, button, a")
	.first()
	.trigger('focus')
	.end()
	.end()
	.find(":checked")
	.first()
	.trigger('focus');
	}

	function addBackdrop() {
	if(this.$backdrop) {
	return;
	}

	var that = this;
	this.$backdrop = $("<div class='multi-select-backdrop'/>");
	this.$element.append(this.$backdrop);

	this.$backdrop
	.on('click', function() {
	$(this)
	.off('click')
	.remove();

	that.$backdrop = null;
	that.close();
	});
	}

	function open() {
	if(this.$element.hasClass('in')) {
	return;
	}

	this.$element
	.addClass('in');

	this.$element
	.attr('aria-expanded', true)
	.attr('aria-haspopup', true);

	addBackdrop.apply(this);
	//ensureFocus.apply(this);
	}

	function close() {
	this.$element
	.removeClass('in')
	.trigger('focus');

	this.$element
	.attr('aria-expanded', false)
	.attr('aria-haspopup', false);

	if(this.$backdrop) {
	this.$backdrop.trigger('click');
	}
	}
	})(jQuery);

	$(document).ready(function(){
	$('#multi-select-plugin')
	.MultiSelect();
	});
</script>
