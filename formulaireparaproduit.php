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
	print"<table width=\"328\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"parametres.php\">".GENE."</a></td>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"parametreproduit.php\">".PROD."</a></td>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"exportparametre.php\">".EXPOR."</a></td>
		  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"parametremaintenance.php\">".MAINT."</a></td>
		  </tr>
		  </table>";
	if (isset($erreur)) print"<p class=\"erreur\">".$erreur."</p>";
	echo "<h3 align=\"center\"><a name=\"nu\" id=\"nu\">".STOCK."</a></h3>";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\"><tr><td>";
	$formulaire=new formulaire ("parametrage","changeparaproduit.php","POST",true);
	$formulaire->affiche_formulaire();
	$sql="SELECT para_stock,para_origin_defaut FROM parametres";
	$result1 =$dbh->query($sql);
	$row1=$result1->fetch(PDO::FETCH_NUM);
	if (isset($_POST['massestock'])) $row1[0]=$_POST['massestock'];
	$formulaire->ajout_text (2, $row1[0], 2, "massestock", MASSESTOCK."<br/>",MG,"");
	echo "<br/><br/>";
	$formulaire->ajout_cache ("1","formu");
	echo "</td><td>";
	//recherche des informations sur le champ pro_origine_substance
	$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_originesubstance'";
	//les résultats sont retournées dans la variable $result
	$resultsub=$dbh->query($sql);
	//Les résultats sont mis sous forme de tableau
	$rowsub=$resultsub->fetch(PDO::FETCH_NUM);
	$traitement=new traitement_requete_sql($rowsub[0]);
	$tabsub=$traitement->imprime();
	$formulaire->ajout_select (1,"origmol",$tabsub,false,$row1[1],SELECTORIGINEMOL,ORIGINEMOL."<br/>",false,"");
	echo "</td></tr></table>";
	echo "<p align=\"right\">";
	$formulaire->ajout_button (SUBMIT,"","submit","");
	echo "</p>";
	//fin du formulaire
	$formulaire->fin();
	echo "<hr>";
	echo "<h3 align=\"center\"><a name=\"nu\" id=\"nu\">".NUMEROTATION."</a></h3>";
	if (!empty($_POST['choixnum'])) {
		$sql="UPDATE parametres SET para_numerotation='".$_POST['choixnum']."'";
		$update=$dbh->exec($sql);
	}
	$sql="SELECT para_numerotation FROM parametres";
	$result2=$dbh->query($sql);
	$row2=$result2->fetch(PDO::FETCH_NUM);
	$formulaire1=new formulaire ("parametrage","parametreproduit.php","POST",true);
	$formulaire1->affiche_formulaire();
	echo "<p><strong>".TYPENUM."</strong></p>";
	$tab2["MANU"]=MANU;
	$tab2["AUTO"]=AUTO;
	$formulaire1->ajout_radio ("choixnum",$tab2,$row2[0],"",true,"onClick=submit()");
	//fin du formulaire
	$formulaire1->fin();
	if ($row2[0]=="AUTO") {
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n<tr>\n<td valign=\"top\">
			<br/><br/>";

		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_numtype'";
		//les résultats sont retournées dans la variable $result3
		$result3=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$row3=$result3->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row3[0]);
		$tab3=$traitement->imprime();

		$tab4["-"]="-";
		$tab4["/"]="/";
		$tab4["("]="(";
		$tab4[")"]=")";

		$formulaire2=new formulaire ("parametrage","parametreproduit.php#nu","POST",true);
		$formulaire2->affiche_formulaire();
		print"<p><strong>".COMPOSITIONPHY."</strong></p>";
		print"<table width=\"400\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";

		for ($i=1;$i<=(count($tab3))*2;$i++) {
			if (!isset($_POST["textfixe$i"])) $_POST["textfixe$i"]='';
			$sql="SELECT num_id_numero,num_type,num_valeur FROM numerotation WHERE num_id_numero='$i' and num_parametre=1";
			$result4=$dbh->query($sql);
			$numresult4=$result4->rowCount();

			if ($numresult4>0) {
				$row4=$result4->fetch(PDO::FETCH_NUM);
				$search= array('{','}');
				$row4[1]=str_replace($search,'',$row4[1]);

				if (!in_array($row4[2],$tab4) and $i%2 and empty($_POST["num$i"])) {
					$_POST["num$i"]=$row4[1];
					if ($row4[1]=="FIXE" and empty($_POST["textfixe$i"])) $_POST["textfixe$i"]=$row4[2];
				}
				elseif (in_array($row4[2],$tab4) and empty($_POST["num$i"])) $_POST["num$i"]=$row4[2];
			}

			print"<tr><td>";
			if (!isset($_POST["num$i"])) $_POST["num$i"]="";
			if ($i%2) $formulaire2->ajout_select (1,"num".$i,$tab3,false,$_POST["num$i"],SELECTNUM,"",false,"onChange=submit()");
			else $formulaire2->ajout_select (1,"num".$i,$tab4,false,$_POST["num$i"],SELECTNUM,"",false,"onChange=submit()");
			print"</td>\n<td>";
			if ($_POST["num$i"]=="FIXE") $formulaire2->ajout_text (6, $_POST["textfixe$i"], 5, "textfixe$i", FIXE." :<br/>","","onBlur=submit()");
			else print "&nbsp;";
			print"</td>\n</tr>";

		}
		print"</table><br/>";

		//copie du tab3 en enlevant les valeurs : Numéro de la boite, Coordonnées dans la boite
		foreach ($tab3 as $key=>$elem) {
			if ($key!="COORDONEE" and $key!="BOITE" ) $tab5[$key]=$elem;
		}

		//conservation des valeurs du formulaire des produits non stockés
		for ($i=1;$i<=(count($tab5))*2;$i++) {
			if (!isset($_POST["numvir$i"])) $_POST["numvir$i"]="";
			if (!isset($_POST["textfixevir$i"])) $_POST["textfixevir$i"]='';
			if ($_POST["numvir$i"]!="") {
				$formulaire2->ajout_cache ($_POST["numvir$i"],"numvir$i");
				if ($_POST["numvir$i"]=="FIXE") $formulaire2->ajout_cache ($_POST["textfixevir$i"],"textfixevir$i");
			}
		}
		//fin du formulaire
		$formulaire2->fin();

		//affichage du numéro de stockage physique
		print"<br/><p><font size=\"4\"><strong>";
		for ($i=1;$i<=(count($tab3))*2;$i++) {
			if ($_POST["num$i"]!="") {
				switch ($_POST["num$i"]) {
					case "FIXE": echo $_POST["textfixe$i"];
					break;
					case "COORDONEE": echo "A02";
					break;
					case "EQUIPE": echo "EEEE";
					break;
					case "TYPE": echo "L";
					break;
					case "BOITE": echo "11";
					break;
					case "NUMERIC": echo "11111111";
					break;
					default: echo $_POST["num$i"];
					break;
				}
			}
		}
		print"</strong></font></p>";
		print"</td>\n<td valign=\"top\" class=\"barre\" ><br/><br/><p><strong>".COMPOSITIONVIR."</strong></p>";
		$formulaire4=new formulaire ("parametrage","parametreproduit.php#nu","POST",true);
		$formulaire4->affiche_formulaire();
		print"<table width=\"400\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";

		for ($i=1;$i<=(count($tab5))*2;$i++) {
			if (!isset($_POST["textfixevir$i"])) $_POST["textfixevir$i"]='';
			$sql="SELECT num_id_numero,num_type,num_valeur FROM numerotation WHERE num_id_numero='$i' and num_parametre=2";
			$result5=$dbh->query($sql);
			$numresult5=$result5->rowCount();

			if ($numresult5>0) {
				$row5=$result5->fetch(PDO::FETCH_NUM);
				$search= array('{','}');
				$row5[1]=str_replace($search,'',$row5[1]);
				if (!in_array($row5[2],$tab4) and $i%2 and empty($_POST["numvir$i"])) {
					$_POST["numvir$i"]=$row5[1];
					if ($row5[1]=="FIXE" and empty($_POST["textfixevir$i"])) $_POST["textfixevir$i"]=$row5[2];
				}
				elseif (in_array($row5[2],$tab4) and empty($_POST["numvir$i"])) $_POST["numvir$i"]=$row5[2];
			}

			print"<tr><td>";
			if ($i%2) $formulaire4->ajout_select (1,"numvir".$i,$tab5,false,$_POST["numvir$i"],SELECTNUM,"",false,"onChange=submit()");
			else $formulaire4->ajout_select (1,"numvir".$i,$tab4,false,$_POST["numvir$i"],SELECTNUM,"",false,"onChange=submit()");
			print"</td>\n<td>";
			if ($_POST["numvir$i"]=="FIXE") $formulaire4->ajout_text (6, $_POST["textfixevir$i"], 5, "textfixevir$i", FIXE." :<br/>","","onBlur=submit()");
			else print "&nbsp;";
			print"</td>\n</tr>\n";

		}
		print"</table><br/>";

		//conservation des valeurs du formulaire des produits stockés
		for ($i=1;$i<=(count($tab3))*2;$i++) {
			if ($_POST["num$i"]!="") {
				$formulaire4->ajout_cache ($_POST["num$i"],"num$i");
				if ($_POST["num$i"]=="FIXE") $formulaire4->ajout_cache ($_POST["textfixe$i"],"textfixe$i");
			}
		}

		//fin du formulaire
		$formulaire4->fin();

		//affichage du numéro de stockage physique
		print"<br/><p><font size=\"4\"><strong>";
		for ($i=1;$i<=(count($tab5))*2;$i++) {
			if ($_POST["numvir$i"]!="") {
				switch ($_POST["numvir$i"]) {
					case "FIXE": echo $_POST["textfixevir$i"];
					break;
					case "EQUIPE": echo "EEEE";
					break;
					case "TYPE": echo "L";
					break;
					case "NUMERIC": echo "11111111";
					break;
					default: echo $_POST["numvir$i"];
					break;
				}
			}
		}
		print"</strong></font></p>";

		print"</td>\n</tr>\n<tr>
		<td colspan=\"2\" align=\"center\"><br/>";
		$formulaire3=new formulaire ("parametrage","changeparaproduit.php","POST",true);
		$formulaire3->affiche_formulaire();
		$formulaire3->ajout_cache ($row2[0],"choixnum");
		$formulaire3->ajout_cache ("2","formu");
		for ($i=1;$i<=(count($tab3))*2;$i++) {
			if ($_POST["num$i"]!="") {
				$formulaire3->ajout_cache ($_POST["num$i"],"num$i");
				if ($_POST["num$i"]=="FIXE") $formulaire3->ajout_cache ($_POST["textfixe$i"],"textfixe$i");
			}
		}
		for ($i=1;$i<=(count($tab5))*2;$i++) {
			if ($_POST["numvir$i"]!="") {
				$formulaire3->ajout_cache ($_POST["numvir$i"],"numvir$i");
				if ($_POST["numvir$i"]=="FIXE") $formulaire3->ajout_cache ($_POST["textfixevir$i"],"textfixevir$i");
			}
		}
		echo "<p align=\"right\">";
		$formulaire3->ajout_button (SUBMIT,"","submit","");
		echo "</p>";
		$formulaire3->fin();
		print"</td>
		</tr>
		</table>";
	}
}
else require 'deconnexion.php';
unset($dbh);
?>
