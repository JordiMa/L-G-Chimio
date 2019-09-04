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

$id_chim_session = $row[1];


set_time_limit(0);
?>
	<br/>
	<h3 align="center">Importation pour l'extractothèque</h3>

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="CSVfile" accept=".csv">
		<br/><br/>
		<input type="submit"/>
	</form>

<a href="./images/importation_Extrait.xlsx">Explication et exemple pour l'importation</a>

<?php

	if (isset($_FILES['CSVfile'])){
		$fileHandle = fopen($_FILES['CSVfile']['tmp_name'], 'r') or die ("Impossible d'ouvrir le fichier");

		$ligneTab = fgetcsv($fileHandle, 0, ';');

		$ligneTab = array_map('strtolower', $ligneTab);
		$ligneTab = array_map('addslashes', $ligneTab);

		// [JM 08/2019] verifie si la case A1 est egale à 'importation'
		if ($ligneTab[0] == "importation") {
			// [JM 08/2019] Recherche des position dans le CSV
			// cf. template (images/importation_Extrait.xlsx)
			$key_ext_code = array_search('ext_code', $ligneTab);
			$key_ext_solvant = array_search('ext_solvant', $ligneTab);
			$key_ext_dispo = array_search('ext_dispo', $ligneTab);
			$key_ext_extraction = array_search('ext_extraction', $ligneTab);
			$key_ext_etat = array_search('ext_etat', $ligneTab);
			$key_ext_protocole = array_search('ext_protocole', $ligneTab);
			$key_ext_stockage = array_search('ext_stockage', $ligneTab);
			$key_ext_observation = array_search('ext_observation', $ligneTab);
			$key_ext_licence = array_search('ext_licence', $ligneTab);
			$key_ech_code = array_search('ech_code', $ligneTab);
			$key_ech_dispo = array_search('ech_dispo', $ligneTab);
			$key_ech_qte = array_search('ech_qte', $ligneTab);
			$key_ech_stockage = array_search('ech_stockage', $ligneTab);
			$key_ech_partieorga = array_search('ech_partieorga', $ligneTab);
			$key_ech_contact = array_search('ech_contact', $ligneTab);
			$key_ech_doi = array_search('ech_doi', $ligneTab);
			$key_con_temperature = array_search('con_temperature', $ligneTab);
			$key_con_milieu = array_search('con_milieu', $ligneTab);
			$key_con_culture = array_search('con_culture', $ligneTab);
			$key_con_modeop = array_search('con_modeop', $ligneTab);
			$key_con_observation = array_search('con_observation', $ligneTab);
			$key_spe_code = array_search('spe_code', $ligneTab);
			$key_spe_daterec = array_search('spe_daterec', $ligneTab);
			$key_spe_lieurec = array_search('spe_lieurec', $ligneTab);
			$key_spe_gps = array_search('spe_gps', $ligneTab);
			$key_spe_observation = array_search('spe_observation', $ligneTab);
			$key_spe_collection = array_search('spe_collection', $ligneTab);
			$key_spe_contact = array_search('spe_contact', $ligneTab);
			$key_spe_collecteur = array_search('spe_collecteur', $ligneTab);
			$key_tax_type = array_search('tax_type', $ligneTab);
			$key_tax_genre = array_search('tax_genre', $ligneTab);
			$key_tax_espece = array_search('tax_espece', $ligneTab);
			$key_tax_phylum = array_search('tax_phylum', $ligneTab);
			$key_tax_classe = array_search('tax_classe', $ligneTab);
			$key_tax_ordre = array_search('tax_ordre', $ligneTab);
			$key_tax_famille = array_search('tax_famille', $ligneTab);
			$key_tax_sous_espece = array_search('tax_sous_espece', $ligneTab);
			$key_tax_variete = array_search('tax_variete', $ligneTab);
			$key_tax_protocole = array_search('tax_protocole', $ligneTab);
			$key_tax_sequencage = array_search('tax_sequencage', $ligneTab);
			$key_tax_refbook = array_search('tax_refbook', $ligneTab);
			$key_exp_pays = array_search('exp_pays', $ligneTab);
			$key_exp_nom = array_search('exp_nom', $ligneTab);
			$key_exp_contact = array_search('exp_contact', $ligneTab);
			$key_chi_nom = array_search('chi_nom', $ligneTab);

			$keys_aut_numero = array_keys($ligneTab,'aut_numero');
			$keys_aut_type = array_keys($ligneTab,'aut_type');

			$i = 1;
			$erreur = "";

			if(!$ligneTab[$key_chi_nom]){
				$erreur .= "<br/>la colonne Chi_nom doit être présente.";
			}

			// [JM 08/2019] lit le fichier ligne par ligne
			while (($ligneTab = fgetcsv($fileHandle, 0, ';')) !== FALSE) {
				$i++;

				// [JM 08/2019] prepare la ligne du fichier
				$ligneTab = array_map('utf8_encode', $ligneTab);
				$ligneTab = array_map('trim', $ligneTab);
				$ligneTab = array_map('addslashes', $ligneTab);

				$ligneTab[0] = FALSE;

				// [JM 08/2019] Vérification des champs obligatoire
				if(!$ligneTab[$key_exp_pays]){
					$erreur .= "<br/>Erreur à la ligne $i : Exp_pays ne doit pas être vide.";
				}
				if(!$ligneTab[$key_tax_type]){
					$erreur .= "<br/>Erreur à la ligne $i : Tax_type ne doit pas être vide.";
				}
				if(!$ligneTab[$key_tax_genre]){
					$erreur .= "<br/>Erreur à la ligne $i : Tax_Genre ne doit pas être vide.";
				}
				if(!$ligneTab[$key_tax_espece]){
					$erreur .= "<br/>Erreur à la ligne $i : Tax_Espece ne doit pas être vide.";
				}
				if(!$ligneTab[$key_spe_code]){
					$erreur .= "<br/>Erreur à la ligne $i : Spe_code ne doit pas être vide.";
				}

				if(!$ligneTab[$key_spe_daterec]){
					$erreur .= "<br/>Erreur à la ligne $i : Spe_dateRec ne doit pas être vide.";
				}

				if(!$ligneTab[$key_spe_lieurec]){
					$erreur .= "<br/>Erreur à la ligne $i : Spe_lieuRec ne doit pas être vide.";
				}

				if(!$ligneTab[$key_ech_code]){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_code ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ech_dispo]){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_dispo ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ech_qte]){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_qte ne doit pas être vide.";
				}
				if(!is_numeric($ligneTab[$key_ech_qte])){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_qte doit être un nombre.";
				}
				if(!$ligneTab[$key_ech_stockage]){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_stockage ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ech_partieorga]){
					$erreur .= "<br/>Erreur à la ligne $i : Ech_partieOrga ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ext_code]){
					$erreur .= "<br/>Erreur à la ligne $i : Ext_code ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ext_solvant]){
					$erreur .= "<br/>Erreur à la ligne $i : Ext_solvant ne doit pas être vide.";
				}
				if(!$ligneTab[$key_ext_dispo]){
					$erreur .= "<br/>Erreur à la ligne $i : Ext_dispo ne doit pas être vide.";
				}
				if ($erreur != "") {
					break;// [JM 08/2019] annule la boucle si il y a une erreur
				}

				// [JM 08/2019] Recherche/Insertion étape par étapes
				// si un résultat de la recherche est trouvé, on garde l'id
				// sinon, on insère la partie dans la BDD
				//--Chimiste
				if(!$ligneTab[$key_chi_nom]){
					$id_chim = $id_chim_session;
				}
				else {
					$sql="select chi_id_chimiste FROM chimiste
					WHERE chi_nom || ' ' || chi_prenom iLIKE E'".$ligneTab[$key_chi_nom]."'
					OR chi_prenom || ' ' || chi_nom iLIKE E'".$ligneTab[$key_chi_nom]."'
					OR chi_nom iLIKE E'".$ligneTab[$key_chi_nom]."'";

					$result = $dbh->query($sql);
					$row = $result->fetch(PDO::FETCH_NUM);

					if($row){
						$id_chim = $row[0];
					}
					else {
						$stmt = $dbh->prepare("INSERT INTO Chimiste (chi_nom, chi_statut, chi_passif, chi_id_responsable, chi_id_equipe) VALUES (:chi_nom, :chi_statut, :chi_passif, :chi_id_responsable, :chi_id_equipe)");
				    $stmt->bindParam(':chi_nom', $ligneTab[$key_chi_nom]);
						$var_tmp = "{CHIMISTE}";
				    $stmt->bindParam(':chi_statut', $var_tmp);
						$var_tmp = 'TRUE';
				    $stmt->bindParam(':chi_passif', $var_tmp);
						$var_tmp = NULL;
						$stmt->bindParam(':chi_id_responsable', $var_tmp);
						$stmt->bindParam(':chi_id_equipe', $var_tmp);

				    $stmt->execute();
						$id_chim = $dbh->lastInsertId();

						if ($stmt->errorInfo()[0] != 00000){
							 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
							 break;
						 }
					}
				}
				//--Fin_Chimiste

				//--Expedition
				$sql="select exp_id FROM Expedition
				WHERE exp_nom iLIKE E'".$ligneTab[$key_exp_nom]."'
				AND exp_contact iLIKE E'".$ligneTab[$key_exp_contact]."'
				AND pay_code_pays iLIKE E'".$ligneTab[$key_exp_pays]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_exp = $row[0];
				}
				else {
					$stmt = $dbh->prepare("INSERT INTO Expedition (exp_nom, exp_contact, pay_code_pays) VALUES (:exp_nom, :exp_contact, :pay_code_pays)");
			    $stmt->bindParam(':exp_nom', $ligneTab[$key_exp_nom]);
			    $stmt->bindParam(':exp_contact', $ligneTab[$key_exp_contact]);
			    $stmt->bindParam(':pay_code_pays', $ligneTab[$key_exp_pays]);

			    $stmt->execute();

					$id_exp = $dbh->lastInsertId();

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }
				}
				//--Fin_Expedition

				//--Taxonomie
				$sql="select tax_id FROM Taxonomie
				INNER JOIN type_taxonomie on Taxonomie.typ_tax_id = type_taxonomie.typ_tax_id
				WHERE tax_phylum iLIKE E'".$ligneTab[$key_tax_phylum]."'
				AND tax_classe iLIKE E'".$ligneTab[$key_tax_classe]."'
				AND tax_ordre iLIKE E'".$ligneTab[$key_tax_ordre]."'
				AND tax_famille iLIKE E'".$ligneTab[$key_tax_famille]."'
				AND tax_genre iLIKE E'".$ligneTab[$key_tax_genre]."'
				AND tax_espece iLIKE E'".$ligneTab[$key_tax_espece]."'
				AND tax_sous_espece iLIKE E'".$ligneTab[$key_tax_sous_espece]."'
				AND tax_variete iLIKE E'".$ligneTab[$key_tax_variete]."'
				AND tax_protocole iLIKE E'".$ligneTab[$key_tax_protocole]."'
				AND tax_sequencage iLIKE E'".$ligneTab[$key_tax_sequencage]."'
				AND tax_seq_ref_book iLIKE E'".$ligneTab[$key_tax_refbook]."'
				AND typ_tax_type iLIKE E'".$ligneTab[$key_tax_type]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_tax = $row[0];
				}
				else {
					$sql="select typ_tax_id FROM Type_taxonomie
					WHERE typ_tax_type iLIKE E'".$ligneTab[$key_tax_type]."'";

					$result = $dbh->query($sql);
					$row = $result->fetch(PDO::FETCH_NUM);

					if($row){
						$id_typ_tax = $row[0];
					}
					else {
						$stmt = $dbh->prepare("INSERT INTO Type_taxonomie (typ_tax_type) VALUES (:typ_tax_type)");
						$stmt->bindParam(':typ_tax_type', $ligneTab[$key_tax_type]);

						$stmt->execute();

						$id_typ_tax = $dbh->lastInsertId();

						if ($stmt->errorInfo()[0] != 00000){
							 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
							 break;
						 }
					}

					$stmt = $dbh->prepare("INSERT INTO Taxonomie (tax_phylum, tax_classe, tax_ordre, tax_famille, tax_genre, tax_espece, tax_sous_espece, tax_variete, tax_protocole, tax_sequencage, tax_seq_ref_book, typ_tax_id) VALUES (:tax_phylum, :tax_classe, :tax_ordre, :tax_famille, :tax_genre, :tax_espece, :tax_sous_espece, :tax_variete, :tax_protocole, :tax_sequencage, :tax_seq_ref_book, :typ_tax_id)");
					$stmt->bindParam(':tax_phylum', $ligneTab[$key_tax_phylum]);
					$stmt->bindParam(':tax_classe', $ligneTab[$key_tax_classe]);
					$stmt->bindParam(':tax_ordre', $ligneTab[$key_tax_ordre]);
					$stmt->bindParam(':tax_famille', $ligneTab[$key_tax_famille]);
					$stmt->bindParam(':tax_genre', $ligneTab[$key_tax_genre]);
					$stmt->bindParam(':tax_espece', $ligneTab[$key_tax_espece]);
					$stmt->bindParam(':tax_sous_espece', $ligneTab[$key_tax_sous_espece]);
					$stmt->bindParam(':tax_variete', $ligneTab[$key_tax_variete]);
					$stmt->bindParam(':tax_protocole', $ligneTab[$key_tax_protocole]);
					$stmt->bindParam(':tax_sequencage', $ligneTab[$key_tax_sequencage]);
					$stmt->bindParam(':tax_seq_ref_book', $ligneTab[$key_tax_refbook]);
					$stmt->bindParam(':typ_tax_id', $id_typ_tax);

					$stmt->execute();

					$id_tax = $dbh->lastInsertId();

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }
				}
				//--Fin_Taxonomie

				//--Specimen
				$sql="select spe_code_specimen FROM Specimen
				WHERE spe_code_specimen iLIKE E'".$ligneTab[$key_spe_code]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_spe = $row[0];
				}
				else {

					$stmt = $dbh->prepare("INSERT INTO Specimen (spe_code_specimen, spe_date_recolte, spe_lieu_recolte, spe_gps_recolte, spe_observation, spe_collection, spe_contact, spe_collecteur, tax_id, exp_id) VALUES (:spe_code_specimen, :spe_date_recolte, :spe_lieu_recolte, :spe_gps_recolte, :spe_observation, :spe_collection, :spe_contact, :spe_collecteur, :tax_id, :exp_id)");

					$stmt->bindParam(':spe_code_specimen', $ligneTab[$key_spe_code]);

					if ($ligneTab[$key_spe_daterec] == 'NULL')
						$ligneTab[$key_spe_daterec] = NULL;
					else{
						$date = str_replace('/', '-', $ligneTab[$key_spe_daterec]);
						$ligneTab[$key_spe_daterec] = date('Y-m-d', strtotime($date));
					}

					$stmt->bindParam(':spe_date_recolte', $ligneTab[$key_spe_daterec]);
					$stmt->bindParam(':spe_lieu_recolte', $ligneTab[$key_spe_lieurec]);
					$stmt->bindParam(':spe_gps_recolte', $ligneTab[$key_spe_gps]);
					$stmt->bindParam(':spe_observation', $ligneTab[$key_spe_observation]);
					$stmt->bindParam(':spe_collection', $ligneTab[$key_spe_collection]);
					$stmt->bindParam(':spe_contact', $ligneTab[$key_spe_contact]);
					$stmt->bindParam(':spe_collecteur', $ligneTab[$key_spe_collecteur]);
					$stmt->bindParam(':tax_id', $id_tax);
					$stmt->bindParam(':exp_id', $id_exp);

			    $stmt->execute();
					$id_spe = $ligneTab[$key_spe_code];

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }

				}
				//--Fin_Specimen

				//--Autorisation
				foreach ($keys_aut_numero as $key => $value) {
					if ($ligneTab[$value]) {
						$sql="select aut_numero_autorisation FROM autorisation
						WHERE aut_numero_autorisation iLIKE E'".$ligneTab[$value]."'";

						//echo "<br>$sql<br>";
						$result = $dbh->query($sql);
						$row = $result->fetch(PDO::FETCH_NUM);

						if(!$row){
							$stmt = $dbh->prepare("INSERT INTO autorisation (aut_numero_autorisation, aut_type) VALUES (:aut_numero_autorisation, :aut_type)");
			        $stmt->bindParam(':aut_numero_autorisation', $ligneTab[$value]);
			        $stmt->bindParam(':aut_type', $ligneTab[$keys_aut_type[$key]]);
			        $stmt->execute();

							if ($stmt->errorInfo()[0] != 00000){
								 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
								 break;
							 }
						}

						$sql="select aut_numero_autorisation FROM autorisation_specimen
						WHERE aut_numero_autorisation iLIKE E'".$ligneTab[$value]."'
						AND spe_code_specimen iLIKE E'".$ligneTab[$key_spe_code]."'";

						//echo "<br>$sql<br>";
						$result = $dbh->query($sql);
						$row = $result->fetch(PDO::FETCH_NUM);

						if(!$row){
							$stmt = $dbh->prepare("INSERT INTO autorisation_specimen (aut_numero_autorisation, spe_code_specimen) VALUES (:aut_numero_autorisation, :spe_code_specimen)");
			        $stmt->bindParam(':aut_numero_autorisation', $ligneTab[$value]);
			        $stmt->bindParam(':spe_code_specimen', $ligneTab[$key_spe_code]);
			        $stmt->execute();

							if ($stmt->errorInfo()[0] != 00000){
								 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
								 break;
							 }
						}
					}
				}
				//--Fin_Autorisation

				//--Condition
				$id_con = NULL;
				if ($ligneTab[$key_con_milieu] || $ligneTab[$key_con_temperature] || $ligneTab[$key_con_culture] || $ligneTab[$key_con_modeop] || $ligneTab[$key_con_observation]) {

					if(!is_double($ligneTab[$key_con_temperature]) && !is_numeric($ligneTab[$key_con_temperature])){
						$erreur .= "<br/>Erreur à la ligne $i : Con_temperature doit être un nombre (double précision).";
						break;
					}

					$sql="select con_id FROM condition
					WHERE con_milieu iLIKE E'".$ligneTab[$key_con_milieu]."'
					AND con_temperature = '".$ligneTab[$key_con_temperature]."'
					AND con_type_culture iLIKE E'".$ligneTab[$key_con_culture]."'
					AND con_mode_operatoir iLIKE E'".$ligneTab[$key_con_modeop]."'
					AND con_observation iLIKE E'".$ligneTab[$key_con_observation]."'";

					$result = $dbh->query($sql);
					$row = $result->fetch(PDO::FETCH_NUM);

					if($row){
						$id_con = $row[0];
					}
					else {
						$stmt = $dbh->prepare("INSERT INTO condition (con_milieu, con_temperature, con_type_culture, con_mode_operatoir, con_observation) VALUES (:con_milieu, :con_temperature, :con_type_culture, :con_mode_operatoir, :con_observation)");
				    $stmt->bindParam(':con_milieu', $ligneTab[$key_con_milieu]);
				    $stmt->bindParam(':con_temperature', $ligneTab[$key_con_temperature]);
				    $stmt->bindParam(':con_type_culture', $ligneTab[$key_con_culture]);
						$stmt->bindParam(':con_mode_operatoir', $ligneTab[$key_con_modeop]);
						$stmt->bindParam(':con_observation', $ligneTab[$key_con_observation]);

				    $stmt->execute();
						$id_con = $dbh->lastInsertId();

						if ($stmt->errorInfo()[0] != 00000){
							 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
							 break;
						 }
					}
				}
				//--Fin_Condition

				//--Partie_organisme
				$sql="select par_id FROM partie_organisme
				WHERE par_fr iLIKE E'".$ligneTab[$key_ech_partieorga]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_par = $row[0];
				}
				else {
					$stmt = $dbh->prepare("INSERT INTO partie_organisme (par_fr) VALUES (:par_fr)");
					$stmt->bindParam(':par_fr', $ligneTab[$key_ech_partieorga]);

			    $stmt->execute();
					$id_par = $dbh->lastInsertId();

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }
				}
				//--Fin_Parti_organisme

				//--Echantillon
				$sql="select ech_code_echantillon FROM Echantillon
				WHERE ech_code_echantillon iLIKE E'".$ligneTab[$key_ech_code]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_ech = $row[0];
				}
				else {
					$stmt = $dbh->prepare("INSERT INTO Echantillon (ech_code_echantillon, ech_contact, ech_publication_doi, ech_stock_disponibilite, ech_stock_quantite, ech_lieu_stockage, par_id, spe_code_specimen, con_id) VALUES (:ech_code_echantillon, :ech_contact, :ech_publication_doi, :ech_stock_disponibilite, :ech_stock_quantite, :ech_lieu_stockage, :par_id, :spe_code_specimen, :con_id)");

					$stmt->bindParam(':ech_code_echantillon', $ligneTab[$key_ech_code]);
					$stmt->bindParam(':ech_contact', $ligneTab[$key_exp_contact]);
					$stmt->bindParam(':ech_publication_doi', $ligneTab[$key_ech_doi]);
					if ($ligneTab[$key_ech_dispo] == "oui") $ligneTab[$key_ech_dispo] = "TRUE";
					if ($ligneTab[$key_ech_dispo] == "non") $ligneTab[$key_ech_dispo] = "FALSE";
					$stmt->bindParam(':ech_stock_disponibilite', $ligneTab[$key_ech_dispo]);
					$stmt->bindParam(':ech_stock_quantite', $ligneTab[$key_ech_qte]);
					$stmt->bindParam(':ech_lieu_stockage', $ligneTab[$key_ech_stockage]);
					$stmt->bindParam(':par_id', $id_par);
					$stmt->bindParam(':spe_code_specimen', $id_spe);
					$stmt->bindParam(':con_id', $id_con);

			    $stmt->execute();

					$id_ech = $ligneTab[$key_ech_code];

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }
				}
				//--Fin_Echantillon

				//--Extrait
				$sql="select sol_id_solvant FROM solvant
				WHERE sol_solvant iLIKE E'".$ligneTab[$key_ext_solvant]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_solv = $row[0];
				}
				else {
					$id_solv = 18;
				}

				$sql="select ext_code_extraits FROM Extraits
				WHERE ext_code_extraits iLIKE E'".$ligneTab[$key_ext_code]."'";

				$result = $dbh->query($sql);
				$row = $result->fetch(PDO::FETCH_NUM);

				if($row){
					$id_ext = $row[0];
				}
				else {

					if($ligneTab[$key_ext_licence]){
						switch ($ligneTab[$key_ext_licence]) {
							case 'LIBRE':
								$ligneTab[$key_ext_licence] = 1;
								break;
							case 'CONTRAT':
								$ligneTab[$key_ext_licence] = 2;
								break;
							case 'BREVET':
								$ligneTab[$key_ext_licence] = 3;
								break;
							default:
								$ligneTab[$key_ext_licence] = 1;
								break;
						}
					}
					else {
						$ligneTab[$key_ext_licence] = 1;
					}

					$stmt = $dbh->prepare("INSERT INTO Extraits (ext_code_extraits, ext_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_id_chimiste, ech_code_echantillon, typ_id_type) VALUES (:ext_code_extraits, :ext_solvant, :ext_type_extraction, :ext_etat, :ext_disponibilite, :ext_protocole, :ext_stockage, :ext_observations, :chi_id_chimiste, :ech_code_echantillon, :typ_id_type)");

					$stmt->bindParam(':ext_code_extraits', $ligneTab[$key_ext_code]);
					$stmt->bindParam(':ext_solvant', $id_solv);
					$stmt->bindParam(':ext_type_extraction', $ligneTab[$key_ext_extraction]);
					$stmt->bindParam(':ext_etat', $ligneTab[$key_ext_etat]);

					if ($ligneTab[$key_ext_dispo] == "oui") $ligneTab[$key_ext_dispo] = "TRUE";
					if ($ligneTab[$key_ext_dispo] == "non") $ligneTab[$key_ext_dispo] = "FALSE";
					$stmt->bindParam(':ext_disponibilite', $ligneTab[$key_ext_dispo]);

					$stmt->bindParam(':ext_protocole', $ligneTab[$key_ext_protocole]);
					$stmt->bindParam(':ext_stockage', $ligneTab[$key_ext_stockage]);
					$stmt->bindParam(':ext_observations', $ligneTab[$key_ext_observation]);
					$stmt->bindParam(':chi_id_chimiste', $id_chim);
					$stmt->bindParam(':ech_code_echantillon', $id_ech);
					$stmt->bindParam(':typ_id_type', $ligneTab[$key_ext_licence]);

					$stmt->execute();
					$id_ext = $ligneTab[$key_ext_code];

					if ($stmt->errorInfo()[0] != 00000){
						 $erreur .= "<br/>Erreur à la ligne $i : ". $stmt->errorInfo()[0] . " : " . $stmt->errorInfo()[2];
						 break;
					 }
				}
				//--Fin_Extrait

		  }//Fin_while

			// [JM 08/2019] Affichage du résultat de l'importation
			echo "<center><h3>Importation terminée</h3></center><br/>";
			echo "<center><h3 style='color: limegreen;'>Nombre de ligne parcourues : ".($i-1)."</h3></center>";

			if ($erreur != "") {
				echo "<center><h3 style='color: red;'>".$erreur."</h3></center>";
			}
		}
		else {
			echo "<center><h2>La mention 'IMPORTATION' doit être placée en position A1 du fichier CSV</h2></center>";
		}
	}
}

else require 'deconnexion.php';
unset($dbh);
set_time_limit(120);
?>
