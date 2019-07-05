<script type="text/javascript" src="js/jquery.min.js"></script>
<style>
.table-pays {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.td-pays, .th-pays {
	border: 1px solid #ddd;
  padding: 8px;
}

.tr-pays:nth-child(even) {
	background-color: #f2f2f2;
}

.tr-pays:hover {
	background-color: #ddd;
}

.th-pays {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}

div.container {
  display: flex;
  flex-direction: row;
  justify-content: normal;
  flex-wrap: wrap;
}

div.pays {
  width: 57%;
  display: inline-block;
  margin: 5px;
  vertical-align: top;
  padding: 5px;
  border-color: #3399CC;
  border-top-style: solid;
  border-right-style: dashed;
  border-bottom-style: dashed;
  border-left-style: solid;
  text-align: justify;
  word-break: break-all;
}

div.type_taxonomie {
  width: 40%;
  display: inline-block;
  margin: 5px;
  vertical-align: top;
  padding: 5px;
  border-color: #3399CC;
  border-top-style: solid;
  border-right-style: dashed;
  border-bottom-style: dashed;
  border-left-style: solid;
  text-align: justify;
  word-break: break-all;
}

div.partie_organisme {
  width: 100%;
  display: inline-block;
  margin: 5px;
  vertical-align: top;
  padding: 5px;
  border-color: #3399CC;
  border-top-style: solid;
  border-right-style: dashed;
  border-bottom-style: dashed;
  border-left-style: solid;
  text-align: justify;
  word-break: break-all;
}

.scrollTable {
    max-height: 450px;
    overflow: auto;
    width: 100%;
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
include_once 'langues/'.$_SESSION['langue'].'/lang_parametre.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

	if (isset($erreur)) print"<p class=\"erreur\">".$erreur."</p>";
	echo "<h1 align=\"center\">Extractothèque</h1>";
	echo "<hr/>";
	echo "<div class='container'>";
	echo "<div class='pays'>";
	echo "<h2 align=\"center\">Pays</h2>";

	// [JM - 05/07/2019] Table pays
	?>
		<div class="scrollTable">
		<table class="table-pays">
	  <tr class="tr-pays">
	    <th class="th-pays" width="10%">Code</th>
	    <th class="th-pays" width="35%">Pays</th>
	    <th class="th-pays" width="10%">APA</th>
			<th class="th-pays" width="25%">N° de permis</th>
			<th class="th-pays" width="10%">Collaboration</th>
			<th class="th-pays" width="10%"></th>
	  </tr>
<?php
	// [JM - 05/07/2019] affiche dans le tableau tout les pays et leurs information
    foreach ($dbh->query("SELECT * FROM Pays ORDER BY pay_code_pays") as $row) {
			// [JM - 05/07/2019] Si la ligne est en mode modification, on affiche un formulaire
			if(isset($_GET['modif']) && $_GET['modif'] == "pays" && $_GET['ID'] == $row[0]){
				echo '<form action="" method="GET">';
				echo '
				<tr class="tr-pays">
					<td class="td-pays"><input type="text" minlength="2" maxlength="3" name="code" value="'.urldecode($row[0]).'" required></td>
					<td class="td-pays"><input type="text" name="pays" value="'.urldecode($row[1]).'" required></td>
					<td class="td-pays"><input type="checkbox" name="APA"'; if($row[2]) echo "checked"; ; echo' ></td>
					<td class="td-pays"><input type="text" name="permis" value="'.urldecode($row[3]).'"></td>
					<td class="td-pays"><input type="checkbox" name="collaboration"'; if($row[4]) echo "checked"; echo' ></td>
					<input type="hidden" name="oldIDPays" value="'.urldecode($row[0]).'">
					<td class="td-pays"><button type="submit" name="envoi_modif" value="pays" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
				</tr>
				';
				echo '</form>';
			}
			else{
        echo '
					<tr class="tr-pays">
				    <td class="td-pays">'.urldecode($row[0]).'</td>
				    <td class="td-pays">'.urldecode($row[1]).'</td>
				    <td class="td-pays">';if($row[2]) echo "Oui"; else echo "Non";echo'</td>
						<td class="td-pays">'.urldecode($row[3]).'</td>
						<td class="td-pays">';if($row[4]) echo "Oui"; else echo "Non";echo'</td>
						<td class="td-pays"><a href="?modif=pays&ID='.urldecode($row[0]).'"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"></a></td>
				  </tr>
				';
			}
    }
?>
		<!-- [JM - 05/07/2019] formulaire pour ajouter un nouveau pays -->
		<form id="myForm" action="" method="GET">
		<?php if (isset($_GET['Ajouter'])): ?>
			<tr class="tr-pays">
				<td class="td-pays"><input type="text" minlength="2" maxlength="3" name="code" required></td>
				<td class="td-pays"><input type="text" name="pays" required></td>
				<td class="td-pays"><input type="checkbox" name="APA"></td>
				<td class="td-pays"><input type="text" name="permis"></td>
				<td class="td-pays"><input type="checkbox" name="collaboration"></td>
				<td class="td-pays"><button type="submit" name="save" value="pays" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
			</tr>
		<?php endif; ?>

	</table>
</div>

	<!-- [JM - 05/07/2019]  on affiche le bouton 'Ajouter' seulement si le mode Ajout ou modifi ne sont pas acctivé -->
	<?php if (!isset($_GET['Ajouter']) && !isset($_GET['modif']) || (isset($_GET['modif']) && $_GET['modif'] != "pays")): ?>
		<input type="submit" name="Ajouter" value="Ajouter">
	<?php endif; ?>

</form>

<br/><br/>

<!-- [JM - 05/07/2019] Table type de taxonomie -->
</div>
<div class='type_taxonomie'>
<h2 align="center">Type de taxonomie</h2>

<div class="scrollTable">
<table class="table-pays">
<tr class="tr-pays">
	<th class="th-pays" width="10%">ID</th>
	<th class="th-pays" width="80%">Type</th>
	<th class="th-pays" width="10%"></th>
</tr>
<?php
    foreach ($dbh->query("SELECT * FROM type_taxonomie ORDER BY typ_tax_type") as $row) {
			if(isset($_GET['modif']) && $_GET['modif'] == "type" && $_GET['ID'] == $row[0]){
				echo '<form action="" method="GET">';
				echo '
					<tr class="tr-pays">
						<td class="td-pays">'.urldecode($row[0]).'</td>
						<td class="td-pays"><input type="text" name="type" value="'.urldecode($row[1]).'" required></td>
						<input type="hidden" name="oldIDType" value="'.urldecode($row[0]).'">
						<td class="td-pays"><button type="submit" name="envoi_modif" value="type" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
					</tr>
				';
				echo '</form>';
			}
			else{
        echo '
					<tr class="tr-pays">
						<td class="td-pays">'.urldecode($row[0]).'</td>
						<td class="td-pays">'.urldecode($row[1]).'</td>
						<td class="td-pays"><a href="?modif=type&ID='.urldecode($row[0]).'"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"></a></td>
					</tr>
				';
			}
		}
?>

<form id="myForm2" action="" method="GET">
<?php if (isset($_GET['Ajouter2'])): ?>
	<tr class="tr-pays">
		<td class="td-pays"></td>
		<td class="td-pays"><input type="text" name="type" required></td>
		<td class="td-pays"><button type="submit" name="save" value="type" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
	</tr>
<?php endif; ?>

</table>
</div>

<?php if (!isset($_GET['Ajouter2']) && !isset($_GET['modif']) || (isset($_GET['modif']) && $_GET['modif'] != "type")): ?>
	<input type="submit" name="Ajouter2" value="Ajouter">
<?php endif; ?>
</form>


<!-- [JM - 05/07/2019] table partie d'organisme -->
<br/><br/>
</div>
<div class='partie_organisme'>
<h2 align="center">Partie d'organisme</h2>

<div class="scrollTable">
<table class="table-pays">
<tr class="tr-pays">
	<th class="th-pays" width="10%">ID</th>
	<th class="th-pays" width="20%">Origine</th>
	<th class="th-pays" width="15%">Partie (FR)</th>
	<th class="th-pays" width="15%">Partie (EN)</th>
	<th class="th-pays" width="30%">Observation</th>
	<th class="th-pays" width="10%"></th>
</tr>
<?php
    foreach ($dbh->query("SELECT * FROM partie_organisme ORDER BY par_fr") as $row) {
			if(isset($_GET['modif']) && $_GET['modif'] == "PartieOrga" && $_GET['ID'] == $row[0]){
				echo '<form action="" method="GET">';
				echo '
					<tr class="tr-pays">
						<td class="td-pays">'.urldecode($row[0]).'</td>
						<td class="td-pays"><input type="text" name="origine" value="'.urldecode($row[1]).'"></td>
						<td class="td-pays"><input type="text" name="partie_fr" value="'.urldecode($row[2]).'" required></td>
						<td class="td-pays"><input type="text" name="partie_en" value="'.urldecode($row[3]).'" ></td>
						<td class="td-pays"><input type="text" name="observation" value="'.urldecode($row[4]).'"></td>
						<input type="hidden" name="oldIDPartieOrga" value="'.urldecode($row[0]).'">
						<td class="td-pays"><button type="submit" name="envoi_modif" value="PartieOrga" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
					</tr>
				';
				echo '</form>';
			}
			else{
        echo '
					<tr class="tr-pays">
						<td class="td-pays">'.urldecode($row[0]).'</td>
						<td class="td-pays">'.urldecode($row[1]).'</td>
						<td class="td-pays">'.urldecode($row[2]).'</td>
						<td class="td-pays">'.urldecode($row[3]).'</td>
						<td class="td-pays">'.urldecode($row[4]).'</td>
						<td class="td-pays"><a href="?modif=PartieOrga&ID='.urldecode($row[0]).'"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"></a></td>
					</tr>
				';
			}
		}
?>

<form id="myForm2" action="" method="GET">
<?php if (isset($_GET['Ajouter3'])): ?>
	<tr class="tr-pays">
		<td class="td-pays"></td>
		<td class="td-pays"><input type="text" name="origine"></td>
		<td class="td-pays"><input type="text" name="partie_fr" required></td>
		<td class="td-pays"><input type="text" name="partie_en"></td>
		<td class="td-pays"><input type="text" name="observation"></td>
		<td class="td-pays"><button type="submit" name="save" value="PartieOrga" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
	</tr>
<?php endif; ?>

</table>
</div>

<?php if (!isset($_GET['Ajouter3']) && !isset($_GET['modif']) || (isset($_GET['modif']) && $_GET['modif'] != "PartieOrga")): ?>
	<input type="submit" name="Ajouter3" value="Ajouter">
<?php endif; ?>
</form>
<br/><br/>
</div>
</div>
<?php

	if(isset($_GET['save'])){
		// [JM - 05/07/2019] insertion dans la base de données
		if($_GET['save'] == 'pays'){
			$stmt = $dbh->prepare("INSERT INTO Pays (pay_code_pays, pay_pays, pay_APA, pay_numero_permis, pay_collaboration) VALUES (:pay_code_pays, :pay_pays, :pay_APA, :pay_numero_permis, :pay_collaboration)");
			$stmt->bindParam(':pay_code_pays', $_GET['code']);
			$stmt->bindParam(':pay_pays', $_GET['pays']);

			if (isset($_GET['APA'])) $_GET['APA'] = "TRUE"; else $_GET['APA'] = "FALSE";
			$stmt->bindParam(':pay_APA', $_GET['APA']);

			$stmt->bindParam(':pay_numero_permis', $_GET['permis']);

			if (isset($_GET['collaboration'])) $_GET['collaboration'] = "TRUE"; else $_GET['collaboration'] = "FALSE";
			$stmt->bindParam(':pay_collaboration', $_GET['collaboration']);

			$stmt->execute();

		}
		elseif ($_GET['save'] == 'type') {
			$stmt = $dbh->prepare("INSERT INTO Type_taxonomie (typ_tax_type) VALUES (:typ_tax_type)");
			$stmt->bindParam(':typ_tax_type', $_GET['type']);
			$stmt->execute();
		}
		elseif ($_GET['save'] == 'PartieOrga') {
			$stmt = $dbh->prepare("INSERT INTO partie_organisme (par_origine, par_fr, par_en, par_observation) VALUES (:par_origine, :par_fr, :par_en, :par_observation)");
			$stmt->bindParam(':par_origine', $_GET['origine']);
			$stmt->bindParam(':par_fr', $_GET['partie_fr']);
			$stmt->bindParam(':par_en', $_GET['partie_en']);
			$stmt->bindParam(':par_observation', $_GET['observation']);
			$stmt->execute();
		}
		echo "<script type=\"text/javascript\">location.href = 'param_extra.php';</script>";
	}
	elseif (isset($_GET['envoi_modif'])) {
		// [JM - 05/07/2019] modification des données
		if($_GET['envoi_modif'] == 'pays'){
			$stmt = $dbh->prepare("UPDATE Pays SET pay_code_pays = :pay_code_pays, pay_pays = :pay_pays, pay_APA = :pay_APA, pay_numero_permis = :pay_numero_permis, pay_collaboration = :pay_collaboration WHERE pay_code_pays = :oldIDPays");
			$stmt->bindParam(':pay_code_pays', $_GET['code']);
			$stmt->bindParam(':pay_pays', $_GET['pays']);

			if (isset($_GET['APA'])) $_GET['APA'] = "TRUE"; else $_GET['APA'] = "FALSE";
			$stmt->bindParam(':pay_APA', $_GET['APA']);

			$stmt->bindParam(':pay_numero_permis', $_GET['permis']);

			if (isset($_GET['collaboration'])) $_GET['collaboration'] = "TRUE"; else $_GET['collaboration'] = "FALSE";
			$stmt->bindParam(':pay_collaboration', $_GET['collaboration']);

			$stmt->bindParam(':oldIDPays', $_GET['oldIDPays']);

			$stmt->execute();
		}
		elseif ($_GET['envoi_modif'] == 'type') {
			$stmt = $dbh->prepare("UPDATE Type_taxonomie SET typ_tax_type = :typ_tax_type WHERE typ_tax_id = :oldIDType");
			$stmt->bindParam(':typ_tax_type', $_GET['type']);
			$stmt->bindParam(':oldIDType', $_GET['oldIDType']);
			$stmt->execute();
		}
		elseif ($_GET['envoi_modif'] == 'PartieOrga') {
			$stmt = $dbh->prepare("UPDATE partie_organisme SET par_origine = :par_origine, par_fr = :par_fr, par_en = :par_en, par_observation = :par_observation  WHERE par_id = :oldIDPartieOrga");
			$stmt->bindParam(':par_origine', $_GET['origine']);
			$stmt->bindParam(':par_fr', $_GET['partie_fr']);
			$stmt->bindParam(':par_en', $_GET['partie_en']);
			$stmt->bindParam(':par_observation', $_GET['observation']);
			$stmt->bindParam(':oldIDPartieOrga', $_GET['oldIDPartieOrga']);
			$stmt->execute();
		}
		echo "<script type=\"text/javascript\">location.href = 'param_extra.php';</script>";
	}
}
else require 'deconnexion.php';
unset($dbh);
?>
