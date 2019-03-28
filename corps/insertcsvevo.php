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
include_once 'numero.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_import.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe,chi_prenom FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	if(($fic = fopen($_FILES['filevo']['tmp_name'], 'r'))!==FALSE) {
		$erreur="";
		while(($donne = fgetcsv($fic,90,";"))!==FALSE) {
			$nb=count($donne);
			for ($k=0; $k<$nb; $k++) {
				if (strlen($donne[$k])==8) $numlocal=$donne[$k];
				else $masse=$donne[$k];
			}
			$masse=str_replace("\,",".",$masse);
			$sql="SELECT pro_id_produit FROM produit WHERE pro_num_constant=$numlocal";
			//echo $sql;
			$existancenumero=$dbh->query($sql);
			$nbexiste=$existancenumero->rowCount();
			if ($nbexiste>0) {

				$sql="SELECT str_inchi_md5 FROM produit,structure WHERE pro_num_constant=$numlocal and produit.pro_id_structure=structure.str_id_structure and str_inchi_md5 not in (SELECT str_inchi_md5 FROM produit,structure,evotec WHERE evotec.evo_numero_permanent=produit.pro_num_constant and produit.pro_id_structure=structure.str_id_structure);";
				$rechercheinchikey=$dbh->query($sql);
				$rowinchi=$rechercheinchikey->fetch(PDO::FETCH_NUM);
				$numinchi=$rechercheinchikey->rowCount();

				if ($numinchi>0) {
					$masse=str_replace(",",".",$masse);
					$sql="INSERT INTO evotec (evo_numero_permanent,evo_masse) VALUES ('$numlocal','$masse')";
					$numaff=$dbh->exec($sql);
					if($numaff<1) $erreur.=ERROREVOCSV.$numlocal.ERROREVOCSV1.$masse.ERROREVOCSV2."<br/>";
					//modification de la masse de produit et changement du numéro si besoin
					elseif (isset($_POST['massetran']) and $_POST['massetran']==1) {
						//Modification de la masse dans la table produit et insertion dans l'historique
						$sql="SELECT pro_masse,pro_suivi_modification,pro_id_equipe,pro_id_type,pro_numero FROM produit WHERE pro_num_constant='".$numlocal."'";
						$resultat2=$dbh->query($sql);
						$row2=$resultat2->fetch(PDO::FETCH_NUM);

						$suivi=$row2[1];
						$massenew=$row2[0]-$masse;
						if ($massenew<0) $massenew=0;
						$suivi.=$_SESSION['nom']." ".$masse."@".date("Y-m-d H:i:s")."@MASSE@".$row2[0]."\n";
						$sql="UPDATE produit SET pro_masse='".$massenew."', pro_suivi_modification='".$suivi."' WHERE pro_num_constant='".$numlocal."'";
						echo $sql;
						$upd=$dbh->exec($sql);

						//si la masse tombe à 0mg alors le numéro du produit est changé pour le type sans masse
						if ($massenew==0) {
							$sql="SELECT para_stock,para_numerotation FROM parametres";
							$result21=$dbh->query($sql);
							$row21=$result21->fetch(PDO::FETCH_NUM);
							if ($row21[1]=="AUTO") {
								$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='2' ORDER BY num_id_numero";
								$resultat24=$dbh->query($sql);
								while ($row24=$resultat24->fetch(PDO::FETCH_NUM)) {
									$tab24[]=$row24[0];
								}
								if (in_array("{NUMERIC}",$tab24)) {
									//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
									$sql="SELECT distinct(pro_num_sansmasse) FROM produit WHERE pro_id_equipe='".$row2[2]."' and pro_id_type='".$row2[3]."' and pro_num_sansmasse>0 ORDER BY pro_num_sansmasse";
									$result23=$dbh->query($sql);
									$o=0;
									while($row23=$result23->fetch(PDO::FETCH_NUM)) {
										$tab23[$o]=$row23[0];
										$o++;
									}
									$numoincremental="";
								}
								$nbtab23=count($tab23);
								$u=0;
								$numeroassemble=numero(2);
								//vidange de la table temporaire
								$sql="DELETE FROM numerotation_temporaire WHERE nume_date<>'".date("Y-m-d")."'";
								$deletenum=$dbh->exec($sql);
								//insertion du numéro dans la table temporaire
								while ($u<1) {
									if ($nbtab23==0) {
										$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$row2[3]."','".$row2[2]."','".date("Y-m-d")."')";
										$insertnum=$dbh->exec($sql);
										if (!empty($insertnum))  $u=1;
										else $numeroassemble=numero(2);
									}
									elseif (!in_array($numeroassemble,$tab23)) {
										$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$row2[3]."','".$row2[2]."','".date("Y-m-d")."')";
										$insertnum=$dbh->exec($sql);
										if (!empty($insertnum))  $u=1;
										else $numeroassemble=numero(2);
									}
									else $numeroassemble=numero(2);
								}

								$numerocomplet="";
								$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='2' ORDER BY num_id_numero";
								$resultat25=$dbh->query($sql);
								while ($row25=$resultat25->fetch(PDO::FETCH_NUM)) {
									if ($row25[0]=="{FIXE}") $numerocomplet.=$row25[1];
									elseif ($row25[0]=="{EQUIPE}") {
										$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe='$row2[2]'";
										$result26=$dbh->query($sql);
										$row26=$result26->fetch(PDO::FETCH_NUM);
										$numerocomplet.=$row26[0];
									}
									elseif ($row25[0]=="{TYPE}") {
										$sql="SELECT * FROM type";
										$resultat27=$dbh->query($sql);
										while($row27=$resultat27->fetch(PDO::FETCH_NUM)) {
											$tab27[$row27[0]]=$row27[2];
										}
										switch ($row2[3]) {
											case 1 : $numerocomplet.=$tab27[1];
											break;
											case 2 : $numerocomplet.=$tab27[2];
											break;
											case 3 : $numerocomplet.=$tab27[3];
											break;
										}
									}
								elseif ($row25[0]=="{BOITE}") {
										$tab28=explode("@",$numeroassemble);
										list($boite,$numpostemp)=$tab28;
										$numerocomplet.=$boite;
									}
									elseif ($row25[0]=="{COORDONEE}") {
										$tab29=explode("@",$numeroassemble);
										list($boitetemp,$coordon)=$tab29;
										$numerocomplet.=$coordon;
									}
								elseif ($row25[0]=="{NUMERIC}") {
										if (preg_match("/@/",$numeroassemble)){
											$tab30=explode("@",$numeroassemble);
											list($boitetemp,$numeric)=$tab30;
											$numerocomplet.=$numeric;
										}
										else  $numerocomplet.=$numeroassemble;
									}
								}
								$suivi.=$_SESSION['nom']." ".$row[3]."@".date("Y-m-d H:i:s")."@NUMERO@".$row2[4]."\n";
								$sql="UPDATE produit SET pro_numero='$numerocomplet',pro_num_boite='0',pro_num_position='0',pro_num_incremental='0',pro_num_sansmasse='$numeroassemble', pro_suivi_modification='".$suivi."' WHERE pro_num_constant='".$numlocal."'";
								$upt=$dbh->exec($sql);
							}
						}
					}
				}

				else {
					$sql="SELECT pro_num_constant FROM produit,structure WHERE produit.pro_id_structure=structure.str_id_structure and str_inchi_md5 in (SELECT str_inchi_md5 FROM produit,structure WHERE pro_num_constant=$numlocal and produit.pro_id_structure=structure.str_id_structure);";
					$recherchedoublon=$dbh->query($sql);

					$doublon='';
					while($rowdoublon=$recherchedoublon->fetch(PDO::FETCH_NUM)) {
						$doublon.=$rowdoublon[0].", ";
					}
					$erreur.=ERROREVOCSV3.$numlocal.ERROREVOCSV4.$masse.ERROREVOCSV5.$doublon."<br/>";
				}
			}
			else 	$erreur.=ERROREVOCSV3.$numlocal.ERROREVOCSV4.$masse.ERROREVOCSV6."<br/>";
			unset ($numlocal,$masse);
		}
	}
	if(empty($erreur)) {
		$ssmenu=12;
		echo "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".TRANSREUSSI1."</p>";
	}
	else include_once 'importevo.php';
}
else require 'deconnexion.php';
unset($dbh);
?>
