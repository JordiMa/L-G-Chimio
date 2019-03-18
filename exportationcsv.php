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
//include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_export.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"exportation.php\">".SDF."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"exportationcsvpesee.php\">".CSV."</a></td>
		</tr>
		</table><br/>";

	//initialisation du formulaire1 pesée à partir d'une boite
	$formulaire1=new formulaire ("plaqueboite","exportcsvpesee.php","POST",true);
	print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td>";
		//Selection d'une boite à mettre en plaque
		$sql="SELECT num_type FROM numerotation WHERE num_parametre=1 and num_type<>'{FIXE}' and num_type<>'{COORDONEE}' and num_type<>'{NUMERIC}' ORDER BY num_id_numero";
		$result4=$dbh->query($sql);
		$resultrow4="";
		$nubresultat4=$result4->rowCount();
		$y=0;
		while($row4=$result4->fetch(PDO::FETCH_NUM)) {
			switch ($row4[0]) {
				case "{TYPE}": $resultrow4.="pro_id_type";
				break;
				case "{EQUIPE}": $resultrow4.="pro_id_equipe";
				break;
				case  "{BOITE}": $resultrow4.="pro_num_boite";
				break;
				case "{NUMERIC}": $resultrow4.="pro_num_incremental";
				break;
			}
			$y++;
			if ($y<$nubresultat4) $resultrow4.=",";
		}
		$resultrow4=str_replace(",$","",$resultrow4);
		$sql="SELECT $resultrow4 FROM produit WHERE pro_num_boite<>0 GROUP BY $resultrow4";
		$result5=$dbh->query($sql);
		if (!empty($result5)) {
			while ($row5=$result5->fetch(PDO::FETCH_OBJ)) {
				$requetesql="";
				if (isset($row5->pro_id_equipe)) $requetesql.="pro_id_equipe='".$row5->pro_id_equipe."' and ";
				if (isset($row5->pro_id_type)) $requetesql.="pro_id_type='".$row5->pro_id_type."' and ";
				if (isset($row5->pro_num_boite)) $requetesql.="pro_num_boite='".$row5->pro_num_boite."' and ";
				if (isset($row5->pro_num_incremental)) $requetesql.="pro_num_incremental='".$row5->pro_num_incremental."' and ";
				$requetesql=rtrim($requetesql);
				$requetesql=preg_replace("/and$/","",$requetesql);
				$sql="SELECT count(pro_id_produit) FROM produit WHERE $requetesql";
				$result9=$dbh->query($sql);
				$row9=$result9->fetch(PDO::FETCH_NUM);
				if ($row9[0]==80) {
					$varrequete="";
					$numerocomplet="";
					$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre=1 ORDER BY num_id_numero";
					$result6=$dbh->query($sql);
					while ($row6=$result6->fetch(PDO::FETCH_NUM)) {
						if ($row6[0]=="{FIXE}") $numerocomplet.=$row6[1];
						elseif ($row6[0]=="{EQUIPE}") {
							$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe=".$row5->pro_id_equipe."";
							$result7=$dbh->query($sql);
							$row7=$result7->fetch(PDO::FETCH_NUM);
							$numerocomplet.=$row7[0];
							$varrequete.="&equipe=".$row5->pro_id_equipe;
						}
						elseif ($row6[0]=="{TYPE}") {
							$sql="SELECT typ_initiale FROM type where typ_id_type=".$row5->pro_id_type."";
							$resultat8=$dbh->query($sql);
							$row8=$resultat8->fetch(PDO::FETCH_NUM);
							$numerocomplet.=$row8[0];
							$varrequete.="&type=".$row5->pro_id_type;
						}
						elseif ($row6[0]=="{BOITE}") {
							$numerocomplet.=$row5->pro_num_boite;
							$varrequete.="&boite=".$row5->pro_num_boite;
						}
						elseif ($row6[0]=="{NUMERIC}") {
							$numerocomplet.=$row5->pro_num_incremental;
							$varrequete.="&incremental=".$row5->pro_num_incremental;
						}
						$varrequete=str_replace("^&","",$varrequete);
					}
					$tab6[$varrequete]=$numerocomplet;
				}
			}
		}
		if (isset($tab6) and count($tab6)>0) {
			if(!isset($_POST['boitetotal'])) $boitetotal="";
			else $boitetotal=$_POST['boitetotal'];
			asort($tab6);

			$formulaire1->affiche_formulaire();
			$formulaire1->ajout_select (15,"boitetotal",$tab6,true,$boitetotal,"",BOITETO."<br/>",false,"");

		}
		echo "</td>
		<td>";
		$tabalt[1]=ALTERNATIVE;
		$formulaire1->ajout_checkbox ("alternative",$tabalt,"","",false);
		$tabdoub[1]=DOUBLONS;
		$formulaire1->ajout_checkbox ("doublon",$tabdoub,"","",false);
		$tabale[1]=ALEATOIRE;
		$formulaire1->ajout_checkbox ("aleatoire",$tabale,"","",false);
		echo "</td>
		<td>";
		$formulaire1->ajout_button (SUBMITCSV,"","submit","");
		echo"</td>
		</tr>
		</table>";
		$formulaire1->fin();
		echo "<hr />";

		$formulaire2=new formulaire ("plaqueboite","exportcsvpesee.php","POST",true);
		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td>";
		$formulaire2->affiche_formulaire();
		$formulaire2->ajout_textarea ("listetotal",50,"",20,true,LISTETO."<br/>");
		echo "</td>
		<td>";
		$tabsepa=array (
			";"=>";",
			","=>",",
			"espace"=>ESPACE,
			"ligne"=>RLIGNE,
		);
		$formulaire2->ajout_select (1,"separateur",$tabsepa,false,";","",SEPARATEUR."<br/>",false,"");
		echo "</td>
		<td>";
		$formulaire2->ajout_button (SUBMITCSV,"","submit","");
		echo"</td>
		</tr>
		</table>";
		$formulaire2->fin();
	}

else require 'deconnexion.php';
unset($dbh);
?>
