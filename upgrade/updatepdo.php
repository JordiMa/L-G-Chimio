<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

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
//include_once '../script/secure.php';
include_once '../protection.php';
require '../script/connectiona.php';

echo "<tr>
    <td bgcolor=\"#FFFFFF\" valign=\"top\" width=\"100%\" colspan=\"2\">";
$sql="SHOW COLUMNS FROM parametres";
$resultat=$db->query($sql);
while ($row =$resultat->fetch(PDO::FETCH_NUM)) {
	$tab[]=$row[0];
}
	if (!in_array("para_version",$tab)) {
		echo "<br><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.1 vers la version 1.2.1</h2><br><div align=\"center\">";
		$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
		$formulaire->affiche_formulaire();
		$formulaire->ajout_cache ("1.1","ver");
		$formulaire->ajout_button ("Mettre à jour","","submit","");
		$formulaire->fin();
		echo "</div>";
	}
	else{
		$sql="SELECT para_version from parametres";
		$resultat1=$db->query($sql);
		$row =$resultat1->fetch(PDO::FETCH_NUM);
		if (empty($row[0])) {
			echo "<br/><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.2 vers la version 1.2.1</h2><br/><div align=\"center\">";
			$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ("1.2","ver");
			$formulaire->ajout_button ("Mettre à jour","","submit","");
			$formulaire->fin();
			echo "</div>";	
		}
		elseif ($row[0]=='1.2.1') {
			echo "<br/><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.2.1 vers la version 1.3</h2><br/><div align=\"center\">";
			$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ("1.2.1","ver");
			$formulaire->ajout_cache ("0","etape");
			$formulaire->ajout_cache ("0","j");
			$formulaire->ajout_button ("Mettre à jour","","submit","");
			$formulaire->fin();
			echo "</div>";
		}
		elseif ($row[0]=='1.3') {
			echo "<br/><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.3 vers la version 1.3.1</h2><br/><div align=\"center\">";
			echo "<p class=\"h3erreur\">Cette mise à jour nécessite de modifier le fichier connectiona.php ce trouvant dans le sous répertoire ";
			
			$repprincipal=getcwd();
			//vérifie si on est sous un système windows
			if(preg_match("/:/",$repprincipal)) $win=1;
			if ($win==1) {
				$permscrip=fileperms(REPEPRINCIPAL."script\\connectiona.php");
				echo REPEPRINCIPAL."script";
				if (decoct($permscrip)==100444) $okscript=1;
				else $okscript=0;
			}
			else {
				$permscrip=fileperms(REPEPRINCIPAL."script");
				echo REPEPRINCIPAL."script";
				if (decoct($permscrip)==16749) $okscript=1;
				else $okscript=0;
			}
			if ($okscript==0) {
				$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
				$formulaire->affiche_formulaire();
				$formulaire->ajout_cache ("1.3","ver");
				$formulaire->ajout_button ("Mettre à jour","","submit","");
				$formulaire->fin();
			}
			else echo "<br/>Vous devez donc modifier les droits d'accès (droits Linux 777 ou windows décocher lecture seule) à ce fichier pour le rentre provisoirement modifiable</p>";
			echo "</div>";
		}
		elseif  ($row[0]=='1.3.1') {
			echo "<br/><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.3.1 vers la version 1.3.2</h2><br/><div align=\"center\">";		
			$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ("1.3.1","ver");
			$formulaire->ajout_button ("Mettre à jour","","submit","");
			$formulaire->fin();
			echo "</div>";
		}
		elseif ($row[0]=='1.3.2') {
			echo "<br/><h2 align=\"center\">Mettre à jour votre version de la base de données du logiciel de L-g-<i>Chimio</i> v1.3.2 vers la version 1.3.2.1</h2><br/><div align=\"center\">";		
			$formulaire=new formulaire ("Mjour","mjour.php","POST",true);
			$formulaire->affiche_formulaire();
			$formulaire->ajout_cache ("1.3.2","ver");
			$formulaire->ajout_button ("Mettre à jour","","submit","");
			$formulaire->fin();
			echo "</div>";
		}
		else echo "<br><h2 align=\"center\">La base de données du logiciel L-g-<i>Chimio</i> est à jour.</h2><h2 align=\"center\">Vous pouvez supprimer sur le serveur web, le répertoire ".REPEPRINCIPAL."upgrade et son contenu.</h2>";
	}
	
?>