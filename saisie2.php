<?php
/*
Copyright Laurent ROBIN CNRS - Universit� d'Orl�ans 2011
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique
Universit� d�Orl�ans
Rue de Chartre � BP6759
45067 Orl�ans Cedex 2

Ce logiciel est un programme informatique servant � la gestion d'une chimioth�que de produits de synth�ses.

Ce logiciel est r�gi par la licence CeCILL soumise au droit fran�ais et respectant les principes de diffusion des logiciels libres.
Vous pouvez utiliser, modifier et/ou redistribuer ce programme sous les conditions de la licence CeCILL telle que diffus�e par le CEA,
 le CNRS et l'INRIA sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit� au code source et des droits de copie, de modification et de redistribution accord�s par cette licence,
 il n'est offert aux utilisateurs qu'une garantie limit�e. Pour les m�mes raisons, seule une responsabilit� restreinte p�se sur l'auteur du
 programme, le titulaire des droits patrimoniaux et les conc�dants successifs.

A cet �gard l'attention de l'utilisateur est attir�e sur les risques associ�s au chargement, � l'utilisation, � la modification et/ou au d�veloppement
 et � la reproduction du logiciel par l'utilisateur �tant donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe � manipuler et qui le
r�serve donc � des d�veloppeurs et des professionnels avertis poss�dant des connaissances informatiques approfondies. Les utilisateurs sont donc
invit�s � charger et tester l'ad�quation du logiciel � leurs besoins dans des conditions permettant d'assurer la s�curit� de leurs syst�mes et ou de
 leurs donn�es et, plus g�n�ralement, � l'utiliser et l'exploiter dans les m�mes conditions de s�curit�.

Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez pris connaissance de la licence CeCILL, et que vous en avez accept� les
termes.
*/

include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'presentation/entete.php';
$menu=1;
include_once 'presentation/gauche.php';
require 'script/connectionb.php';
$sql="SELECT bingo.CheckMolecule('".$_POST['mol']."')";
$resultat=$dbh->query($sql);
$check =$resultat->fetch(PDO::FETCH_NUM);

// [JM - 22/01/2019] Traduction de MOL en INCHI
$sql1="SELECT Bingo.InchI('".$_POST["mol"]."','')";
$resul1=$dbh->query($sql1);
$row1=$resul1->fetch(PDO::FETCH_NUM);

// [JM - 22/01/2019] Recherche INCHI existant
$sql2="SELECT str_inchi FROM structure WHERE str_inchi ='".$row1[0]."'";
$result2=$dbh->query($sql2);
$row2=$result2->fetch(PDO::FETCH_NUM);

// [JM - 22/01/2019] Avertis si doublon
if ($row2[0]){
	echo '<script language="javascript">';
	// [JM - 22/01/2019] Si l'utilisateur annule, il revient à la page précédente
	echo 'if(confirm("Attention doublon ! La structure existe déjà dans la base.\rVoulez-vous continuer ?")){

	}
	else{
		window.stop();
		history.back();
	}';
	echo '</script>';
}

if ($check[0]==NULL) include_once 'corps/saisieformulaire2.php';
else {
	$erreur=$check[0];
	include_once 'corps/saisie1.php';
}

include_once 'presentation/pied.php';
?>
