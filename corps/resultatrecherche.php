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
include_once 'langues/'.$_SESSION['langue'].'/lang_resultat.php';



if(!isset($_POST['masseexact'])) $_POST['masseexact']="";
if(!isset($_POST['massemol'])) $_POST['massemol']="";
if(!isset($_POST['formbrute'])) $_POST['formbrute']="";
if(!isset($_POST['forbrutexact'])) $_POST['forbrutexact']="";
if(!isset($_POST['numero'])) $_POST['numero']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['mol'])) $_POST['mol']="";
if(!isset($_POST['recherche'])) $_POST['recherche']="";
if(!isset($_POST['valtanimoto'])) $_POST['valtanimoto']="";
if(!isset($_POST['refcahier'])) $_POST['refcahier']="";


//définition des paramètres de la page $limitepage définit le nombre de résultats par page et $page définit la page à afficher
$limitepage=8;
if (empty($_POST['page'])) {
	$_POST['page']=1;
	$nbrequete=0;
}
else {
	if ($_POST['page']==1)$nbrequete=0;
	else {
		if($_POST['page']>$_POST['nbpage']) $_POST['page']=$_POST['nbpage'];
		$nbrequete=(($_POST['page']-1)*$limitepage)+1;
	}
}
if ($_POST['masseexact']=='exacte' and $_POST['supinf']!="%3D")  {
	$erreur="<br/><p align=\"center\"><font color=\"#CC0000\"><strong>".CHAMP."</strong></font></p>";
	require 'rechercheformul.php';
}

