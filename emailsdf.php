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
include_once 'langues/'.$_SESSION['langue'].'/lang_export.php';
include_once 'protection.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe,chi_email FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

	if(!isset($_POST["masvrac"])) $_POST["masvrac"]="";

    //définition du numéro utilisé pour l'exportation
    $sql="SELECT para_num_exportation,para_acronyme,para_email_national,para_nom_labo FROM parametres";
    $resultat=$dbh->query($sql);
    $row1=$resultat->fetch(PDO::FETCH_NUM);
    if ($row1[0]>0) $num="pro_num_constant";
    else $num="pro_numero";

    if (!empty($_POST["typ"]) or !empty($_POST["typ1"])) {
      if (!empty($_POST["typ"])) $typeglo=$_POST["typ"];
      if (!empty($_POST["typ1"])) $typeglo=$_POST["typ1"];
      $i=0;
      $typ="and (";
      foreach ($typeglo as $elemtyp) {
        if ($i>0 and $i<count($typeglo)) $typ.=" or ";
          $typ.="pro_id_type='$elemtyp'";
          $i++;
      }
      $typ.=")";
    }
	else $typ="";

    if (!empty($_POST["equipe"])) {
      $i=0;
      $equi="and (";
      foreach ($_POST["equipe"] as $elemequi) {
        if ($i>0 and $i<count($_POST["equipe"])) $equi.=" or ";
          $equi.="pro_id_equipe='$elemequi'";
          $i++;
      }
      $equi.=")";
    }
	else $equi="";

    if (!empty($_POST["etudiant"])) {
      $i=0;
      $etu="and (";
      foreach ($_POST["etudiant"] as $elemetu) {
      //                                                                                                                                                                                                                                      0
          if ($i>0 and $i<count($_POST["etudiant"])) $etu.=" or ";
          $etu.="pro_id_chimiste='$elemetu'";
          $i++;
      }
      $etu.=")";
    }
	else $etu="";

    if (!empty($_POST["sup"])) $supglo=rawurldecode($_POST["sup"]);
    if (!empty($_POST["sup1"])) $supglo=rawurldecode($_POST["sup1"]);
    if (!empty($_POST['massemini'])) $massglo=rawurldecode($_POST['massemini']);
	else $massglo=0;
    if (!empty($_POST['massemini1'])) $massglo=rawurldecode($_POST['massemini1']);
	else $massglo=0;
	if($_POST["masvrac"]==2) $sql="SELECT distinct(pro_id_produit), pro_id_structure FROM produit WHERE pro_masse ".$supglo.$massglo." $typ $equi $etu UNION SELECT distinct(pro_id_produit), pro_id_structure FROM position, produit WHERE pos_id_produit = pro_id_produit $typ $equi AND pro_id_produit NOT IN (SELECT pro_id_produit FROM produit WHERE pro_masse ".$supglo.$massglo." $typ $equi $etu) ORDER BY pro_id_structure ASC";
 	else $sql="SELECT distinct(pro_id_produit), pro_id_structure FROM produit WHERE pro_masse ".$supglo.$massglo." $typ $equi $etu";
    $op="";
	$i=1;
	$resultat2=$dbh->query($sql);
    $y=0;
	$num1=$resultat2->rowCount();
    while($row2=$resultat2->fetch(PDO::FETCH_NUM)) {
		$sql="SELECT str_mol,pro_masse,$num,pro_purete,pro_methode_purete,pro_origine_substance FROM structure,produit WHERE str_id_structure='".$row2[1]."' and pro_id_produit=$row2[0] and structure.str_id_structure=produit.pro_id_structure";
		$resultat3=$dbh->query($sql);
		while ($row3=$resultat3->fetch(PDO::FETCH_NUM)) {
			$op.=$row3[0];
			$op.=">  <identificateur> ($i)\n".$row3[2];
			if ($row3[1]>0) $op.="\n\n>  <vrac> ($i)\n".$row3[1]; 
			$sql="SELECT pla_identifiant_local, pla_id_plaque FROM position,plaque WHERE pos_id_produit='$row2[0]' and pla_id_plaque_mere='0' and position.pos_id_plaque=plaque.pla_id_plaque";
			$resultat5=$dbh->query($sql);
			$num5=$resultat5->rowCount();
			$sql="SELECT * FROM evotec,produit WHERE pro_id_produit='$row2[0]' and produit.pro_num_constant=evotec.evo_numero_permanent";
			$resultat51=$dbh->query($sql);
			$num51=$resultat51->rowCount();
			if ($num5>0 or $num51>0) {
				$optppla="\n\n>  <plaque> ($i)\n";
				$pla="";
				$u=1;
				if ($num5>0) {
					while($row5=$resultat5->fetch(PDO::FETCH_NUM)) {
						$sql="SELECT lot_num_lot FROM lotplaque,lot WHERE lopla_id_plaque='".$row5[1]."' and lotplaque.lopla_id_lot=lot.lot_id_lot";
						$resultat6=$dbh->query($sql);
						$num6=$resultat6->rowCount();
						if ($num6>0) {
							$y=1;
							while($row6=$resultat6->fetch(PDO::FETCH_NUM)) {
								$pla.=$row6[0];
								if($y<$num6) $pla.="\n";
								$y++;
							}
						}
						else {
							$pla.=$row5[0];
							if ($u<$num5) $pla.="\n";
							$u++;
						}
					}
				}
				if ($num5>0 and $num51>0) $pla.="\n"; 
				if ($num51>0) $pla.="Evotec";		
				$op.="\n\n>  <plaque> ($i)\n".$pla;
			}
			if ($row3[3]!=0) $op.="\n\n>  <purete> ($i)\n".$row3[3];
			if (!empty($row3[4])) $op.="\n\n>  <methode_mesure_purete> ($i)\n".$row3[4];
			if (!empty($row3[5])) $op.="\n\n>  <origine> ($i)\n".$row3[5];		
                
			$op.="\n\n$$$$\n";
			$i++;
		}
	}


	$nomfichier="sdf-$row1[1].sdf.gz";
	$zp = gzopen(REPTEMP.$nomfichier, "w9");
	gzwrite($zp, $op);
	gzclose($zp);


	$boundary="-----=".md5(uniqid (rand()));
	if($_POST["temail"]==2) $email="$row1[2]";
	elseif($_POST["temail"]==1) $email="$row[3]";
	$message="--$boundary\n";
	$message.="Content-Type: text/plain; charset=\"UTF-8\"\n";
	$message.="Content-Transfer-Encoding: 8bit\n";
	$message.="\n";
	if($_POST["temail"]==2) $message.="Bonjour,\n\nVoici en fichier attaché la mise à jour de la chimiothèque du laboratoire : ".$row1[3].".\nCette mise à jour contient ".$num1." molécules.\n\nBien cordialement.\n\n";
	elseif($_POST["temail"]==1) $message.="Voici le fichier de votre exportation. Il y a  ".$num1." molécules\n\n";
	$fp=fopen(REPTEMP.$nomfichier,"rb");
	$attachment=fread($fp,filesize(REPTEMP.$nomfichier));
	$attachment=chunk_split(base64_encode($attachment));
	$message.="--$boundary\n";
	$message.="Content-Type: application/x-gzip .gz; name=\"$nomfichier\"\n";
	$message.="Content-Transfer-Encoding: base64\n";
	$message.="Content-Disposition: attachment; filename=\"$nomfichier\"\n\n";
	$message."\n";
	$message.=$attachment."\n";
	$message.="\n";
	$message.="--$boundary\n";
	$message.="\n";
	if($_POST["temail"]==2) $headers="Disposition-Notification-To: ".$row[3]."\n";
	else $headers="";
	$headers.="Date: ".date("D, d M Y H:i:s")."\n";
	$headers.="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\n";
	$headers.="From: ".$row[3]."\n";
	if($_POST["temail"]==2) $headers.="Cc: ".$row[3]."\n";
	$headers.="X-Accept-Language: fr\n";
	$headers.="Mime-Version: 1.0\n";
	$headers.="Content-Type: text/plain; charset=\"UTF-8\"\n";
	$headers.="Content-Transfer-Encoding: 8bit\n";
	$sujet="Chimiothèque";
	$headers.="\n";
	$resultat=mail($email,$sujet,$message,$headers);

	if ($resultat) print"<div align=\"center\"><h3>".REUSSI."</h3></div>";
	else print"<div align=\"center\"><h3>".ECHEC."</h3></div>";
}
else require 'deconnexion.php';
unset($dbh);
unlink (REPTEMP.$nomfichier);
?>