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

if(isset($_POST['chx_equipe'])){
	// [JM - 24/01/2019] Selectionne toute les equipes
	$sql_equipe="SELECT * FROM equipe;";
	$result_equipe = $dbh->query($sql_equipe);
}

if(isset($_POST['chx_utilisateur'])){
	// [JM - 24/01/2019] Selectionne les utilisateur
	$sql_utilisateur="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste ;";
	if(isset($_POST['chx_equipe'])){
		if (isset($_POST['equipe'])){
			$sql_utilisateur="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_equipe =".$_POST['equipe'].";";
		}
	}
	$result_utilisateur = $dbh->query($sql_utilisateur);
}

if(isset($_POST['chx_typeContrat'])){
	// [JM - 24/01/2019] Selectionne les type de contrat
 	$sql_type = "SELECT * FROM type;";
 	$result_type = $dbh->query($sql_type);
}
?>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="82" height="23" align="center" valign="middle" background="images/onglet1.gif"><a class="onglet" href="exportation.php"><?php echo "EXPORT" ?></a></td>
			<td width="82" height="23" align="center" valign="middle" background="images/onglet.gif"><a class="onglet" href="exportationcsvpesee.php"><?php echo CSV ?></a></td>
		</tr>
	</table>
	<br/>

	<!-- [JM - 24/01/2019] Debut du formulaire -->
	<form action="exportation.php" method="post">
		<div>
			<input type="radio" name="rad_format" value="SDF" onchange="this.form.submit()" <?php if(isset($_POST['rad_format']) && $_POST['rad_format'] == "SDF") echo "checked"; ?> >SDF<br>
			<input type="radio" name="rad_format" value="CSV" onchange="this.form.submit()" <?php if(isset($_POST['rad_format']) && $_POST['rad_format'] == "CSV") echo "checked"; ?> >CSV<br>
		</div>

		<div>
			<br>Sélectionnez les champs à exporter :
			<?php
				print"<div id=\"dhtmltooltip\"></div>
			  			<script language=\"javascript\" src=\"ttip.js\"></script>";
				$infoBule =
				"Si rien n'est coché, les champs exporter par défaut sont :<br><br>
				- Identificateur<br>
				- Numéro constant<br>
				- Inchi<br>
				- Masse<br>
				- Numéro de plaque<br>
				- Chez evotec (oui/non)<br>
				- Pureté<br>
				- Methode de messure de la purete<br>
				- Origine de la substance";
				$infoBule = str_replace(array("\n","\r","\t"),"",$infoBule);

				print"<a href=\"#\" onmouseover=\"ddrivetip('<p>".AddSlashes($infoBule)."</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/aide.gif\" /></a>";
			?>
			<br>
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
						"Numéro constant",
						"Inchi",
						"Point de fusion",
						"Point d'ebullition",
						"Methode de messure de la purete",
						"Numéro CN",
						"Origine de la substance",
						"QR code",
						"Pureté contrôlée (oui/non)",
						"Date de contrôle pureté",
						"Structure contrôlée (oui/non)",
						"Numéro de plaque",
						"Chez evotec (oui/non)"
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
										<input type="checkbox" name="chx_ChampsBDD_'.$key.'" value="'.$value.'" ';if(isset($_POST['chx_ChampsBDD_'.$key])) echo "checked";echo'/>
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
			<input type="checkbox" name="chx_equipe" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_equipe'])) echo "checked"; ?> >Equipe<br>
			<input type="checkbox" name="chx_utilisateur" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_utilisateur'])) echo "checked"; ?> >Utilisateur<br>
			<input type="checkbox" name="chx_typeContrat" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_typeContrat'])) echo "checked"; ?> >Type de contrat<br>
			<input type="checkbox" name="chx_masseDispo" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_masseDispo'])) echo "checked"; ?> >Masse disponible<br>
			<input type="checkbox" name="chx_plaqueNonVrac" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_plaqueNonVrac'])) echo "checked"; ?> >Produits en plaque mais pas en vrac<br>
			<br>
			<input type="radio" name="rad_evotec" <?php if (isset($_POST['rad_evotec']) && $_POST['rad_evotec']=="evotec") echo "checked";?> value="evotec" onchange="this.form.submit()">Seulement les produits présents chez Evotec<br>
  		<input type="radio" name="rad_evotec" <?php if (isset($_POST['rad_evotec']) && $_POST['rad_evotec']=="pasEvotec") echo "checked";?> value="pasEvotec" onchange="this.form.submit()">Seulement les produits qui ne sont pas chez Evotec<br>
  		<input type="radio" name="rad_evotec" <?php if ((isset($_POST['rad_evotec']) && $_POST['rad_evotec']=="lesDeux") || !isset($_POST['rad_evotec'])) echo "checked";?> value="lesDeux" onchange="this.form.submit()">Les deux<br>
			<br>
			<?php
			if(isset($_POST['rad_evotec']) && ($_POST['rad_evotec'] == "evotec")){
			?>
			<input type="radio" name="rad_evotec_insoluble" <?php if (isset($_POST['rad_evotec_insoluble']) && $_POST['rad_evotec_insoluble']=="evotec") echo "checked";?> value="evotec" onchange="this.form.submit()">Seulement les produits soluble (Evotec)<br>
  		<input type="radio" name="rad_evotec_insoluble" <?php if (isset($_POST['rad_evotec_insoluble']) && $_POST['rad_evotec_insoluble']=="pasEvotec") echo "checked";?> value="pasEvotec" onchange="this.form.submit()">Seulement les produits insoluble (Evotec)<br>
  		<input type="radio" name="rad_evotec_insoluble" <?php if ((isset($_POST['rad_evotec_insoluble']) && $_POST['rad_evotec_insoluble']=="lesDeux") || !isset($_POST['rad_evotec_insoluble'])) echo "checked";?> value="lesDeux" onchange="this.form.submit()">Les deux<br>
			<br>
			<?php
			}
			?>
			<input type="checkbox" name="chx_liste" value="1" onchange="this.form.submit()" <?php if(isset($_POST['chx_liste']) || isset($_GET['chx_liste'])) echo "checked"; ?> >Depuis une liste d'identificateurs<br>
		</div>

		<br>
		<?php if(isset($_POST['chx_equipe'])) { ?>
			<label>Sélectionnez une équipe :</label><br>
			<select name="equipe" size="4" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les equipes dans une liste box -->
				<?php
					foreach ($result_equipe as $key => $value) {
						echo "<option value='".$value[0]."'"; if(isset($_POST['equipe']) and $_POST['equipe'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
					}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_POST['chx_utilisateur'])) { ?>
			<label>Sélectionnez un utilisateur :</label><br>
			<select name="utilisateur" size="4" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les utilisateurs dans une liste box -->
				<?php
						foreach ($result_utilisateur as $key => $value) {
							echo "<option value='".$value[0]."'"; if(isset($_POST['utilisateur']) and $_POST['utilisateur'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]. " " .$value[2]."</option>";
						}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_POST['chx_typeContrat'])) { ?>
			<label>Sélectionnez un type de contrat :</label><br>
			<select name="typeContrat" size="3" onchange="this.form.submit()" style="width: 150px;">
				<!-- [JM - 24/01/2019] Affiche les utilisateurs dans une liste box -->
				<?php
						foreach ($result_type as $key => $value) {
							echo "<option value='".$value[0]."'"; if(isset($_POST['typeContrat']) and $_POST['typeContrat'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
						}
				?>
			</select><br><br>
		<?php } ?>

		<?php if(isset($_POST['chx_masseDispo'])) { ?>
			<label>masse disponible : </label><br>
			<select name="masseOperateur" size="1"  onchange="this.form.submit()">
				<option value=">" <?php if(isset($_POST['masseOperateur']) and $_POST['masseOperateur'] == ">") echo "selected='selected'"; ?> >&gt;</option>
				<option value=">=" <?php if(isset($_POST['masseOperateur']) and $_POST['masseOperateur'] == ">=") echo "selected='selected'"; ?> >≥</option>
				<option value="<" <?php if(isset($_POST['masseOperateur']) and $_POST['masseOperateur'] == "<") echo "selected='selected'"; ?> >&lt;</option>
				<option value="<=" <?php if(isset($_POST['masseOperateur']) and $_POST['masseOperateur'] == "<=") echo "selected='selected'"; ?> >≤</option>
				<option value="=" <?php if(isset($_POST['masseOperateur']) and $_POST['masseOperateur'] == "=") echo "selected='selected'"; ?> >=</option>
			</select>

			<label><input type="number" name="masse" value="-1" style="width: 110px;"> mg</label><br><br>
		<?php } ?>

		<?php if(isset($_POST['chx_liste']) || isset($_GET['chx_liste'])) { ?>
			<label>Liste d'identificateurs :</label><br>
			<textarea name="listeID" rows="8" cols="80" onchange="this.form.submit()"><?php if(isset($_POST['listeID'])) echo $_POST['listeID']; ?><?php if(isset($_GET['listeID'])) echo $_GET['listeID']; ?></textarea><br>

			<label>Séparateur utilisé pour la liste :<br>
				<select name="listeID_separateur" size="1" onchange="this.form.submit()">
					<option value=";" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == ";") echo "selected='selected'"; ?>>;</option>
					<option value="," <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == ",") echo "selected='selected'"; ?>>,</option>
					<option value="espace" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == "espace") echo "selected='selected'"; ?>>Espace</option>
					<option value="ligne" <?php if(isset($_POST['listeID_separateur']) and $_POST['listeID_separateur'] == "ligne") echo "selected='selected'"; ?>>Retour à la ligne</option>
				</select>
			</label><br><br>
		<?php } ?>

		<br><B style="color: red;">Mot de passe :</B><br>
		<input type="password" name="pass" placeholder="Mot de passe"><br>

	<br><br>
	<?php if(isset($_POST['rad_format'])) { ?>
		<input type="image" name="download" value="download" src="images/charge.gif" alt="Télécharger le fichier" title="Télécharger le fichier" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">
	<?php } ?>
	<input type="image" name="liste" value="liste" src="images/liste.gif" alt="Afficher les resultats" title="Afficher les resultats" onClick="document.getElementById('loader').style.visibility = 'visible';document.getElementById('table_principal').style.filter = 'blur(5px)';">

	</form>

