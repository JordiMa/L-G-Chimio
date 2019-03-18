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
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"importationSDF.php\">SDF</a></td>
		  </tr>
		  </table>";
	print"<div id=\"dhtmltooltip\"></div>
		<script language=\"javascript\" src=\"ttip.js\"></script>";
	echo "<br/><h3 align=\"center\">Importation de fichier SDF</h3>";

	if (!empty($erreur)) echo "<p align=\"center\" class=\"erreur\">".constant($erreur)."</p>";
	//formulaire d'importatio du fichier

	$categories = [];
	$equivalence = [];

	//SDF
	if($_POST['extension'] === 'sdf'){
		for($i=1;$i<=$_POST['nbrMol'];$i++){
			$path = 'files/sdf/molecule'.$i;

			if(is_readable($path)){
				if(!(filesize($path) === 0)){

					$file = fopen($path,"r");
					$lastCategory = "molecule";

					while(!feof($file)){
						$contenu = fgets($file);
						$contenu = utf8_encode($contenu);
						$category = strpos($contenu,">  <"); // ">  <" est le symbole caractéristique précédent une catégorie dans les fichiers SDF
						if(!($category===0)){
							if(!(rtrim($contenu) === "" OR $lastCategory == "molecule" OR rtrim($contenu) === "(null)")){ //éviter l'insertion de lignes vides (ou contenant seulement des sauts de ligne (\n)), ainsi que la molécule, dont on connaît déjà la nature
								if(count($categories[$lastCategory])<3){ //Dans cette lecture, on ne cherche à récupérer que jusqu'à 3 exemples pour l'utilisateur.
									array_push($categories[$lastCategory],$contenu);
								}
							}
						}
						else{
							$length = (strpos($contenu,">",1)-4); //la longueur de la catégorie
							$newCategory = substr($contenu, 4,$length ); //la catégorie

							if(!(array_key_exists($newCategory,$categories))){ //on cherche ici à référencer toutes les différentes catégories, qui peuvent ne pas toutes appartenir à toutes les molécules
								//echo $newCategory.'<br>';
								${$newCategory} = [];
								$categories[$newCategory] = ${$newCategory};
							}
							$lastCategory = $newCategory;



						}
					}

				}
			}else{echo'Erreur lecture : '.$path;}
		}
		//var_dump($categories);

	}




	//RDF
	if($_POST['extension'] === 'rdf'){

		for($i=1;$i<=$_POST['nbrMol'];$i++){
			$path = 'files/rdf/molecule'.$i;


			if(is_readable($path)){

				if(!(filesize($path) === 0)){
					$file = fopen($path,"r");
					${"donnees".$i} = array("molecule" => "",);
					$skip = false; //cette condition nous permet de gagner du temps de traitement en passant la lecture de "champs suivant un modèle commun", passer 3 exemples.
					$lastCategory = "molecule";
					$buffer = ""; //la passage par un buffer est nécessaire pour qu'une donnée sur plusieurs lignes ne constitue qu'une seul exemple

					while(!feof($file)){
						$contenu = fgets($file);
						$contenu = utf8_encode($contenu);
						$category = strpos($contenu,"\$DTYPE ROOT:"); // "$DTYPE ROOT:" est le symbole caractéristique précédent une catégorie dans les fichiers RDF
						if(!($category===0)){ //si c'est une nouvelle catégorie

							if(!(rtrim($contenu) === "" OR $lastCategory == "molecule" OR $skip)){//éviter l'insertion de lignes vides (ou contenant seulement des sauts de ligne (\n)), ainsi que la molécule, dont on connaît déjà la nature (ainsi que si l'on doit la passer ou non)

								if($buffer === ""){ //si buffer est vide ici, il s'agit de la première ligne de la donnée
									$contenu = substr_replace($contenu, "", 0, 7); //on retire alors le "$DATUM " caractéristique des données
								}
								$buffer = $buffer.$contenu;



							}
						}else{
							if(array_key_exists($lastCategory,$categories)){
								if(count($categories[$lastCategory])<3 AND $buffer !== ""){ //si l'on a déjà moins de 3 exemples et que le buffer n'est pas vide (dans le case où la catégorie précédente était vide)

									array_push($categories[$lastCategory],trim($buffer));

								}
								$buffer = "";
							}

							$newCategory = trim(substr_replace($contenu, "",0, 12)); // on retire le "$DTYPE ROOT:" caractéristique des catégories
							$newCategory = str_replace(".","_",$newCategory); // IMPORTANT ! le "." étant symbole de la concaténation en php, sa présence dans un string fait que le POST semble automatiquement le remplacer par un "_". Pour éviter les inconsistences, on le remple nous même.
							$newCategory = utf8_encode($newCategory);
							$keys = array_keys($categories);
							$nbrKeys = count($keys);
							$filtre = ["#BIOLOGY\(\d+\):[TARGETS\(\d+\):|PROJECT]#"];  // /!\ CHAMPS INFORMATIONS AVEC PLUSIEURS ENTREES : le modèle qui va dicter si oui on non on doit passer cette catégorie. Toutes les catégories suivant ce modèle seront par la suite traiter de manière identique à celle de la première rencontrée.
							$nbrFiltres = count($filtre);
							$existe = false;
							$skip = false;

							for($j=0;$j<$nbrFiltres;$j++){
								if(preg_match($filtre[$j],$newCategory)){

									$test = false;
									for($k=0;$k<$nbrKeys;$k++){
										if(preg_match($filtre[$j],$keys[$k])){
											$model = $keys[$k];
											$test = true;
											break;
										}
									}
									if($test){
										if(count($categories[$model])>4 OR $newCategory !== $model){
											$skip = true;
											$equivalence[$newCategory] = $model; //la table d'équivalence permet ce traitement commun à différents champs
										}
										break;
									}
								}
							}

							if(in_array($newCategory, $keys)){
								$existe = true;
							}

							if(!($existe OR $skip)){

								${$newCategory} = [];
								$categories[$newCategory] = ${$newCategory};

							}
							$lastCategory = $newCategory;
						}
					}
					if(count($categories[$lastCategory])<3 AND $buffer !== ""){

						array_push($categories[$lastCategory],$buffer);
					}
				}
			}else{echo'Erreur lecture : '.$path;}
		}
	}

	//(la syntaxe suivante survient plusieurs fois et vient de ma découverte tardive de foreach(), code optimisable donc
	$nbrCategories = count($categories);
	$keys = array_keys($categories);

	//VERIF_SELECTION : on s'assure que la sélection est valide, c'est-à-dire : un champ correspond à l'identifiant de la molécule, aucun champ sélectionné plusieurs fois, excepté "Observation". La validation se fait via un script javascript vérifiant le formulaire
	echo'
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style1.css">
	<script>
	function validation(){

		var selectionnees = [];
		';

		for($i=0;$i<$nbrCategories;$i++){

			echo'

			if(document.getElementById("cat_'.$keys[$i].'").value == "plusieurs" ){

				if(document.getElementById("quant_cat_'.$keys[$i].'").value === ""){
					alert("Entrer un nombre valide de catégories !");
					document.getElementById(\'loader\').style.visibility = \'hidden\';
					document.getElementById(\'table_principal\').style.filter = \'none\';"

					return false;
				}

				if(document.getElementById("quant_cat_'.$keys[$i].'").value === "2"){

					if(document.forms["selection"]["sep_cat_'.$keys[$i].'1"].value===""){
						alert("Entrer un séparateur de catégorie valide !");
						document.getElementById(\'loader\').style.visibility = \'hidden\';
						document.getElementById(\'table_principal\').style.filter = \'none\';"
						return false;
					}

					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'1"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'2"].value);
				}

				if(document.getElementById("quant_cat_'.$keys[$i].'").value === "3"){

					if(document.forms["selection"]["sep_cat_'.$keys[$i].'1"].value==="" || document.forms["selection"]["sep_cat_'.$keys[$i].'2"].value===""){
						alert("Entrer un séparateur de catégorie valide !");
						document.getElementById(\'loader\').style.visibility = \'hidden\';
						document.getElementById(\'table_principal\').style.filter = \'none\';"
						return false;
					}

					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'1"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'2"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'3"].value);
				}

				if(document.getElementById("quant_cat_'.$keys[$i].'").value === "4"){

					if(document.forms["selection"]["sep_cat_'.$keys[$i].'1"].value==="" || document.forms["selection"]["sep_cat_'.$keys[$i].'2"].value==="" || document.forms["selection"]["sep_cat_'.$keys[$i].'3"].value===""){
						alert("Entrer un séparateur de catégorie valide !");
						document.getElementById(\'loader\').style.visibility = \'hidden\';
						document.getElementById(\'table_principal\').style.filter = \'none\';"
						return false;
					}

					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'1"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'2"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'3"].value);
					selectionnees.push(document.forms["selection"]["cut_cat_'.$keys[$i].'4"].value);
				}

			}else{
				selectionnees.push(document.forms["selection"]["cat_'.$keys[$i].'"].value);

			}
			';

		}

		echo'
		var numero = false;
		for( var i=0 ; i<selectionnees.length-1 ; i++ ){
			for( var j=i+1 ; j<selectionnees.length ; j++ ){
				if( selectionnees[i] === selectionnees[j] && selectionnees[i] != "plusieurs" && selectionnees[i] != "null" && selectionnees[i] != "pro_observation"){

					alert(selectionnees[i]+" sélectionné plusieurs fois !");	//MESSAGE CONFLIT DE SELECTION
					document.getElementById(\'loader\').style.visibility = \'hidden\';
					document.getElementById(\'table_principal\').style.filter = \'none\';"

					return false;
				}
				if(selectionnees[i]==="pro_numero" || selectionnees[j] === "pro_numero") numero = true;
			}
		}
		if(!numero){
			alert("Il doit y avoir un identifiant/numéro de molécule ! Traitement impossible !");
			document.getElementById(\'loader\').style.visibility = \'hidden\';
			document.getElementById(\'table_principal\').style.filter = \'none\';"
			return false;
		}
	}

	function showQuant(nom){

		var choix = document.getElementById(nom).value;
		var element = document.getElementById("quant_"+nom);
		if(choix == "plusieurs"){
			element.style.visibility = "visible";
		}else{
			element.style.visibility = "hidden";
			element.value = "";

			document.getElementById("cut_"+nom+"1").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"2").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"1").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"3").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"2").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"4").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"3").style.visibility = "hidden";

		}
	}

	function showOptions(nom){
		var choix = document.getElementById(nom).value;

		var nom = nom.substring(nom.indexOf("_")+1);

		if(choix != ""){
			switch(choix){

				case "2":

				document.getElementById("cut_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"2").style.visibility = "visible";
				document.getElementById("sep_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"3").style.visibility = "hidden";
				document.getElementById("cut_"+nom+"4").style.visibility = "hidden";
				document.getElementById("sep_"+nom+"2").style.visibility = "hidden";
				document.getElementById("sep_"+nom+"3").style.visibility = "hidden";

				break;

				case "3":

				document.getElementById("cut_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"2").style.visibility = "visible";
				document.getElementById("sep_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"3").style.visibility = "visible";
				document.getElementById("sep_"+nom+"2").style.visibility = "visible";
				document.getElementById("cut_"+nom+"4").style.visibility = "hidden";
				document.getElementById("sep_"+nom+"3").style.visibility = "hidden";


				break;

				case "4":

				document.getElementById("cut_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"2").style.visibility = "visible";
				document.getElementById("sep_"+nom+"1").style.visibility = "visible";
				document.getElementById("cut_"+nom+"3").style.visibility = "visible";
				document.getElementById("sep_"+nom+"2").style.visibility = "visible";
				document.getElementById("cut_"+nom+"4").style.visibility = "visible";
				document.getElementById("sep_"+nom+"3").style.visibility = "visible";

				break;

				default:
				alert("erreur");
				document.getElementById(\'loader\').style.visibility = \'hidden\';
				document.getElementById(\'table_principal\').style.filter = \'none\';"

			}

		}else{
			document.getElementById("cut_"+nom+"1").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"2").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"1").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"3").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"2").style.visibility = "hidden";
			document.getElementById("cut_"+nom+"4").style.visibility = "hidden";
			document.getElementById("sep_"+nom+"3").style.visibility = "hidden";
		}
	}



	</script>
	</head>
	<body id="body2">
	<form name="selection" action="importationSDF_choixModifs.php" method="post" onsubmit="return validation()">

	<input type="hidden" name="nbrMol" value="'.$_POST["nbrMol"].'"/>
	<input type="hidden" name="extension" value="'.$_POST["extension"].'"/>
	';
	if (isset($_POST["correctionOnLive"]))
		echo '<input type="hidden" name="correctionOnLive" value="'.$_POST["correctionOnLive"].'"/>';

	$nbrEquivalence = count($equivalence);
	$typeEquivalence = array_keys($equivalence);
	for($i=0;$i<$nbrEquivalence;$i++){
		echo'
		<input type="hidden" name="equ_'.$typeEquivalence[$i].'" value="'.$equivalence[$typeEquivalence[$i]].'"/>
		';
	}
	echo'
	<input type="hidden" name="nbrEquivalence" value="'.$nbrEquivalence.'"/>
	';

	//SELECTION
	for($i = 0; $i<$nbrCategories; $i++){
		echo'
		<div>
		<p class="exemples">'.$keys[$i].' : ';
		for($j=0;$j<count($categories[$keys[$i]]);$j++){
			echo $categories[$keys[$i]][$j].' | ';
		}

		echo'<p/>';

		$nom = "cat_".$keys[$i];
		//echo "<br>".$keys[$i]." : ";

		echo'
		<div>
		<select name="'.$nom.'" id="'.$nom.'" onchange="showQuant(this.id)" class="selecteurs">

		<!--	ICI LES DIFFERENTES OPTIONS POSSIBLES POUR LA SELECTION FAITE PAR L UTILISATEUR		-->

		<option value="null">NONE</option>

		<optgroup label="Chimiste :">
		<option value="chi_nom">Nom du chimiste</option>
		<option value="chi_prenom">Prénom du chimiste</option>
		</optgroup>

		<optgroup label="Responsable :">
		<option value="chi_responsable">Nom du responsable</option>
		</optgroup>

		<optgroup label="Couleur :">
		<option value="cou_couleur">Couleur</option>
		</optgroup>

		<optgroup label="Type :">
		<option value="typ_type">LIBRE / CONTRAT / BREVET</option>
		</optgroup>

		<optgroup label="Structure :">
		<option value="str_nom">Nom de la molécule</option>
		<option value="str_date">Date de production</option>
		</optgroup>

		<optgroup label="Solvant :">
		<option value="sol_solvant">Solvant</option>
		</optgroup>

		<optgroup label="Plaque :">
		<option value="pla_identifiant_local">Identifiant local</option>
		<option value="pla_pos_coordonnees">Position</option>
		<option value="pla_lot_num_lot">Numéro de LOT</option>
		<option value="pla_concentration">Concentration</option>
		<option value="pla_nb_decongelation">Nombre de décongélations</option>
		<option value="pla_date">Date</option>
		<option value="pla_volume">Volume plaque</option>
		<option value="pla_masse">Masse plaque</option>
		<option value="pla_identifiant_externe">Identifiant externe</option>
		</optgroup>

		<optgroup label="Produit :">
		<option value="pro_purete">Pureté</option>
		<option value="pro_purification">Purification</option>
		<option value="pro_pourcentage_actif">Pourcentage actif</option>
		<option value="pro_sel">Sel</option>
		<option value="pro_masse">Masse</option>
		<option value="pro_aspect">Aspect</option>
		<option value="pro_date_entree">Date d\'entrée</option>
		<option value="pro_ref_cahier_labo">Référence de cahier de labo</option>
		<option value="pro_modop">Modop</option>
		<option value="pro_statut">Statut</option>
		<option value="pro_num_brevet">Numéro de brevet</option>
		<option value="pro_ref_contrat">Référence du contrat</option>
		<option value="pro_date_contrat">Date du contrat</option>
		<option value="pro_etape_mol">Étape de molécule</option>
		<option value="pro_configuration">Configuration</option>
		<option value="pro_numero">Numéro de la molécule / Identifiant</option>
		<option value="pro_analyse_elem_trouve">Analyse élémentaire trouvée</option>
		<option value="pro_point_fusion">Point de fusion</option>
		<option value="pro_point_ebullition">Point d\'ébulition</option>
		<option value="pro_pression_pb">Pression PB</option>
		<option value="pro_rf">RF</option>
		<option value="pro_rf_solvant">RF SOLVANT</option>
		<option value="pro_doi">DOI</option>
		<option value="pro_hal">HAL</option>
		<option value="pro_cas">CAS</option>
		<option value="pro_suivi_modification">Suivi modification</option>
		<option value="pro_methode_purete">Méthode de mesure de pureté</option>
		<option value="pro_num_cn">Num CN</option>
		<option value="pro_tare_pilulier">Tare Pilulier</option>
		<option value="pro_origine_substance">Origine substance</option>
		<option value="pro_qrcode">QR CODE</option>
		</optgroup>
		<option value="pro_observation">OBSERVATION</option>

		<optgroup label="Champ annexe :">
			<option value="pro_champsannexe|Text">Text</option>
			<option value="pro_champsannexe|Nombre">Nombre</option>
			<option value="pro_champsannexe|Date">Date</option>
		</optgroup>

		<option value="plusieurs")">PLUSIEURS</option>

		</select>

		<select name="quant_'.$nom.'" id="quant_'.$nom.'" style="visibility:hidden" onchange="showOptions(this.id)" class="selecteurs">
		<optgroup label="Nombre d\'informations :">
		<option value=""></option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		</optgroup>
		</select>

		<select name="cut_'.$nom.'1" id="cut_'.$nom.'1" style="visibility:hidden" class="selecteurs">

		<option value="null">NONE</option>

		<optgroup label="Chimiste :">
		<option value="chi_nom">Nom du chimiste</option>
		<option value="chi_prenom">Prénom du chimiste</option>
		</optgroup>

		<optgroup label="Responsable :">
		<option value="chi_responsable">Nom du responsable</option>
		</optgroup>

		<optgroup label="Couleur :">
		<option value="cou_couleur">Couleur</option>
		</optgroup>

		<optgroup label="Type :">
		<option value="typ_type">LIBRE / CONTRAT / BREVET</option>
		</optgroup>

		<optgroup label="Structure :">
		<option value="str_nom">Nom de la molécule</option>
		<option value="str_date">Date de production</option>
		</optgroup>

		<optgroup label="Solvant :">
		<option value="sol_solvant">Solvant</option>
		</optgroup>

		<optgroup label="Plaque :">
		<option value="pla_identifiant_local">Identifiant local</option>
		<option value="pla_pos_coordonnees">Position</option>
		<option value="pla_lot_num_lot">Numéro de LOT</option>
		<option value="pla_concentration">Concentration</option>
		<option value="pla_nb_decongelation">Nombre de décongélations</option>
		<option value="pla_date">Date</option>
		<option value="pla_volume">Volume</option>
		<option value="pla_masse">Masse</option>
		<option value="pla_identifiant_externe">Identifiant externe</option>
		</optgroup>

		<optgroup label="Produit :">
		<option value="pro_purete">Pureté</option>
		<option value="pro_purification">Purification</option>
		<option value="pro_pourcentage_actif">Pourcentage actif</option>
		<option value="pro_sel">Sel</option>
		<option value="pro_masse">Masse</option>
		<option value="pro_aspect">Aspect</option>
		<option value="pro_date_entree">Date d\'entrée</option>
		<option value="pro_ref_cahier_labo">Référence de cahier de labo</option>
		<option value="pro_modop">Modop</option>
		<option value="pro_statut">Statut</option>
		<option value="pro_num_brevet">Numéro de brevet</option>
		<option value="pro_ref_contrat">Référence du contrat</option>
		<option value="pro_date_contrat">Date du contrat</option>
		<option value="pro_etape_mol">Étape de molécule</option>
		<option value="pro_configuration">Configuration</option>
		<option value="pro_numero">Numéro de la molécule / Identifiant</option>
		<option value="pro_analyse_elem_trouve">Analyse élémentaire trouvée</option>
		<option value="pro_point_fusion">Point de fusion</option>
		<option value="pro_point_ebullition">Point d\'ébulition</option>
		<option value="pro_pression_pb">Pression PB</option>
		<option value="pro_rf">RF</option>
		<option value="pro_rf_solvant">RF SOLVANT</option>
		<option value="pro_doi">DOI</option>
		<option value="pro_hal">HAL</option>
		<option value="pro_cas">CAS</option>
		<option value="pro_suivi_modification">Suivi modification</option>
		<option value="pro_methode_purete">Méthode de mesure de pureté</option>
		<option value="pro_num_cn">Num CN</option>
		<option value="pro_tare_pilulier">Tare Pilulier</option>
		<option value="pro_origine_substance">Origine substance</option>
		<option value="pro_qrcode">QR CODE</option>
		</optgroup>

		</select>

		<input type="text" size="3" maxlength="3" name="sep_'.$nom.'1" id="sep_'.$nom.'1" style="visibility:hidden" class="selecteurs" />

		<select name="cut_'.$nom.'2" id="cut_'.$nom.'2" style="visibility:hidden" class="selecteurs">

		<option value="null">NONE</option>

		<optgroup label="Chimiste :">
		<option value="chi_nom">Nom du chimiste</option>
		<option value="chi_prenom">Prénom du chimiste</option>
		</optgroup>

		<optgroup label="Responsable :">
		<option value="chi_responsable">Nom du responsable</option>
		</optgroup>

		<optgroup label="Couleur :">
		<option value="cou_couleur">Couleur</option>
		</optgroup>

		<optgroup label="Type :">
		<option value="typ_type">LIBRE / CONTRAT / BREVET</option>
		</optgroup>

		<optgroup label="Structure :">
		<option value="str_nom">Nom de la molécule</option>
		<option value="str_date">Date de production</option>
		</optgroup>

		<optgroup label="Solvant :">
		<option value="sol_solvant">Solvant</option>
		</optgroup>

		<optgroup label="Plaque :">
		<option value="pla_identifiant_local">Identifiant local</option>
		<option value="pla_pos_coordonnees">Position</option>
		<option value="pla_lot_num_lot">Numéro de LOT</option>
		<option value="pla_concentration">Concentration</option>
		<option value="pla_nb_decongelation">Nombre de décongélations</option>
		<option value="pla_date">Date</option>
		<option value="pla_volume">Volume</option>
		<option value="pla_masse">Masse</option>
		<option value="pla_identifiant_externe">Identifiant externe</option>
		</optgroup>

		<optgroup label="Produit :">
		<option value="pro_purete">Pureté</option>
		<option value="pro_purification">Purification</option>
		<option value="pro_pourcentage_actif">Pourcentage actif</option>
		<option value="pro_sel">Sel</option>
		<option value="pro_masse">Masse</option>
		<option value="pro_aspect">Aspect</option>
		<option value="pro_date_entree">Date d\'entrée</option>
		<option value="pro_ref_cahier_labo">Référence de cahier de labo</option>
		<option value="pro_modop">Modop</option>
		<option value="pro_statut">Statut</option>
		<option value="pro_num_brevet">Numéro de brevet</option>
		<option value="pro_ref_contrat">Référence du contrat</option>
		<option value="pro_date_contrat">Date du contrat</option>
		<option value="pro_etape_mol">Étape de molécule</option>
		<option value="pro_configuration">Configuration</option>
		<option value="pro_numero">Numéro de la molécule / Identifiant</option>
		<option value="pro_analyse_elem_trouve">Analyse élémentaire trouvée</option>
		<option value="pro_point_fusion">Point de fusion</option>
		<option value="pro_point_ebullition">Point d\'ébulition</option>
		<option value="pro_pression_pb">Pression PB</option>
		<option value="pro_rf">RF</option>
		<option value="pro_rf_solvant">RF SOLVANT</option>
		<option value="pro_doi">DOI</option>
		<option value="pro_hal">HAL</option>
		<option value="pro_cas">CAS</option>
		<option value="pro_suivi_modification">Suivi modification</option>
		<option value="pro_methode_purete">Méthode de mesure de pureté</option>
		<option value="pro_num_cn">Num CN</option>
		<option value="pro_tare_pilulier">Tare Pilulier</option>
		<option value="pro_origine_substance">Origine substance</option>
		<option value="pro_qrcode">QR CODE</option>
		</optgroup>

		</select>

		<input type="text" size="3" maxlength="3" name="sep_'.$nom.'2" id="sep_'.$nom.'2" style="visibility:hidden" class="selecteurs" />

		<select name="cut_'.$nom.'3" id="cut_'.$nom.'3" style="visibility:hidden" class="selecteurs">

		<option value="null">NONE</option>

		<optgroup label="Chimiste :">
		<option value="chi_nom">Nom du chimiste</option>
		<option value="chi_prenom">Prénom du chimiste</option>
		</optgroup>

		<optgroup label="Responsable :">
		<option value="chi_responsable">Nom du responsable</option>
		</optgroup>

		<optgroup label="Couleur :">
		<option value="cou_couleur">Couleur</option>
		</optgroup>

		<optgroup label="Type :">
		<option value="typ_type">LIBRE / CONTRAT / BREVET</option>
		</optgroup>

		<optgroup label="Structure :">
		<option value="str_nom">Nom de la molécule</option>
		<option value="str_date">Date de production</option>
		</optgroup>

		<optgroup label="Solvant :">
		<option value="sol_solvant">Solvant</option>
		</optgroup>

		<optgroup label="Plaque :">
		<option value="pla_identifiant_local">Identifiant local</option>
		<option value="pla_pos_coordonnees">Position</option>
		<option value="pla_lot_num_lot">Numéro de LOT</option>
		<option value="pla_concentration">Concentration</option>
		<option value="pla_nb_decongelation">Nombre de décongélations</option>
		<option value="pla_date">Date</option>
		<option value="pla_volume">Volume</option>
		<option value="pla_masse">Masse</option>
		<option value="pla_identifiant_externe">Identifiant externe</option>
		</optgroup>

		<optgroup label="Produit :">
		<option value="pro_purete">Pureté</option>
		<option value="pro_purification">Purification</option>
		<option value="pro_pourcentage_actif">Pourcentage actif</option>
		<option value="pro_sel">Sel</option>
		<option value="pro_masse">Masse</option>
		<option value="pro_aspect">Aspect</option>
		<option value="pro_date_entree">Date d\'entrée</option>
		<option value="pro_ref_cahier_labo">Référence de cahier de labo</option>
		<option value="pro_modop">Modop</option>
		<option value="pro_statut">Statut</option>
		<option value="pro_num_brevet">Numéro de brevet</option>
		<option value="pro_ref_contrat">Référence du contrat</option>
		<option value="pro_date_contrat">Date du contrat</option>
		<option value="pro_etape_mol">Étape de molécule</option>
		<option value="pro_configuration">Configuration</option>
		<option value="pro_numero">Numéro de la molécule / Identifiant</option>
		<option value="pro_analyse_elem_trouve">Analyse élémentaire trouvée</option>
		<option value="pro_point_fusion">Point de fusion</option>
		<option value="pro_point_ebullition">Point d\'ébulition</option>
		<option value="pro_pression_pb">Pression PB</option>
		<option value="pro_rf">RF</option>
		<option value="pro_rf_solvant">RF SOLVANT</option>
		<option value="pro_doi">DOI</option>
		<option value="pro_hal">HAL</option>
		<option value="pro_cas">CAS</option>
		<option value="pro_suivi_modification">Suivi modification</option>
		<option value="pro_methode_purete">Méthode de mesure de pureté</option>
		<option value="pro_num_cn">Num CN</option>
		<option value="pro_tare_pilulier">Tare Pilulier</option>
		<option value="pro_origine_substance">Origine substance</option>
		<option value="pro_qrcode">QR CODE</option>
		</optgroup>

		</select>

		<input type="text" size="3" maxlength="3" name="sep_'.$nom.'3" id="sep_'.$nom.'3" style="visibility:hidden" class="selecteurs" />

		<select name="cut_'.$nom.'4" id="cut_'.$nom.'4" style="visibility:hidden" class="selecteurs">

		<option value="null">NONE</option>

		<optgroup label="Chimiste :">
		<option value="chi_nom">Nom du chimiste</option>
		<option value="chi_prenom">Prénom du chimiste</option>
		</optgroup>

		<optgroup label="Responsable :">
		<option value="chi_responsable">Nom du responsable</option>
		</optgroup>

		<optgroup label="Couleur :">
		<option value="cou_couleur">Couleur</option>
		</optgroup>

		<optgroup label="Type :">
		<option value="typ_type">LIBRE / CONTRAT / BREVET</option>
		</optgroup>

		<optgroup label="Structure :">
		<option value="str_nom">Nom de la molécule</option>
		<option value="str_date">Date de production</option>
		</optgroup>

		<optgroup label="Solvant :">
		<option value="sol_solvant">Solvant</option>
		</optgroup>

		<optgroup label="Plaque :">
		<option value="pla_identifiant_local">Identifiant local</option>
		<option value="pla_pos_coordonnees">Position</option>
		<option value="pla_lot_num_lot">Numéro de LOT</option>
		<option value="pla_concentration">Concentration</option>
		<option value="pla_nb_decongelation">Nombre de décongélations</option>
		<option value="pla_date">Date</option>
		<option value="pla_volume">Volume</option>
		<option value="pla_masse">Masse</option>
		<option value="pla_identifiant_externe">Identifiant externe</option>
		</optgroup>

		<optgroup label="Produit :">
		<option value="pro_purete">Pureté</option>
		<option value="pro_purification">Purification</option>
		<option value="pro_pourcentage_actif">Pourcentage actif</option>
		<option value="pro_sel">Sel</option>
		<option value="pro_masse">Masse</option>
		<option value="pro_aspect">Aspect</option>
		<option value="pro_date_entree">Date d\'entrée</option>
		<option value="pro_ref_cahier_labo">Référence de cahier de labo</option>
		<option value="pro_modop">Modop</option>
		<option value="pro_statut">Statut</option>
		<option value="pro_num_brevet">Numéro de brevet</option>
		<option value="pro_ref_contrat">Référence du contrat</option>
		<option value="pro_date_contrat">Date du contrat</option>
		<option value="pro_etape_mol">Étape de molécule</option>
		<option value="pro_configuration">Configuration</option>
		<option value="pro_numero">Numéro de la molécule / Identifiant</option>
		<option value="pro_analyse_elem_trouve">Analyse élémentaire trouvée</option>
		<option value="pro_point_fusion">Point de fusion</option>
		<option value="pro_point_ebullition">Point d\'ébulition</option>
		<option value="pro_pression_pb">Pression PB</option>
		<option value="pro_rf">RF</option>
		<option value="pro_rf_solvant">RF SOLVANT</option>
		<option value="pro_doi">DOI</option>
		<option value="pro_hal">HAL</option>
		<option value="pro_cas">CAS</option>
		<option value="pro_suivi_modification">Suivi modification</option>
		<option value="pro_methode_purete">Méthode de mesure de pureté</option>
		<option value="pro_num_cn">Num CN</option>
		<option value="pro_tare_pilulier">Tare Pilulier</option>
		<option value="pro_origine_substance">Origine substance</option>
		<option value="pro_qrcode">QR CODE</option>
		</optgroup>
		</select>
		';

		echo'</div><br><br>';

	}

	echo'
	</div>
	<div>
	<input onClick="document.getElementById(\'loader\').style.visibility = \'visible\';document.getElementById(\'table_principal\').style.filter = \'blur(5px)\';" type="submit" id="send" value="Envoyer"/>
	</div>
	</form>';

}
else require 'deconnexion.php';
unset($dbh);


include_once 'presentation/pied.php';
?>
