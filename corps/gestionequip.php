<style>
.table-equipe {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.td-equipe, .th-equipe {
	border: 1px solid #ddd;
  padding: 8px;
}

.tr-equipe:nth-child(even) {
	background-color: #f2f2f2;
}

.tr-equipe:hover {
	background-color: #ddd;
}

.th-equipe {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>

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
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<script language=\"javascript\">
		function suppression(link,nom) {
		oks=confirm('".VCONFIRMSUP." '+nom+' ?');
		if (oks==true) {confirma=link.href += '&ok=1';}
		else {confirma=link.href += '&ok=0';}
		return(confirma);
		}
	</script>";
	print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"institutgestion.php\">Institut</a></td>
		</tr>
		</table><br/>";


			echo "<h3 align=\"center\">Gestion des equipes</h3>";
		?>

			<table class="table-equipe">
			<tr class="tr-equipe">
				<th class="th-equipe" width="10%">Initiate</th>
				<th class="th-equipe" width="70%">Nom de l'equipe</th>
				<th class="th-equipe" width="10%">Institut</th>
				<th class="th-equipe" width="10%"></th>
			</tr>
		<?php
			foreach ($dbh->query("SELECT * FROM equipe ORDER BY equi_initiale_numero") as $row) {
				if(isset($_GET['modif']) && $_GET['modif'] == "equipe" && $_GET['ID'] == $row[0]){
					echo '<form action="" method="GET">';
					echo '
					<tr class="tr-equipe">
						<td class="td-equipe"><input type="text" name="Initiate" maxlength="6" value="'.urldecode($row[2]).'" required></td>
						<td class="td-equipe"><input type="text" name="equipe" value="'.urldecode($row[1]).'" required></td>
						<td class="td-equipe">
							<select id="institut" name="institut">>
							<option value="NULL">Pas d\'institut</option>';
							foreach ($dbh->query("SELECT ins_code_institut FROM institut ORDER BY ins_code_institut") as $row1) {
									echo '<option value="'.urldecode($row1[0]).'"';if ($row[3] == $row1[0]) echo "selected"; echo ' >'.urldecode($row1[0]).'</option>';
							}
							echo '
							</select>
						</td>
						<input type="hidden" name="IDequipe" value="'.urldecode($row[0]).'">
						<td class="td-equipe"><button type="submit" name="envoi_modif" value="equipe" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
					</tr>
					';
					echo '</form>';
				}
				else{
					echo '
						<tr class="tr-equipe">
							<td class="td-equipe">'.urldecode($row[2]).'</td>
							<td class="td-equipe">'.urldecode($row[1]).'</td>
							<td class="td-equipe">'.urldecode($row[3]).'</td>
							<td class="td-equipe"><a href="?modif=equipe&ID='.urldecode($row[0]).'"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"></a></td>
						</tr>
					';
				}
			}
		?>

			<form id="myForm" action="" method="GET">
			<?php if (isset($_GET['Ajouter'])): ?>
				<tr class="tr-equipe">
					<td class="td-equipe"><input type="text" name="Initiate" maxlength="6" required></td>
					<td class="td-equipe"><input type="text" name="equipe" required></td>
					<td class="td-equipe">
						<select id="institut" name="institut">
						<option value="NULL">Pas d'institut</option>';
						<?php
						foreach ($dbh->query("SELECT ins_code_institut FROM institut ORDER BY ins_code_institut") as $row1) {
								echo '<option value="'.urldecode($row1[0]).'">'.urldecode($row1[0]).'</option>';
						}
						?>
						</select>
					</td>
					<td class="td-equipe"><button type="submit" name="save" value="equipe" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
				</tr>
			<?php endif; ?>

		</table>

		<?php if (!isset($_GET['Ajouter']) && !isset($_GET['modif'])): ?>
			<input type="submit" name="Ajouter" value="Ajouter">
		<?php endif; ?>

		</form>

		<?php

		if(isset($_GET['save'])){
				$stmt = $dbh->prepare("INSERT INTO equipe (equi_nom_equipe, equi_initiale_numero, ins_code_institut) VALUES (:equi_nom_equipe, :equi_initiale_numero, :ins_code_institut)");
				$stmt->bindParam(':equi_initiale_numero', $_GET['Initiate']);
				$stmt->bindParam(':equi_nom_equipe', $_GET['equipe']);
				$stmt->bindParam(':ins_code_institut', $_GET['institut']);

				$stmt->execute();

			 	echo "<script type=\"text/javascript\">location.href = 'equipegestion.php';</script>";
		}
		elseif (isset($_GET['envoi_modif'])) {
				$stmt = $dbh->prepare("UPDATE equipe SET equi_nom_equipe = :equi_nom_equipe, equi_initiale_numero = :equi_initiale_numero, ins_code_institut = :ins_code_institut WHERE equi_id_equipe = :IDequipe");
				$stmt->bindParam(':equi_initiale_numero', $_GET['Initiate']);
				$stmt->bindParam(':equi_nom_equipe', $_GET['equipe']);
				if ($_GET['institut'] == "NULL") $_GET['institut'] = NULL;
				$stmt->bindParam(':ins_code_institut', $_GET['institut']);
				$stmt->bindParam(':IDequipe', $_GET['IDequipe']);

				$stmt->execute();

				echo "<script type=\"text/javascript\">location.href = 'equipegestion.php';</script>";
		}


}
else require 'deconnexion.php';
unset($dbh);
?>
