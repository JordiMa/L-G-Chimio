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
  if ($row[0]=="{CHEF}"){
    $sql="SELECT equi_id_equipe FROM equipe WHERE equi_id_equipe in(SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable='".$row[1]."') order by equi_nom_equipe";
    //les résultats sont retournées dans la variable $result5
    $result5 =$dbh->query($sql);
    $nbrow5=$result5->rowCount();
    $requete="";
    $i=1;
    if (!empty($nbrow5)) {
      while($row5 =$result5->fetch(PDO::FETCH_NUM)) {
        $tab5[$row5[0]]=$row5[0];
      }
    }
  }
  
  print"<div id=\"dhtmltooltip\"></div>
    <script language=\"javascript\" src=\"ttip.js\"></script>";
	
  $sql="SELECT pro_id_equipe,pro_id_chimiste FROM produit WHERE pro_id_produit='".$_GET['id']."'";
  //les résultats sont retournées dans la variable $result1
  $result1 =$dbh->query($sql);
  $row1 =$result1->fetch(PDO::FETCH_NUM);
  if (($_GET['menu']==2 and $row[0]=="{RESPONSABLE}" and $row[2]==$row1[0]) or ($_GET['menu']==2 and $row[0]=="{ADMINISTRATEUR}") or ($row[0]=="{CHEF}" and in_array($row1[0],$tab5))) {
    print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">
    <table width=\"328\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fiche.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."\">".STRUCTURE."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"ficheana.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."\">".ANALYSE."</a></td>";
    if (($row[0]=="{RESPONSABLE}" and $_GET['menu']==2 and $row[2]==$row1[0]) or ($row[0]=="{ADMINISTRATEUR}" and $menu==2) or ($row[0]=="{CHEF}" and in_array($row1[0],$tab5))) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"fichebio.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."\">".ANABIO."</a></td>";
    else print"<td width=\"82\" height=\"23\"></td>";
    if ($row[0]=="{ADMINISTRATEUR}" and $menu==2) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fichehistorique.php?id=".$_GET['id']."&menu=".$_GET['menu']."&type=".$_GET['type']."&mol=".$_GET['mol']."&formbrute=".$_GET['formbrute']."&massemol=".$_GET['massemol']."&supinf=".$_GET['supinf']."&massexact=".$_GET['massexact']."&forbrutexact=".$_GET['forbrutexact']."&page=".$_GET['page']."&nbrs=".$_GET['nbrs']."&nbpage=".$_GET['nbpage']."&typechimiste=".$_GET['typechimiste']."&chimiste=".$_GET['chimiste']."&equipechi=".$_GET['equipechi']."&numero=".$_GET['numero']."&refcahier=".$_GET['refcahier']."&recherche=".$_GET['recherche']."\">".CHANGEMENT."</a></td>";
    else print"<td width=\"82\" height=\"23\"></td>";
    print"</tr>
	</table></td><td><div align=\"center\">
	<form method=\"post\" action=\"consultation.php\">
	<input type=\"image\" src=\"images/retour.gif\">
	<input type=\"hidden\" name=\"menu\" value=\"2\">
	<input type=\"hidden\" name=\"type\" value=\"".$_GET['type']."\">
	<input type=\"hidden\" name=\"mol\" value=\"".Base64_decode($_GET['mol'])."\">
	<input type=\"hidden\" name=\"formbrute\" value=\"".$_GET['formbrute']."\">
	<input type=\"hidden\" name=\"massemol\" value=\"".$_GET['massemol']."\">
	<input type=\"hidden\" name=\"supinf\" value=\"".$_GET['supinf']."\">
	<input type=\"hidden\" name=\"massexac\" value=\"".$_GET['massexact']."\">
	<input type=\"hidden\" name=\"forbrutexact\" value=\"".$_GET['forbrutexact']."\">
	<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\">
	<input type=\"hidden\" name=\"nbrs\" value=\"".$_GET['nbrs']."\">
	<input type=\"hidden\" name=\"nbpage\" value=\"".$_GET['nbpage']."\">
	<input type=\"hidden\" name=\"typechimiste\" value=\"".$_GET['typechimiste']."\">
	<input type=\"hidden\" name=\"chimiste\" value=\"".$_GET['chimiste']."\">
	<input type=\"hidden\" name=\"equipechi\" value=\"".$_GET['equipechi']."\">
	<input type=\"hidden\" name=\"numero\" value=\"".$_GET['numero']."\">
	<input type=\"hidden\" name=\"refcahier\" value=\"".$_GET['refcahier']."\">
	<input type=\"hidden\" name=\"recherche\" value=\"".$_GET['recherche']."\">
	</form>
	</div>
	</td></tr></table>";
    $sql="SELECT res_resultat_pourcentactivite,res_resultat_ic50,cib_nom,res_commentaire,res_actif,lab_concentration,lab_protocol,lab_laboratoire,cib_uniprot,res_resultat_ec50, res_resultat_autre,cib_uniprot,res_resultat_pourcentageinhi FROM resultat,cible,labocible WHERE res_id_produit='".$_GET['id']."' and resultat.res_id_labocible=labocible.lab_id_labocible and cible.cib_id_cible=labocible.lab_id_cible";
    $result2 =$dbh->query($sql);
    $numresult2=$result2->rowCount();
    if (empty($numresult2)) print"<p align=\"center\"><br/><br/><strong>".RIEN."</strong></p>";
    else {
      print"<table width=\"100%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
      <tr>
        <th width=\"31%\" scope=\"col\" rowspan=\"2\">".CIBLE."</th>
        <th width=\"5%\" scope=\"col\" rowspan=\"2\">".ACTIF."</th>
        <th width=\"38%\" scope=\"col\" colspan=\"5\">".RESULTATS."</th>
        <th width=\"26%\" scope=\"col\" rowspan=\"2\">".COMMENTAIRE."</th>
        </tr>
        <tr>
        <th width=\"8%\" scope=\"col\">".ACTIVITE."</th>
		<th width=\"8%\" scope=\"col\">".POURINHI."</th>
        <th width=\"7%\" scope=\"col\">".IC."</th>
		<th width=\"7%\" scope=\"col\">".EC."</th>
        <th width=\"8%\" scope=\"col\">".AUTRE."</th>
        </tr>";
      while($row2 =$result2->fetch(PDO::FETCH_NUM)) {
        print"<tr>
        <td width=\"31%\">";
		if (empty ($row2[2])) print"&nbsp;";
		else {
                  $row2[7]=nl2br($row2[7]);
                  $row2[7]=str_replace(array("\r","\n")," ",$row2[7]);
                  $row2[5]=nl2br($row2[5]);
                  $row2[5]=str_replace(array("\r","\n")," ",$row2[5]);
                  $row2[6]=nl2br($row2[6]);
                  $row2[6]=str_replace(array("\r","\n")," ",$row2[6]);
                  print"<a href=\"#\" onmouseover=\"ddrivetip('<p><i><strong>".LABO."</strong></i><br/>".$row2[7]."<br/><i><strong>".CONCEN."</strong></i><br/>".$row2[5].MOL."<br/><i><strong>".PROTOCOL."</strong></i><br/>".$row2[6]."</p>')\" onmouseout=\"hideddrivetip()\">$row2[2]</a>";
		}
         print" - <a href=\"http://www.uniprot.org/uniprot/$row2[8]\" target=\"blank\">$row2[8]</a></td>
        <td width=\"5%\"";
        if ($row2[4]==1) print"bgcolor= \"#009900\"";
        elseif($row2[4]==0) print"bgcolor=\"\"#FFFFFF";
		elseif($row2[4]==2) print"bgcolor=\"#FF0000\"";
        print">&nbsp;</td>
        <td width=\"8%\" align=\"center\">";
		if (empty ($row2[0])) print"&nbsp;";
		else echo $row2[0];
		print"</td>
		 <td width=\"8%\" align=\"center\">";
		if (empty ($row2[12])) print"&nbsp;";
		else echo $row2[12];
		print"</td>
        <td width=\"7%\" align=\"center\">";
		if (empty ($row2[1])) print"&nbsp;";
		else echo $row2[1];
		print"</td>
		<td width=\"7%\" align=\"center\">";
		if (empty ($row2[9])) print"&nbsp;";
		else echo $row2[9];
		print"</td>
		<td width=\"8%\" align=\"center\">";
		if (empty ($row2[10])) print"&nbsp;";
		else echo $row2[10];
		print"</td>
        <td width=\"26%\"><div style=\"width:100%; height100px; overflow:auto; border:solid 1px black;\">";
		if (empty ($row2[3])) print"&nbsp;";
		else echo nl2br($row2[3]);
		print"</div></td>
        </tr>";
      }
      print"</table>";
    }
    //fermeture de la connexion à la base de données
    unset($dbh);
  }
  else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>