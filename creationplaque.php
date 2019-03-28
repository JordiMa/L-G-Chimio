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
include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'presentation/entete.php';
$menu=6;
include_once 'presentation/gauche.php';
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
include 'corps/numero.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe,chi_prenom FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result=$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	if (!empty($_POST["numero"]) and !empty($_POST["L"]) and !empty($_POST["H"]) and !empty($_POST["id"])) {
		//transforme la variable numerique $_POST["L"] en une variable comprise entre a et h
		$x="a";
		for($i=2;$i<10;$i++) {
			if ($i==$_POST["L"]) $positionl=$x;
			$x++;
		}
		if (isset($_POST["up"]) and $_POST["up"]==1) $sql="UPDATE position SET pos_mass_prod='".$_POST["massplaque"]."',pos_id_produit='".$_POST["numero"]."'  WHERE pos_id_plaque='".$_POST["id"]."' and pos_coordonnees='".$positionl.$_POST["H"]."'";
		else {
			if ($_POST['massety']==2) $sql="INSERT INTO position (pos_id_plaque,pos_id_produit,pos_coordonnees,pos_mass_prod) VALUES ('".$_POST["id"]."','".$_POST["numero"]."','".$positionl.$_POST["H"]."','".$_POST["massprod"]."')";
			else $sql="INSERT INTO position (pos_id_plaque,pos_id_produit,pos_coordonnees) VALUES ('".$_POST["id"]."','".$_POST["numero"]."','".$positionl.$_POST["H"]."')";
		}
		$insert=$dbh->exec($sql);
		
		if (isset($_POST['massetran']) and $_POST['massetran']==1) {
			//Modification de la masse dans la table produit et insertion dans l'historique
			$sql="SELECT pro_masse,pro_suivi_modification,pro_id_equipe,pro_id_type,pro_numero FROM produit WHERE pro_id_produit='".$_POST["numero"]."'";
			$resultat2=$dbh->query($sql);
			$row2=$resultat2->fetch(PDO::FETCH_NUM);
			
			$suivi=$row2[1];
			$masse=$row2[0]-$_POST['massplaque'];
			if ($masse<0) $masse=0;
			$suivi.=$_SESSION['nom']." ".$_POST['massplaque']."@".date("Y-m-d H:i:s")."@MASSE@".$row2[0]."\n";
			$sql="UPDATE produit SET pro_masse='".$masse."', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$_POST["numero"]."'";
			$upd=$dbh->exec($sql);
		
			//si la masse tombe à 0mg alors le numéro du produit est changé pour le type sans masse
			if ($row2[0]-$row3[0]<1) {
				$sql="SELECT para_stock,para_numerotation  FROM parametres";
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
					$sql="UPDATE produit SET pro_numero='$numerocomplet',pro_num_boite='0',pro_num_position='',pro_num_incremental='0',pro_num_sansmasse='$numeroassemble', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$_POST["numero"]."'";
					$upt=$dbh->exec($sql);
				}
			}
		}
	}
	elseif (!empty($_POST["id"]) and !empty($_FILES['filecoor']['tmp_name']) and !$_FILES['filecoor']['error']) {
		$mimetype=array("text/comma-separated-values", "text/csv", "application/csv", "application/excel", "application/vnd.ms-excel", "application/vnd.msexcel", "text/anytext");
		if (in_array($_FILES['filecoor']['type'],$mimetype)) {
			if ($ft=fopen($_FILES['filecoor']['tmp_name'],"r")) {
				while (!feof ($ft)) {
					$tabligne=fgetcsv($ft,1024,";");
					$num = count($tabligne);
					$numid="";
					$masspuit="";
					$coor="";
					//reconnaissance du type d'information : numéro - position - masse
					for ($c=0; $c<$num; $c++) {
						if (strlen($tabligne[$c])==8 and !preg_match('/[a-zA-Z]/',$tabligne[$c])) $numid=$tabligne[$c];
						elseif (strlen($tabligne[$c])>=2 and strlen($tabligne[$c])<=3 and preg_match('/[a-hA-H]/',$tabligne[$c])) {
							$coor=strtolower($tabligne[$c]);
							for ($a=1; $a<=9 ; $a++) {
								$coor=str_replace("0$a","$a",$coor);
							}
						}
						elseif (preg_match('/,|./',$tabligne[$c]) and !preg_match('/[a-hA-H]/',$tabligne[$c])) {
							if(preg_match('/,/',$tabligne[$c])) $masspuit=str_replace(",",".",$tabligne[$c]);
							else $masspuit=$tabligne[$c];
						}
						elseif (strlen($tabligne[$c])<=3 and !preg_match('/[a-zA-Z]/',$tabligne[$c])) $masspuit=$tabligne[$c];
						else $numid=$tabligne[$c];
					}
					if (!empty($numid) and !empty($coor)) {
						$sql="SELECT pro_id_produit FROM produit WHERE pro_numero='$numid' or pro_num_constant='$numid'";
						$resultat=$dbh->query($sql);
						$nbresultat=$resultat->rowCount();
						if ($nbresultat>0) {
							$numeroid=$resultat->fetch(PDO::FETCH_NUM);
							$sql="INSERT INTO position (pos_id_plaque,pos_id_produit,pos_coordonnees,pos_mass_prod) VALUES ('".$_POST["id"]."','$numeroid[0]','$coor','$masspuit')";
							$insert=$dbh->exec($sql);
							if (isset($_POST['massetran'])) {
								$sql="SELECT pro_masse,pro_suivi_modification,pro_id_equipe,pro_id_type,pro_numero FROM produit WHERE pro_id_produit='".$numeroid[0]."'";
								$resultat2=$dbh->query($sql);
								$row2=$resultat2->fetch(PDO::FETCH_NUM);
								if($_POST['massety']==2) $masse=$row2[0]-$masspuit;
								elseif($_POST['massety']==1) {
									$sql="SELECT pla_masse FROM plaque WHERE pla_id_plaque='".$_POST["id"]."'";
									$resultat3=$dbh->query($sql);
									$row3=$resultat3->fetch(PDO::FETCH_NUM);
									$masse=$row2[0]-$row3[0];
								}	
								$suivi=$row2[1];
								
								if ($masse<0) $masse=0;
								$suivi.=$_SESSION['nom']." ".$row[3]."@".date("Y-m-d H:i:s")."@MASSE@".$row2[0]."\n";
								$sql="UPDATE produit SET pro_masse='".$masse."', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$numeroid[0]."'";
								$upd=$dbh->exec($sql);
					
								//si la masse tombe à 0mg alors le numéro du produit est changé pour le type sans masse
								if ($row2[0]-$row3[0]<1) {
									$sql="SELECT para_stock,para_numerotation  FROM parametres";
									$result21=$dbh->query($sql);
									$row21=$result21->fetch(PDO::FETCH_NUM);
									if ($row21[1]=="AUTO") {
										$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='2' ORDER BY num_id_numero";
										$resultat24=$dbh->query($sql);
										while ($row24=$resultat24->fetch(PDO::FETCH_NUM)) {
											$tab24[]=$row24[0];
										}
										if (in_array("NUMERIC",$tab24)) {
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
										$sql="UPDATE produit SET pro_numero='$numerocomplet',pro_num_boite='0',pro_num_position='',pro_num_incremental='0',pro_num_sansmasse='$numeroassemble', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$numeroid[0]."'";
										$upt=$dbh->exec($sql);
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
else require 'deconnexion.php';
unset($dbh);
include_once 'corps/geneplaque.php';
include_once 'presentation/pied.php';
?>