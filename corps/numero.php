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
function numero($parametre)
{
	global $numoboite,$numoposition,$numoincremental,$dbh;

	if (isset($numoboite) and isset($numoposition) and empty($numoboite) and empty($numoposition)) {
		$sql="SELECT num_type FROM numerotation WHERE num_parametre='$parametre' ORDER BY num_id_numero";
		$resultat=$dbh->query($sql);
		while ($row=$resultat->fetch(PDO::FETCH_NUM)) {
			$tableau[]=$row[0];
		}
		if (in_array("{BOITE}",$tableau) and $numoboite=="") $numoboite='01';
		if (in_array("{COORDONEE}",$tableau) and $numoposition=="") $numoposition='A02';
	}
	elseif (isset($numoboite) and isset($numoincremental) and empty($numoboite) and empty($numoincremental)) {
		$sql="SELECT num_type FROM numerotation WHERE num_parametre='$parametre' ORDER BY num_id_numero";
		$resultat=$dbh->query($sql);
		while ($row=$resultat->fetch(PDO::FETCH_NUM)) {
			$tableau[]=$row[0];
		}
		if (in_array("{BOITE}",$tableau) and $numoboite=="") $numoboite='01';
		if (in_array("{NUMERIC}",$tableau) and $numoincremental=="") $numoincremental=1;
	}
	elseif (isset($numoincremental) and empty($numoincremental)) {
		$sql="SELECT num_type FROM numerotation WHERE num_parametre='$parametre' ORDER BY num_id_numero";
		$resultat=$dbh->query($sql);
		while ($row=$resultat->fetch(PDO::FETCH_NUM)) {
			$tableau[]=$row[0];
		}
		if (in_array("{NUMERIC}",$tableau) and $numoincremental=="") $numoincremental=1;
	}
	else {
		if (!empty($numoboite) and !empty($numoposition)) {
			list ($pp,$zz,$xx)=$numoposition;
			$position=$zz.$xx;
			if ($position==11 and $pp=='H') {
				$numoboite++;
				$pp='A';
				$position='02';
				$numoposition=$pp.$position;
				if ($numoboite<10) $numoboite="0".$numoboite;
			}
			else {
				if ($position==11) {
					$pp++;
					$position='02';
					$numoposition=$pp.$position;
				}
				else {
					$position++;
					if ($position<10) $position="0".$position;
					$numoposition=$pp.$position;
				}
			}
		}
		elseif(!empty($numoboite) and !empty($numoincremental)) {
			$numoincremental++;
			$numoboite=ceil($numoincremental/80);
		}
		elseif (!empty($numoincremental)) {
			$numoincremental++;
		}
	}

	if ($numoboite!="" and $numoposition!="")  return $numoboite."@".$numoposition;
	elseif ($numoboite!="" and $numoincremental!="") return $numoboite."@".$numoincremental;
	elseif ($numoincremental!="") return $numoincremental;
}
?>