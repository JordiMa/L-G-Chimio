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
/*version 1.2.2*/
include_once 'script/secure.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_plaque.php';

echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

	if(!isset($_GET['id'])) $_GET['id']="";
	if(!isset($_POST['id'])) $_POST['id']="";
	if(!isset($_POST['numplaque'])) $_POST['numplaque']="";
	if(!isset($_POST['numextplaque'])) $_POST['numextplaque']="";
	if(!isset($_POST['conplaque'])) $_POST['conplaque']="0.00";
	if(!isset($_POST['solvantplaque'])) $_POST['solvantplaque']="";
	if(!isset($_POST['jour'])) $_POST['jour']="";
	if(!isset($_POST['mois'])) $_POST['mois']="";
	if(!isset($_POST['annee'])) $_POST['annee']="";
	if(!isset($_POST['massplaque'])) $_POST['massplaque']="0.00";
	if(!isset($_POST['plaquefil'])) $_POST['plaquefil']="0";
	if(!isset($_POST['unitevol'])) $_POST['unitevol']="";
	if(!isset($_POST['unitevolprel'])) $_POST['unitevolprel']="";
	if(!isset($_POST['volprel'])) $_POST['volprel']="0.00";
	if(isset($_POST['massety'])) $_GET['massety']=$_POST['massety'];
	else $_GET['massety']='';

	if ($_POST['numplaque']!='' and $_POST['solvantplaque']!='' and $_POST['volplaque']!='' and $_POST['jour']!='' and $_POST['mois']!='' and $_POST['annee']!='') {

		//enlève la , et remplace par un . dans les variables $_POST['conplaque'] et $_POST['volplaque']
		$_POST['conplaque']=str_replace(",",".",$_POST['conplaque']);
		$_POST['volplaque']=str_replace(",",".",$_POST['volplaque']);
		$_POST['massplaque']=str_replace(",",".",$_POST['massplaque']);

		$sql="INSERT INTO plaque (pla_concentration,pla_date,pla_identifiant_local,pla_volume,pla_id_solvant,pla_unite_volume,pla_id_plaque_mere,pla_masse,pla_volume_preleve,pla_unite_vol_preleve,pla_identifiant_externe) VALUES ('".addslashes($_POST['conplaque'])."','".addslashes($_POST['annee'])."-".addslashes($_POST['mois'])."-".addslashes($_POST['jour'])."','".addslashes($_POST['numplaque'])."','".addslashes($_POST['volplaque'])."','".addslashes($_POST['solvantplaque'])."','{".addslashes($_POST['unitevol'])."}','".addslashes($_POST['plaquefil'])."','".addslashes($_POST['massplaque'])."','".addslashes($_POST['volprel'])."','{".addslashes($_POST['unitevolprel'])."}','".addslashes($_POST['numextplaque'])."')";
		$insert=$dbh->exec($sql);
		$error=$dbh->errorInfo();
		$id=$dbh->lastInsertId('plaque_pla_id_plaque_seq');
		if (!empty($_POST['numlot'])) {
			$sql="INSERT INTO lot (lot_num_lot) VALUES ('".addslashes($_POST['numlot'])."')";
			$resultatlot=$dbh->exec($sql);
			$idlot=$dbh->lastInsertId();
			$sql="INSERT INTO lotplaque (lopla_id_lot,lopla_id_plaque) VALUES('$idlot','$id')";
			$resultatlotplaque=$dbh->exec($sql);
		}
		if (isset($_POST['plaquefil']) and !empty($_POST['plaquefil'])) {
			$sql="SELECT pos_id_produit,pos_coordonnees FROM position WHERE pos_id_plaque='".$_POST['plaquefil']."'";
			$resultatpos=$dbh->query($sql);
			while($rowpos=$resultatpos->fetch(PDO::FETCH_NUM)) {
				$sql="INSERT INTO position (pos_id_produit,pos_coordonnees,pos_id_plaque) VALUES ('".addslashes($rowpos[0])."','".addslashes($rowpos[1])."','".addslashes($id)."')";
				$insert=$dbh->exec($sql);
			}
		}
		if (!empty($_POST["boitetotal"])) {
			parse_str($_POST["boitetotal"]);
			$requetesql="";
			if (isset($equipe)) $requetesql.="pro_id_equipe=".$equipe." and ";
			if (isset($type)) $requetesql.="pro_id_type=".$type." and ";
			if (isset($boite)) $requetesql.="pro_num_boite=".$boite." and ";
			if (isset($incremental)) $requetesql.="pro_id_equipe=".$incremental." and ";
			$requetesql=rtrim($requetesql);
			$requetesql=preg_replace("/and$/","",$requetesql);
			$sql="SELECT pro_id_produit FROM produit WHERE $requetesql ORDER BY pro_numero";
			$resultat1=$dbh->query($sql);
			$position=2;
			$pp="a";
			$numoposition=$pp.$position;
			while($row1=$resultat1->fetch(PDO::FETCH_NUM)) {
				$sql="INSERT INTO position (pos_id_plaque,pos_id_produit,pos_coordonnees) VALUES ('".addslashes($id)."','".addslashes($row1[0])."','".addslashes($numoposition)."')";
				$insert=$dbh->query($sql);
				if ($position==11) {
					$pp++;
					$position=2;
					$numoposition=$pp.$position;
				}
				else {
					$position++;
					$numoposition=$pp.$position;
				}

				//Modification de la masse dans la table produit et insertion dans l'historique
				if ($_POST['massety']==1 and (isset($_POST['massetran']) and $_POST['massetran']==1) and !empty($_POST['massplaque'])) {

					$sql="SELECT pro_masse,pro_suivi_modification,pro_id_equipe,pro_id_type,pro_numero FROM produit WHERE pro_id_produit='".$row1[0]."'";
					$resultat2=$dbh->query($sql);
					$row2=$resultat2->fetch(PDO::FETCH_NUM);
					$suivi=$row2[1];
					$masse=$row2[0]-$_POST['massplaque'];
					if ($masse<0) $masse=0;
					$suivi.=$_SESSION['nom']." ".$_POST['massplaque']."@".date("Y-m-d H:i:s")."@MASSE@".$row2[0]."\n";
					$sql="UPDATE produit SET pro_masse='".$masse."', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$row1[0]."'";
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
								if ($row25[0]=="FIXE") $numerocomplet.=$row25[1];
								elseif ($row25[0]=="EQUIPE") {
									$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe='$row2[2]'";
									$result26=$dbh->query($sql);
									$row26=$result26->fetch(PDO::FETCH_NUM);
									$numerocomplet.=$row26[0];
								}
								elseif ($row25[0]=="TYPE") {
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
								elseif ($row25[0]=="BOITE") {
									$tab28=explode("@",$numeroassemble);
									list($boite,$numpostemp)=$tab28;
									$numerocomplet.=$boite;
								}
								elseif ($row25[0]=="COORDONEE") {
									$tab29=explode("@",$numeroassemble);
									list($boitetemp,$coordon)=$tab29;
									$numerocomplet.=$coordon;
								}
								elseif ($row25[0]=="NUMERIC") {
									if (preg_match("/@/",$numeroassemble)){
										$tab30=explode("@",$numeroassemble);
										list($boitetemp,$numeric)=$tab30;
										$numerocomplet.=$numeric;
									}
									else  $numerocomplet.=$numeroassemble;
								}
							}
							$suivi.=$_SESSION['nom']." ".$row[3]."@".date("Y-m-d H:i:s")."@NUMERO@".$row2[4]."\n";
							$sql="UPDATE produit SET pro_numero='$numerocomplet',pro_num_boite='',pro_num_position='',pro_num_incremental='',pro_num_sansmasse='$numeroassemble', pro_suivi_modification='".$suivi."' WHERE pro_id_produit='".$_POST["numero"]."'";
							$upt=$dbh->exec($sql);
						}
					}
				}
				//fin de la modification de la masse
				if ($_POST['massety']==2) {

				}
			}
		}
	}
	if (isset($_POST['massetran']) and $_POST['massetran']==1) $massetran=$_POST['massetran'];
	if (isset($_GET['massetran']) and $_GET['massetran']==1) $massetran=$_GET['massetran'];
	if ($_GET["id"]>0) $id=$_GET["id"];
	if ($_POST["id"]>0) $id=$_POST["id"];

	print "<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"plaques.php\">".CREA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"gestionplaque.php\">".GESTION."</a></td>
		</tr>
		</table>";

    print "<div id=\"dhtmltooltip\"></div>
		<script language=\"javascript\" src=\"ttip.js\"></script>
		<script type=\"text/JavaScript\">
		<!--
		function MM_swapImgRestore() { //v3.0
		  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
		}

		function MM_findObj(n, d) { //v4.01
		  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		  if(!x && d.getElementById) x=d.getElementById(n); return x;
		}

		function MM_swapImage() { //v3.0
		  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
		   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
		}
		//-->
		</script>";
    $sql="SELECT pla_concentration,pla_volume,pla_unite_volume,pla_masse,pla_identifiant_local,pla_identifiant_externe FROM plaque,solvant WHERE pla_id_plaque='$id' and plaque.pla_id_solvant=solvant.sol_id_solvant";
	// echo $sql;
	$resultplaque=$dbh->query($sql);
	$rowplaque=$resultplaque->fetch(PDO::FETCH_NUM);
	print"<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" width=\"100%\">
    <tr>
	<td width=\"25%\"><strong>".NUMERO."</strong><br/> $rowplaque[4]<br/>
	<strong>".NUMEROEVOTEC."</strong><br/> $rowplaque[5]<br/>";
	if ($rowplaque[0]>0)  print"<strong>".CONCENTRATION."</strong><br/> $rowplaque[0] ".MOL."<br/>";
	if ($rowplaque[1]>0) {
		$search= array('{','}');
		$rowplaque[2]=str_replace($search,'',$rowplaque[2]);
		print"<strong>".VOLUME."</strong><br/> $rowplaque[1] ".constant($rowplaque[2])."<br/>";
	}
	if ($rowplaque[3]>0) print"<strong>".MASSE."</strong><br/> $rowplaque[3] ".MG."</td>";

    print"<td align=\"center\" valign=\"middle\">";
    print"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"489\">
		  <tr>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"64\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"37\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"55\" height=\"1\" border=\"0\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"1\" height=\"1\" border=\"0\" alt=\"\" /></td>
		  </tr>

		  <tr>
		   <td colspan=\"12\" class=\"plaque\"><img name=\"plaque_r1_c1\" src=\"images/plaque/plaque_r1_c1.gif\" width=\"489\" height=\"26\" border=\"0\" id=\"plaque_r1_c1\" alt=\"\" /></td>
		   <td class=\"plaque\"><img src=\"spacer.gif\" width=\"1\" height=\"26\" border=\"0\" alt=\"\" /></td>
		  </tr>
		  <tr>
		   <td rowspan=\"9\" class=\"plaque\"><img name=\"plaque_r2_c1\" src=\"images/plaque/plaque_r2_c1.gif\" width=\"64\" height=\"318\" border=\"0\" id=\"plaque_r2_c1\" alt=\"\" /></td>";

	//formulaire pour charger un fichier CSV
	$sql="SELECT * FROM position WHERE pos_id_plaque=$id";
    $resultatcount=$dbh->query($sql);
    $numresultatplaque=$resultatcount->rowCount();
	$rowplaque=$resultatcount->fetch(PDO::FETCH_NUM);
	$x="a";
    for ($i=2 ; $i<=9 ; $i++) {
        if ($i>2) print"<tr>";
        for ($y=2 ; $y<=11; $y++) {
			if (isset($_GET['massety']) and $_GET['massety']==2) $sql="SELECT str_nom,pro_numero,equi_nom_equipe,chi_nom,chi_prenom,pro_id_chimiste,pro_id_equipe,pos_id_produit,pos_mass_prod FROM position,produit,structure,equipe,chimiste WHERE pos_coordonnees='$x$y' and pos_id_plaque=$id and position.pos_id_produit=produit.pro_id_produit and produit.pro_id_structure=structure.str_id_structure and produit.pro_id_equipe=equipe.equi_id_equipe and produit.pro_id_chimiste=chimiste.chi_id_chimiste";
			else $sql="SELECT str_nom,pro_numero,equi_nom_equipe,chi_nom,chi_prenom,pro_id_chimiste,pro_id_equipe,pos_id_produit FROM position,produit,structure,equipe,chimiste WHERE pos_coordonnees='$x$y' and pos_id_plaque=$id and position.pos_id_produit=produit.pro_id_produit and produit.pro_id_structure=structure.str_id_structure and produit.pro_id_equipe=equipe.equi_id_equipe and produit.pro_id_chimiste=chimiste.chi_id_chimiste";
			$resultat=$dbh->query($sql);
			$numresultat=$resultat->rowCount();
			if (!empty($_GET["L"]) and !empty($_GET["H"]) and !empty($id) and $_GET["L"]==$i and $_GET["H"]==$y) {
				print"<td class=\"plaque\"><img src=\"images/plaque/plaque_r".$i."_c".$y.".gif\" width=\"37\" height=\"37\" border=\"0\" /></td>\n";
            }
			else {
				if ($numresultat>0) {
					$row=$resultat->fetch(PDO::FETCH_NUM);
					//traitement du nom de la molécule
					$nommol="";
					if (preg_match('/\^{/',$row[0])) {
						$tabnom=preg_split("/\^{/",$row[0]);
						$numnom=count($tabnom);
						for ($h=0; $h<$numnom; $h++) {
							if (preg_match("/}/",$tabnom[$h])) {
								$tabnom[$h]=str_replace("}","</sup>",$tabnom[$h]);
								$tabnom[$h]="<sup>".$tabnom[$h];
							}
							$nommol.=$tabnom[$h];
						}
					}
					else $nommol=$row[0];
					if (isset($_GET['massety']) and $_GET['massety']==2 and $row[8]==0) {
						if ($_POST['massetran']==1 or $_GET['massetran']==1) print"<td><a href=\"creationplaque.php?L=$i&H=$y&id=$id&massety=".$_GET["massety"]."&equipechi=$row[6]&chimiste=$row[5]&numero=$row[7]&up=1&massetran=1\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<p>".PUITVIDE."</p>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."r.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
						else print"<td><a href=\"creationplaque.php?L=$i&H=$y&id=$id&massety=".$_GET["massety"]."&equipechi=$row[6]&chimiste=$row[5]&numero=$row[7]&up=1\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<p>".PUITVIDE."</p>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."r.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
					}
					else {
						print"<td class=\"plaque\"><a href=\"#\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<table width=\'100%\' border=\'0\' cellpadding=\'0\' cellspacing=\'0\'><tr><td align=\'center\'>";
						print"</td></tr><tr><td align=\'center\'>".addslashes($row[2])."<br/>".addslashes($row[3])." ".addslashes($row[4])."<br/>".addslashes($row[1])."</td></tr><tr><td align=\'center\'>".addslashes($nommol)."</td></tr>";
						if ($_GET['massety']==2) {
							$row[8]=str_replace(".",",",$row[8]);
							print"<tr><td align=\'center\'>".$row[8]." ".MG."</td></tr>";
						}
						print"</table>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."v.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
					}
				}
				else {
					print"<td class=\"plaque\"><a href=\"creationplaque.php?L=$i&H=$y&id=$id&massety=".$_GET["massety"];
					if (isset($massetran) and $massetran==1) print"&massetran=$massetran";
					if ($numresultatplaque>0) {
						if ($rowplaque[3]==0 and $_GET["massety"]==2) print"\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<p>".PUITVIDE."</p>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."r.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
						else print"\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<p>".PUITVIDE1."</p>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."r.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
					}
					else print"\" onmouseout=\"hideddrivetip(),MM_swapImgRestore()\" onmouseover=\"ddrivetip('<p>".PUITVIDE."</p>'),MM_swapImage('Image$i$y','','images/plaque/plaque_r".$i."_c".$y.".gif',1)\"><img src=\"images/plaque/plaque_r".$i."_c".$y."r.gif\" name=\"Image$i$y\" width=\"37\" height=\"37\" border=\"0\" id=\"Image$i$y\" /></a></td>\n";
				}
				if ($i==2 and $y==11) print"<td rowspan=\"9\"><img name=\"plaque_r2_c12\" src=\"images/plaque/plaque_r2_c12.gif\" width=\"55\" height=\"318\" border=\"0\" id=\"plaque_r2_c12\" /></td>";
			}
        }

        print"<td class=\"plaque\"><img src=\"images/plaque/spacer.gif\" width=\"1\" height=\"37\" border=\"0\" /></td>
				</tr>\n";
		$x++;
	}

    print"<tr>
		   <td colspan=\"10\"><img name=\"plaque_r10_c2\" src=\"images/plaque/plaque_r10_c2.gif\" width=\"370\" height=\"22\" border=\"0\" id=\"plaque_r10_c2\" alt=\"\" /></td>
		   <td><img src=\"images/plaque/spacer.gif\" width=\"1\" height=\"22\" border=\"0\" alt=\"\" /></td>
		  </tr>
		</table></td>";
    if (!empty($_GET["L"]) and !empty($_GET["H"]) and !empty($id)) {
		print"<td valign=\"middle\">";
		if(!isset($_GET['equipechi'])) $_GET['equipechi']="";
		if(!isset($_GET['chimiste'])) $_GET['chimiste']="";
		if(!isset($_GET['numero'])) $_GET['numero']="";
		//initialisation du formulaire
		$formulaire=new formulaire ("insertproduit","creationplaque.php","GET",true);
		$formulaire->affiche_formulaire();
		$sql="SELECT equi_id_equipe, equi_nom_equipe FROM equipe, produit WHERE produit.pro_id_equipe = equipe.equi_id_equipe AND pro_id_equipe IN ( SELECT DISTINCT (pro_id_equipe) FROM produit) GROUP BY equi_id_equipe ORDER BY equi_nom_equipe";
		//les résultats sont retournées dans la variable $result1
		$result1 =$dbh->query($sql);
		$nbrow1=$result1->rowCount();
		if (!empty($nbrow1)) {
			while($row1=$result1->fetch(PDO::FETCH_NUM)) {
				$tab1[$row1[0]]=$row1[1];
			}
			$formulaire->ajout_select (1,"equipechi",$tab1,false,$_GET['equipechi'],SELECTEQUIPE,EQUIPE."<br/>",false,"onChange=submit()");
		}
		$formulaire->ajout_cache ($_GET['chimiste'],"chimiste");
		$formulaire->ajout_cache ($_GET['massety'],"massety");
		$formulaire->ajout_cache ($_GET["L"],"L");
		$formulaire->ajout_cache ($_GET["H"],"H");
		$formulaire->ajout_cache ($id,"id");

		if (isset($massetran) and $massetran==1) $formulaire->ajout_cache ($massetran,"massetran");
		//fin du formulaire
		$formulaire->fin();


		//initialisation du formulaire
		$formulaire1=new formulaire ("insertproduit","creationplaque.php","GET",true);
		$formulaire1->affiche_formulaire();
		if (empty($_GET['equipechi'])) $sql="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste, produit WHERE produit.pro_id_chimiste = chimiste.chi_id_chimiste AND pro_id_chimiste IN (SELECT DISTINCT pro_id_chimiste FROM produit) GROUP BY chi_id_chimiste ORDER BY chi_nom";
		else $sql="SELECT chi_id_chimiste,chi_nom,chi_prenom FROM chimiste,produit WHERE pro_id_equipe='".$_GET['equipechi']."' and chimiste.chi_id_chimiste=produit.pro_id_chimiste ORDER BY chi_nom";
		//les résultats sont retournées dans la variable $result2
		$result2 =$dbh->query($sql);
		$nbrow2=$result2->rowCount();
		if (!empty($nbrow2)) {
			while($row2=$result2->fetch(PDO::FETCH_NUM)) {
				$tab2[$row2[0]]=$row2[1];
			}
			$formulaire1->ajout_select (1,"chimiste",$tab2,false,$_GET['chimiste'],SELECTCHIMISTE,CHIMISTE."<br/>",false,"onChange=submit()");
		}
		$formulaire1->ajout_cache ($_GET['equipechi'],"equipechi");
		$formulaire1->ajout_cache ($_GET['massety'],"massety");
		$formulaire1->ajout_cache ($_GET["L"],"L");
		$formulaire1->ajout_cache ($_GET["H"],"H");
		$formulaire1->ajout_cache ($id,"id");
		if (isset($massetran) and $massetran==1) $formulaire1->ajout_cache ($massetran,"massetran");
		//fin du formulaire
		$formulaire1->fin();

		//initialisation du formulaire
		$formulaire2=new formulaire ("insertproduit","creationplaque.php","GET",true);
		$formulaire2->affiche_formulaire();
		$sql="SELECT pro_numero,pro_id_produit FROM produit WHERE pro_id_equipe='".$_GET['equipechi']."' and pro_id_chimiste='".$_GET['chimiste']."' and pro_masse>0 ORDER BY pro_numero";
		$result3=$dbh->query($sql);
		if (!empty($result3)) {
			$nbrow3=$result3->rowCount();
			if ($nbrow3>0) {
				while($row3=$result3->fetch(PDO::FETCH_NUM)) {
					$tab3[$row3[1]]=$row3[0];
					natsort($tab3);
				}
				$formulaire2->ajout_select (1,"numero",$tab3,false,$_GET['numero'],SELECTNUMERO,NUMEROPRO."<br/>",false,"onChange=submit()");
				$formulaire2->ajout_cache ($_GET['equipechi'],"equipechi");
				$formulaire2->ajout_cache ($_GET['chimiste'],"chimiste");
				$formulaire2->ajout_cache ($_GET['massety'],"massety");
				$formulaire2->ajout_cache ($_GET["L"],"L");
				$formulaire2->ajout_cache ($_GET["H"],"H");
				$formulaire2->ajout_cache ($id,"id");
				if (isset($massetran) and $massetran==1) $formulaire2->ajout_cache ($massetran,"massetran");
			}
		}
		//fin du formulaire
		$formulaire2->fin();

		if (!empty($_GET["numero"])) {
			if ($_GET['massety']==2) print"<script language=\"JavaScript\">
				  function verif(theForm) {
					if (document.insproduit.massplaque.value==\"\") {alert(\"".CHAMPMASSPROD."\");}
					else {theForm.submit();}
				  }
				  </script>";
			//initialisation du formulaire
			$formulaire3=new formulaire ("insproduit","creationplaque.php","POST",true);
			$formulaire3->affiche_formulaire();
			if ($_GET['massety']==2) $formulaire3->ajout_text (5,$_POST['massplaque'],5, "massplaque", MASSEPROD."<br/>",MG,"");
			$sql="SELECT str_mol FROM structure,produit WHERE pro_id_produit='".$_GET['numero']."' and produit.pro_id_structure=structure.str_id_structure";
			$resultat6=$dbh->query($sql);
			$mol=$resultat6->fetch(PDO::FETCH_NUM);
			$jsme=new visualisationmoleculejme (150,150,$mol[0]);
			$jsme->imprime();
			// $marvin=new visualisationmolecule (150,150,$mol[0],"true",'all');
			// $marvin->imprime();
			$formulaire3->ajout_cache ($_GET['numero'],"numero");
			$formulaire3->ajout_cache ($_GET['massety'],"massety");
			$formulaire3->ajout_cache ($_GET["L"],"L");
			$formulaire3->ajout_cache ($_GET["H"],"H");
			$formulaire3->ajout_cache ($id,"id");
			if (isset($_GET["up"])) $formulaire3->ajout_cache ($_GET['up'],"up");
			if (isset($massetran) and $massetran==1) $formulaire3->ajout_cache ($massetran,"massetran");
			if ($_GET['massety']==2) $formulaire3->ajout_button (SAUVEGARDE,"","button","onClick=\"verif(form)\"");
			else $formulaire3->ajout_button (SAUVEGARDE,"sauvegarde","submit","");
			//fin du formulaire
			$formulaire3->fin();
		}
		print "</td>";
	}
    print"</tr>";

    if (empty($numresultatplaque) or $numresultatplaque==0) {
		print"<tr><td colspan=\"";
		if (!empty($_GET["L"]) and !empty($_GET["H"]) and !empty($id)) echo "3";
		else echo "2";
		print"\"><hr></td></tr><tr><td colspan=\"";
		if (!empty($_GET["L"]) and !empty($_GET["H"]) and !empty($id)) echo "3";
		else echo "2";
		print"\">";
		//initialisation du formulaire
		$formulaire4=new formulaire ("insertproduit","creationplaque.php","POST",true);
		$formulaire4->affiche_formulaire();
		$formulaire4->ajout_file (30, "filecoor",true,CHARGERCOO."<br/>","");
		$formulaire4->ajout_cache ($id,"id");
		$formulaire4->ajout_cache ($_GET['massety'],"massety");
		if (isset($massetran) and $massetran==1) $formulaire4->ajout_cache ($massetran,"massetran");
		$formulaire4->ajout_button (SAUVEGARDE,"produnique","submit","");
		print "<a href=\"#\" onmouseover=\"ddrivetip('<p>";
		if (isset($_POST["massety"]) && $_POST["massety"]==2) echo addSlashes(AIDECSV1);
		else echo addSlashes(AIDECSV);
		print "</p>')\" onmouseout=\"hideddrivetip()\"><img border=\"0\" src=\"images/aide.gif\" /></a>";

		//fin du formulaire
		$formulaire4->fin();

		print"</td></tr>";
    }
	else {
		print"<tr><td colspan=\"";
		if (!empty($_GET["L"]) and !empty($_GET["H"]) and !empty($id)) echo "3";
		else echo "2";
		print"\"><hr></td></tr><tr><td>&nbsp;</td><td align=\"center\">";

		$sql="SELECT str_mol,pos_coordonnees FROM position,produit,structure WHERE pos_id_plaque='".$id."' and position.pos_id_produit=produit.pro_id_produit and produit.pro_id_structure=structure.str_id_structure ORDER BY pos_coordonnees";
		$resultat7=$dbh->query($sql);
		$numresultat7=$resultat7->rowCount();
		print"<table width=\"407\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<th scope=\"col\" width=\"37\">&nbsp;</th>
			<th scope=\"col\" width=\"37\">2</th>
			<th scope=\"col\" width=\"37\">3</th>
			<th scope=\"col\" width=\"37\">4</th>
			<th scope=\"col\" width=\"37\">5</th>
			<th scope=\"col\" width=\"37\">6</th>
			<th scope=\"col\" width=\"37\">7</th>
			<th scope=\"col\" width=\"37\">8</th>
			<th scope=\"col\" width=\"37\">9</th>
			<th scope=\"col\" width=\"37\">10</th>
			<th scope=\"col\" width=\"37\">11</th>
		  </tr>";


		while($row=$resultat7->fetch(PDO::FETCH_NUM)) {
			$tab7[$row[1]]=$row[0];
		}

		$i=0;
		$u="a";
		$uu=2;

		while ($i<80) {
			if ($uu==2) echo "<tr>
			<th scope=\"row\" height=\"75\">".mb_strtoupper($u)."</th>";
			print"<td>";
			if (!empty($tab7[$u.$uu])) {
				$jme=new visualisationmoleculejme (80,80,$tab7[$u.$uu]);
				$jme->imprime();
			}
			print"</td>";
			$uu++;
			if ($uu==12) {
				$uu=2;
				$u++;
				echo "</tr>";
			}
			$i++;
		}
	}
    print"</table>";
}
else require 'deconnexion.php';
unset($dbh);
?>