else {
	//traitement des variables pour supprimer l'utilisation du % et remplacer la , par un .
	$_POST['formbrute']=str_replace('\%','',$_POST['formbrute']);
	$_POST['massemol']=str_replace('\%','',$_POST['massemol']);
	$_POST['massemol']=str_replace('\,','.',$_POST['massemol']);
	$_POST['numero']=str_replace('\%','',$_POST['numero']);

	//echo "<p>!".$_POST['mol']."!</p>";

	require 'script/connectionb.php';
	$tab="";
	if (!empty($_POST['mol']) or !empty ($_POST['formbrute']) or ($_POST['massemol'])!="" or !empty($_POST['numero']) or !empty($_POST['logp']) or !empty($_POST['refcahier'])) {


		$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
		//les résultats sont retournées dans la variable $result
		$result =$dbh->query($sql);
		$row =$result->fetch(PDO::FETCH_NUM);
		if ($row[0]=="{RESPONSABLE}") $requetepartielle="pro_id_type=2 and pro_id_equipe=".$row[2];
		elseif ($row[0]=="{CHEF}")  {
			$sql="SELECT equi_id_equipe FROM equipe WHERE equi_id_equipe in(SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$row[1]."') order by equi_nom_equipe";
			//les résultats sont retournées dans la variable $result5
			$result5 =$dbh->query($sql);
			$nbrow5=$result5->rowCount();
			$requetepartielle="pro_id_type=2 and ";
			if (!empty($nbrow5)) {
				$i=1;
				while($row5 =$result5->fetch(PDO::FETCH_NUM)) {
					$requetepartielle.="pro_id_equipe=".$row5[0];
					if ($i<$nbrow5) $requetepartielle.=" or ";
					$i++;
				}
			}
		}
		else $requetepartielle="pro_id_type=2 and pro_id_chimiste=".$row[1];

		if (!empty($_POST['mol'])) {
			switch ($_POST['recherche']) {
				case 0: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.exact and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 1: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.sub and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 2: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
			}
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}

		if (!empty ($_POST['formbrute'])) {
			if ($_POST['forbrutexact']=='exact') {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_formule_brute='".$_POST['formbrute']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_formule_brute='".$_POST['formbrute']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				$result3 =$dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_formule_brute='".$_POST['formbrute']."' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
		if ($_POST['massemol']!="") {
			if (isset ($_POST['massexact']) and $_POST['massexact']=='exacte') {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire='".$_POST['massemol']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire='".$_POST['massemol']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_masse_molaire='".$_POST['massemol']."' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif ($_POST['supinf']!=rawurlencode("=")) {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf'])."'".$_POST['massemol']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf'])."'".$_POST['massemol']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf'])."'".$_POST['massemol']."' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif ($_POST['supinf']==rawurlencode("=")) {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire like'".$_POST['massemol']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_masse_molaire like'".$_POST['massemol']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_masse_molaire like'".$_POST['massemol']."%'  and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
		if (!empty($_POST['numero'])) {
			$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}

		//recherche par référence de cahier de laboratoire
			if ($_POST['refcahier']!="") {
				if ($row[0]=="{ADMINISTRATEUR}") {
					$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
					$result3 = $dbh->query($sql);
					while($row3=$result3->fetch(PDO::FETCH_NUM)) {
						$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
						$result4 = $dbh->query($sql);
						while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
							$tab[$row4[0]]=$row4[1];
						}
					}
				}
				 elseif ($row[0]=="{CHEF}") {
					$sql="SELECT equi_id_equipe FROM equipe WHERE equi_id_equipe in(SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$row[1]."') order by equi_nom_equipe";
					//les résultats sont retournées dans la variable $result5
					$result5 =$dbh->query($sql);
					$nbrow5=$result5->rowCount();
					$requete="";
					$i=1;
					if (!empty($nbrow5)) {
					  while($row5 =$result5->fetch(PDO::FETCH_NUM)) {
							$requete.="pro_id_equipe='".$row5[0]."'";
							if ($i<$nbrow5) $requete.=" or ";
							$i++;
						}
					}
					$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
					$result3 = $dbh->query($sql);
					while($row3=$result3->fetch(PDO::FETCH_NUM)) {
						$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
						$result4 = $dbh->query($sql);
						while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
							$tab[$row4[0]]=$row4[1];
						}
					}
				}
				elseif($row[0]=="{RESPONSABLE}") {
					$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
					$result3 = $dbh->query($sql);
					while($row3=$result3->fetch(PDO::FETCH_NUM)) {
						$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
						$result4 = $dbh->query($sql);
						while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
							$tab[$row4[0]]=$row4[1];
						}
					}
				}
				else {
					$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
					$result3 = $dbh->query($sql);
					while($row3=$result3->fetch(PDO::FETCH_NUM)) {
						$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and pro_ref_cahier_labo like '%".$_POST['refcahier']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
						$result4 = $dbh->query($sql);
						while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
							$tab[$row4[0]]=$row4[1];
						}
					}
				}
			}

		// if ($_POST['logp']!="") {
			// if ($_POST['logpexact']=='exact') {
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp='".$_POST['logp']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				// $result2 = $dbh->query($sql);
				// $nbrs=$result2->rowCount();
				// $nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp='".$_POST['logp']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				// $result3 = $dbh->query($sql);
				// while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					// $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_logp='".$_POST['logp']."' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					// $result4 = $dbh->query($sql);
					// while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						// $tab[$row4[0]]=$row4[1];
					// }
				// }
			// }
			// elseif ($_POST['supinflog']!=rawurlencode("=")) {
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp".rawurldecode($_POST['supinflog'])."'".$_POST['logp']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				// $result2 = $dbh->query($sql);
				// $nbrs=$result2->rowCount();
				// $nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp".rawurldecode($_POST['supinflog'])."'".$_POST['logp']."' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				// $result3 = $dbh->query($sql);
				// while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					// $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_logp".rawurldecode($_POST['supinflog'])."'".$_POST['logp']."' and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					// $result4 = $dbh->query($sql);
					// while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						// $tab[$row4[0]]=$row4[1];
					// }
				// }
			// }
			// elseif ($_POST['supinflog']==rawurlencode("=")) {
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp like '".$_POST['logp']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
				// $result2 = $dbh->query($sql);
				// $nbrs=$result2->rowCount();
				// $nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				// $sql="SELECT DISTINCT pro_id_structure FROM produit,structure WHERE str_logp like '".$_POST['logp']."%' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure LIMIT $limitepage OFFSET $nbrequete";
				// $result3 = $dbh->query($sql);
				// while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					// $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_logp like '".$_POST['logp']."%'  and pro_id_structure='$row3[0]' and (pro_id_type<>2 or ($requetepartielle)) and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
					// $result4 = $dbh->query($sql);
					// while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						// $tab[$row4[0]]=$row4[1];
					// }
				// }
			// }
		// }
	}

	if (!isset($nbrs)) $nbrs=0;
	if (!isset($nbpage)) $nbpage=0;



	page ($_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['masseexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche'],$_POST['valtanimoto']);
	$recherche= new affiche_recherche ($tab,$_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['masseexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche'],$_POST['valtanimoto']);

	$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	//les résultats sont retournées dans la variable $result
	$result =$dbh->query($sql);
	$row =$result->fetch(PDO::FETCH_NUM);
	if ($row[0]=='{ADMINISTRATEUR}') {
		echo '<a class="btnlink" target="_blank" href="exportation.php?chx_liste=1&listeID_separateur=%3B&listeID='.$recherche->getListeID().'">Exporter la page</a>';
	}

	$recherche->imprime();
	page ($_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['masseexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche'],$_POST['valtanimoto']);
}

