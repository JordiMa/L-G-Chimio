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

//définition des paramètres de la page $limitepage définit le nombre de résultats par page et $page définit la page à afficher
$limitepage=8;

if (empty($_POST['page'])) {
	$_POST['page']=1;
	$nbrequete=0;
}
else {
	if ($_POST['page']==1) $nbrequete=0;
	else  {
		if ($_POST['page']>$_POST['nbpage']) $_POST['page']=$_POST['nbpage'];
		$nbrequete=(($_POST['page']-1)*$limitepage);
		}
}

if(!isset($_POST['type'])) $_POST['type']="";
if(!isset($_POST['equipechi'])) $_POST['equipechi']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['supinf'])) $_POST['supinf']="";
if(!isset($_POST['massemol'])) $_POST['massemol']="";
if(!isset($_POST['massexact'])) $_POST['massexact']="";
if(!isset($_POST['formbrute'])) $_POST['formbrute']="";
if(!isset($_POST['forbrutexact'])) $_POST['forbrutexact']="";
if(!isset($_POST['numero'])) $_POST['numero']="";
if(!isset($_POST['refcahier'])) $_POST['refcahier']="";
if(!isset($_POST['mol'])) $_POST['mol']="";
if(!isset($_POST['recherche'])) $_POST['recherche']="";
$tab="";

//traitement des deux variables pour supprimer l'utilisation du % et remplace la , par .
$_POST['formbrute']=str_replace('\%','',$_POST['formbrute']);
$_POST['massemol']=str_replace('\%','',$_POST['massemol']);
$_POST['massemol']=str_replace('\,','.',$_POST['massemol']);
$_POST['numero']=str_replace('\%','',$_POST['numero']);


//appel le fichier de connexion à la base de données
require 'script/connectionb.php';

$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);



