<script type="text/javascript" src="js/jquery.min.js"></script>
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
include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'presentation/entete.php';
$menu=9;
include_once 'presentation/gauche.php';

include_once 'script/secure.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_import.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
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

	// retire la limite du timeout
	set_time_limit(0);
	echo'
		<input type="hidden" id="nb" value= '.$_POST['nbrMol'].' />
		<progress id="progressBar" style="width:100%;"></progress>
		<br>
		<input type="text" id="nbProgress" value= "0/'.$_POST['nbrMol'].'" style="width: 100%;text-align: center;background: none;border: none;" disabled/>

		<script>
			var maxBar = document.getElementById(\'nb\').value;
			var currentBar = 0;
			var progressBar;
			var intervalId;

			var initialisation = function() {
				progressBar = document.getElementById( "progressBar" );
				progressBar.value = currentBar;
				progressBar.max = maxBar -1;
			}

			var displayBar = function() {
				currentBar++;
				progressBar.value = currentBar;
			}

			initialisation();

		</script>';

	require 'corps/importationSDF_massemolaire.php';

		function traitement($donnees,$i){ //formatage des données
			global $valid;
			global $decoupage;
			global $correspondance;
			global $transformation;
			global $contenuFichier_csv;
			global $array_afficheListe;
			global $valueBar;
			//$donnees = array_map('utf8_encode', $donnees);
			$aEnvoyer = [];

			$key = array_search('pro_numero', $correspondance);

			if (isset($_POST["correctionOnLive"])){
				if ($_POST["correctionOnLive"] == "false") {
					$donnees["molecule"] = validite_mol($donnees["molecule"],$i,false, $donnees[$key]);
				}
				else {
					$donnees["molecule"] = validite_mol($donnees["molecule"],$i,true, $donnees[$key]);
				}
			}
			else {
					$donnees["molecule"] = validite_mol($donnees["molecule"],$i,true, $donnees[$key]); //méthode de vérification et de potentielle correction de la molécule. ici, true car correctrion
			}

			if($valid){
			foreach($donnees as $key => $value){
				$infos = decoupe(utf8_encode($key),utf8_encode($value));

				foreach($infos as $key2 => $value2){
					if(array_key_exists($key2,$transformation)){
						$infos[$key2] = modification($key2,$value2);
					}

					if(array_key_exists($key2,$aEnvoyer)){
					$aEnvoyer[$key2] = $aEnvoyer[$key2]."\n".$infos[$key2];
					}else{
						$aEnvoyer[$key2] = $infos[$key2];
					}
				}
			}
			insertion($aEnvoyer);

			}
			$valueBar = ($_POST['nbrMol']-1)*$i/($_POST['nbrMol']-1);
			//echo "<script>progressBar.value = ".$valueBar.";</script>";
			echo "
			<script>
			document.getElementById(\"progressBar\").value = ".$valueBar."
			document.getElementById(\"nbProgress\").value ='".$i."/".($_POST['nbrMol']-1)."';
			</script>";
			if ($valueBar == ($_POST['nbrMol']-1)){
				echo "<br/><h1 align=\"center\">Traitement terminé !</h1>";
				if (sizeof($array_afficheListe) > 0){

					$timestamp = time();

					// [JM - 24/01/2019] création du fichier SDF
					$fichier_csv = fopen('temp/'.$timestamp.'.csv', 'w+');
					// [JM - 24/01/2019] Remplissage du fichier
					fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));
					foreach($contenuFichier_csv as $ligne){
						fputcsv($fichier_csv, $ligne, ";");
					}

					echo "<a class='download-file' href='temp/".$timestamp.".csv' download='Log_Importaiton_".date("Y-m-d").".csv'></a>";
					echo "
					<script type='text/javascript'>
						$('.download-file').get(0).click();
					</script>";
					}

					if (sizeof($array_afficheListe) == 0)
						echo "Aucune erreur trouvée<br>";
					else
						if (sizeof($array_afficheListe) == 1)
							echo "Une erreur trouvée<br>";
					else
						echo sizeof($array_afficheListe)." erreurs trouvées<br>";

					if (sizeof($array_afficheListe) >= 1){
						echo "<br>";
						echo "Liste des erreurs trouvées :<br>";
						echo "<textarea rows='20' disabled style='width: 100%'>";
						foreach ($array_afficheListe as $key => $value) {
							echo $value. "\n";
						}
						echo "</textarea>";
					}

			}

		}


		function decoupe($category,$str){ //découpe si besoin la donnée
			$infos = [];
			global $decoupage;
			global $correspondance;
			global $equivalence;
			if(array_key_exists($category,$decoupage)){

				$plan = explode("|",$decoupage[$category]);

				if($plan[1]==="2"){
					$tab = explode($plan[3],$str,2);
					$infos[$plan[2]] = $tab[0];
					if($plan[4] == "cou_couleur"){
						if(isset($tab[1])){
							$infos[$plan[4]] = $tab[1];
						}
						else {
							$infos[$plan[4]] = "INCON";
						}
					}
					else {
						$infos[$plan[4]] = $tab[1];
					}
				}

				if($plan[1]==="3"){
					$tab = explode($plan[3],$str,2);
					$infos[$plan[2]] = $tab[0];
					$tab = explode($plan[5], $tab[1], 2);
					$infos[$plan[4]] = $tab[0];
					$infos[$plan[6]] = $tab[1];
				}

				if($plan[1]==="4"){
					$tab = explode($plan[3],$str,2);
					$infos[$plan[2]] = $tab[0];
					$tab = explode($plan[5], $tab[1], 2);
					$infos[$plan[4]] = $tab[0];
					$tab = explode($plan[7], $tab[1], 2);
					$infos[$plan[6]] = $tab[0];
					$infos[$plan[8]] = $tab[1];
				}

			}else{
				if($category === "molecule"){
					$infos["str_mol"] = $str;
				}else{
					if(array_key_exists($category,$equivalence)){
						$infos[$correspondance[$equivalence[$category]]] = $str;
					}else{
						if($correspondance[$category]==="pro_observation"){
							$infos[$correspondance[$category]] = $category." : ".$str."<br><br>";
						}else{
							$infos[$correspondance[$category]] = $str;
						}
					}
				}
			}
		return $infos;
		}


		function modification($type,$str){ //applique la fonction de modification choisie dans "choixModifs.php"
			global $transformation;
			$modifAFaire = $transformation[$type];
			$newStr = $modifAFaire($str);
			return $newStr;
		}


		function insertion($infos){ //insert et/ou récupère les infos nécessaires à l'insertion du produit dans la base de donnée
			global $baseDonnees;

			//LES DIFFERENTES TABLES//
			$tab_chimiste = [];
			$tab_structure = [];
			$tab_plaque = [];
			$tab_produit = [];
			/////////////////////////

			foreach($infos as $key => $value){
				switch(getTable($key)){

					case "chimiste":
						$tab_chimiste[$key] = $value;
						break;
					case "structure":
						$tab_structure[$key] = $value;
						break;

					case "produit":
						$tab_produit[$key] = $value;
						break;

					case "plaque":
						$tab_plaque[$key] = $value;
						break;

					default:
				}
			}

			$infos_chimiste = insert_chimiste($tab_chimiste);

			foreach($infos_chimiste as $key => $value){
				$tab_produit[$key] = $value;
			}

			if(array_key_exists("cou_couleur",$infos)){
				$tab_produit["pro_id_couleur"] = getID("couleur","cou_id_couleur","cou_couleur",$infos["cou_couleur"]);
			}else{
				$tab_produit["pro_id_couleur"] = 218;
			}

			if(array_key_exists("typ_type",$infos)){
				$tab_produit["pro_id_type"] = insert_type($infos["typ_type"]);
			}else{
				$tab_produit["pro_id_type"] = 1;
			}

			$tab_produit["pro_id_structure"] = insert_structure($tab_structure);

			if(array_key_exists("pro_purification",$infos)){
				$tab_produit["pro_purification"] = $infos["pro_purification"];
			}else{
				$tab_produit["pro_purification"] = "{INCONNUE}";
			}

			if(array_key_exists("pro_etape_mol",$infos)){
				$tab_produit["pro_etape_mol"] = $infos["pro_etape_mol"];
			}else{
				$tab_produit["pro_etape_mol"] = "{INCONNUE}";
			}

			if(array_key_exists("pro_ref_cahier_labo",$infos)){
				$tab_produit["pro_ref_cahier_labo"] = $infos["pro_ref_cahier_labo"];
			}else{
				$tab_produit["pro_ref_cahier_labo"] = "{ND}";
			}

			if(array_key_exists("pro_aspect",$infos)){
				switch ($infos["pro_aspect"]) {
					case '{GOMME}':
						$tab_produit["pro_aspect"] = $infos["pro_aspect"];
						break;
					case '{HUILE}':
						$tab_produit["pro_aspect"] = $infos["pro_aspect"];
						break;
					case '{LIQUIDE}':
						$tab_produit["pro_aspect"] = $infos["pro_aspect"];
						break;
					case '{MOUSSE}':
						$tab_produit["pro_aspect"] = $infos["pro_aspect"];
						break;
					case '{SOLIDE}':
						$tab_produit["pro_aspect"] = $infos["pro_aspect"];
						break;
					case '{INCONNU}':
						$tab_produit["pro_aspect"] = "{INCONNU}";
						break;
					default:
						$tab_produit["pro_aspect"] = "{INCONNU}";
						break;
				}
			}else{
				$tab_produit["pro_aspect"] = "{INCONNU}";
			}

			if(array_key_exists("pro_masse",$infos)){
				$tab_produit["pro_masse"] = $infos["pro_masse"];
			}else{
				$tab_produit["pro_masse"] = 0.0;
			}
				$tab_produit["pro_unite_masse"] = "{MG}";

			if(array_key_exists("pro_date_entree",$infos)){
				$tab_produit["pro_date_entree"] = $infos["pro_date_entree"];
			}else{
				$tab_produit["pro_date_entree"] = date("Y")."-".date("m")."-".date("d")." ".date("g").":".date("i").":".date("s");
			}

			if(array_key_exists("sol_solvant",$infos)){
				$tab_produit["sol_solvant"] = $infos["sol_solvant"];
			}else{
				$tab_produit["sol_solvant"] = "INCONNU";
			}

			if(array_key_exists("pro_purete",$infos)){
				$tab_produit["pro_purete"] = $infos["pro_purete"];
			}else{
				$tab_produit["pro_purete"] = "";
			}

			if(array_key_exists("pro_pourcentage_actif",$infos)){
				$tab_produit["pro_pourcentage_actif"] = $infos["pro_pourcentage_actif"];
			}else{
				$tab_produit["pro_pourcentage_actif"] = "";
			}

			if(array_key_exists("pro_sel",$infos)){
				$tab_produit["pro_sel"] = $infos["pro_sel"];
			}else{
				$tab_produit["pro_sel"] = "";
			}

			if(array_key_exists("pro_modop",$infos)){
				$tab_produit["pro_modop"] = $infos["pro_modop"];
			}else{
				$tab_produit["pro_modop"] = "";
			}

			if(array_key_exists("pro_statut",$infos)){
				$tab_produit["pro_statut"] = $infos["pro_statut"];
			}else{
				$tab_produit["pro_statut"] = "";
			}

			if(array_key_exists("pro_num_brevet",$infos)){
				$tab_produit["pro_num_brevet"] = $infos["pro_num_brevet"];
			}else{
				$tab_produit["pro_num_brevet"] = "";
			}

			if(array_key_exists("pro_ref_contrat",$infos)){
				$tab_produit["pro_ref_contrat"] = $infos["pro_ref_contrat"];
			}else{
				$tab_produit["pro_ref_contrat"] = "";
			}

			if(array_key_exists("pro_date_contrat",$infos)){
				$tab_produit["pro_date_contrat"] = $infos["pro_date_contrat"];
			}else{
				$tab_produit["pro_date_contrat"] = NULL;
			}


			if(array_key_exists("pro_configuration",$infos)){
				$tab_produit["pro_configuration"] = $infos["pro_configuration"];
			}else{
				$tab_produit["pro_configuration"] = "";
			}

			if(array_key_exists("pro_analyse_elem_trouve",$infos)){
				$tab_produit["pro_analyse_elem_trouve"] = $infos["pro_analyse_elem_trouve"];
			}else{
				$tab_produit["pro_analyse_elem_trouve"] = "";
			}

			if(array_key_exists("pro_point_fusion",$infos)){
				$tab_produit["pro_point_fusion"] = $infos["pro_point_fusion"];
			}else{
				$tab_produit["pro_point_fusion"] = "";
			}

			if(array_key_exists("pro_point_ebullition",$infos)){
				$tab_produit["pro_point_ebullition"] = $infos["pro_point_ebullition"];
			}else{
				$tab_produit["pro_point_ebullition"] = "";
			}

			if(array_key_exists("pro_pression_pb",$infos)){
				$tab_produit["pro_pression_pb"] = $infos["pro_pression_pb"];
			}else{
				$tab_produit["pro_pression_pb"] = "";
			}

			if(array_key_exists("pro_rf",$infos)){
				$tab_produit["pro_rf"] = $infos["pro_rf"];
			}else{
				$tab_produit["pro_rf"] = NULL;
			}

			if(array_key_exists("pro_rf_solvant",$infos)){
				$tab_produit["pro_rf_solvant"] = $infos["pro_rf_solvant"];
			}else{
				$tab_produit["pro_rf_solvant"] = "";
			}

			if(array_key_exists("pro_doi",$infos)){
				$tab_produit["pro_doi"] = $infos["pro_doi"];
			}else{
				$tab_produit["pro_doi"] = "";
			}

			if(array_key_exists("pro_hal",$infos)){
				$tab_produit["pro_hal"] = $infos["pro_hal"];
			}else{
				$tab_produit["pro_hal"] = "";
			}

			if(array_key_exists("pro_cas",$infos)){
				$tab_produit["pro_cas"] = $infos["pro_cas"];
			}else{
				$tab_produit["pro_cas"] = "";
			}

			if(array_key_exists("pro_suivi_modification",$infos)){
				$tab_produit["pro_suivi_modification"] = $infos["pro_suivi_modification"];
			}else{
				$tab_produit["pro_suivi_modification"] = "";
			}

			if(array_key_exists("pro_methode_purete",$infos)){
				$tab_produit["pro_methode_purete"] = $infos["pro_methode_purete"];
			}else{
				$tab_produit["pro_methode_purete"] = "";
			}

			if(array_key_exists("pro_num_cn",$infos)){
				$tab_produit["pro_num_cn"] = $infos["pro_num_cn"];
			}else{
				$tab_produit["pro_num_cn"] = "";
			}

			if(array_key_exists("pro_tare_pilulier",$infos)){
				$tab_produit["pro_tare_pilulier"] = $infos["pro_tare_pilulier"];
			}else{
				$tab_produit["pro_tare_pilulier"] = NULL;
			}

			insert_produit($tab_produit,$tab_plaque);


		}

		//OBTENIR ID
		function getID($table, $id, $nom, $valeur){ //récupère l'ID d'une ligne où "$nom = $valeur" ou la crée si elle n'existe pas

			global $baseDonnees;
			$sql = "SELECT * FROM ".$table." WHERE ".$nom." = '".$valeur."';";

			$req = $baseDonnees->query(utf8_encode($sql));
			$num=$req->rowCount();

			if($num>0){

				$sql = "SELECT ".$id." FROM ".$table." WHERE ".$nom." = '".$valeur."';";
				$req = $baseDonnees->query(utf8_encode($sql));
				$val = $req->fetch();
				return $val[$id];

			}else{

				$sql = "INSERT INTO ".$table." (".$nom.") VALUES (E'".addslashes($valeur)."');";
				$result = $baseDonnees->exec(utf8_encode($sql));
				$insertedID = $baseDonnees->lastInsertId($table."_".$id."_seq");
				return $insertedID;

			}
		}

		//GET VALEUR
		function getValeur($table,$id,$valId,$nom){ //récupère la valeur de $nom dans la ligne de "$table" où "$id = $valId"
			global $baseDonnees;

			$sql = "SELECT ".$nom." FROM ".$table." WHERE ".$id." = '".$valId."';";
			$result = $baseDonnees->query($sql);

			$valeur = $result->fetch();

			if($valeur == null) return null;
			return $valeur[$nom];
		}



		//AJOUTER INFO
		function update($table, $id, $valId, $nom, $valeur){ //change la ligne de "$table" où "$id = $valId" pour que "$nom = $valeur"
			global $baseDonnees;

			$sql = "UPDATE ".$table." SET ".$nom." = E'".addslashes($valeur)."' WHERE ".$id." = '".$valId."';";
			$baseDonnees->exec(utf8_encode($sql));
		}

		//CHECK INFO
		function check($table, $nom, $valeur){ //renvoie TRUE si il existe une ligne où "$nom = $valeur" dans la table "$table"
			global $baseDonnees;

			$sql = "SELECT * FROM ".$table." WHERE ".$nom." = '".$valeur."';";
			$req = $baseDonnees->query($sql);
			$resultat = $req->fetch();

			if ($resultat[0] != null) {
				return true;
			}

			return false;
		}

		//CHECK SI INFO
		function checkIf($table,$id,$valId, $nom, $valeur){ //renvoie TRUE si dans la ligne de "$table" où "$id = $valId", on a "$nom = $valeur"
			global $baseDonnees;

			$sql = "SELECT * FROM ".$table." WHERE ".$nom." = '".$valeur."' AND ".$id." = '".$valId."';";
			$req = $baseDonnees->query($sql);
			$resultat = $req->fetch();
			if ($resultat[0] != null) {
				return true;
			}
			return false;
		}

		//OBTENIR TABLE
		function getTable($str){ //renvoie le nom de la table selon le préfixe de la donnée
			$table = explode('_',$str)[0];

			switch($table){

				case "chi":
					return "chimiste";
					break;
				case "cib":
					return "cible";
					break;
				case "cou":
					return "couleur";
					break;
				case "equi";
					return "equipe";
					break;
				case "evo":
					return "evotec";
					break;
				case "hrms":
					return "hmrs";
					break;
				case "ir":
					return "ir";
					break;
				case "lab":
					return "labocible";
					break;
				case "lis":
					return "liste_precaution";
					break;
				case "lot":
					return "lot";
					break;
				case "num":
					return "num";
					break;
				case "nume":
					return "nume";
					break;
				case "para":
					return "parametres";
					break;
				case "pla":
					return "plaque";
					break;
				case "plac":
					return "plaque_cible";
					break;
				case "pos":
					return "position";
					break;
				case "pre":
					return "precaution";
					break;
				case "pro":
					return "produit";
					break;
				case "ref":
					return "reference_resultat";
					break;
				case "res":
					return "resultat";
					break;
				case "rmnc":
					return "rmnc";
					break;
				case "rmnh":
					return "rmnh";
					break;
				case "sm":
					return "sm";
					break;
				case "str":
					return "structure";
					break;
				case "typ":
					return "type";
					break;
				case "uv":
					return "uv";
					break;
				case "null":
					return "AUCUNE";
					break;
				default:
			}


		}

		function insert_chimiste($tab_chimiste){ //insert dans la base de donnée les infos concernant le chimiste et le responsable et les renvoie
			global $baseDonnees;
			$infos_chimiste = ["pro_id_chimiste" => "", "pro_id_responsable" => NULL, "pro_id_equipe" => NULL];
			$id = "chi_id_chimiste";
			$table = "chimiste";

			if(array_key_exists("chi_nom",$tab_chimiste)){
				$ID_chimiste = getID($table,$id,'chi_nom',$tab_chimiste["chi_nom"]);
				$infos_chimiste["pro_id_chimiste"] = $ID_chimiste;
				update($table,$id,$ID_chimiste,"chi_statut","{CHIMISTE}");

				if(array_key_exists("chi_prenom",$tab_chimiste)){
					update($table,$id,$ID_chimiste,"chi_prenom",$tab_chimiste["chi_prenom"]);
				}

				if(array_key_exists("chi_responsable",$tab_chimiste)){
					//CHIMISTE CONNU & RESPONSABLE CONNU
					$ID_responsable = getID($table,$id,"chi_id_responsable",$tab_chimiste["chi_responsable"]);
					update($table,$id,$ID_responsable,"chi_statut","{RESPONSABLE}");
					update($table,$id,$ID_chimiste,"chi_id_responsable",$ID_responsable);
					$infos_chimiste["pro_id_responsable"] = $ID_responsable;
					$nom_responsable = $tab_chimiste["chi_responsable"];

				}/*else{
					//CHIMISTE CONNU & RESPONSABLE INCONNU
					update($table,$id,$ID_chimiste,"chi_id_responsable",1);
					$infos_chimiste["pro_id_responsable"] = 1;
					$nom_responsable = "RESPONSABLE";
				}*/

			}elseif(array_key_exists("chi_responsable",$tab_chimiste)){

				//CHIMISTE INCONNU & RESPONSABLE CONNU
				$ID_chimiste = getID($table,$id,'chi_nom',$tab_chimiste["chi_responsable"]);

				update($table,$id,$ID_chimiste,"chi_id_responsable",$ID_chimiste);
				$infos_chimiste["pro_id_chimiste"] = $ID_chimiste;
				$infos_chimiste["pro_id_responsable"] = $ID_chimiste;
				$nom_responsable = $tab_chimiste["chi_responsable"];
				update($table,$id,$ID_chimiste,"chi_statut","{RESPONSABLE}");
			}else{
				//CHIMISTE INCONNU & RESPONSABLE INCONNU
				$ID_chimiste = getID($table,$id,'chi_nom',"Chimiste_Importation");
				update($table,$id,$ID_chimiste,"chi_statut","{CHIMISTE}");
				$infos_chimiste["pro_id_chimiste"] = $ID_chimiste;
				//$infos_chimiste["pro_id_responsable"] = 1;
				//$nom_responsable = "RESPONSABLE";
				}

				//update($table,$id,$ID_chimiste,"chi_id_equipe",id_equipe($nom_responsable));
				//$infos_chimiste["pro_id_equipe"] = id_equipe($nom_responsable);
				update($table,$id,$ID_chimiste,"chi_passif","true");
				//update("equipe","equi_id_equipe",id_equipe($nom_responsable),"equi_initiale_numero",substr($nom_responsable,0,1));

			return $infos_chimiste;
		}


		function id_equipe($nom){
			return getID("equipe", "equi_id_equipe", "equi_nom_equipe", "equipe_".$nom);
		}

		function insert_type($str){

			$ID_type = getID("type","typ_id_type","typ_type",$str);
			return $ID_type;

		}


		function insert_plaque($infos,$id_produit){
			global $baseDonnees;
			$table = "plaque";
			$id = "pla_id_plaque";

			if(array_key_exists("pla_identifiant_local",$infos) AND array_key_exists("pla_pos_coordonnees",$infos)){

				$ID_plaque = getID($table,$id,"pla_identifiant_local",$infos["pla_identifiant_local"]);

				if(array_key_exists("pla_concentration",$infos)){
					update($table,$id,$ID_plaque,"pla_concentration",$infos["pla_concentration"]);
				}else{
					update($table,$id,$ID_plaque,"pla_concentration",0.0);
				}

				if(array_key_exists("pla_nb_decongelation",$infos)){
					update($table,$id,$ID_plaque,"pla_nb_decongelation",$infos["pla_nb_decongelation"]);
				}else{
					update($table,$id,$ID_plaque,"pla_nb_decongelation",0);
				}

				if(array_key_exists("pla_date",$infos)){
					update($table,$id,$ID_plaque,"pla_date",$infos["pla_date"]);
				}else{
					update($table,$id,$ID_plaque,"pla_date",date("d")."/".date("m")."/".date("Y"));
				}

				if(array_key_exists("pla_volume",$infos)){
					update($table,$id,$ID_plaque,"pla_volume",$infos["pla_volume"]);
				}else{
					update($table,$id,$ID_plaque,"pla_volume",100.0);
				}

				update($table,$id,$ID_plaque,"pla_unite_volume","{MIL}");

				if(array_key_exists("pla_masse",$infos)){
					update($table,$id,$ID_plaque,"pla_masse",$infos["pla_masse"]);
				}else{
					update($table,$id,$ID_plaque,"pla_masse",0.0);
				}

				update($table,$id,$ID_plaque,"pla_id_solvant",8);

				if(array_key_exists("pla_identifiant_externe",$infos)){
					update($table,$id,$ID_plaque,"pla_identifiant_externe",$infos["pla_identifiant_externe"]);
				}

				update($table,$id,$ID_plaque,"pla_id_plaque_mere",0);

				$baseDonnees->exec(utf8_encode("INSERT INTO position(pos_id_plaque,pos_id_produit,pos_coordonnees) VALUES ('".$ID_plaque."','".$id_produit."','".strtolower($infos["pla_pos_coordonnees"])."');"));
				if(array_key_exists("pla_lot_num_lot",$infos)){
					$ID_lot = getID("lot","lot_id_lot","lot_num_lot",$infos["pla_lot_num_lot"]);

					$sql = "SELECT * FROM lotplaque WHERE lopla_id_lot = '".$ID_lot."' AND lopla_id_plaque = '".$ID_plaque."';";
					$req = $baseDonnees->query($sql);
					$result = $req->fetch();
					if($result[0] == null){
						$baseDonnees->exec(utf8_encode("INSERT INTO lotplaque(lopla_id_lot,lopla_id_plaque) VALUES ('".$ID_lot."','".$ID_plaque."');"));
					}

				}

			}else{

				if(!(checkIf("produit","pro_id_produit",$id_produit,"pro_observation",null))){

				$observations = getValeur("produit","pro_id_produit",$id_produit,"pro_observation");
				}else{
					$observations = "";
				}
				foreach($infos as $key => $value){
					$observations = $observations." ".$key." : ".$value."<br><br>";
				}
				update("produit","pro_id_produit",$id_produit,"pro_observation",$observations);

			}

		}

		function insert_structure($infos){
			global $baseDonnees;
			$table = "structure";
			$id = "str_id_structure";
			$mol = $infos["str_mol"];

			if(!check($table,"str_mol",$mol)){
				if(!(array_key_exists("str_nom",$infos))){
					$infos["str_nom"] = "NON DEFINI";
				}
			$sql = "INSERT INTO structure(str_nom,str_mol,str_formule_brute,str_masse_molaire,str_analyse_elem,str_inchi,str_inchi_md5) VALUES(E'".addslashes($infos["str_nom"])."','".$mol."','".formule_brute($mol)."','".masse_molaire($mol)."','".analyse_elem($mol)."','".inchi($mol)."','".inchi_md5($mol)."');";
			$result = $baseDonnees->exec(utf8_encode($sql));

			}
			$ID_structure = getID($table,$id,"str_mol",$mol);

			if(checkIf($table,$id,$ID_structure,"str_nom","NON DEFINI") AND array_key_exists("str_nom",$infos)){
				update($table,$id,$ID_structure,"str_nom",$infos["str_nom"]);
			}

			return $ID_structure;
		}

		function insert_solvant($str,$id_produit){
			global $baseDonnees;
			$table = "solvant";
			$id = "sol_id_solvant";

			$solvants = array(

				"ACETATETYLE" => "#ACETATETYLE#",
				"ACETONE" => "#ACETONE#",
				"ACETONITRILE" => "#ACETONITRILE#",
				"BENZENE" => "#BENZENE#",
				"CHOLOROFORME" => "#CHOLOROFORME#",
				"DICHLO" => "#DICHLO#",
				"DMF" => "#DMF#",
				"DMSO" => "#DMSO#",
				"EAU" => "#EAU#",
				"ETHANOL" => "#ETHANOL#",
				"ETHERPET" => "#ETHERPET#",
				"ETHERETHYL" => "#ETHERETHYL#",
				"METHANOL" => "#METHANOL#",
				"PYRIDINE" => "#PYRIDINE#",
				"THF" => "#THF#",
				"TOLUENE" => "#TOLUENE#",
				"INSOLUBLE" => "#INSOLUBLE#",

			);

			foreach($solvants as $key => $value){

				if(preg_match($value,$str)){

					$ID_solvant = getID($table,$id,"sol_solvant",$key);
					$sql = "INSERT INTO solubilite(sol_id_solvant,sol_id_produit) VALUES ('".$ID_solvant."','".$id_produit."');";

						$baseDonnees->exec(utf8_encode($sql));

				}
			}
			if(!(check("solubilite","sol_id_produit",$id_produit))){
				$sql = "INSERT INTO solubilite(sol_id_solvant,sol_id_produit) VALUES ('18','".$id_produit."');";
				$sql = utf8_encode($sql);
			}
		}

		function insert_origine_substance($str,$id_produit){

			$table = "produit";
			$id = "pro_id_produit";

			$origines = array(

				"{SYNTHESE}" => "#SYNTHESE#",
				"{HEMISYNTHESE}" => "#HEMISYNTHESE#",
				"{NATURELLE}" => "#NATUREL#",
				"{INCONNU}" => "#INCONNU#",

			);

			foreach($origines as $key => $value){
				if(preg_match($value,$str)){
					update($table,$id,$id_produit,"pro_origine_substance",$key);
				}
			}
		}


		function formule_brute($mol){
			global $baseDonnees;
			$sql="SELECT bingo.Gross ('".$mol."');";
	        $result21=$baseDonnees->query($sql);
	        $formulebrute=$result21->fetch(PDO::FETCH_NUM);
	        $formulebrute1=str_replace(' ','-',$formulebrute[0]);
					//echo "string :" . $mol. "<br>";
					//echo "string2 :"; print_r($formulebrute) ;echo"<br>";
					//echo "string2 :" . $formulebrute1 . "<br>";
			return $formulebrute1;
		}

		function masse_molaire($mol){
			global $baseDonnees;
			$sql="SELECT bingo.getWeight ('".$mol."','molecular-weight');";
	        $result22=$baseDonnees->query($sql);
	        $massemolaire=$result22->fetch(PDO::FETCH_NUM);
			return $massemolaire[0];
		}

		function analyse_elem($mol){
			global $baseDonnees;
	    $composition=pourcentelement (masse_molaire($mol),formule_brute($mol));

			return $composition;

		}

		function inchi($mol){
			global $baseDonnees;

			$sql="SELECT Bingo.InchI('".$mol."','')";
	        $resultinchi=$baseDonnees->query($sql);
	        $rowinchi=$resultinchi->fetch(PDO::FETCH_NUM);
			return $rowinchi[0];
		}

		function inchi_md5($mol){
			global $baseDonnees;
	        $sql="SELECT bingo.InChIKey ('".inchi($mol)."')";
	        $resultinchikey=$baseDonnees->query($sql);
	        $rowinchikey=$resultinchikey->fetch(PDO::FETCH_NUM);
			return $rowinchikey[0];
		}


		function insert_produit($infos,$plaque){
			global $baseDonnees;
			$table = "produit";
			$id = "pro_id_produit";
			$num_const = numero_constant();

			if (isset($infos["pro_numero"])){
				if(!(check("produit","pro_numero",$infos["pro_numero"]))){

					//$sql = "INSERT INTO produit(pro_id_type,pro_id_equipe,pro_id_responsable,pro_id_chimiste,pro_id_couleur,pro_id_structure,pro_purification,pro_masse,pro_unite_masse,pro_aspect,pro_date_entree,pro_ref_cahier_labo,pro_etape_mol,pro_numero,pro_num_constant, pro_purete) VALUES('".$infos["pro_id_type"]."',".$infos["pro_id_equipe"].",".$infos["pro_id_responsable"].",".$infos["pro_id_chimiste"].",'".$infos["pro_id_couleur"]."','".$infos["pro_id_structure"]."','".$infos["pro_purification"]."','".$infos["pro_masse"]."','".$infos["pro_unite_masse"]."','".$infos["pro_aspect"]."','".$infos["pro_date_entree"]."',E'".addslashes($infos["pro_ref_cahier_labo"])."','".$infos["pro_etape_mol"]."','".$infos["pro_numero"]."','".$num_const."', '".$infos["pro_purete"]."');";

					$stmt = $baseDonnees->prepare("INSERT INTO produit(pro_id_type, pro_id_equipe, pro_id_responsable, pro_id_chimiste, pro_id_couleur, pro_id_structure, pro_purification, pro_masse, pro_unite_masse, pro_aspect, pro_date_entree, pro_ref_cahier_labo, pro_etape_mol, pro_numero, pro_num_constant, pro_purete, pro_pourcentage_actif, pro_sel, pro_modop, pro_statut, pro_num_brevet, pro_ref_contrat, pro_date_contrat, pro_configuration, pro_analyse_elem_trouve, pro_point_fusion, pro_point_ebullition, pro_pression_pb, pro_rf, pro_rf_solvant, pro_doi, pro_hal, pro_cas, pro_suivi_modification, pro_methode_purete, pro_num_cn, pro_tare_pilulier) VALUES (:pro_id_type, :pro_id_equipe, :pro_id_responsable, :pro_id_chimiste, :pro_id_couleur, :pro_id_structure, :pro_purification, :pro_masse, :pro_unite_masse, :pro_aspect, :pro_date_entree, :pro_ref_cahier_labo, :pro_etape_mol, :pro_numero, :pro_num_constant, :pro_purete, :pro_pourcentage_actif, :pro_sel, :pro_modop, :pro_statut, :pro_num_brevet, :pro_ref_contrat, :pro_date_contrat, :pro_configuration, :pro_analyse_elem_trouve, :pro_point_fusion, :pro_point_ebullition, :pro_pression_pb, :pro_rf, :pro_rf_solvant, :pro_doi, :pro_hal, :pro_cas, :pro_suivi_modification, :pro_methode_purete, :pro_num_cn, :pro_tare_pilulier)");

					$stmt->bindParam(':pro_id_type', $infos["pro_id_type"]);
					$stmt->bindParam(':pro_id_equipe', $infos["pro_id_equipe"]);
					$stmt->bindParam(':pro_id_responsable', $infos["pro_id_responsable"]);
					$stmt->bindParam(':pro_id_chimiste', $infos["pro_id_chimiste"]);
					$stmt->bindParam(':pro_id_couleur', $infos["pro_id_couleur"]);
					$stmt->bindParam(':pro_id_structure', $infos["pro_id_structure"]);
					$stmt->bindParam(':pro_purification', $infos["pro_purification"]);
					$stmt->bindParam(':pro_masse', $infos["pro_masse"]);
					$stmt->bindParam(':pro_unite_masse', $infos["pro_unite_masse"]);
					$stmt->bindParam(':pro_aspect', $infos["pro_aspect"]);
					$stmt->bindParam(':pro_date_entree', $infos["pro_date_entree"]);
					$stmt->bindParam(':pro_ref_cahier_labo', $infos["pro_ref_cahier_labo"]);
					$stmt->bindParam(':pro_etape_mol', $infos["pro_etape_mol"]);
					$stmt->bindParam(':pro_numero', $infos["pro_numero"]);
					$stmt->bindParam(':pro_num_constant', $num_const);

					$stmt->bindParam(':pro_purete', $infos["pro_purete"]);
					$stmt->bindParam(':pro_pourcentage_actif', $infos["pro_pourcentage_actif"]);
					$stmt->bindParam(':pro_sel', $infos["pro_sel"]);
					$stmt->bindParam(':pro_modop', $infos["pro_modop"]);
					$stmt->bindParam(':pro_statut', $infos["pro_statut"]);
					$stmt->bindParam(':pro_num_brevet', $infos["pro_num_brevet"]);
					$stmt->bindParam(':pro_ref_contrat', $infos["pro_ref_contrat"]);
					$stmt->bindParam(':pro_date_contrat', $infos["pro_date_contrat"]);
					$stmt->bindParam(':pro_configuration', $infos["pro_configuration"]);
					$stmt->bindParam(':pro_analyse_elem_trouve', $infos["pro_analyse_elem_trouve"]);
					$stmt->bindParam(':pro_point_fusion', $infos["pro_point_fusion"]);
					$stmt->bindParam(':pro_point_ebullition', $infos["pro_point_ebullition"]);
					$stmt->bindParam(':pro_pression_pb', $infos["pro_pression_pb"]);
					$stmt->bindParam(':pro_rf', $infos["pro_rf"]);
					$stmt->bindParam(':pro_rf_solvant', $infos["pro_rf_solvant"]);
					$stmt->bindParam(':pro_doi', $infos["pro_doi"]);
					$stmt->bindParam(':pro_hal', $infos["pro_hal"]);
					$stmt->bindParam(':pro_cas', $infos["pro_cas"]);
					$stmt->bindParam(':pro_suivi_modification', $infos["pro_suivi_modification"]);
					$stmt->bindParam(':pro_methode_purete', $infos["pro_methode_purete"]);
					$stmt->bindParam(':pro_num_cn', $infos["pro_num_cn"]);
					$stmt->bindParam(':pro_tare_pilulier', $infos["pro_tare_pilulier"]);

					$stmt->execute();
					//$result = $baseDonnees->exec($sql);
					}
					else {
						//$sql = "UPDATE produit SET pro_id_type = '".$infos["pro_id_type"]."', pro_id_equipe = ".$infos["pro_id_equipe"].", pro_id_responsable = ".$infos["pro_id_responsable"].", pro_id_chimiste = ".$infos["pro_id_chimiste"].", pro_id_couleur = '".$infos["pro_id_couleur"]."', pro_id_structure = '".$infos["pro_id_structure"]."', pro_purification = '".$infos["pro_purification"]."', pro_masse = '".$infos["pro_masse"]."', pro_unite_masse = '".$infos["pro_unite_masse"]."', pro_aspect = '".$infos["pro_aspect"]."', pro_date_entree = '".$infos["pro_date_entree"]."', pro_ref_cahier_labo = E'".addslashes($infos["pro_ref_cahier_labo"])."', pro_etape_mol = '".$infos["pro_etape_mol"]."', pro_num_constant = '".$num_const."', pro_purete = '".$infos["pro_purete"]."' WHERE pro_numero = '".$infos["pro_numero"]."';";

						$stmt = $baseDonnees->prepare("UPDATE produit SET pro_id_type = :pro_id_type, pro_id_equipe = :pro_id_equipe, pro_id_responsable = :pro_id_responsable, pro_id_chimiste = :pro_id_chimiste, pro_id_couleur = :pro_id_couleur, pro_id_structure = :pro_id_structure, pro_purification = :pro_purification, pro_masse = :pro_masse, pro_unite_masse = :pro_unite_masse, pro_aspect = :pro_aspect, pro_date_entree = :pro_date_entree, pro_ref_cahier_labo = :pro_ref_cahier_labo, pro_etape_mol = :pro_etape_mol, pro_numero = :pro_numero, pro_num_constant = :pro_num_constant, pro_purete = :pro_purete, pro_pourcentage_actif = :pro_pourcentage_actif, pro_sel = :pro_sel, pro_modop = :pro_modop, pro_statut = :pro_statut, pro_num_brevet = :pro_num_brevet, pro_ref_contrat = :pro_ref_contrat, pro_date_contrat = :pro_date_contrat, pro_configuration = :pro_configuration, pro_analyse_elem_trouve = :pro_analyse_elem_trouve, pro_point_fusion = :pro_point_fusion, pro_point_ebullition = :pro_point_ebullition, pro_pression_pb = :pro_pression_pb, pro_rf = :pro_rf, pro_rf_solvant = :pro_rf_solvant, pro_doi = :pro_doi, pro_hal = :pro_hal, pro_cas = :pro_cas, pro_suivi_modification = :pro_suivi_modification, pro_methode_purete = :pro_methode_purete, pro_num_cn = :pro_num_cn, pro_tare_pilulier = :pro_tare_pilulier WHERE pro_numero = '".$infos["pro_numero"]."';");

						$stmt->bindParam(':pro_id_type', $infos["pro_id_type"]);
						$stmt->bindParam(':pro_id_equipe', $infos["pro_id_equipe"]);
						$stmt->bindParam(':pro_id_responsable', $infos["pro_id_responsable"]);
						$stmt->bindParam(':pro_id_chimiste', $infos["pro_id_chimiste"]);
						$stmt->bindParam(':pro_id_couleur', $infos["pro_id_couleur"]);
						$stmt->bindParam(':pro_id_structure', $infos["pro_id_structure"]);
						$stmt->bindParam(':pro_purification', $infos["pro_purification"]);
						$stmt->bindParam(':pro_masse', $infos["pro_masse"]);
						$stmt->bindParam(':pro_unite_masse', $infos["pro_unite_masse"]);
						$stmt->bindParam(':pro_aspect', $infos["pro_aspect"]);
						$stmt->bindParam(':pro_date_entree', $infos["pro_date_entree"]);
						$stmt->bindParam(':pro_ref_cahier_labo', $infos["pro_ref_cahier_labo"]);
						$stmt->bindParam(':pro_etape_mol', $infos["pro_etape_mol"]);
						$stmt->bindParam(':pro_numero', $infos["pro_numero"]);
						$stmt->bindParam(':pro_num_constant', $num_const);

						$stmt->bindParam(':pro_purete', $infos["pro_purete"]);
						$stmt->bindParam(':pro_pourcentage_actif', $infos["pro_pourcentage_actif"]);
						$stmt->bindParam(':pro_sel', $infos["pro_sel"]);
						$stmt->bindParam(':pro_modop', $infos["pro_modop"]);
						$stmt->bindParam(':pro_statut', $infos["pro_statut"]);
						$stmt->bindParam(':pro_num_brevet', $infos["pro_num_brevet"]);
						$stmt->bindParam(':pro_ref_contrat', $infos["pro_ref_contrat"]);
						$stmt->bindParam(':pro_date_contrat', $infos["pro_date_contrat"]);
						$stmt->bindParam(':pro_configuration', $infos["pro_configuration"]);
						$stmt->bindParam(':pro_analyse_elem_trouve', $infos["pro_analyse_elem_trouve"]);
						$stmt->bindParam(':pro_point_fusion', $infos["pro_point_fusion"]);
						$stmt->bindParam(':pro_point_ebullition', $infos["pro_point_ebullition"]);
						$stmt->bindParam(':pro_pression_pb', $infos["pro_pression_pb"]);
						$stmt->bindParam(':pro_rf', $infos["pro_rf"]);
						$stmt->bindParam(':pro_rf_solvant', $infos["pro_rf_solvant"]);
						$stmt->bindParam(':pro_doi', $infos["pro_doi"]);
						$stmt->bindParam(':pro_hal', $infos["pro_hal"]);
						$stmt->bindParam(':pro_cas', $infos["pro_cas"]);
						$stmt->bindParam(':pro_suivi_modification', $infos["pro_suivi_modification"]);
						$stmt->bindParam(':pro_methode_purete', $infos["pro_methode_purete"]);
						$stmt->bindParam(':pro_num_cn', $infos["pro_num_cn"]);
						$stmt->bindParam(':pro_tare_pilulier', $infos["pro_tare_pilulier"]);

						$stmt->execute();

						//$result = $baseDonnees->exec($sql);
					}

					$ID_produit = getID("produit","pro_id_produit","pro_numero",$infos["pro_numero"]);

					if(!(check("solubilite","sol_id_produit",$ID_produit))){
						if($infos["sol_solvant"] == "INCONNU")
						$infos["sol_solvant"] = "18";
						$baseDonnees->exec("INSERT INTO solubilite(sol_id_solvant,sol_id_produit) VALUES ('".$infos["sol_solvant"]."','".$ID_produit."');");
					}

					insert_plaque($plaque,$ID_produit);
					insert_solvant($infos["sol_solvant"],$ID_produit);

					if(array_key_exists("pro_origine_substance",$infos)){
						insert_origine_substance($infos["pro_origine_substance"],$ID_produit);
					}else{
						insert_origine_substance("INCONNU",$ID_produit);
					}

					$observations="";
					$qrcode="";
					//$observations = getValeur("produit","pro_id_produit",$ID_produit,"pro_observation");
					foreach($infos as $key => $value){
						if(getTable($key)==="produit"){
							if($key === "pro_observation"){
								$observations = $observations."<br><br>".$value;
							}
							if($key === "pro_qrcode"){
								$qrcode = $value;
							}
						}
					}
					$observations = addslashes($observations);

					// TODO
					$sql2 = "UPDATE produit SET pro_observation = E'".$observations."', pro_qrcode = '".$qrcode."' WHERE pro_numero = '".$infos["pro_numero"]."'";
					$result2 = $baseDonnees->exec($sql2);

					// fonctionne pas ?
					//update("produit","pro_id_produit",$ID_produit,"pro_observation",$observations);

			}
		}


		function numero_constant(){
			global $baseDonnees;
			//génère un chiffre aléatoire entre 10000000 et 9999999 (pro_num_constant)
			mt_srand(microtime()*10000);
			$o=0;
			while ($o!=1) {
				$chiffre=mt_rand(10000000,99999999);
				$sql="SELECT pro_num_constant FROM produit WHERE pro_num_constant='".$chiffre."'";
				$resultnb=$baseDonnees->query($sql);
				$nbresultnb=$resultnb->rowCount();
				if ($nbresultnb>0) $o=0;
				else $o=1;
			}
			return $chiffre;
		}

		function aucune($str){
			return $str;
		}

		function noAccents($str){

			return strtr(
			$str,
			'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
			'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
		);

		}


		function FR_to_Hexa($str){
			$couleurs = array(

				"argent" => "CCCCCC",
				"beige" => "FFCC99",
				"blanc" => "FFFFFF",
				"blanc cassé" => "FFFFCC",
			//	"blanche" => "FFFFFF",
				"bleu" => "0000FF",
				"cyan" => "00FFFF",
				"écru" => "FFFFCC",
				"gris" => "CCCCCC",
				"jaune" => "FFFF00",
				"jaune clair" => "FFFF99",
				"jaune pâle" => "FFFF99",
				"magenta" => "FF00FF",
				"marron" => "996600",
				"mauve" => "FFCCFF",
				"noir" => "000000",
				//"noisette" => "955628",
				"or" => "FFFF33",
				"orange" => "FF9900",
				"rose" => "FF66CC",
				"rouge" => "FF0000",
				"turquoise" => "00CCFF",
				"vert" => "00FF00",
				"violet" => "660099",
				"incol" => "INCOL",
				"INCON" => "INCON"
			);

			foreach($couleurs as $key => $value){
				$modele = "#".$key."\w*#";
				if(preg_match($modele,$str)){
					return $value;
				}
			}
			return "ND";
		}

		function G_to_MG($str){
			$str2 = formatage_nombre($str);
			$val = doubleval($str2);
			return ($val * 1000);
		}

		function microG_to_MG($str){
			$str2 = formatage_nombre($str);
			$val = doubleval($str2);
			return ($val / 1000);
		}

		function formatage_nombre($str){
			if(strpos($str, ',')>0){
			$str2 = str_replace(",",".",$str);
			}else{
				$str2 = $str.".0";
			}
			return $str2;
		}

		function ML_to_MIL($str){
			$str2 = formatage_nombre($str);
			$val = doubleval($str2);
			return ($val * 1000);

		}

		function init_to_mot($str){
			if($str === "L" || $str === "l"){
				return "LIBRE";
			}elseif($str === "C" || $str === "c"){
				return "CONTRAT";
			}elseif($str === "B" || $str === "b"){
				return "BREVET";
			}
		}

		function formatage_majuscules($str){
			$str = noAccents($str);
			return strtoupper($str);
		}

		function formatage_purification($str){
			$str = noAccents($str);
			$str = strtoupper($str);
			return "{".$str."}";
		}
		function formatage_etape_mol($str){
			$str = noAccents($str);
			$str = strtoupper($str);
			return "{".$str."}";
		}

		function formatage_aspect($str){
			if(preg_match("#crista#",$str)){ $str = "SOLIDE";}
			if(preg_match("#poudre#",$str)){ $str = "SOLIDE";}

			$str = noAccents($str);
			$str = strtoupper($str);
			return "{".$str."}";
		}

		function date_jour_mois_annee($str){
			$infos = explode('/',$str);
			foreach($infos as $key => $value){
				if(strlen($value)===1){
					$infos[$key] = "0".$value;
				}
			}
			return $infos[2]."-".$infos[1]."-".$infos[0]." 00:00:00";
		}

		function date_mois_jour_annee($str){
			$infos = explode('/',$str);
			foreach($infos as $key => $value){
				if(strlen($value)===1){
					$infos[$key] = "0".$value;
				}
			}
			return $infos[2]."-".$infos[0]."-".$infos[1]." 00:00:00";
		}

		function date_jour_mois_annee_sans_heure($str){
			$infos = explode('/',$str);
			foreach($infos as $key => $value){
				if(strlen($value)===1){
					$infos[$key] = "0".$value;
				}
			}
			return $infos[2]."-".$infos[1]."-".$infos[0];
		}

		function date_mois_jour_annee_sans_heure($str){
			$infos = explode('/',$str);
			foreach($infos as $key => $value){
				if(strlen($value)===1){
					$infos[$key] = "0".$value;
				}
			}
			return $infos[2]."-".$infos[0]."-".$infos[1];
		}

		function caracteres_speciaux($str){
			$str2 = str_replace("'","''",$str);
			return $str2;
		}


		function validite_mol($mol,$i,$corriger, $identifiant_molecule = null){

			global $valid;
			$valid = true;
			global $baseDonnees;
			global $contenuFichier_csv;
			global $array_afficheListe;
			$sql = "SELECT bingo.CheckMolecule('".$mol."');";
			$result = $baseDonnees->query($sql);
			$validite = $result->fetch();
			if($validite[0] != null){
				if($corriger){
					if (!$identifiant_molecule == null){
						echo 'Identifiant de la molecule : ' .$identifiant_molecule. '<br>';
					}
					echo '<br> La structure que vous avez dessinée contient une abréviation ou une flèche de réaction,
					<br>consultez les <a href="images/CNBrochure.pdf" target="_blank"><strong>recommandations pour le dessin des structures.</strong></a></br>';
					echo "<br>" . $validite[0];
					correction_mol($mol,$i);
					$valid = false;
				}

				end($contenuFichier_csv);
				$key = key($contenuFichier_csv);
				$contenuFichier_csv[$key+1][0] = $identifiant_molecule;
				$contenuFichier_csv[$key+1][1] = $validite[0];

				$array_afficheListe[] = $identifiant_molecule ." : ". $validite[0];

			}
			else{
				return $mol;
			}
		}

		function extraire_annee($str){

			preg_match("#([0-9]{4})#",$str,$matches);
			return $matches[1];


		}

		function correction_mol($mol,$i){
			dessin($mol);
				echo'
					<form name="correction" action="importationSDF_envoi.php" method="post" onsubmit="validation()">
						<input type="hidden" name="id_mol" value="'.$i.'"/>
						<input type="hidden" name="mol"/>
						';
						if (isset($_POST["correctionOnLive"]))
							echo '<input type="hidden" name="correctionOnLive" value="'.$_POST["correctionOnLive"].'"/>';

						foreach($_POST as $key => $value){
							if($key != "mol" AND $key != "id_mol"){
								echo'<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
							}
						}
						echo'
						<input type="submit" value="Valider"/>
					</form>
			';

		}

		function dessin($mol) {
			echo "<div code=\"JME.class\" name=\"JME\" archive=\"JME.jar\" width=360 height=350 id=\"JME\">";
			if (!empty($mol)) {
				echo "<param name=\"smiles\" value=\"";
				echo $mol;
				echo "\">";
			}
			echo "<param name=\"options\" value=\"polarnitro\">";
			echo "</div>";
		}

		if (count(glob("files/sdf/*")) <= 1 && count(glob("files/rdf/*")) <= 1){
			echo '<meta http-equiv="refresh" content="0;URL=importationSDF.php">';
			die();
		}

		try{
			$baseDonnees = $dbh;
		}catch(PDOException $e){
			echo'<br>Erreur accès à la base de données !<br>'.$e;
			die();
		}
		$contenuFichier_csv[0][0] = "Identifiant de la molecule";
		$contenuFichier_csv[0][1] = "Erreur";
		$array_afficheListe = [];
		$valid = true; //condition de validité de la molécule, permet la pause du code en cas de besoin de correction
		$correspondance = [];
		$decoupage = [];
		$transformation = [];
		$equivalence = [];
		$valueBar;

		foreach($_POST as $key => $value){ // récupération des infos de traitement
			if(strpos($key, 'cor_')===0){

				$correspondance[str_replace("cor_","",$key)] = $value;

			}
			if(strpos($key, 'dec_')===0){

				$decoupage[str_replace("dec_","",$key)] = $value;

			}

			if(strpos($key, 'trans_')===0){
				$transformation[str_replace("trans_","",$key)] = $value;

			}

			if(strpos($key, 'equ_')===0){
				$equivalence[str_replace("equ_","",$key)] = $value;
			}

		}

		//données récupérés après une correction de molécule
		$id_molecule = -1;
		if(isset($_POST["id_mol"])){
			$id_molecule = $_POST["id_mol"];
			$mol_corrigee = $_POST["mol"];
		}
			//SDF
			if($_POST['extension'] === 'sdf'){

				for($i=1;$i<=$_POST['nbrMol'];$i++){
					if($valid){
					if($i<=$id_molecule){
						$i = $id_molecule;
					}

					$path = 'files/sdf/molecule'.$i;
					if(is_readable($path)){
						if(!(filesize($path) === 0)){
							$file = fopen($path,"r");
							${"donnees".$i} = array("molecule" => "",);
							$categories = array("molecule",);
							$lastCategory = "molecule";
							$addCategory = "";

							if($i === $id_molecule){
								validite_mol($mol_corrigee,$i,false); //méthode de vérification et de potentielle correction de la molécule. ici, false car pas de correctrion
								if($valid){
									${"donnees".$i}["molecule"] = $mol_corrigee;
								}else{
									$id_molecule = -1;
								}
							}

							while(!feof($file)){
								$contenu = fgets($file);
								$category = strpos($contenu,">  <");
								if(!($category===0)){

									if(!(rtrim($contenu) === "") OR $lastCategory == "molecule"){
										if($lastCategory != "molecule" AND $addCategory != $lastCategory){
											$contenu = trim($contenu);
										}
										if(!($lastCategory === "molecule" AND $i === $id_molecule)){
										${"donnees".$i}[$lastCategory] = ${"donnees".$i}[$lastCategory].$contenu;
										$addCategory = $lastCategory;
										}
									}
								}else{
									$length = (strpos($contenu,">",1)-4);
									$newCategory = substr($contenu, 4,$length );
									$newCategory = str_replace(".","_",$newCategory);
									$newCategory = str_replace(" ","_",$newCategory);
									${"donnees".$i}[$newCategory] = "";
									$lastCategory = $newCategory;
									$categories[] = $lastCategory;
								}
							}

							$nbrDonnees = count(${"donnees".$i});
							for($j=0;$j<$nbrDonnees;$j++){
								${"donnees".$i}[$categories[$j]] = rtrim(${"donnees".$i}[$categories[$j]]);
							}

							traitement(${"donnees".$i},$i); //insertion dans la base de donnée, molécule par molécule
							fclose($file);
						}
					}else{echo'Erreur lecture : '.$path;}

				}

				}

			}



				//RDF
			if($_POST['extension'] === 'rdf'){

				for($i=1;$i<=$_POST['nbrMol'];$i++){

					if($valid){
					if($i<=$id_molecule){
						$i = $id_molecule;
					}


					$path = 'files/rdf/molecule'.$i;


					if(is_readable($path)){

						if(!(filesize($path) === 0)){
							$file = fopen($path,"r");
							${"donnees".$i} = array("molecule" => "",);
							$categories = array("molecule",);
							$lastCategory = "molecule";
							$addCategory = "";

							if($i === $id_molecule){
								validite_mol($mol_corrigee,$i,false); //méthode de vérification et de potentielle correction de la molécule. ici, false car pas de correctrion
								if($valid){
									${"donnees".$i}["molecule"] = $mol_corrigee;
								}else{
									$id_molecule = -1;
								}
							}
							while(!feof($file)){
								$contenu = fgets($file);
								$category = strpos($contenu,"\$DTYPE ROOT:");
								if(!($category===0)){
									if(!(rtrim($contenu) === "") OR $lastCategory === "molecule"){
										if($lastCategory != "molecule" AND $addCategory != $lastCategory){
											$contenu = substr_replace($contenu, "", 0, 7);
											$contenu = trim($contenu);
										}
										if(!($lastCategory === "molecule" AND $i === $id_molecule)){
											${"donnees".$i}[$lastCategory] = ${"donnees".$i}[$lastCategory].$contenu;
											$addCategory = $lastCategory;
										}
									}
								}else{
									$newCategory = trim(substr_replace($contenu, "",0, 12));

									$newCategory = str_replace(".","_",$newCategory);
									$newCategory = str_replace(" ","_",$newCategory);
									${"donnees".$i}[$newCategory] = "";
									$categories[] = $newCategory;
									$lastCategory = $newCategory;
								}
							}

						traitement(${"donnees".$i},$i);	//insertion dans la base de donnée, molécule par molécule
						fclose($file);
					}
						else{
							echo'Erreur lecture : '.$path;
						}
					}
				}

				}
			}


			if ($valueBar >= ($_POST['nbrMol']-1)){
				/*
				suppression("files");
				suppression("files/sdf");
				suppression("files/rdf");
				*/
			}


	echo'
				<meta charset="UTF-8">
				<script type="text/javascript" language="javascript" src="jsme/jsme.nocache.js"></script>
				<script>
					function validation(){
						if (document.JME.smiles()=="") {
							alert("Vous devez dessiner une molécules");
						}else {
							document.correction.mol.value=document.JME.molFile();
							var resultat=value=document.JME.molFile().indexOf("STY");
							var resultat1=value=document.JME.molFile().indexOf("$RXN");
							if (resultat==-1 && resultat1==-1) {
								correction.submit();
							}else {
								alert("La structure que vous avez dessinée contient une abréviation ou une flèche de réaction, consultez les recommandations pour le dessin des structures");
							}
						}
					}
				</script>
	';

}
else require 'deconnexion.php';
unset($dbh);


include_once 'presentation/pied.php';
//remet le timeout
set_time_limit(120);
?>