<?php

if (isset($_POST["pass"]) && password_verify($_POST["pass"],$row[3])){

// TODO
$countACB = count($arrayChampsBDD);
for ($i=0; $i < $countACB; $i++) {
	if(isset($_POST['chx_ChampsBDD_'.$i])){
				$arrayChampsExport[] = $_POST['chx_ChampsBDD_'.$i];
		}
	}
	if(!isset($arrayChampsExport)){
		$arrayChampsExport = [
			"Identificateur",
			"Numéro constant",
			"Inchi",
			"Masse",
			"Numéro de plaque",
			"Chez evotec (oui/non)",
			"Pureté",
			"Methode de messure de la purete",
			"Origine de la substance"
		];
	}


	if (isset($_POST['download_x']) || isset($_POST['liste_x'])){
		$sql_sdf = "SELECT pro_id_produit, pro_num_constant, str_mol, pro_masse, pro_purete, pro_methode_purete, pro_origine_substance, pro_numero, str_inchi, typ_type, equi_nom_equipe, chim.chi_nom AS chim_nom, chim.chi_prenom AS chim_prenom, resp.chi_nom AS resp_nom, resp.chi_prenom AS resp_prenom, cou_couleur, pro_purification, pro_aspect, pro_date_entree, pro_ref_cahier_labo, pro_observation, pro_point_fusion, pro_point_ebullition, pro_num_cn, pro_qrcode, pro_controle_purete, pro_date_controle_purete, pro_controle_structure, pro_champsannexe
								FROM produit
								LEFT JOIN structure ON produit.pro_id_structure = structure.str_id_structure
								LEFT JOIN equipe ON produit.pro_id_equipe = equipe.equi_id_equipe
								LEFT JOIN chimiste chim ON produit.pro_id_chimiste = chim.chi_id_chimiste
								LEFT JOIN chimiste resp ON produit.pro_id_responsable = resp.chi_id_chimiste
								LEFT JOIN couleur ON produit.pro_id_couleur = couleur.cou_id_couleur
								LEFT JOIN type ON produit.pro_id_type = type.typ_id_type
								WHERE 1=1";

		if(isset($_POST['chx_equipe']))
			if(isset($_POST['equipe']))
				$sql_sdf .= " AND pro_id_equipe = ". $_POST['equipe'];

		if(isset($_POST['chx_utilisateur']))
			if(isset($_POST['utilisateur']))
				$sql_sdf .= " AND (pro_id_responsable = ".$_POST['utilisateur']." or pro_id_chimiste = ".$_POST['utilisateur'].")";

		if(isset($_POST['chx_typeContrat']))
			if(isset($_POST['typeContrat']))
				$sql_sdf .= " AND pro_id_type = ". $_POST['typeContrat'];

		if(isset($_POST['chx_masseDispo']))
			if(isset($_POST['masseOperateur']) && isset($_POST['masse']))
				$sql_sdf .= " AND pro_masse ". $_POST['masseOperateur'] . $_POST['masse'];

		if(isset($_POST['rad_evotec'])){
			if(isset($_POST['rad_evotec_insoluble'])){
				if($_POST['rad_evotec_insoluble'] == "evotec"){
					$sql_sdf .= " AND pro_num_constant IN (SELECT evo_numero_permanent FROM evotec WHERE evo_insoluble = FALSE)";
				}
				else
					if($_POST['rad_evotec_insoluble'] == "pasEvotec"){
						$sql_sdf .= " AND pro_num_constant IN (SELECT evo_numero_permanent FROM evotec WHERE evo_insoluble = TRUE)";
					}
				else {
					$sql_sdf .= " AND pro_num_constant IN (SELECT evo_numero_permanent FROM evotec)";
				}
			}
			else
			if($_POST['rad_evotec'] == "evotec"){
				$sql_sdf .= " AND pro_num_constant IN (SELECT evo_numero_permanent FROM evotec)";
			}
			else
				if($_POST['rad_evotec'] == "pasEvotec"){
					$sql_sdf .= " AND pro_num_constant NOT IN (SELECT evo_numero_permanent FROM evotec)";
				}
		}


		if (isset($_POST['chx_plaqueNonVrac'])){
			$sql_stockParametre = "SELECT para_stock FROM parametres;";
			$result_stockParametre = $dbh->query($sql_stockParametre);
			$row_stockParametre = $result_stockParametre->fetch(PDO::FETCH_NUM);

			$sql_sdf .= " AND (pro_id_produit IN (SELECT pos_id_produit FROM position) AND pro_masse >".$row_stockParametre[0].")";
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


			$sql_sdf .= " and (pro_numero IN (".$listeID_value.")";

			/* TODO
			$listeID_value_num = substr($listeID_value_num,0,-1);
			if(!empty($listeID_value_num)){
				$sql_sdf .= " OR pro_num_constant IN (".$listeID_value_num.")";
			}*/

			$sql_sdf .= ")";
		}

		$sql_sdf .= " ORDER BY pro_numero";
			// [JM - 24/01/2019] Preparation du contenue du fichier SDF
			$result_sdf = $dbh->query($sql_sdf);

			// [JM - 24/01/2019] Récupération de la liste des produits en plaque
			$sql_plaque="SELECT pos_id_plaque, pos_id_produit FROM position;";
			$result_plaque =$dbh->query($sql_plaque);
			$row_plaque=$result_plaque->fetchAll(PDO::FETCH_NUM);

			// [JM - 24/01/2019] Récupération de la liste des produits chez Evotec
			$sql_evotec="SELECT evo_numero_permanent FROM evotec;";
			$result_evotec =$dbh->query($sql_evotec);
			$row_evotec=$result_evotec->fetchAll(PDO::FETCH_NUM);

			$timestamp = time();
			ini_set('memory_limit', '256M');

			if (isset($_POST['liste_x'])){
				$array_afficheListe = array();
				foreach ($result_sdf as $key => $value) {
					$array_afficheListe[] = $value['pro_numero'];
					unset($value);
				}

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
			else
				if (isset($_POST['download_x'])){
					if ($_POST['rad_format'] == "SDF"){
						$fichier_sdf = fopen('temp/'.$timestamp.'.sdf', 'w+');

						foreach ($result_sdf as $key => $value) {
							unset($contenuFichier_sdf);

							$contenuFichier_sdf = $value['str_mol'];
							unset($value['str_mol']);

							if(in_array("Identificateur", $arrayChampsExport)){
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <identificateur_local> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['pro_numero'];
							}
							unset($value['pro_numero']);

							if(in_array("Numéro constant", $arrayChampsExport)){
							// [JM - 24/01/2019] Imprime le numero permanent dans le fichier SDF
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <identificateur> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['pro_num_constant'];
							}

							if(in_array("Inchi", $arrayChampsExport)){
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <inchi> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['str_inchi'];
							}
							unset($value['str_inchi']);

							if(in_array("Masse", $arrayChampsExport)){
							// [JM - 24/01/2019] Imprime la masse du produit dans le fichier SDF
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <vrac> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['pro_masse'];
							}
							unset($value['pro_masse']);

							if(in_array("Numéro de plaque", $arrayChampsExport)){
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <plaque> (".($key + 1) .")";
								// [JM - 24/01/2019] Boucle sur la liste des produits en plaque

								$key_arr = array_search($value[0], array_column($row_plaque, 0));
								if ($key_arr){
									$contenuFichier_sdf .= "\n". $row_plaque[$key_arr][0];
								}
								unset($key_arr);
							}

							if(in_array("Chez evotec (oui/non)", $arrayChampsExport)){
								//[JM - 24/01/2019] Si contrainte Evotec cocher
								if(isset($_POST['rad_evotec']) && $_POST['rad_evotec'] == "evotec"){
								// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
									$contenuFichier_sdf .= "\nEvotec";
								}
								else {
									// [JM - 24/01/2019] Boucle sur la liste des produits chez Evotec
									foreach ($row_evotec as $key_evotec => $value_evotec) {
										if(in_array($value['pro_num_constant'], $value_evotec)){
											// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
											$contenuFichier_sdf .= "\nEvotec";
											break;
										}
									}
								}
							}

							if(in_array("Pureté", $arrayChampsExport)){
							// [JM - 24/01/2019] Imprime la purete dans le fichier SDF
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <purete> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['pro_purete'];
							}
							unset($value['pro_purete']);

							if(in_array("Methode de messure de la purete", $arrayChampsExport)){
							// [JM - 24/01/2019] Imprime la methode de mesure de la purete dans le fichier SDF
								$contenuFichier_sdf .= "\n";
								$contenuFichier_sdf .= "\n>  <methode_mesure_purete> (".($key + 1) .")";
								$contenuFichier_sdf .= "\n".$value['pro_methode_purete'];
							}
							unset($value['pro_methode_purete']);

							if(in_array("Origine de la substance", $arrayChampsExport)){
							// [JM - 24/01/2019] Imprime l'origine de la substance dans le fichier SDF
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <origine> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_origine_substance'];
						}
						unset($value['pro_origine_substance']);

						if(in_array("Type", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Type> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['typ_type'];
						}
						unset($value['typ_type']);

						if(in_array("Equipe", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Equipe> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['equi_nom_equipe'];
						}
						unset($value['equi_nom_equipe']);

						if(in_array("Responsable", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Responsable> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['resp_nom'] . " " . $value['resp_prenom'];
						}
						unset($value['resp_nom']);
						unset($value['resp_prenom']);

						if(in_array("Chimiste", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Chimiste> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['chim_nom'] . " " . $value['chim_prenom'];
						}
						unset($value['chim_nom']);
						unset($value['chim_prenom']);

						if(in_array("Couleur", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Couleur> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['cou_couleur'];
						}
						unset($value['cou_couleur']);

						if(in_array("Purification", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Purification> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_purification'];
						}
						unset($value['pro_purification']);

						if(in_array("Aspect", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Aspect> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_aspect'];
						}
						unset($value['pro_aspect']);

						if(in_array("Date de saisie", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Date_de_saisie> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_date_entree'];
						}
						unset($value['pro_date_entree']);

						if(in_array("Reference cahier de labo", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Reference_cahier_de_labo> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_ref_cahier_labo'];
						}
						unset($value['pro_ref_cahier_labo']);

						if(in_array("Observation", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Observation> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_observation'];
						}
						unset($value['pro_observation']);

						if(in_array("Point de fusion", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Point_de_fusion> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_point_fusion'];
						}
						unset($value['pro_point_fusion']);

						if(in_array("Point d'ebullition", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Point_d'ebullition> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_point_ebullition'];
						}
						unset($value['pro_point_ebullition']);

						if(in_array("Numéro CN", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Numéro_CN> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_num_cn'];
						}
						unset($value['pro_num_cn']);

						if(in_array("QR code", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <QR_code> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_qrcode'];
						}
						unset($value['pro_qrcode']);

						if(in_array("Pureté contrôlée (oui/non)", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Pureté_contrôlée> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_controle_purete'];
						}
						unset($value['pro_controle_purete']);

						if(in_array("Date de contrôle pureté", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Date_de_contrôle_pureté> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_date_controle_purete'];
						}
						unset($value['pro_date_controle_purete']);

						if(in_array("Structure contrôlée (oui/non)", $arrayChampsExport)){
							$contenuFichier_sdf .= "\n";
							$contenuFichier_sdf .= "\n>  <Structure_contrôlée> (".($key + 1) .")";
							$contenuFichier_sdf .= "\n".$value['pro_controle_structure'];
						}
						unset($value['pro_controle_structure']);

						$contenuFichier_sdf .= "\n";
						$contenuFichier_sdf .= "\n$$$$\n";

						fwrite($fichier_sdf, $contenuFichier_sdf);

						}
						fclose($fichier_sdf);
						echo "<a class='download-file' href='temp/".$timestamp.".sdf' download='Export_SDF_".date("Y-m-d").".sdf'></a>";
						//unlink("temp/".$timestamp.".sdf");

					}
					else
						if ($_POST['rad_format'] == "CSV"){
							if(in_array("Identificateur", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Numéro local';
							}
							if(in_array("Numéro constant", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Numéro constant';
							}
							if(in_array("Inchi", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Inchi';
							}
							if(in_array("Masse", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Masse';
							}
							if(in_array("Numéro de plaque", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Numéro de plaque';
							}
							if(in_array("Chez evotec (oui/non)", $arrayChampsExport)){
								$contenuFichier_csv[0][] = "Chez Evotec";
							}
							if(in_array("Pureté", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Pureté';
							}
							if(in_array("Methode de messure de la purete", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Méthode pureée';
							}
							if(in_array("Origine de la substance", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Origine substance';
							}
							if(in_array("Type", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Type';
							}
							if(in_array("Equipe", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Equipe';
							}
							if(in_array("Responsable", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Responsable';
							}
							if(in_array("Chimiste", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Chimiste';
							}
							if(in_array("Couleur", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Couleur';
							}
							if(in_array("Purification", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Purification';
							}
							if(in_array("Aspect", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Aspect';
							}
							if(in_array("Date de saisie", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Date de saisie';
							}
							if(in_array("Reference cahier de labo", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Reference cahier de labo';
							}
							if(in_array("Observation", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Observation';
							}
							if(in_array("Point de fusion", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Point de fusion';
							}
							if(in_array("Point d'ebullition", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Point d\'ebullition';
							}
							if(in_array("Numéro CN", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Numéro CN';
							}
							if(in_array("QR code", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'QR code';
							}
							if(in_array("Pureté contrôlée (oui/non)", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Pureté contrôlée';
							}
							if(in_array("Date de contrôle pureté", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Date de contrôle pureté';
							}
							if(in_array("Structure contrôlée (oui/non)", $arrayChampsExport)){
								$contenuFichier_csv[0][] = 'Structure contrôlée';
							}

							foreach ($result_sdf as $key => $value) {
								foreach ($contenuFichier_csv[0] as $key1 => $value1) {
									// [JM - 24/01/2019] Remplissage du fichier
									$contenuFichier_csv[$key+1][$key1] = " ";
								}

								if(in_array("Identificateur", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Numéro local", $contenuFichier_csv[0])] = $value['pro_numero'];
								}
								unset($value['pro_numero']);

								if(in_array("Numéro constant", $arrayChampsExport)){
								// [JM - 24/01/2019] Imprime le numero permanent dans le fichier SDF
									$contenuFichier_csv[$key+1][array_search("Numéro constant", $contenuFichier_csv[0])] = $value['pro_num_constant'];
								}

								if(in_array("Inchi", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Inchi", $contenuFichier_csv[0])] = $value['str_inchi'];
								}
								unset($value['str_inchi']);

								if(in_array("Masse", $arrayChampsExport)){
								// [JM - 24/01/2019] Imprime la masse du produit dans le fichier SDF
									$contenuFichier_csv[$key+1][array_search("Masse", $contenuFichier_csv[0])] = $value['pro_masse'];
								}
								unset($value['pro_masse']);

								if(in_array("Numéro de plaque", $arrayChampsExport)){
									$key_arr = array_search($value[0], array_column($row_plaque, 0));
									if ($key_arr){
										$contenuFichier_csv[$key+1][array_search("Numéro de plaque", $contenuFichier_csv[0])] = $row_plaque[$key_arr][0];
									}
									unset($key_arr);
								}

								if(in_array("Chez evotec (oui/non)", $arrayChampsExport)){
									//[JM - 24/01/2019] Si contrainte Evotec cocher
									if(isset($_POST['rad_evotec']) && $_POST['rad_evotec'] == "evotec"){
									// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
										$contenuFichier_csv[$key+1][array_search("Chez Evotec", $contenuFichier_csv[0])] = "OUI";
									}
									else {
										// [JM - 24/01/2019] Boucle sur la liste des produits chez Evotec
										foreach ($row_evotec as $key_evotec => $value_evotec) {
											if(in_array($value['pro_num_constant'], $value_evotec)){
												// [JM - 24/01/2019] Imprime le TAG Evotec dans le fichier SDF
												$contenuFichier_csv[$key+1][array_search("Chez Evotec", $contenuFichier_csv[0])] = "OUI";
												break;
											}
										}
									}
								}

								if(in_array("Pureté", $arrayChampsExport)){
								// [JM - 24/01/2019] Imprime la purete dans le fichier SDF
									$contenuFichier_csv[$key+1][array_search("Pureté", $contenuFichier_csv[0])] = $value['pro_purete'];
								}
								unset($value['pro_purete']);

								if(in_array("Methode de messure de la purete", $arrayChampsExport)){
								// [JM - 24/01/2019] Imprime la methode de mesure de la purete dans le fichier SDF
									$contenuFichier_csv[$key+1][array_search("Méthode pureée", $contenuFichier_csv[0])] = $value['pro_methode_purete'];
								}
								unset($value['pro_methode_purete']);

								if(in_array("Origine de la substance", $arrayChampsExport)){
									// [JM - 24/01/2019] Imprime l'origine de la substance dans le fichier SDF
									$contenuFichier_csv[$key+1][array_search("Origine substance", $contenuFichier_csv[0])] = $value['pro_origine_substance'];
								}
								unset($value['pro_origine_substance']);

								if(in_array("Type", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Type", $contenuFichier_csv[0])] = $value['typ_type'];
								}
								unset($value['typ_type']);

								if(in_array("Equipe", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Equipe", $contenuFichier_csv[0])] = $value['equi_nom_equipe'];
								}
								unset($value['equi_nom_equipe']);

								if(in_array("Responsable", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Responsable", $contenuFichier_csv[0])] = $value['resp_nom'] . " " . $value['resp_prenom'];
								}
								unset($value['resp_nom']);
								unset($value['resp_prenom']);

								if(in_array("Chimiste", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Chimiste", $contenuFichier_csv[0])] = $value['chim_nom'] . " " . $value['chim_prenom'];
								}
								unset($value['chim_nom']);
								unset($value['chim_prenom']);

								if(in_array("Couleur", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Couleur", $contenuFichier_csv[0])] = $value['cou_couleur'];
								}
								unset($value['cou_couleur']);

								if(in_array("Purification", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Purification", $contenuFichier_csv[0])] = $value['pro_purification'];
								}
								unset($value['pro_purification']);

								if(in_array("Aspect", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Aspect", $contenuFichier_csv[0])] = $value['pro_aspect'];
								}
								unset($value['pro_aspect']);

								if(in_array("Date de saisie", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Date de saisie", $contenuFichier_csv[0])] = $value['pro_date_entree'];
								}
								unset($value['pro_date_entree']);

								if(in_array("Reference cahier de labo", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Reference cahier de labo", $contenuFichier_csv[0])] = $value['pro_ref_cahier_labo'];
								}
								unset($value['pro_ref_cahier_labo']);

								if(in_array("Observation", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Observation", $contenuFichier_csv[0])] = $value['pro_observation'];
								}
								unset($value['pro_observation']);

								if(in_array("Point de fusion", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Point de fusion", $contenuFichier_csv[0])] = $value['pro_point_fusion'];
								}
								unset($value['pro_point_fusion']);

								if(in_array("Point d'ebullition", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Point d'ebullition", $contenuFichier_csv[0])] = $value['pro_point_ebullition'];
								}
								unset($value['pro_point_ebullition']);

								if(in_array("Numéro CN", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Numéro CN", $contenuFichier_csv[0])] = $value['pro_num_cn'];
								}
								unset($value['pro_num_cn']);

								if(in_array("QR code", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("QR code", $contenuFichier_csv[0])] = $value['pro_qrcode'];
								}
								unset($value['pro_qrcode']);

								if(in_array("Pureté contrôlée (oui/non)", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Pureté contrôlée", $contenuFichier_csv[0])] = $value['pro_controle_purete'];
								}
								unset($value['pro_controle_purete']);

								if(in_array("Date de contrôle pureté", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Date de contrôle pureté", $contenuFichier_csv[0])] = $value['pro_date_controle_purete'];
								}
								unset($value['pro_date_controle_purete']);

								if(in_array("Structure contrôlée (oui/non)", $arrayChampsExport)){
									$contenuFichier_csv[$key+1][array_search("Structure contrôlée", $contenuFichier_csv[0])] = $value['pro_controle_structure'];
								}
								unset($value['pro_controle_structure']);
								}

								// [JM - 24/01/2019] création du fichier SDF
								$fichier_csv = fopen('temp/'.$timestamp.'.csv', 'w+');
								// [JM - 24/01/2019] Remplissage du fichier
								fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
								foreach($contenuFichier_csv as $ligne){
									if(!empty($ligne[0]))
										fputcsv($fichier_csv, $ligne, ";");
							}
							echo "<a class='download-file' href='temp/".$timestamp.".csv' download='Export_CSV_".date("Y-m-d").".csv'></a>";

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
