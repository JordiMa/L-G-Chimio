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

	$correspondance = [];
	$decoupage = [];
	$equivalence = [];

	foreach($_POST as $key => $value){ // récupération des infos de traitement
		if(strpos($key, 'cat_')===0){
			if($value != "plusieurs"){
				$correspondance[str_replace("cat_","",$key)] = $value;
			}else{

				$val = "plus|".$_POST["quant_".$key]."|".$_POST["cut_".$key."1"]."|".$_POST["sep_".$key."1"]."|".$_POST["cut_".$key."2"];

				$correspondance[str_replace("cat_","",$key)."_1"] = $_POST["cut_".$key."1"];
				$correspondance[str_replace("cat_","",$key)."_2"] = $_POST["cut_".$key."2"];

				if(intval($_POST["quant_".$key])>=3){
					$val = $val."|".$_POST["sep_".$key."2"]."|".$_POST["cut_".$key."3"];
					$correspondance[str_replace("cat_","",$key)."_3"] = $_POST["cut_".$key."3"];

					if(intval($_POST["quant_".$key])>=4){
						$val = $val."|".$_POST["sep_".$key."3"]."|".$_POST["cut_".$key."4"];
						$correspondance[str_replace("cat_","",$key)."_4"] = $_POST["cut_".$key."4"];
					}
				}
				$decoupage[str_replace("cat_","",$key)] = $val;
			}
		}

		if(strpos($key, 'equ_')===0){

			$equivalence[str_replace("equ_","",$key)] = $value;
		}


	}

	$data = [];

		//SDF
		if($_POST['extension'] === 'sdf'){

			for($i=1;$i<=$_POST['nbrMol'];$i++){
				$path = 'files/sdf/molecule'.$i;


				if(is_readable($path)){

					if(!(filesize($path) === 0)){
						$file = fopen($path,"r");
						${"donnees".$i} = array("molecule" => "",);
						$categories = array("molecule",);
						$lastCategory = "molecule";

						while(!feof($file)){
							$contenu = fgets($file);
							$category = strpos($contenu,">  <");
							if(!($category===0)){

								if(!(rtrim($contenu) === "") OR $lastCategory == "molecule"){
									if($lastCategory != "molecule"){
										$contenu = trim($contenu);
									}
									${"donnees".$i}[$lastCategory] = ${"donnees".$i}[$lastCategory].$contenu;
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
						$data["molecule".$i] = ${"donnees".$i};
					}
				}else{echo'Erreur lecture : '.$path;}
			}

		}



			//RDF
		if($_POST['extension'] === 'rdf'){

			for($i=1;$i<=$_POST['nbrMol'];$i++){
				$path = 'files/rdf/molecule'.$i;


				if(is_readable($path)){

					if(!(filesize($path) === 0)){
						$file = fopen($path,"r");
						${"donnees".$i} = array("molecule" => "",);
						$categories = array("molecule",);
						$lastCategory = "molecule";
						$addCategory = "";

						while(!feof($file)){
							$contenu = fgets($file);
							$category = strpos($contenu,"\$DTYPE ROOT:");
							if(!($category===0)){
								if(!(rtrim($contenu) === "") OR $lastCategory === "molecule"){
									if($lastCategory != "molecule" AND $addCategory != $lastCategory){
										$contenu = substr_replace($contenu, "", 0, 7);
									}
									if($lastCategory != "molecule"){
										$contenu = trim($contenu);
									}
									${"donnees".$i}[$lastCategory] = ${"donnees".$i}[$lastCategory].$contenu;
									$addCategory = $lastCategory;
								}
							}else{
								$newCategory = substr_replace($contenu, "",0, 12);

								$newCategory = str_replace(".","_",$newCategory);
								$newCategory = str_replace(" ","_",$newCategory);
								${"donnees".$i}[$newCategory] = "";
								$categories[] = trim($newCategory);
								$lastCategory = $newCategory;
							}
						}
					}
				}else{echo'Erreur lecture : '.$path;}

				$data["molecule".$i] = ${"donnees".$i};

			}

		}


		//CHOIX DES MODIFICATIONS POTENTIELLES

		echo'
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset="UTF-8">
				<link rel="stylesheet" href="style1.css">
			</head>
			<body>
				<form name="modifcations" action="importationSDF_envoi.php" method="post" onsubmit="">
					<input type="hidden" name="extension" value="'.$_POST['extension'].'"/>
					<input type="hidden" name="nbrMol" value="'.$_POST["nbrMol"].'"/>
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



		$transformation = array( //table des différentes modifs possibles, "selection" étant le nom du la donnée dans les selects de traitement, "nomModification" est le nom exacte de la fonction qui transformera la donnée, et "modèle" ce qui sera affiché, permettant à l'utilisateur de choisir
		/*
		"selection" => array(
			"nomModification" => "modèle",
			),
		*/

		"cou_couleur" => array(
			"FR_to_Hexa" => "Francais",
		),

		"pro_masse" => array(
			"aucune" => "Unité : mg",
			"G_to_MG" => "Unité : g",
			"microG_to_MG" => "Unité : µg"
		),

		"typ_type" => array(
			"init_to_mot" => "Initiale",
			"aucune" => "Aucune modification",
		),

		"sol_solvant" => array(
			"formatage_majuscules" => "Formatage en majuscules",
		),

		"pro_purification" => array(
			"formatage_purification" => "Formatage en majuscules",
		),

		"pro_etape_mol" => array(
			"formatage_etape_mol" => "Formatage en majuscules",
		),

		"pro_aspect" => array(
			"formatage_aspect" => "Formatage en majuscules",
		),

		"pro_date_entree" => array(
			"date_jour_mois_annee" => "Format jour/mois/année",
			"date_moi_jour_annee" => "Format mois/jour/année",
		),

		"str_nom" => array(
			"caracteres_speciaux" => "Échappement des caractères spéciaux",
		),

		"pla_concentration" => array(
			"formatage_nombre" => "Formatage nombre",
		),

		"pla_volume" => array(
			"aucune" => "Unité : µL",
			"ML_to_MIL" => "Unité : mL",
		),

		"pla_masse" => array(
			"formatage_nombre" => "Formatage nombre",
		),

		"pro_tare_pilulier" => array(
			"formatage_nombre" => "Formatage nombre",
		),

		"pro_origine_substance" => array(
			"formatage_majuscules" => "Formatage en majuscules",
		),

		"pro_date_contrat" => array(
			"extraire_annee" => "Année",
		),

		);


		$nbrMolecules = count($data);
		foreach($correspondance as $key => $value){

			if(array_key_exists($value,$transformation)){
			echo'
					<div>
						<p class="exemples">'.$key.' :
			';
			//echo $key." : ";
			$exemples = 0;
			$molecule = 0;
			while($exemples <3 AND $molecule < $nbrMolecules){

				++$molecule;
				if($molecule > $nbrMolecules){
					break;
				}
				if(array_key_exists($key,$data["molecule".$molecule])){

					echo $data["molecule".$molecule][$key]." | ";
					++$exemples;
				}

			}
				echo'
					</p>
						<select name="trans_'.$value.'" class="selecteurs">
				';
				$modifs = array_keys($transformation[$value]);
				$nbrModifs = count($modifs);

				for($j=0;$j<$nbrModifs;$j++){

					echo'

							<option value="'.$modifs[$j].'">'.$transformation[$value][$modifs[$j]].'</option>

					';
				}

				echo'
						</select>
					</div>

				';
			}

			echo'

			<input type="hidden" name="cor_'.$key.'" value="'.$value.'" />

			';
		}
		$nbrDecoupage = count($decoupage);
		$aDecouper = array_keys($decoupage);
		for($i=0;$i<$nbrDecoupage;$i++){

			echo'
				<input type="hidden" name="dec_'.$aDecouper[$i].'" value="'.$decoupage[$aDecouper[$i]].'" />
			';

		}


		echo'
			<div>
				<input type="submit" class="centre" id="send" value="CONTINUER" />
			</div>



				</form>
		';

}
else require 'deconnexion.php';
unset($dbh);


include_once 'presentation/pied.php';
?>