unset($dbh);

function page ($mol,$formbrute,$massemol,$supinf,$massexact,$forbrutexact,$page,$nbrs,$nbpage,$typechimiste,$chimiste,$numero,$refcah,$recherche,$valtanimoto) {

		if ($nbrs>0) print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
						  <tr valign=\"middle\">
							<td width=\"44%\" align=\"left\"><strong>".IL.$nbrs.REPONSE.$nbpage.PAGES."</strong></td>
							<td width=\"9%\">";
        if ($page>1) {
			$formulaire=new formulaire ("changepage","consultation1.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ($mol,"mol");
			$formulaire->ajout_cache ($formbrute,"formbrute");
			$formulaire->ajout_cache ($massemol,"massemol");
			$formulaire->ajout_cache ($supinf,"supinf");
			$formulaire->ajout_cache ($massexact,"massexact");
			$formulaire->ajout_cache ($forbrutexact,"forbrutexact");
			$formulaire->ajout_cache ($numero,"numero");
			$formulaire->ajout_cache ($refcah,"refcahier");
			$formulaire->ajout_cache (($page-1),"page");
			$formulaire->ajout_cache ($nbpage,"nbpage");
			$formulaire->ajout_cache ($recherche,"recherche");
			$formulaire->ajout_cache ($valtanimoto,"valtanimoto");
			$formulaire->ajout_button (PAGE.($page-1),"","submit","");

			$formulaire->fin();
        }
        if ($nbrs>0) print"</td><td width=\"9%\" valign=\"top\">
							<strong>".PAGE."$page</strong>
							</td>
							<td width=\"9%\">";
        if ($page<($nbpage)) {
			$formulaire=new formulaire ("changepage","consultation1.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ($mol,"mol");
			$formulaire->ajout_cache ($formbrute,"formbrute");
			$formulaire->ajout_cache ($massemol,"massemol");
			$formulaire->ajout_cache ($supinf,"supinf");
			$formulaire->ajout_cache ($massexact,"massexact");
			$formulaire->ajout_cache ($forbrutexact,"forbrutexact");
			$formulaire->ajout_cache ($numero,"numero");
			$formulaire->ajout_cache ($refcah,"refcahier");
			$formulaire->ajout_cache (($page+1),"page");
			$formulaire->ajout_cache ($nbpage,"nbpage");
			$formulaire->ajout_cache ($recherche,"recherche");
			$formulaire->ajout_cache ($valtanimoto,"valtanimoto");
			$formulaire->ajout_button (PAGE.($page+1),"","submit","");
			$formulaire->fin();
        }
        if ($nbrs>0) print"</td>
							<td width=\"29%\">
							<div align=\"right\">";
        if ($nbpage>1) {
			$formulaire=new formulaire ("changepage","consultation1.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ($mol,"mol");
			$formulaire->ajout_cache ($formbrute,"formbrute");
			$formulaire->ajout_cache ($massemol,"massemol");
			$formulaire->ajout_cache ($supinf,"supinf");
			$formulaire->ajout_cache ($massexact,"massexact");
			$formulaire->ajout_cache ($forbrutexact,"forbrutexact");
			$formulaire->ajout_cache ($numero,"numero");
			$formulaire->ajout_cache ($refcah,"refcahier");
			$formulaire->ajout_cache ($nbpage,"nbpage");
			$formulaire->ajout_cache ($recherche,"recherche");
			$formulaire->ajout_cache ($valtanimoto,"valtanimoto");
			$formulaire->ajout_text (4,"",6,"page","","","");
			if ($typechimiste="{RESPONSABLE}" or $typechimiste="{CHEF}") $formulaire->ajout_cache ($chimiste,"{CHIMISTE}");
			$formulaire->ajout_button (RENDRE,"","submit","");
			$formulaire->fin();
        }
        if ($nbrs>0) print"</div></td>
						  </tr>
						</table>";
}
?>
