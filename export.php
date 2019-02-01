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
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

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

		<input type="radio" name="rad_format" value="SDF" onchange="this.form.submit()" <?php if(isset($_GET['rad_format']) && $_GET['rad_format'] == "SDF") echo "checked"; ?> >SDF<br>
		<input type="radio" name="rad_format" value="CSV" onchange="this.form.submit()" <?php if(isset($_GET['rad_format']) && $_GET['rad_format'] == "CSV") echo "checked"; ?> >CSV<br>

		<br>Sélectionnez vos critères de sélection :<br>
		<input type="checkbox" name="chx_equipe" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_equipe'])) echo "checked"; ?> >Equipe<br>
		<input type="checkbox" name="chx_utilisateur" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_utilisateur'])) echo "checked"; ?> >Utilisateur<br>
		<input type="checkbox" name="chx_typeContrat" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_typeContrat'])) echo "checked"; ?> >Type de contrat<br>
		<input type="checkbox" name="chx_masseDispo" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_masseDispo'])) echo "checked"; ?> >Masse disponible<br>
		<input type="checkbox" name="chx_plaqueNonVrac" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_plaqueNonVrac'])) echo "checked"; ?> >Produits en plaque mais pas en vrac<br>
		<input type="checkbox" name="chx_evotec" value="1" onchange="this.form.submit()" <?php if(isset($_GET['chx_evotec'])) echo "checked"; ?> >Chez Evotec<br>

		<br>
		<?php if(isset($_GET['chx_equipe'])) { ?>
			Sélectionnez une équipe :<br>
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
			Sélectionnez un utilisateur :<br>
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
			Sélectionnez un type de contrat :<br>
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
			masse disponible : <br>
			<select name="masseOperateur" size="1"  onchange="this.form.submit()">
				<option value=">" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == ">") echo "selected='selected'"; ?> >&gt;</option>
				<option value=">=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == ">=") echo "selected='selected'"; ?> >≥</option>
				<option value="<" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "<") echo "selected='selected'"; ?> >&lt;</option>
				<option value="<=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "<=") echo "selected='selected'"; ?> >≤</option>
				<option value="=" <?php if(isset($_GET['masseOperateur']) and $_GET['masseOperateur'] == "=") echo "selected='selected'"; ?> >=</option>
			</select>

			<label><input type="number" name="masse" value="-1" style="width: 110px;"> mg</label>
		<?php } ?>

	<br><br>
	<?php if(isset($_GET['rad_format'])) { ?>
		<input type="image" name="download" value="download" src="images/charge.gif" alt="Télécharger le fichier" title="Télécharger le fichier">
	<?php } ?>
	<input type="image" name="liste" value="liste" src="images/liste.gif" alt="Afficher les resultats" title="Afficher les resultats">

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
				$contenuFichier_sdf .= "\n> <identificateur_local> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_numero'];

				// [JM - 24/01/2019] Imprime le numero permanent dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n> <identificateur> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_num_constant'];


				// [JM - 24/01/2019] Imprime la masse du produit dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n> <vrac> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_masse'];


				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n> <plaque> (".($key + 1) .")";
				// [JM - 24/01/2019] Boucle sur la liste des produits en plaque
				foreach ($row_plaque as $key_plaque => $value_plaque) {
					// [JM - 24/01/2019] Imprime le numero de plaque dans le fichier SDF
					if(in_array($value[0], $value_plaque)){
						$contenuFichier_sdf .= "\n". $value_plaque[0];
						$contenuFichier_csv[$key+1][4] = $value_plaque[0];
						break;
					}
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
				$contenuFichier_sdf .= "\n> <purete> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_purete'];
				$contenuFichier_csv[$key+1][6] = $value['pro_purete'];

				// [JM - 24/01/2019] Imprime la methode de mesure de la purete dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n> <methode_mesure_purete> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_methode_purete'];
				$contenuFichier_csv[$key+1][7] = $value['pro_methode_purete'];

				// [JM - 24/01/2019] Imprime l'origine de la substance dans le fichier SDF
				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n> <origine> (".($key + 1) .")";
				$contenuFichier_sdf .= "\n".$value['pro_origine_substance'];
				$contenuFichier_csv[$key+1][8] = $value['pro_origine_substance'];

				$contenuFichier_sdf .= "\n";
				$contenuFichier_sdf .= "\n$$$$\n";


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
					echo sizeof($array_afficheListe)." résultats trouvé<br>";

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
?>
<!-- Auto click sur la balise <a class='download-file'> ci dessus -->
<script type="text/javascript">
	$('.download-file').get(0).click();
</script>
