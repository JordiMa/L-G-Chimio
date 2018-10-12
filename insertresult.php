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
include_once 'langues/'.$_SESSION['langue'].'/lang_bio.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	unset($_SESSION["tabval0"]);
	unset($_SESSION["tabval1"]);
	unset($_SESSION["tabval2"]);
	unset($_SESSION["tabval3"]);
	unset($_SESSION["elem"]);
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"resultatbio.php\">".CONSULTER."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"importbio.php\">".IMPORTER."</a></td>
			</tr>
			</table><br/>";
	$fic = fopen($_FILES['filebio']['tmp_name'], 'r');
  
	$formulaire=new formulaire ("cible","insertcsv1.php","POST",true);
	$formulaire->affiche_formulaire();

	$tab[1]=NUM;
	$tab[2]=IC50;
	$tab[3]=ACT;
	$tab[4]=POURACT;
	$tab[5]=EC50;
	$tab[6]=POURINHI;
	$tab[7]=AUTRE;
	
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	print "<tr>";
	$ligne1 = fgetcsv($fic,0,";");
	$nb=count($ligne1);
	for ($k=0; $k<$nb; $k++) {
		if (!isset($_POST["col$k"])) $_POST["col$k"]=""; 
		print "<td>";
		$formulaire->ajout_select (1,"col$k",$tab,false,$_POST["col$k"],SELEC,TYP."<br/>",false,"");
		print "<br/><br/></td>";
	}
	print"</tr>";
	$y=1;
	while (($data = fgetcsv($fic, 0, ";")) !== FALSE) {
		$y++;
	}
	fseek($fic, 0);
	if ($y>10) $y=10;
	for ($i=0; $i<$y ; $i++) {
		print "<tr>";
		$ligne = fgetcsv($fic,0,";");
		foreach ($ligne as $elem) {
			echo "<td>$elem</td>";
		}
		print "</tr>";
	}
	//entre chaque colonne dans un tableau sauvegardé dans la session
	fseek($fic, 0);
	$j=0;
	while (($data = fgetcsv($fic, 0, ";")) !== FALSE) {
		$i=0;
		foreach ($data as $elem) {
			$_SESSION['tabval'.$i][$j]=$elem;
			$i++;
		}
		$j++;
	}

	echo "</table>\n";
	$formulaire->ajout_cache ($i,"nbcol");
	$formulaire->ajout_cache ($_POST["cible"],"cible");
	$formulaire->ajout_cache ($_POST["labo"],"labo");
	$formulaire->ajout_cache ($_POST["jour"],"jour");
	$formulaire->ajout_cache ($_POST["mois"],"mois");
	$formulaire->ajout_cache ($_POST["annee"],"annee");
	$formulaire->ajout_cache ($_POST["molref"],"molref");
	$formulaire->ajout_cache ($_POST["resulref"],"resulref");
	$formulaire->ajout_cache ($_POST["uniteref"],"uniteref");
	print"<br/>";
	$formulaire->ajout_button (SUBMIT,"","submit","");
	$formulaire->fin();
}
else require 'deconnexion.php';
unset($dbh);
?>