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

  $extensions_voulues = ["sdf","rdf"];
  $compat = false;

  if (isset($_FILES['file_name']) AND $_FILES['file_name']['error'] == 0) {

  	$infos_file = pathinfo($_FILES['file_name']['name']);
  	$extension_file = $infos_file['extension'];

  	for($i=0;$i<count($extensions_voulues);$i++){
  		if($extension_file===$extensions_voulues[$i]){
  			$compat = true;
  		}
  	}

  	if($compat){
  		move_uploaded_file($_FILES['file_name']['tmp_name'], 'files/'.basename($_FILES['file_name']['name']));

  		$fichier = $_FILES['file_name']['name'];

  			echo'
  				<body>
  					<form action="importationSDF_decoupage.php" method="post">

  						<p id="extension" class="centre">Extension du fichier : '.$extension_file.'<p/>


  						<input type="hidden" name="file2Read" value="'.$fichier.'" />
  						<input type="hidden" name="extension" value="'.$extension_file.'"/>
							';

							if (isset($_POST["correctionOnLive"]))
								echo '<input type="hidden" name="correctionOnLive" value="'.$_POST["correctionOnLive"].'"/>';

							echo'
  						<div>
  							<input onClick="document.getElementById(\'loader\').style.visibility = \'visible\';document.getElementById(\'table_principal\').style.filter = \'blur(5px)\';" type="submit" class="centre" id="lire" value="Lire"/>
  						</div>
  					</form>
  			';

  	}else{
  		echo'<input type="text" id="erreur" class="centre" value="Format du fichier invalide" disabled />';
  	}
  }else{
  	echo'<input type="text" id="erreur" class="centre" value="ERREUR" disabled />';
  }
?>



<?php
}
else require 'deconnexion.php';
unset($dbh);


include_once 'presentation/pied.php';
?>
