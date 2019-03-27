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
include_once 'langues/'.$_SESSION['langue'].'/lang_fiche.php';
if (!empty($_GET['id'])) {
	//vérification que la session a le droit de visualiser la fiche demandée

	//appel le fichier de connexion à la base de données
	require 'script/connectionb.php';
	$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	//les résultats sont retournées dans la variable $result
	$result =$dbh->query($sql);
	$row =$result->fetch(PDO::FETCH_NUM);
	$sql="SELECT pro_id_equipe,pro_id_chimiste,pro_id_type FROM produit WHERE pro_id_produit='".$_GET['id']."'";
	//les résultats sont retournées dans la variable $result
	$result1 =$dbh->query($sql);
	$row1 =$result1->fetch(PDO::FETCH_NUM);
	if ($row1[2]==1 or $row1[2]==3 or ($row1[2]==2 and ($row[1]==$row1[1])) or ($row1[2]==2 and $row[0]="{RESPONSABLE}" and $row[2]==$row1[0])) {
		if(!isset($_GET['typechimiste'])) $_GET['typechimiste']="";
		if(!isset($_GET['chimiste'])) $_GET['chimiste']="";

		$sqluv="SELECT uv_text, uv_nom_fichier, uv_date FROM produit P
		INNER JOIN uv U
		ON P.pro_id_uv=U.uv_id_uv
		WHERE pro_id_produit='".$_GET['id']."'";
		$resultuv =$dbh->query($sqluv);
		$rowuv =$resultuv->fetch(PDO::FETCH_NUM);

		$sqlir="SELECT ir_text, ir_nom_fichier, ir_date FROM produit P
		INNER JOIN ir I
		ON P.pro_id_ir=I.ir_id_ir
		WHERE pro_id_produit='".$_GET['id']."'";
		$resultir =$dbh->query($sqlir);
		$rowir =$resultir->fetch(PDO::FETCH_NUM);

		$sqlsm="SELECT sm_text, sm_type, sm_nom_fichier, sm_date FROM produit P
		INNER JOIN sm S
		ON P.pro_id_sm=S.sm_id_sm
		WHERE pro_id_produit='".$_GET['id']."'";
		$resultsm =$dbh->query($sqlsm);
		$rowsm =$resultsm->fetch(PDO::FETCH_NUM);
		$search= array('{','}');
		$rowsm[1]=str_replace($search,'',$rowsm[1]);

		$sqlhrms="SELECT hrms_text, hrms_type, hrms_nom_fichier, hrms_date FROM produit P
		INNER JOIN hrms H
		ON P.pro_id_hrms=H.hrms_id_hrms
		WHERE pro_id_produit='".$_GET['id']."'";
		$resulthrms =$dbh->query($sqlhrms);
		$rowhrms =$resulthrms->fetch(PDO::FETCH_NUM);
		$search= array('{','}');
		$rowhrms[1]=str_replace($search,'',$rowhrms[1]);

		$sqlrmnh="SELECT rmnh_text, rmnh_nom_fichier, rmnh_date FROM produit P
		INNER JOIN rmnh R
		ON P.pro_id_rmnh=R.rmnh_id_rmnh
		WHERE pro_id_produit='".$_GET['id']."'";
		$resultrmnh =$dbh->query($sqlrmnh);
		$rowrmnh =$resultrmnh->fetch(PDO::FETCH_NUM);

		$sqlrmnc="SELECT rmnc_text, rmnc_nom_fichier, rmnc_date FROM produit P
		INNER JOIN rmnc C
		ON P.pro_id_rmnc=C.rmnc_id_rmnc
		WHERE pro_id_produit='".$_GET['id']."'";
		$resultrmnc =$dbh->query($sqlrmnc);
		$rowrmnc =$resultrmnc->fetch(PDO::FETCH_NUM);

		$sql="SELECT pro_alpha, pro_alpha_temperature, pro_alpha_concentration, pro_alpha_solvant, pro_rf, pro_rf_solvant,pro_purete,pro_methode_purete FROM produit WHERE pro_id_produit='".$_GET['id']."'";
		$result2 =$dbh->query($sql);
		$row2 =$result2->fetch(PDO::FETCH_NUM);
		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">
			<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fiche.php?id=".$_GET['id']."&menu=".$_GET['menu']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&numero=".$_GET['numero']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".STRUCTURE."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"ficheana.php?id=".$_GET['id']."&menu=".$_GET['menu']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&numero=".$_GET['numero']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&recherche=".$_GET['recherche']."&valtanimoto=".$_GET['valtanimoto']."\">".ANALYSE."</a></td>";
		print"</tr>
			</table></td><td><div align=\"center\">
			<form method=\"post\" action=\"consultation1.php\">
			<input type=\"image\" src=\"images/retour.gif\" alt=\"".RETOUR."\">
			<input type=\"hidden\" name=\"menu\" value=\"3\">
			<input type=\"hidden\" name=\"mol\" value=\"".Base64_decode($_GET['mol'])."\">
			<input type=\"hidden\" name=\"formbrute\" value=\"".$_GET['formbrute']."\">
			<input type=\"hidden\" name=\"massemol\" value=\"".$_GET['massemol']."\">
			<input type=\"hidden\" name=\"supinf\" value=\"".$_GET['supinf']."\">
			<input type=\"hidden\" name=\"massexac\" value=\"".$_GET['massexact']."\">
			<input type=\"hidden\" name=\"forbrutexact\" value=\"".$_GET['forbrutexact']."\">
			<input type=\"hidden\" name=\"numero\" value=\"".$_GET['numero']."\">
			<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">
			<input type=\"hidden\" name=\"nbrs\" value=\"".$_GET['nbrs']."\">
			<input type=\"hidden\" name=\"nbpage\" value=\"".$_GET['nbpage']."\">
			<input type=\"hidden\" name=\"typechimiste\" value=\"".$_GET['typechimiste']."\">
			<input type=\"hidden\" name=\"chimiste\" value=\"".$_GET['chimiste']."\">
			<input type=\"hidden\" name=\"recherche\" value=\"".$_GET['recherche']."\">
			<input type=\"hidden\" name=\"valtanimoto\" value=\"".$_GET['valtanimoto']."\">
			</form>
			</div>
			</td></tr></table>";
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">";
		if (!empty($rowuv[0])) $rowuv[0]=str_replace("\r","<br/>",$rowuv[0]);
		if (!empty($rowsm[0])) $rowsm[0]=str_replace("\r","<br/>",$rowsm[0]);
		else $rowsm[0]='';
		if (!empty($rowhrms[0])) $rowhrms[0]=str_replace("\r","<br/>",$rowhrms[0]);
		else $rowhrms[0]='';
		if (!empty($rowrmnh[0])) $rowrmnh[0]=str_replace("\r","<br/>",$rowrmnh[0]);
		if (!empty($rowrmnc[0])) $rowrmnc[0]=str_replace("\r","<br/>",$rowrmnc[0]);
		if (!empty($rowir[0])) $rowir[0]=str_replace("\r","<br/>",$rowir[0]);

		if (!isset($rowuv[2])) $rowuv[2] = "";
		if (!isset($rowuv[0])) $rowuv[0] = "";

		if (!isset($rowsm[3])) $rowsm[3] = "";
		if (!isset($rowsm[0])) $rowsm[0] = "";

		if (!isset($rowhrms[3])) $rowhrms[3] = "";
		if (!isset($rowhrms[0])) $rowhrms[0] = "";

		if (!isset($rowrmnc[2])) $rowrmnc[2] = "";
		if (!isset($rowrmnc[0])) $rowrmnc[0] = "";

		if (!isset($rowir[2])) $rowir[2] = "";
		if (!isset($rowir[0])) $rowir[0] = "";

		if (!isset($rowrmnh[2])) $rowrmnh[2] = "";
		if (!isset($rowrmnh[0])) $rowrmnh[0] = "";

		print"<tr><td><div style=\"width:500px; height:100px; overflow:auto; border:solid 1px black;\"><strong>".PURETE."</strong>&nbsp;".$row2[6]."&nbsp;".POURCENT."<br/><br/><strong>".METHOPURETE."</strong>&nbsp;".$row2[7]."</div></td><td>&nbsp;</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".UV."</strong><br/>".$rowuv[2]."<br/><br/>".$rowuv[0]."</div></td><td>";
		if (!empty($rowuv[1])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=uv\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".SM."</strong><br/>".$rowsm[3]."<br/><br/>".$rowsm[0]."<br/><strong>".SMTYPE."</strong>&nbsp;".$rowsm[1]."</div></td><td>";
		if (!empty($rowsm[2])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=sm\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".HRMS."</strong><br/>".$rowhrms[3]."<br/><br/>".$rowhrms[0]."<br/><strong>".SMTYPE."</strong>&nbsp;".$rowhrms[1]."</div></td><td>";
		if (!empty($rowhrms[2])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=hrms\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".RMNH."</strong><br/>".$rowrmnh[2]."<br/><br/>".$rowrmnh[0]."</div></td><td>";
		if (!empty($rowrmnh[1])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=rmnh\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".RMNC."</strong><br/>".$rowrmnc[2]."<br/><br/>".$rowrmnc[0]."</div></td><td>";
		if (!empty($rowrmnc[1])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=rmnc\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".IR."</strong><br/>".$rowir[2]."<br/><br/>".$rowir[0]."</div></td><td>";
		if (!empty($rowir[1])) print "<a href=\"telecharge.php?id=".$_GET['id']."&rank=ir\" target=\"_blank\">".FICHIERTEL."</a>";
		else print"&nbsp;";
		print"</td></tr>";
		print"<tr><td><div style=\"width:500px; height:150px; overflow:auto; border:solid 1px black;\"><p><strong>".ALPHA."</strong>&nbsp;";
		if($row2[0]!=0.0) echo $row2[0];
		print"</p><p><strong>".ALPHATEMP."</strong>&nbsp;";
		if ($row2[1]!=0.0) echo $row2[1]."&nbsp;".DEG;
		print"</p><p><strong>".ALPHACONC."</strong>&nbsp;";
		if($row2[2]!=0.0) echo $row2[2]."&nbsp;".MOL;
		print"</p><p><strong>".ALPHASOLVANT."</strong>&nbsp;";
		if(!empty($row2[3])) {
			$sql="SELECT sol_solvant FROM produit,solvant WHERE pro_id_produit='".$_GET['id']."' and pro_apha_solvant='".$row2[3]."' and produit.pro_apha_solvant=solvant.sol_id_solvant";
			$result3 =$dbh->query($sql);
			$row3 =$result3->fetch(PDO::FETCH_NUM);
			echo constant($row3[0])."&nbsp;";
		}
		print"</p></div></td><td></td></tr>";
		print"&nbsp;</td></tr>";
		print"<tr><td><div style=\"width:500px; height:120px; overflow:auto; border:solid 1px black;\"><p><strong>".CCM."</strong><br/></p><p><strong>".CCMRF."</strong>&nbsp;";
		if($row2[4]!=0.00) echo $row2[4];
		print"</p><p><strong>".CCMSOLVANT."</strong>&nbsp;".$row2[5]."</p></div></td><td></td></tr>";
		print"&nbsp;</td></tr>";
		print"</table>";
		unset($dbh);
	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>
