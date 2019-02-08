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
if (!empty($_POST['id'])) $id_sql=$_POST['id'];
if (!empty($_GET['id']))  $id_sql=$_GET['id'];
if (!empty($id_sql)) {
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
		if (!empty($nbrow5)) {
			while($row5 =$result5->fetch(PDO::FETCH_NUM)) {
				$tab5[$row5[0]]=$row5[0];
			}
		}
	}
	$sql="SELECT pro_id_equipe,pro_id_chimiste FROM produit WHERE pro_id_produit='".$id_sql."'";
	//les résultats sont retournées dans la variable $result1
	$result1 =$dbh->query($sql);
	$row1 =$result1->fetch(PDO::FETCH_NUM);
	if (($row[1]==$row1[1]) or ($row[0]=="{RESPONSABLE}" and $row[2]==$row1[0]) or ($row[0]=="{CHEF}" and in_array($row1[0],$tab5)) or $row[0]=="{ADMINISTRATEUR}") {
		$sql="SELECT str_nom, str_mol, str_formule_brute, str_masse_molaire, str_analyse_elem, pro_purification, pro_masse, pro_aspect, cou_couleur, pro_date_entree, pro_ref_cahier_labo, pro_modop, pro_observation, pro_analyse_elem_trouve, pro_point_fusion, pro_point_ebullition, pro_pression_pb, typ_type, pro_doi, pro_cas, pro_hal, pro_ref_contrat, pro_date_contrat, pro_num_brevet, pro_configuration, pro_numero, pro_num_constant,chi_nom, chi_prenom, str_logp,str_acceptorcount, str_rotatablebondcount, str_aromaticatomcount, str_donorcount, str_asymmetricatomcount, str_aromaticbondcount, pro_origine_substance, pro_num_cn,pro_tare_pilulier,pro_etape_mol,pro_qrcode,pro_unite_masse, pro_controle_purete, pro_controle_structure, pro_date_controle_purete
		FROM produit,structure,couleur,type,chimiste
		WHERE pro_id_produit='".$id_sql."' and produit.pro_id_structure=structure.str_id_structure and produit.pro_id_couleur=couleur.cou_id_couleur and produit.pro_id_type=type.typ_id_type and produit.pro_id_chimiste=chimiste.chi_id_chimiste";
		$result2 =$dbh->query($sql);
		$row2 =$result2->fetch(PDO::FETCH_NUM);

		//Supprime {et} du résultat de la requète
		$search= array('{','}');
		$row2[39]=str_replace($search,'',$row2[39]);
		$row2[7]=str_replace($search,'',$row2[7]);
		$row2[5]=str_replace($search,'',$row2[5]);
		$row2[41]=str_replace($search,'',$row2[41]);
		$row2[36]=str_replace($search,'',$row2[36]);

		$search1= array('alpha','beta','bêta','gamma','delta','epsilon','lambda');
		$remplace1= array('&alpha;','&beta;','&beta;','&gamma;','&delta;','&epsilon;','&lambda;');
		$row2[0]=str_replace($search1,$remplace1,$row2[0]);


		//preparation des variables
		if (isset($_POST['type'])) $type=rawurlencode($_POST['type']);
		else $type=$_GET['type'];
		if (isset($_POST['mol'])) $mol=$_POST['mol'];
		elseif(isset($_GET['mol'])) $mol=Base64_decode($_GET['mol']);
		else $mol="";
		if (isset($_POST['formbrute'])) $formbrute=rawurlencode($_POST['formbrute']);
		else $formbrute=$_GET['formbrute'];
		if (isset($_POST['massemol'])) $massemol=rawurlencode($_POST['massemol']);
		else $massemol=$_GET['massemol'];
		if (isset($_POST['supinf'])) $supinf=$_POST['supinf'];
		else $supinf=$_GET['supinf'];
		if (isset($_POST['massexact'])) $massexact=rawurlencode($_POST['massexact']);
		else $massexact=$_GET['massexact'];
		if (isset($_POST['forbrutexact'])) $forbrutexact=rawurlencode($_POST['forbrutexact']);
		else $forbrutexact=$_GET['forbrutexact'];
		if (isset($_POST['page'])) $page=rawurlencode($_POST['page']);
		else $page=$_GET['page'];
		if (isset($_POST['nbrs'])) $nbrs=rawurlencode($_POST['nbrs']);
		else $nbrs=$_GET['nbrs'];
		if (isset($_POST['nbpage'])) $nbpage=rawurlencode($_POST['nbpage']);
		else $nbpage=$_GET['nbpage'];
		if (isset($_POST['typechimiste'])) $typechimiste=rawurlencode($_POST['typechimiste']);
		else $typechimiste=$_GET['typechimiste'];
		if (isset($_POST['chimiste'])) $chimiste=rawurlencode($_POST['chimiste']);
		else $chimiste=$_GET['chimiste'];
		if (isset($_POST['equipechi'])) $equipechi=rawurlencode($_POST['equipechi']);
		else $equipechi=$_GET['equipechi'];
		if (isset($_POST['numero'])) $numero=rawurlencode($_POST['numero']);
		else $numero=$_GET['numero'];
		if (isset($_POST['refcahier'])) $refcahier=rawurlencode($_POST['refcahier']);
		else $refcahier=$_GET['refcahier'];
		if (isset($_POST['recherche'])) $recherche=rawurlencode($_POST['recherche']);
		elseif(isset($_GET['recherche'])) $recherche=$_GET['recherche'];
		else $recherche="";

		//mise en indice des chiffres pour l'affichage de la formule brute
		for ($k=0; $k<10; $k++) {
			$row2[2]=str_replace($k,"<SUB>".$k."</SUB>",$row2[2]);
		}
		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td valign=\"top\">
			<table width=\"328\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  <tr>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"fiche.php?id=".$id_sql."&menu=".$menu."&type=".$type."&mol=".Base64_encode($mol)."&formbrute=".$formbrute."&numero=".$numero."&massemol=".$massemol."&supinf=".$supinf."&massexact=".$massexact."&forbrutexact=".$forbrutexact."&page=".$page."&nbrs=".$nbrs."&nbpage=".$nbpage."&typechimiste=".$typechimiste."&chimiste=".$chimiste."&equipechi=".$equipechi."&refcahier=".$refcahier."&recherche=".$recherche."\">".STRUCTURE."</a></td>
			<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"ficheana.php?id=".$id_sql."&menu=".$menu."&type=".$type."&mol=".Base64_encode($mol)."&formbrute=".$formbrute."&numero=".$numero."&massemol=".$massemol."&supinf=".$supinf."&massexact=".$massexact."&forbrutexact=".$forbrutexact."&page=".$page."&nbrs=".$nbrs."&nbpage=".$nbpage."&typechimiste=".$typechimiste."&chimiste=".$chimiste."&equipechi=".$equipechi."&refcahier=".$refcahier."&recherche=".$recherche."\">".ANALYSE."</a></td>";
		if (($row[0]=="{RESPONSABLE}" and $menu==2 and $row[2]==$row1[0]) or ($row[0]=="{ADMINISTRATEUR}" and $menu==2) or ($row[0]=="{CHEF}" and $menu==2)) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fichebio.php?id=".$id_sql."&menu=".$menu."&type=".$type."&mol=".Base64_encode($mol)."&formbrute=".$formbrute."&massemol=".$massemol."&numero=".$numero."&supinf=".$supinf."&massexact=".$massexact."&forbrutexact=".$forbrutexact."&page=".$page."&nbrs=".$nbrs."&nbpage=".$nbpage."&typechimiste=".$typechimiste."&chimiste=".$chimiste."&equipechi=".$equipechi."&refcahier=".$refcahier."&recherche=".$recherche."\">".ANABIO."</a></td>";
		else print"<td width=\"82\" height=\"23\"></td>";
		if ($row[0]=="{ADMINISTRATEUR}" and $menu==2) print"<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"fichehistorique.php?id=".$id_sql."&menu=".$menu."&type=".$type."&mol=".Base64_encode($mol)."&formbrute=".$formbrute."&massemol=".$massemol."&numero=".$numero."&supinf=".$supinf."&massexact=".$massexact."&forbrutexact=".$forbrutexact."&page=".$page."&nbrs=".$nbrs."&nbpage=".$nbpage."&typechimiste=".$typechimiste."&chimiste=".$chimiste."&equipechi=".$equipechi."&refcahier=".$refcahier."&recherche=".$recherche."\">".CHANGEMENT."</a></td>";
		else print"<td width=\"82\" height=\"23\"></td>";
		print"</tr>
			</table></td><td><div align=\"center\">
			<form method=\"post\" action=\"consultation.php\">
			<input type=\"image\" src=\"images/retour.gif\">
			<input type=\"hidden\" name=\"menu\" value=\"2\">
			<input type=\"hidden\" name=\"type\" value=\"".$type."\">
			<input type=\"hidden\" name=\"mol\" value=\"".$mol."\">
			<input type=\"hidden\" name=\"formbrute\" value=\"".$formbrute."\">
			<input type=\"hidden\" name=\"massemol\" value=\"".$massemol."\">
			<input type=\"hidden\" name=\"supinf\" value=\"".$supinf."\">
			<input type=\"hidden\" name=\"massexac\" value=\"".$massexact."\">
			<input type=\"hidden\" name=\"forbrutexact\" value=\"".$forbrutexact."\">
			<input type=\"hidden\" name=\"page\" value=\"".$page."\">
			<input type=\"hidden\" name=\"nbrs\" value=\"".$nbrs."\">
			<input type=\"hidden\" name=\"nbpage\" value=\"".$nbpage."\">
			<input type=\"hidden\" name=\"typechimiste\" value=\"".$typechimiste."\">
			<input type=\"hidden\" name=\"chimiste\" value=\"".$chimiste."\">
			<input type=\"hidden\" name=\"equipechi\" value=\"".$equipechi."\">
			<input type=\"hidden\" name=\"numero\" value=\"".$numero."\">
			<input type=\"hidden\" name=\"refcahier\" value=\"".$refcahier."\">
			<input type=\"hidden\" name=\"recherche\" value=\"".$recherche."\">
			</form>
			</div>
			</td></tr></table>";

			//remplace dans le nom de la structure alpha, beta... par l'équivalent en symbole

			$nommol="";
			if (preg_match('/\^{/',$row2[0])) {
				$tabnom=preg_split("/\^{/",$row2[0]);
				$numnom=count($tabnom);
				for ($i=0; $i<$numnom; $i++) {
					if (preg_match("/}/",$tabnom[$i])) {
						$tabnom[$i]=str_replace("}","</sup>",$tabnom[$i]);
						$tabnom[$i]="<sup>".$tabnom[$i];
					}
					$nommol.=$tabnom[$i];
				}
			}
			else $nommol=$row2[0];

			// [JM - 22/01/2019] affiche le contrôle de la pureté et de la strucutre
			echo "<div style='text-align: center;'>";
			if ($row2[43]) echo "<strong style='color: blue;'>".CONTROLE_STRUCT."<input type='checkbox' disabled checked></strong>";
			else  echo "<strong style='color: blue;'>".CONTROLE_STRUCT."<input type='checkbox' disabled></strong>";
			echo " | ";
			if ($row2[42]) echo "<strong style='color: blue;'>".CONTROLE_PURETE."<input type='checkbox' disabled checked></strong>";
			else  echo "<strong style='color: blue;'>".CONTROLE_PURETE."<input type='checkbox' disabled></strong>";
			echo " | <strong style='color: blue;'>".DATE_CONTROLE_PURETE."</strong>&nbsp;".$row2[44];
			echo "</div>";

			// [JM - 22/01/2019] affiche date d'envoie chez evotec
			// [JM - 22/01/2019] seulement pour les responsables, les chefs et les admins
			if ($row[0]=="{RESPONSABLE}" || $row[0]=="{CHEF}" || $row[0]=="{ADMINISTRATEUR}"){
				$sql_evo="SELECT evo_date_envoie FROM evotec WHERE evo_numero_permanent=".$row2[26];
				$result_evo =$dbh->query($sql_evo);
				$row_evo =$result_evo->fetch(PDO::FETCH_NUM);
				if ($row_evo[0]){
					echo "<div style='text-align: center;'>";
					echo "<strong style='color: red;'>".DATE_ENVOIE_EVOTEC."</strong>&nbsp;".$row_evo[0];
					echo "</div>";
				}
			}

			print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
				  <tr>
					<td colspan=\"3\"><strong>".NOM."</strong> ".$nommol."</td>
				  </tr>
				  <tr>
					<td rowspan=\"12\" valign=\"top\">";
			echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
			$jme=new visualisationmoleculejme (250,250,$row2[1]);
			$jme->imprime();
			print"</td>
				<td rowspan=\"12\" valign=\"top\">";
			//$marvin3d=new visualisation3dmolecule (250,250,$row2[1],"true",'all');
			//$marvin3d->imprime();
			// $test=new paints ($row2[1]);
			// $test->requete_paints();
			print"</td>
				<td><strong>".NUMERO."</strong>&nbsp;".$row2[25]."</td>
			  </tr>
			  <tr>
				<td><strong>".NUMEROCONS."</strong>&nbsp;".$row2[26]."</td>
			  </tr>
			  <tr>
				<td><strong>".NUMEROCN."</strong>&nbsp;".$row2[37]."</td>
			  </tr>
			  <tr>
				<td><strong>".QRCODE."</strong>&nbsp;<br />";
			if (!empty($row2[40]))	{
				if (preg_match("/\n/",$row2[40])) {
					$tabcode=preg_split("/\n/",$row2[40]);
					foreach ($tabcode as $elem) {
						print"<img src=\"image.php?codeB=".$elem."\"><br />";
					}
				}
				else print"<img src=\"image.php?codeB=".$row2[40]."\">";
			}
			print"</td>
			  </tr>
			  <tr>
				<td><strong>".DATE."</strong>&nbsp;".$row2[9]."</td>
			  </tr>
			  <tr>
					<td>
						<table width=\"300\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">
			  <tr>
			  <td width=\"150\"><strong>".COULEUR."</strong></td>";
			if ($row2[8]=="INCOL" || $row2[8]=="INCON") print"<td width=\"150\">".constant($row2[8])."</td>";
			else print"<td bgcolor=\"#".$row2[8]."\" width=\"150\" class=bord>&nbsp;</td>";

			$sql="SELECT distinct(pla_identifiant_local) FROM position,plaque WHERE pos_id_produit='$id_sql' and pla_id_plaque_mere='0' and position.pos_id_plaque=plaque.pla_id_plaque";
			$result5=$dbh->query($sql);
			$numresult5=$result5->rowCount();
			if ($numresult5>0) {
				$plaq="";
				$u=1;
				while($row5=$result5->fetch(PDO::FETCH_NUM)) {
					$plaq.=$row5[0];
					if($u<$numresult5) $plaq.=" ; ";
					$u++;
				}
			}
			else $plaq=NON;

			print"</tr></table></td>
				  </tr>
				  <tr>
					<td><strong>".MASSEMOL."</strong>&nbsp;".$row2[3]."&nbsp;".GMOL."</td>
				  </tr>
				  <tr>
					<td><strong>".MASSE."</strong>&nbsp;".$row2[6]."&nbsp;".constant($row2[41])."</td>
				  </tr>
				  <tr>
					<td><strong>".IMPORTTARE."</strong>&nbsp;".$row2[38]."&nbsp;".MG."</td>
				  </tr>
				  <tr>
					<td><strong>".PLAQUE."</strong>&nbsp;".$plaq."</td>
				  </tr>
				  <tr>
					<td><strong>".FORMULEBRUTE."</strong>&nbsp;".$row2[2]."</td>
				  </tr>
				  <tr>
					<td><strong>".NOM."</strong>&nbsp;".$row2[28]." ".$row2[27]."</td>
				  </tr>
				  <tr>
					<td colspan=\"2\"><strong>".CONFIG."</strong>&nbsp;".$row2[24]."</td>
					<td><strong>".ORIGINEMOL."</strong>&nbsp;";
			if (!empty($row2[36])) echo constant ($row2[36]);
			echo "</td>
				  </tr>
				  <tr>
					<td colspan=\"2\" rowspan=\"";
			if ($row2[17]!="LIBRE") echo "10";
			else echo "9";
			$row2[11]=str_replace("\r","<br/>",$row2[11]); //remplace les sauts de ligne par <br/>
			print"\"><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".MODOP."</strong><br/>".$row2[11]."</div></td>
					<td><strong>".TYPE."</strong>&nbsp;".constant ($row2[17])."</td>
				  </tr>";
			if ($row2[17]=="CONTRAT") print"<tr>
											<td><strong>".CONTRATDESC."</strong>&nbsp;".$row2[21]."<br/><strong>".DUREE."</strong>&nbsp;".$row2[22]."</td>
											</tr>";
			if ($row2[17]=="BREVET") print"<tr>
										  <td><strong>".NUMBREVET."</strong>&nbsp;".$row2[23]."</td>
										  </tr>";
			$row2[12]=str_replace("\r","<br/>",$row2[12]); //remplace les sauts de ligne par <br/>

			//cacul des règles de Lipinski
			$lipinsky=0;
			if($row2[3]<=500) $lipinsky++; //masse molaire <=500
			if($row2[29]<5) $lipinsky++;   //logp<5
			if($row2[33]<5) $lipinsky++;     //nbr de doneurs <5
			if($row2[30]<10) $lipinsky++; //nbrs d'accepteurs <10
			if($lipinsky>=3) $lipinsky=OUI;
			else $lipinsky=NON;
			//définit si le logp est dans la plage de -4 et 5 sinon affiche ND
			if ($row2[29]>5 or $row2[29]<-4) $row2[29]=ND;

			print" <tr>
					<td><strong>".ETAPESYNT."</strong>&nbsp;".constant ($row2[39])."</td>
				  </tr>
				  <tr>
					<td><strong>".ASPECT."</strong>&nbsp;".constant ($row2[7])."</td>
				  </tr>
				  <tr>
					<td><strong>".LOGP."</strong>&nbsp;".$row2[29]."</td>
				  </tr>
				  <tr>
					<td><strong>".LIPINSKY."</strong>&nbsp;".$lipinsky."</td>
				  </tr>
				  <tr>
					<td><strong>".ANALYSETHEO."</strong>&nbsp;".$row2[4]."</td>
				  </tr>
				  <tr>
					<td><strong>".ANALYSEEXP."</strong>&nbsp;".$row2[13]."</td>
				  </tr>
				  <tr>
					<td><strong>".PURIFICATION."</strong>&nbsp;".constant($row2[5])."</td>
				  </tr>
				  <tr>
					<td><strong>".REFE."</strong>&nbsp;".$row2[10]."</td>
				  </tr>
				  <tr>
					<td colspan=\"2\" rowspan=\"10\"><div style=\"width:500px; height:200px; overflow:auto; border:solid 1px black;\"><strong>".OBSERVATIONS."</strong><br/>".$row2[12]."</div></td>
					<td><strong>".ACCEPTORCOUNT."</strong>&nbsp;".$row2[30]."</td>
				  </tr>
				  <tr>
					<td><strong>".ROTATABLEBONDCOUNT."</strong>&nbsp;".$row2[31]."</td>
				  </tr>
				  <tr>
					<td><strong>".AROMATICATOMCOUNT."</strong>&nbsp;".$row2[32]."</td>
				  </tr>
				  <tr>
					<td><strong>".AROMATICBONDCOUNT."</strong>&nbsp;".$row2[35]."</td>
				  </tr>
				  <tr>
					<td><strong>".DONORCOUNT."</strong>&nbsp;".$row2[33]."</td>
				  </tr>
				  <tr>
					<td><strong>".ASYMETRICATOMCOUNT."</strong>&nbsp;".$row2[34]."</td>
				  </tr>
				  <tr>
					<td><strong>".PF."</strong>&nbsp;".$row2[14]."</td>
				  </tr>
				  <tr>
					<td><strong>".PEB."</strong>&nbsp;".$row2[15];
			if (!empty($row2[15])) print" ".PRESSION.$row2[16]." ".ATM;
			print"</td>
				  </tr>
				  <tr><td><strong>".PRECAUTION."</strong>&nbsp;";
			$sql="SELECT distinct pre_precaution FROM precaution,liste_precaution,produit WHERE pro_id_produit='".$id_sql."' and produit.pro_id_structure=liste_precaution.lis_id_structure and liste_precaution.lis_id_precaution=precaution.pre_id_precaution";
			$result4=$dbh->query($sql);
			$numresult4=$result4->rowCount();
			if ($numresult4>0) {
				while($row4=$result4->fetch(PDO::FETCH_NUM)) {
					echo constant($row4[0]).", ";
				}
			}
			print"</td>
				  </tr>
				  <tr>";
			$solvant="";
			$sql="SELECT sol_solvant FROM solubilite,solvant WHERE sol_id_produit='".$id_sql."' and solubilite.sol_id_solvant=solvant.sol_id_solvant";
			$result3 =$dbh->query($sql);
			while($row3 =$result3->fetch(PDO::FETCH_NUM)) {
				$solvant.=constant($row3[0]).", ";
			}
			print"<td><strong>".SOLVANT."</strong>&nbsp;".$solvant."</td>
				  </tr>
				  <tr><td><strong>".DOI."</strong>&nbsp;";
			if (!empty($row2[18])) print"<a href=\"http://dx.doi.org/".$row2[18]."\" target=\"_blank\">".$row2[18]."</a>";
			print"</td><td><strong>".CAS."</strong>&nbsp;".$row2[19]."</td><td><strong>".HAL."</strong>&nbsp;";
			if (!empty($row2[20])) print"<a href=\"http://hal.archives-ouvertes.fr/".$row2[20]."/fr/\" target=\"_blank\">".$row2[20]."</a>";
			print"</td></tr>
				</table>";
			//fermeture de la connexion à la base de données
			unset($dbh);
	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>