if (!empty($_POST['type']) or !empty($_POST['chimiste']) or !empty($_POST['equipechi'])) {

	if (!empty($_POST['type']) and !empty($_POST['equipechi'])) {
		if($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_equipe='".$_POST['equipechi']."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_equipe='".$_POST['equipechi']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
	}
  
	elseif (empty($_POST['type']) and !empty($_POST['equipechi'])) {
		if($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_equipe='".$_POST['equipechi']."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_equipe='".$_POST['equipechi']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
	}
  
	elseif (!empty($_POST['type']) and !empty($_POST['chimiste'])) {
		if($row[0]=="{RESPONSABLE}") {
			if ($row[1]==$_POST['chimiste']) $sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."') UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."') order by pro_id_structure";
			else $sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				if ($row[1]==$_POST['chimiste']) $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				else $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		elseif ($row[0]=="{ADMINISTRATEUR}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' order by pro_id_structure";
			$result2 =$dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
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
			if ($row[1]==$_POST['chimiste']) $sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and ($requete)) UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."') order by pro_id_structure";
			else $sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and ($requete) order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				if ($row[1]==$_POST['chimiste']) $sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and ($requete) and pro_id_structure='$row3[0]') UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]') order by pro_id_produit";
				else $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_type=".$_POST['type']." and pro_id_chimiste='".$_POST['chimiste']."' and ($requete) and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
	}
  
	elseif (empty($_POST['type']) and !empty($_POST['chimiste'])) {
		if($row[0]=="{RESPONSABLE}") {
			if ($row[1]==$_POST['chimiste']) $sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."') UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."') order by pro_id_structure";
			else $sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				if ($row[1]==$_POST['chimiste']) $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				else $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_equipe='".$row[2]."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		elseif ($row[0]=="{ADMINISTRATEUR}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
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
			if ($row[1]==$_POST['chimiste']) $sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and ($requete)) UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."') order by pro_id_structure";
			else $sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and ($requete) order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				if ($row[1]==$_POST['chimiste']) $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and pro_id_structure='$row3[0]' order by pro_id_produit";
				else $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit WHERE pro_id_chimiste='".$_POST['chimiste']."' and ($requete) and pro_id_structure='$row3[0]' order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
	}
  
	elseif (!empty($_POST['type'])) {
		if($row[0]=="{RESPONSABLE}") {
			$sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type='".$_POST['type']."' and pro_id_equipe='".$row[2]."') UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type='".$_POST['type']."' and pro_id_chimiste='".$row[1]."') order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste WHERE pro_id_type='".$_POST['type']."' and pro_id_structure='$row3[0]' and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		elseif ($row[0]=="{ADMINISTRATEUR}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type='".$_POST['type']."' order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste WHERE pro_id_type='".$_POST['type']."' and pro_id_structure='$row3[0]' and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		elseif ($row[0]=="{CHEF}") {
			$sql="SELECT equi_id_equipe FROM equipe WHERE equi_id_equipe in (SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$row[1]."') order by equi_nom_equipe";
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
			$sql="(SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type='".$_POST['type']."' and ($requete)) UNION (SELECT DISTINCT pro_id_structure FROM produit WHERE pro_id_type='".$_POST['type']."' and pro_id_chimiste='".$row[1]."') order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste WHERE pro_id_type='".$_POST['type']."' and pro_id_structure='$row3[0]' and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		else {
			$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste WHERE pro_id_type=".$_POST['type']." and chi_nom='".$_SESSION['nom']."' and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste WHERE pro_id_type=".$_POST['type']." and chi_nom='".$_SESSION['nom']."' and pro_id_structure='$row3[0]' and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
	}
}

elseif (!empty ($_POST['formbrute']) or $_POST['massemol']!="" or !empty($_POST['mol']) or !empty($_POST['numero']) or !empty($_POST['refcahier'])) {
	if (!empty($_POST['mol'])) {
		
		
		if($row[0]=="{ADMINISTRATEUR}") {
			switch ($_POST['recherche']) {
				case 0: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 1: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.sub and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 2: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and structure.str_id_structure=produit.pro_id_structure";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
			}
			//$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_inchi_md5='".$_POST['mol']."' and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure";
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}
		elseif($row[0]=="{CHEF}") {
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
			switch ($_POST['recherche']) {
				case 0: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.exact and ($requete)  and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste)";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 1: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['mol']."', '')::bingo.sub and ($requete)  and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste)";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 2: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and ($requete)  and structure.str_id_structure=produit.pro_id_structure order by pro_id_structure) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
			}
			//$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure WHERE str_inchi_md5='".$_POST['inchimd5']."' and ($requete) and structure.str_id_structure=produit.pro_id_structure) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_inchi_md5='".$_POST['inchimd5']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}
		elseif ($row[0]=="{RESPONSABLE}"){
			switch ($_POST['recherche']) {
				case 0: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure,chimiste WHERE pro_id_equipe='".$row[2]."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure  and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste)";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 1: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure,chimiste WHERE pro_id_equipe='".$row[2]."' and str_mol @ ('".$_POST['mol']."', '')::bingo.sub and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.sub and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste)";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 2: {
					$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,structure,chimiste WHERE pro_id_equipe='".$row[2]."' and str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and structure.str_id_structure=produit.pro_id_structure  and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste)";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
			}
			//$sql="(SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_inchi_md5='".$_POST['inchimd5']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_inchi_md5='".$_POST['inchimd5']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}
		else {
			switch ($_POST['recherche']) {
				case 0: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.exact and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 1: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['mol']."', '')::bingo.sub and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
				case 2: {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_mol @ ('".$_POST['valtanimoto']."','1','".$_POST['mol']."', 'Tanimoto')::bingo.sim and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste";
					$result2 = $dbh->query($sql);
					$nbrs=$result2->rowCount();
					$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
					$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				}
				break;
			}
			//$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_inchi_md5='".$_POST['inchimd5']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
			$result4 = $dbh->query($sql);
			while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
				$tab[$row4[0]]=$row4[1];
			}
		}
	}

	if (!empty ($_POST['formbrute'])) {
		if ($_POST['forbrutexact']=='exacte') {
      
			if ($row[0]=="{ADMINISTRATEUR}") {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste  order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
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
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and ($requete) and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and pro_id_chimiste='".$row[1]."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif ($row[0]=="{RESPONSABLE}"){
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_formule_brute='".$_POST['formbrute']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and str_formule_brute='".$_POST['formbrute']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute='".$_POST['formbrute']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_formule_brute='".$_POST['formbrute']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste  order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_formule_brute='".$_POST['formbrute']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
		else {
			if ($row[0]=="{ADMINISTRATEUR}") {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
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
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and ($requete) and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_chimiste='".$row[1]."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif ($row[0]=="{RESPONSABLE}"){
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_formule_brute like '%".$_POST['formbrute']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) union (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and str_formule_brute like '%".$_POST['formbrute']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_produit";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_formule_brute like '%".$_POST['formbrute']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_formule_brute like '%".$_POST['formbrute']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
	}
	if ($_POST['numero']!="") {
		if ($row[0]=="{ADMINISTRATEUR}") {
			$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE (pro_num_constant='".intval($_POST['numero'])."' or pro_numero like'%".$_POST['numero']."%' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like'%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE (pro_numero like'%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like'%".$_POST['numero']."%') and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
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
			$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and (pro_numero like'%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like'%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and (pro_numero like'%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like'%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result4 = $dbh->query($sql);
				while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				}
			}
		}
		elseif($row[0]=="{RESPONSABLE}") {
			$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
			}
		}
		else {
			$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
			$result2 = $dbh->query($sql);
			$nbrs=$result2->rowCount();
			$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
			$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
			$result3 = $dbh->query($sql);
			while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and (pro_numero like '%".$_POST['numero']."%' or pro_num_constant='".intval($_POST['numero'])."' or pro_num_cn='".$_POST['numero']."' or pro_qrcode like '%".$_POST['numero']."%') and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
			}
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
	
	if ($_POST['massemol']!="") {
		if ($_POST['massexact']=='exacte') {
			if ($row[0]=="{ADMINISTRATEUR}") {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire='".$_POST['massemol']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
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
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 =$dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and str_masse_molaire='".$_POST['massemol']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif($row[0]=="{RESPONSABLE}") {
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire='".$_POST['massemol']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 =$dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire='".$_POST['massemol']."' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire='".$_POST['massemol']."' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
		elseif ($_POST['supinf']!=rawurlencode("=")) {
			if ($row[0]=="{ADMINISTRATEUR}") {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
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
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			elseif($row[0]=="{RESPONSABLE}") {
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 =$dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire".rawurldecode($_POST['supinf']).$_POST['massemol']." and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
		elseif ($_POST['supinf']==rawurlencode("=")) {
			if ($row[0]=="{ADMINISTRATEUR}") {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				  $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire like'".$_POST['massemol']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
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
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE ($requete) str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_chimiste='".$row[1]."' and str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
				  $sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE ($requete) and str_masse_molaire like'".$_POST['massemol']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				  $result4 = $dbh->query($sql);
				  while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
					$tab[$row4[0]]=$row4[1];
				  }
				}
			}
			elseif($row[0]=="{RESPONSABLE}") {
				$sql="(SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE pro_id_equipe='".$row[2]."' and str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) UNION (SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste) order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE str_masse_molaire like'".$_POST['massemol']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
			else {
				$sql="SELECT DISTINCT pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire like'".$_POST['massemol']."%' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
				$result2 = $dbh->query($sql);
				$nbrs=$result2->rowCount();
				$nbpage=ceil($nbrs/$limitepage); // retourne le nombre de pages pris par la requête
				$sql=$sql." LIMIT $limitepage OFFSET $nbrequete";
				$result3 = $dbh->query($sql);
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$sql="SELECT DISTINCT pro_id_produit,pro_id_structure FROM produit,chimiste,structure WHERE chi_nom='".$_SESSION['nom']."' and str_masse_molaire like'".$_POST['massemol']."%' and pro_id_structure='$row3[0]' and structure.str_id_structure=produit.pro_id_structure and chimiste.chi_id_chimiste=produit.pro_id_chimiste order by pro_id_structure";
					$result4 = $dbh->query($sql);
					while ($row4=$result4->fetch(PDO::FETCH_NUM)) {
						$tab[$row4[0]]=$row4[1];
					}
				}
			}
		}
	}
}

unset($dbh);
if (!isset($nbrs)) $nbrs=0;
if (!isset($nbpage)) $nbpage=0;
page ($_POST['type'],$_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['massexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['equipechi'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche']);
$recherche= new affiche_modification ($tab,$_POST['type'],$_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['massexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['equipechi'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche']);
$recherche->imprime();
page ($_POST['type'],$_POST['mol'],$_POST['formbrute'],$_POST['massemol'],$_POST['supinf'],$_POST['massexact'],$_POST['forbrutexact'],$_POST['page'],$nbrs,$nbpage,$row[0],$_POST['chimiste'],$_POST['equipechi'],$_POST['numero'],$_POST['refcahier'],$_POST['recherche']);

function page ($type,$mol,$formbrute,$massemol,$supinf,$massexact,$forbrutexact,$page,$nbrs,$nbpage,$typechimiste,$chimiste,$equipechi,$numero,$refcah,$recherche) {

    if ($nbrs>0) print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
					  <tr valign=\"middle\">
						<td width=\"44%\" align=\"left\"><strong>".IL.$nbrs.REPONSE.$nbpage.PAGES."</strong></td>
						<td width=\"9%\">";
    if ($page>1) {
        $formulaire=new formulaire ("changepage","consultation.php","POST",true);
        $formulaire->affiche_formulaire();
        $formulaire->ajout_cache ($type,"type");
        $formulaire->ajout_cache ($mol,"mol");
        $formulaire->ajout_cache ($formbrute,"formbrute");
        $formulaire->ajout_cache ($massemol,"massemol");
        $formulaire->ajout_cache ($supinf,"supinf");
        $formulaire->ajout_cache ($massexact,"massexact");
        $formulaire->ajout_cache ($forbrutexact,"forbrutexact");
		$formulaire->ajout_cache ($numero,"numero");
		$formulaire->ajout_cache ($refcah,"refcahier");
        $formulaire->ajout_cache (($page-1),"page");
		$formulaire->ajout_cache (($nbpage),"nbpage");
		$formulaire->ajout_cache ($recherche,"recherche");
        $formulaire->ajout_button (PAGE.($page-1),"","submit","");
        if ($typechimiste=="{RESPONSABLE}" or $typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($chimiste,"chimiste");
        if ($typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($equipechi,"equipechi");
        $formulaire->fin();
    }
    if ($nbrs>0)  print"</td><td width=\"9%\" valign=\"top\"><strong>".PAGE."$page</strong></td>
						<td width=\"9%\">";
    if ($page<($nbpage)) {
        $formulaire=new formulaire ("changepage","consultation.php","POST",true);
        $formulaire->affiche_formulaire();
        $formulaire->ajout_cache ($type,"type");
        $formulaire->ajout_cache ($mol,"mol");
        $formulaire->ajout_cache ($formbrute,"formbrute");
        $formulaire->ajout_cache ($massemol,"massemol");
        $formulaire->ajout_cache ($supinf,"supinf");
        $formulaire->ajout_cache ($massexact,"massexact");
        $formulaire->ajout_cache ($forbrutexact,"forbrutexact");
		$formulaire->ajout_cache ($numero,"numero");
		$formulaire->ajout_cache ($refcah,"refcahier");
        $formulaire->ajout_cache (($page+1),"page");
		$formulaire->ajout_cache (($nbpage),"nbpage");
		$formulaire->ajout_cache ($recherche,"recherche");
        if ($typechimiste=="{RESPONSABLE}" or $typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($chimiste,"chimiste");
        if ($typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($equipechi,"equipechi");
        $formulaire->ajout_button (PAGE.($page+1),"","submit","");
        $formulaire->fin();
    }
    if ($nbrs>0)  print"</td>
					<td width=\"29%\">
					<div align=\"right\">";
    if ($nbpage>1) {
        $formulaire=new formulaire ("changepage","consultation.php","POST",true);
        $formulaire->affiche_formulaire();
        $formulaire->ajout_cache ($type,"type");
        $formulaire->ajout_cache ($mol,"mol");
        $formulaire->ajout_cache ($formbrute,"formbrute");
        $formulaire->ajout_cache ($massemol,"massemol");
        $formulaire->ajout_cache ($supinf,"supinf");
        $formulaire->ajout_cache ($massexact,"massexact");
        $formulaire->ajout_cache ($forbrutexact,"forbrutexact");
		$formulaire->ajout_cache ($numero,"numero");
		$formulaire->ajout_cache ($refcah,"refcahier");
        $formulaire->ajout_text (4,"",3,"page","","","");
		$formulaire->ajout_cache (($nbpage),"nbpage");
		$formulaire->ajout_cache ($recherche,"recherche");
        if ($typechimiste=="{RESPONSABLE}" or $typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($chimiste,"chimiste");
        if ($typechimiste=="{ADMINISTRATEUR}" or $typechimiste=="{CHEF}") $formulaire->ajout_cache ($equipechi,"equipechi");
        $formulaire->ajout_button (RENDRE,"","submit","");
        $formulaire->fin();
    }
    if ($nbrs>0) print"</div></td>
						</tr>
					</table>";
}
?>