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

	if(is_readable('files/'.$_POST['file2Read'])){
		$file = fopen('files/'.$_POST['file2Read'],"r");

		if($_POST['extension'] === 'sdf'){
			$contenu = "";
			$num = 1;
			$finMolecule = false;
			while(!feof($file)){
				$finMolecule = false;
				$molecule = fopen('files/sdf/molecule'.($num++),"w");
				while(!$finMolecule){
					$contenu = fgets($file);
					if(rtrim($contenu) === '$$$$'){
						$finMolecule = true;
					}else{
						/*echo $contenu;*/
						fwrite($molecule,$contenu);
						if(feof($file)){
							$finMolecule = true;
						}
					}
				}
			}
		}

		if($_POST['extension'] === 'rdf'){
			$contenu = "";
			$num = 0;
			$finMolecule = false;
			while(!feof($file)){
				$finMolecule = false;
				if($num === 0){
					$molecule = fopen('files/rdf/molecule1',"w");
				}else{
					$molecule = fopen('files/rdf/molecule'.$num,"w");
				}

				while(!$finMolecule){
					$contenu = fgets($file);
					$debut = explode(" ",$contenu);
					if($debut[0] === '$MFMT'){
						$finMolecule = true;
						$num++;
					}else{
						if(!($num === 0)){
							fwrite($molecule,$contenu);
						}
						if(feof($file)){
							$finMolecule = true;
						}

					}

				}
			}
		}

		echo'
			<form action="importationSDF_traitement.php" method="post">
				<input type="hidden" name="extension" value="'.$_POST['extension'].'"/>
		';

		if($_POST['extension'] === 'sdf'){
			echo'
			<p id="info" class="centre">MOLÉCULES À TRAITER : '.(--$num).'<p/>
			<input type="hidden" name="nbrMol" value="'.$num.'"/>
			';
		}

		if($_POST['extension'] === 'rdf'){
			echo'
			<p id="info" class="centre">MOLÉCULES À TRAITER : '.$num.'<p/>
			<input type="hidden" name="nbrMol" value="'.$num.'"/>
			';
		}
		if (isset($_POST["correctionOnLive"]))
			echo '<input type="hidden" name="correctionOnLive" value="'.$_POST["correctionOnLive"].'"/>';

		echo'
			<input type="submit" id="continuer" class="centre" value="CONTINUER"/>
		</form>
		';
	}else{
		echo'ERREUR';
	}

}
else require 'deconnexion.php';
unset($dbh);


include_once 'presentation/pied.php';
?>
