<style>
.table-institut {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.td-institut, .th-institut {
	border: 1px solid #ddd;
  padding: 8px;
}

.tr-institut:nth-child(even) {
	background-color: #f2f2f2;
}

.tr-institut:hover {
	background-color: #ddd;
}

.th-institut {
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
	print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"institutgestion.php\">Institut</a></td>
		</tr>
		</table><br/>";

	echo "<h3 align=\"center\">Gestion des instituts</h3>";
?>

	<table class="table-institut">
	<tr class="tr-institut">
		<th class="th-institut" width="10%">Code institut</th>
		<th class="th-institut" width="35%">Nom de l'institut</th>
		<th class="th-institut" width="15%">Contact</th>
		<th class="th-institut" width="15%">N° Tel</th>
		<th class="th-institut" width="15%">Email</th>
		<th class="th-institut" width="10%"></th>
	</tr>
<?php
	foreach ($dbh->query("SELECT * FROM institut ORDER BY ins_code_institut") as $row) {
		if(isset($_GET['modif']) && $_GET['modif'] == "institut" && $_GET['ID'] == $row[0]){
			echo '<form action="" method="GET">';
			echo '
			<tr class="tr-institut">
				<td class="td-institut"><input type="text" name="code" value="'.urldecode($row[0]).'" required></td>
				<td class="td-institut"><input type="text" name="institut" value="'.urldecode($row[1]).'" required></td>
				<td class="td-institut"><input type="text" name="contact" value="'.urldecode($row[2]).'" required></td>
				<td class="td-institut"><input type="tel" name="tel" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" value="'.urldecode($row[3]).'"></td>
				<td class="td-institut"><input type="email" name="email" value="'.urldecode($row[4]).'"></td>
				<input type="hidden" name="oldIDinstitut" value="'.urldecode($row[0]).'">
				<td class="td-institut"><button type="submit" name="envoi_modif" value="institut" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
			</tr>
			';
			echo '</form>';
		}
		else{
			echo '
				<tr class="tr-institut">
					<td class="td-institut">'.urldecode($row[0]).'</td>
					<td class="td-institut">'.urldecode($row[1]).'</td>
					<td class="td-institut">'.urldecode($row[2]).'</td>
					<td class="td-institut">'.urldecode($row[3]).'</td>
					<td class="td-institut">'.urldecode($row[4]).'</td>
					<td class="td-institut"><a href="?modif=institut&ID='.urldecode($row[0]).'"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"></a></td>
				</tr>
			';
		}
	}
?>

	<form id="myForm" action="" method="GET">
	<?php if (isset($_GET['Ajouter'])): ?>
		<tr class="tr-institut">
			<td class="td-institut"><input type="text" name="code" required></td>
			<td class="td-institut"><input type="text" name="institut" required></td>
			<td class="td-institut"><input type="text" name="contact" required></td>
			<td class="td-institut"><input type="tel" name="tel" pattern="^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" ></td>
			<td class="td-institut"><input type="email" name="email"></td>
			<td class="td-institut"><button type="submit" name="save" value="institut" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a onclick="history.back()"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
		</tr>
	<?php endif; ?>

</table>

<?php if (!isset($_GET['Ajouter']) && !isset($_GET['modif'])): ?>
	<input type="submit" name="Ajouter" value="Ajouter">
<?php endif; ?>

</form>

<?php

if(isset($_GET['save'])){
		$stmt = $dbh->prepare("INSERT INTO institut (ins_code_institut, ins_nom, ins_contact, ins_tel, ins_mail) VALUES (:ins_code_institut, :ins_nom, :ins_contact, :ins_tel, :ins_mail)");
		$stmt->bindParam(':ins_code_institut', $_GET['code']);
		$stmt->bindParam(':ins_nom', $_GET['institut']);
		$stmt->bindParam(':ins_contact', $_GET['contact']);
		$stmt->bindParam(':ins_tel', $_GET['tel']);
		$stmt->bindParam(':ins_mail', $_GET['email']);

		$stmt->execute();

	echo "<script type=\"text/javascript\">location.href = 'institutgestion.php';</script>";
}
elseif (isset($_GET['envoi_modif'])) {
		$stmt = $dbh->prepare("UPDATE institut SET ins_code_institut = :ins_code_institut, ins_nom = :ins_nom, ins_contact = :ins_contact, ins_tel = :ins_tel, ins_mail = :ins_mail WHERE ins_code_institut = :oldIDinstitut");
		$stmt->bindParam(':ins_code_institut', $_GET['code']);
		$stmt->bindParam(':ins_nom', $_GET['institut']);
		$stmt->bindParam(':ins_contact', $_GET['contact']);
		$stmt->bindParam(':ins_tel', $_GET['tel']);
		$stmt->bindParam(':ins_mail', $_GET['email']);
		$stmt->bindParam(':oldIDinstitut', $_GET['oldIDinstitut']);

		$stmt->execute();

	echo "<script type=\"text/javascript\">location.href = 'institutgestion.php';</script>";
}


}
else require 'deconnexion.php';
unset($dbh);
?>
