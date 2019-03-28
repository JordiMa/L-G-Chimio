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
include_once 'langues/'.$_SESSION['langue'].'/lang_import.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	//suppression des fichier .tmp
	$mask = REPTEMP."*.tmp";
	array_map( "unlink", glob( $mask ) );

	if(($fic = fopen($_FILES['fileCN']['tmp_name'], 'r'))!==FALSE) {
		$sql="UPDATE produit SET pro_num_cn='' WHERE pro_num_cn<>''";
		$updat=$dbh->exec($sql);
		$erreur="";
		$sql="";
		$o=0;
		$ototal=0;
		$contenu_fichier = file_get_contents($_FILES['fileCN']['tmp_name']);
		$nombre_ligne_fichier = substr_count( $contenu_fichier, "\n" );
		while(($donne = fgetcsv($fic,30,";"))!=FALSE) {

			// 2 est le nombre de champs du fichier numéro local + numéro national
			for ($k=0; $k<2; $k++) {
				if(preg_match("/CN/",$donne[$k])) {
					if(preg_match("/V/",$donne[$k])) $numcnp=$donne[$k];
					else $numcnpl=$donne[$k];
				}
				else $numlocal=$donne[$k];
			}
			if(!empty($numcnp) and !empty($numlocal)) {
				$numlocal = str_replace("\xEF\xBB\xBF", '', trim($numlocal));
				$sql.="UPDATE produit SET pro_num_cn='$numcnp' WHERE  pro_numero='$numlocal'";
				if (is_numeric($numlocal)){
					$sql .= "or pro_num_constant='$numlocal'";
				}
				$sql .= ";\n";
			}

			//if(isset($numaff) and $numaff==0) $erreur.=ERRORCSV.$numcnp.ERRORCSV1.$numlocal.ERRORCSV2."<br/>";
			unset ($numcnpl,$numcnp,$numlocal,$numaff);
			$o++;
			$ototal++;
			if ($o==2000 or $ototal==$nombre_ligne_fichier) {
				//création du fichier temporaire contenant le SQL limiter à 2000 entrée $o
				$tmpfname = tempnam(REPTEMP, "CN");
				$repprincipal=getcwd();
				//Sous windows le .tmp est automatique
				if(preg_match("/:/",$repprincipal)) {
					if(($tempofile = fopen($tmpfname, 'w'))!==FALSE) {
						$sql=trim($sql);
						fwrite($tempofile, utf8_encode($sql));
						fclose($tempofile);
					}
					$o=0;
					$sql="";
				}
				//Sous Linux le .tmp n'est pas automatique
				else {
					if(($tempofile = fopen($tmpfname.'.tmp', 'w'))!==FALSE) {
						$sql=trim($sql);
						fwrite($tempofile, utf8_encode($sql));
						fclose($tempofile);
					}
					$o=0;
					$sql="";
				}
			}
		}
		fclose($fic);
	}
	if(!empty($erreur)) echo "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"erreur\">".$erreur."</p>";
	else echo "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".TRANSREUSSI."</p>";
}
else require 'deconnexion.php';
unset($dbh);
?>
