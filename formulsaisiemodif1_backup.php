<script type="text/javascript" src="js/jquery.min.js"></script>
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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';
include_once 'numero.php';
include_once 'massemolaire.php';

if (!empty($_POST['id'])) {
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
	$sql="SELECT pro_id_equipe,pro_id_chimiste FROM produit WHERE pro_id_produit='".$_POST['id']."'";
	//les résultats sont retournées dans la variable $result1
	$result1 =$dbh->query($sql);
	$row1 =$result1->fetch(PDO::FETCH_NUM);
	if (($row[1]==$row1[1]) or ($row[0]=="{RESPONSABLE}" and $row[2]==$row1[0]) or ($row[0]=="{CHEF}" and in_array($row1[0],$tab5)) or $row[0]=="{ADMINISTRATEUR}") {

		// Traitement du code inchi et de l'inchikey
		// $tabinchi=preg_split("[\n]",$_POST["inchi"]);
		// $_POST["inchi"]=str_replace("\r","",$_POST["inchi"]);
		// $_POST["inchimd5"]=str_replace("InChIKey=","",$_POST["inchimd5"]);
		// traitement de la variable nom pour transfmormer $ en lambda
		// $_POST["nom"]=str_replace("\$l","&lambda;",$_POST["nom"]);

		$sql="SELECT str_inchi_md5,str_id_structure FROM structure,produit WHERE pro_id_produit='".$_POST['id']."' and produit.pro_id_structure=structure.str_id_structure";
		$result2 =$dbh->query($sql);
		$row2 =$result2->fetch(PDO::FETCH_NUM);
		if ($_POST["mol"]!="") {
			//javascript de vérification des champs obligatoires
			echo"<script language=\"JavaScript\">
				  <!--
				  function contains(onechar,lstring) {
					retval=false
					for(var i=1;i<=lstring.length;i++) {
					  if(lstring.substring(i,i+1)==onechar) {
						retval=true
						break
					  }
					}
					return retval
				  }

				  function selection(lstring) {
					retvale=false
					for(var i=1;i<lstring.length;i++) {
					  if(document.chimiotheque.D5.options[i].text==document.chimiotheque.T3.value){retvale=true
					  break
					  }
					}
					return retvale
				  }

				  function GetSmiles(theForm) {
					var i=0;
					var top=true;
					if (document.saisie2.config_solvantsDeSolubilisation.value == '1')
					{
						top = false;
						while (document.saisie2[\"solvant\"+i] )
						{
							if (document.saisie2[\"solvant\"+i].checked)
							{
								top = true;
								break;
							}
							i++;
						}
					}
					if (CKEDITOR.instances.nomiupac.getData()==\"\" && document.saisie2.config_nomenclature.value== '1') {alert(\"".CHAMP." \'".NOM."\' ".RENSEIGNE."\");}
					else {
					  if (document.saisie2.masse.value==\"\") {alert(\"".CHAMP." \'".MASS."\' ".RENSEIGNE."\");}
					  else {
						if((isNaN(document.saisie2.masse.value)) || (contains(\".\",saisie2.masse.value))) {alert(\"".CHAMP." \'".MASS."\' ".ERREURMASSE."\");}
						else {
						  if (document.saisie2.couleur.value==\"\" && document.saisie2.config_couleur.value== '1') {alert(\"".CHAMP." \'".COULEUR."\' ".RENSEIGNE."\");}
						  else {
							if (document.saisie2.ref.value==\"\" && document.saisie2.config_refCahier.value== '1') {alert(\"".CHAMP." \'".REFERENCECAHIER."\' ".RENSEIGNE."\");}
							else {
							  if (document.saisie2.aspect.value==\"\" && document.saisie2.config_aspect.value== '1') {alert(\"".CHAMP." \'".ASPECT."\' ".RENSEIGNE."\");}
							  else {
								if (document.saisie2.purification.value==\"\" && document.saisie2.config_typePurif.value== '1') {alert(\"".CHAMP." \'".PURIFICATION."\' ".RENSEIGNE."\");}
								else {
									if((document.saisie2.purification.value==\"recristallisation\" || document.saisie2.purification.value==\"précipitation\") && (document.saisie2.aspect.value==\"liquide\")){alert(\"".RECRISTALISE."\");}
									else {
									  if(document.saisie2.purification.value==\"distillation\" && document.saisie2.aspect.value==\"solide\"){alert(\"".DISTILATION."\");}
									  else {
										if (!top) {alert(\"".CHAMP." \'".SOLVANTS."\' ".RENSEIGNE."\");}
										else {
											var verifRequired = true;
											var all = document.getElementsByClassName(\"form-control\");

											for (var i=0, max=all.length; i < max; i++) {
													if(document.getElementsByClassName(\"form-control\")[i].required){
														if(document.getElementsByClassName(\"form-control\")[i].value == \"\"){
															verifRequired = false;
														}
													}
											}
											document.getElementById('champsAnnexe').value = window.JSON.stringify($(fbRender).formRender(\"userData\"));
											if(verifRequired){
												theForm.submit();
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
					}
				  }
				  </script>";
			//affichage des erreurs du formulaire après traitement par traitement.php
			if (isset($erreur)) echo $erreur;
			//fin de l'affichage des erreurs
			print OBLIGATOIRE."<br/>";
			//initialisation du formulaire
			$formulaire=new formulaire ("saisie2","transfertmodif.php","POST",true);
			$formulaire->affiche_formulaire();

			print"<input type='hidden' name='config_couleur' value='".$config_data['couleur']."'>";
			print"<input type='hidden' name='config_typePurif' value='".$config_data['typePurif']."'>";
			print"<input type='hidden' name='config_aspect' value='".$config_data['aspect']."'>";
			print"<input type='hidden' name='config_refCahier' value='".$config_data['refCahier']."'>";
			print"<input type='hidden' name='config_nomenclature' value='".$config_data['nomenclature']."'>";
			print"<input type='hidden' name='config_solvantsDeSolubilisation' value='".$config_data['solvantsDeSolubilisation']."'>";

			if (isset($_POST["chx_purete"]) && $_POST["chx_purete"] == "chx_purete"){
				print
					"<div>
						<input type='checkbox' id='chx_purete' name='chx_purete' value='chx_purete' checked>
						<label for='chx_purete'>Pureté contrôlée</label>
					</div>";
				}
				else {
					print
						"<div>
							<input type='checkbox' id='chx_purete' name='chx_purete' value='chx_purete'>
							<label for='chx_purete'>Pureté contrôlée</label>
						</div>";
					}

			if (isset($_POST["chx_structure"]) && $_POST["chx_structure"] == "chx_structure"){
				print
					"<div>
						<input type='checkbox' id='chx_structure' name='chx_structure' value='chx_structure' checked>
						<label for='chx_tructure'>Structure contrôlée</label>
					</div>";
				}
				else {
					print
						"<div>
							<input type='checkbox' id='chx_structure' name='chx_structure' value='chx_structure'>
							<label for='chx_tructure'>Structure contrôlée</label>
						</div>";
					}

			//selection des résultats de l'ID demandé dans la table produit
			$sql="SELECT
				  pro_id_produit,
				  str_nom,
				  pro_purification,
				  pro_aspect,
				  pro_id_couleur,
				  pro_ref_cahier_labo,
				  pro_modop,
				  pro_observation,
				  pro_analyse_elem_trouve,
				  pro_point_fusion,
				  pro_point_ebullition,
				  pro_pression_pb,
				  pro_alpha,
				  pro_alpha_temperature,
				  pro_alpha_concentration,
				  pro_alpha_solvant,
				  pro_rf,
				  pro_rf_solvant,
				  pro_doi,
				  pro_hal,
				  pro_cas,
				  pro_id_uv,
				  pro_id_hrms,
				  pro_id_sm,
				  pro_id_rmnc,
				  pro_id_rmnh,
				  pro_id_ir,
				  pro_observation,
				  pro_numero,
				  pro_id_type,
				  pro_masse,
				  pro_id_equipe,
				  pro_date_entree,
				  pro_num_sansmasse,
				  pro_purete,
				  pro_methode_purete,
				  pro_qrcode
				   FROM produit,structure WHERE pro_id_produit='".$_POST['id']."' and structure.str_id_structure=produit.pro_id_structure";
			$resultselect = $dbh->query($sql);
			$rowselect = $resultselect->fetch(PDO::FETCH_NUM);
			$sql = "SELECT * FROM couleur";
			//les résultats sont retournées dans la variable $result
			$result =$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			while($row =$result->fetch(PDO::FETCH_NUM)) {
				$tabcoul[$row[0]]=$row[1];
			}
			print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
				  <tr>
				  <td>";
			echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
			$jme=new visualisationmoleculejme (300,300,$_POST['mol']);
			$jme->imprime();
			print"</td>\n<td>\n";

			//recherche de la masse limite de stockage
			$sql="SELECT para_stock,para_numerotation FROM parametres";
			$result21=$dbh->query($sql);
			$row21=$result21->fetch(PDO::FETCH_NUM);
			//ajoute 7 jours à la date d'entrée de la molécule
			$tab=preg_split("/(?=\s)/",$rowselect[32]);
			$tabdate=preg_split("/(-)/",$tab[0]);
			$datentre=mktime(0,0,0,$tabdate[1],$tabdate[2],$tabdate[0]);
			$datentre+=(3600*24*7);

			//si la date d'entrée est inférieure ou égale à 7 jours par rapport à la date du jour
			//alors on peut changer le numéro par rapport à la limite de masse
			//sinon on change de numéro lorsque la masse atteint 0mg
			if (date("Ymd")<=(date("Ymd",$datentre))) {
				if ($_POST['masse']>=$row21[0]) $typenumero=1;
				else  $typenumero=2;
			}
			else {
				if ($_POST['masse']>0) $typenumero=1;
				else  $typenumero=2;
			}
			if ($rowselect[33]>0) $typenumero1=2;
			else  $typenumero1=1;


			if ($row21[1]=="AUTO") {

				if ($_POST["type"]!=$rowselect[29] or $typenumero1!=$typenumero) {

					//recherche des parametres du numero definient par l'administrateur
					$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='$typenumero' ORDER BY num_id_numero";
					$resultat24=$dbh->query($sql);
					while ($row24=$resultat24->fetch(PDO::FETCH_NUM)) {
						$tab24[]=$row24[0];
					}

					if (in_array("{BOITE}",$tab24) and in_array("{COORDONEE}",$tab24)) {
						//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
						$sql="SELECT pro_num_boite,pro_num_position FROM produit WHERE pro_id_equipe='".$rowselect[31]."' and pro_id_type='".$_POST['type']."' and pro_num_boite<>'0' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
						$result23=$dbh->query($sql);
						$o=0;
						while($row23=$result23->fetch(PDO::FETCH_NUM)) {
							if ($row23[0]<10) $row23[0]="0".$row23[0];
							$tab23[$o]=$row23[0]."@".$row23[1];
							$o++;
						}
						$numoboite="";
						$numoposition="";
					}
					elseif (in_array("{BOITE}",$tab24) and in_array("{NUMERIC}",$tab24)) {
						//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
						$sql="SELECT pro_num_boite,pro_num_incremental FROM produit WHERE pro_id_equipe='".$rowselect[31]."' and pro_id_type='".$_POST['type']."' and pro_num_boite<>'0' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
						$result23=$dbh->query($sql);
						$o=0;
						while($row23=$result23->fetch(PDO::FETCH_NUM)) {
							$tab23[$o]=$row23[0]."-".$row23[1];
							$o++;
						}
						$numoboite="";
						$numoincremental="";
					}
				elseif (in_array("{NUMERIC}",$tab24)) {
						if ($typenumero==1) {
							//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
							$sql="SELECT pro_num_incremental FROM produit WHERE pro_id_equipe='".$rowselect[31]."' and pro_id_type='".$_POST['type']."' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
							$result23=$dbh->query($sql);
							$o=0;
							while($row23=$result23->fetch(PDO::FETCH_NUM)) {
								$tab23[$o]=$row23[0];
								$o++;
							}
							$numoincremental="";
						}
						elseif ($typenumero==2) {
							//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
							$sql="SELECT pro_num_sansmasse FROM produit WHERE pro_id_equipe='".$rowselect[31]."' and pro_id_type='".$_POST['type']."' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental,pro_num_sansmasse";
							$result23=$dbh->query($sql);
							$o=0;
							while($row23=$result23->fetch(PDO::FETCH_NUM)) {
								$tab23[$o]=$row23[0];
								$o++;
							}
							$numoincremental="";
						}
					}
					if(!isset($tab23)) $tab23[]="";
					$nbtab23=count($tab23);
					$o=0;
					$numeroassemble=numero($typenumero);

					//vidange de la table temporaire
					$sql="DELETE FROM numerotation_temporaire WHERE nume_date<>'".date("Y-m-d")."'";
					$deletenum=$dbh->exec($sql);

					//insertion du numéro dans la table temporaire
					while ($o<1) {
						if ($nbtab23==0) {
							$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$_POST['type']."','".$rowselect[31]."','".date("Y-m-d")."')";
							$insertnum=$dbh->exec($sql);
							if (!empty($insertnum))  $o=1;
							else $numeroassemble=numero($typenumero);
						}
						elseif (!in_array($numeroassemble,$tab23)) {
							$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$_POST['type']."','".$rowselect[31]."','".date("Y-m-d")."')";
							$insertnum=$dbh->exec($sql);
							if (!empty($insertnum))  $o=1;
							else $numeroassemble=numero($typenumero);
						}
						else $numeroassemble=numero($typenumero);
					}
					//définition du numéro réservé
					$numerocomplet="";
					$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='$typenumero' ORDER BY num_id_numero";
					$resultat25=$dbh->query($sql);

					while ($row25=$resultat25->fetch(PDO::FETCH_NUM)) {
						if ($row25[0]=="{FIXE}") $numerocomplet.=$row25[1];
						elseif ($row25[0]=="{EQUIPE}") {
							$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe='".$rowselect[31]."'";
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
							switch ($_POST['type']) {
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
							$formulaire->ajout_cache ($boite,"boite");
						}
						elseif ($row25[0]=="{COORDONEE}") {
							$tab29=explode("@",$numeroassemble);
							list($boitetemp,$coordon)=$tab29;
							$numerocomplet.=$coordon;
							$formulaire->ajout_cache ($coordon,"coordonnee");
						}
						elseif ($row25[0]=="{NUMERIC}") {
							if (preg_match("/@/",$numeroassemble)){
								$tab30=explode("@",$numeroassemble);
								list($boitetemp,$numeric)=$tab30;
								$numerocomplet.=$numeric;
								$formulaire->ajout_cache ($numeric,"numerique");
							}
							else  {
								$formulaire->ajout_cache ($numeroassemble,"sansmasse");
								$numerocomplet.=$numeroassemble;
							}
						}
					}
					print "<strong>".NBPILLULIER."<font color=\"red\"> ".$numerocomplet."</font></strong><br/><br/>";
					$formulaire->ajout_cache ($numerocomplet,"numerocomplet");
				}
				else print "<strong>".NBPILLULIER."<font color=\"red\"> ".$rowselect[28]."</font></strong><br/><br/>";
			}
			elseif ($row21[1]=="{MANU}") {
				//recherche des informations sur le champ pro_numero
				$sql="SHOW COLUMNS FROM produit LIKE 'pro_numero'";
				//les résultats sont retournées dans la variable $result
				$result25=$dbh->query($sql);
				//Les résultats sont mis sous forme de tableau
				$rop25=$result25->fetch(PDO::FETCH_NUM);
				$traitement=new traitement_requete_sql($rop25[1]);
				$tab25=$traitement->imprime();
				$formulaire->ajout_text ($tab25+1, $rowselect[28], $tab25, "numerocomplet", NBPILLULIER."<br/>","","");
				print"<br/><br/>";
			}
			$qcodeval="";
			if (!empty($rowselect[36]))	{
				if (preg_match("/¤/",$rowselect[36])) {
					$tabcode=preg_split("/¤/",$rowselect[36]);
					foreach ($tabcode as $elem) {
						$qcodeval=$elem."\n".$qcodeval;
					}
				}
				else $qcodeval=$rowselect[36];
			}

			$formulaire->ajout_textarea ("qrcode",25,$qcodeval,4,true,QRCODE2."<br/>");
			print"<br/><br/>";

			print "<p><strong>".MASS."</strong>".$_POST['masse'].MG."</p>";
			$formulaire->ajout_cache ($_POST['masse'],"masse");
			print"<br/><br/>";
			$formulaire->ajout_select (1,"couleur",$tabcoul,false,$rowselect[4],SELECCOULEUR,COULEUR."<br/>",true,"");
			print"<br/><br/>";

			//recherche des informations sur le champ pro_purification
			$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_purification';";
			//les résultats sont retournées dans la variable $result
			$result1=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row=$result1->fetch(PDO::FETCH_NUM);
			$traitement=new traitement_requete_sql($row[0]);
			$tab=$traitement->imprime();
			$search= array('{','}');
			$rowselect[2]=str_replace($search,'',$rowselect[2]);
			$formulaire->ajout_select (1,"purification",$tab,false,$rowselect[2],SELECPURIFICATION,PURIFICATION."<br/>",false,"");
			print"<br/><br/>";

			//recherche des informations sur le champ pro_aspect
			$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_aspect';";
			//les résultats sont retournées dans la variable $result
			$result2=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result2->fetch(PDO::FETCH_NUM);
			$traitement=new traitement_requete_sql($rop[0]);
			$tab=$traitement->imprime();
			$search= array('{','}');
			$rowselect[3]=str_replace($search,'',$rowselect[3]);
			$formulaire->ajout_select (1,"aspect",$tab,false,$rowselect[3],SELECASPECT,ASPECT."<br/>",false,"");
			print"<br/><br/>";

			$sql="SELECT * FROM precaution ORDER BY precaution.pre_precaution ASC";
			//les résultats sont retournées dans la variable $result
			$result4=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			while($row =$result4->fetch(PDO::FETCH_NUM)) {
				$tab1[$row[0]]=constant($row[1]);
			}
			//recherche des precautions
			// transformation en Inchikey via Bingo
			$sql="SELECT Bingo.InchI('".$_POST["mol"]."','')";
			$resultinchi=$dbh->query($sql);
			$rowinchi=$resultinchi->fetch(PDO::FETCH_NUM);

			$sql="SELECT bingo.InChIKey ('".$rowinchi[0]."')";
			$resultinchikey=$dbh->query($sql);
			$rowinchikey=$resultinchikey->fetch(PDO::FETCH_NUM);
			$tabpre=testprecaution ($rowinchikey[0],$row2[1]);
			$formulaire->ajout_select (3,"precaution",$tab1,true,$tabpre,"",PRECAUTION."<br/>",false,"");
			print"<br/><br/>";

			//recherche des informations sur le champ pro_ref_cahier_labo
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_ref_cahier_labo'";
			//les résultats sont retournées dans la variable $result
			$result3=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result3->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text (intval($rop[0]/1.5), $rowselect[5], $rop[0], "ref", REFERENCECAHIER."<br/>","","");

			print"</td>\n<td>";
			//recherche des informations sur la table solvant
			$sql="SELECT * FROM solvant ORDER BY solvant.sol_solvant ASC";
			//les résultats sont retournées dans la variable $result
			$result5=$dbh->query($sql);
			$nbrsolvants=$result5->rowCount();
			//Les résultats son mis sous forme de tableau
			while($row1 =$result5->fetch(PDO::FETCH_NUM)) {
				$tab2[$row1[0]]=constant($row1[1]);
			}
			$sql="SELECT solubilite.sol_id_solvant FROM solubilite,solvant WHERE sol_id_produit='".$_POST['id']."' and solubilite.sol_id_solvant=solvant.sol_id_solvant";
			$resultsolvants =$dbh->query($sql);
			$isolvant=0;
			while($rowsolvant =$resultsolvants->fetch(PDO::FETCH_NUM)) {
				$tabsolvant[$isolvant]=$rowsolvant[0];
				$isolvant++;
			}
			if (isset($tabsolvant))
				$formulaire->ajout_checkbox ("solvant",$tab2,$tabsolvant,SOLVANTS."<br/>",false);
				else {
					$formulaire->ajout_checkbox ("solvant",$tab2,'',SOLVANTS."<br/>",false);
				}

			print"</td>\n</tr>\n<tr valign=\"top\">\n<td>";
			$formulaire->ajout_textarea ("nomiupac",36,$rowselect[1],12,true,NOM."<br/>");

			print"</td>\n<td";
			if ($_POST['type']==1) print" colspan=\"2\"";
			print">\n";
			$formulaire->ajout_textarea ("modop",47,$rowselect[6],12,true,MODOP."<br/>");
			if ($_POST['type']>1) {
				print"</td>\n<td>";
				if ($_POST['type']==2) {
					if ($rowselect[29]==2) {
						$sql="SELECT pro_ref_contrat,pro_date_contrat FROM produit WHERE pro_id_produit='".$_POST['id']."'";
						$resultcontrat=$dbh->query($sql);
						$rowcontrat =$resultcontrat->fetch(PDO::FETCH_NUM);
						$formulaire->ajout_textarea ("contrat",20,$rowcontrat[0],12,true,CONTRATDESC."<br/>");
						print"<br/>";
						$formulaire->ajout_text (3,$rowcontrat[1],"10","duree",DUREE."<br/>",AN,"");
					}
					else {
						if(!isset($_POST['contrat'])) $_POST['contrat']="";
						if(!isset($_POST['duree'])) $_POST['duree']="";
						$formulaire->ajout_textarea ("contrat",20,$_POST['contrat'],12,true,CONTRATDESC."<br/>");
						print"<br/>";
						$formulaire->ajout_text (3,$_POST['duree'],"10","duree",DUREE."<br/>",AN,"");
					}
				}
				if ($_POST['type']==3) {
					if ($rowselect[29]==3) {
						$sql="SELECT pro_num_brevet FROM produit WHERE pro_id_produit='".$_POST['id']."'";
						$resultbrevet=$dbh->query($sql);
						$rowbrevet =$resultbrevet->fetch(PDO::FETCH_NUM);
						$formulaire->ajout_text (20,$rowbrevet[0],"","numbrevet",NUMBREVET."<br/>","","");
					}
					else {
						if(!isset($_POST['numbrevet'])) $_POST['numbrevet']="";
						$formulaire->ajout_text (20,$_POST['numbrevet'],"","numbrevet",NUMBREVET."<br/>","","");
						}
				}
			}

			//*********Section analyse du formulaire***********
			print"</td>\n</tr>\n<tr>\n<td colspan=\"3\"><div class='hr click_analyses'>".ANALYSE."</div><hr id='arrow_analyses' class='arrow click_analyses'></td>\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td><h3>".ANALYSE."</h3><br/>";
			//recherche des informations sur le champ pro_analyse_elem_trouve
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_analyse_elem_trouve'";
			//les résultats sont retournées dans la variable $result
			$result4=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result4->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text (45,$rowselect[8],$rop[0],"anaelem",ANAELEM."<br/>","","");
			print"<br/><br/>";
			//recherche des informations sur le champ pro_point_fusion
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_point_fusion'";
			//les résultats sont retournées dans la variable $result
			$result7=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result7->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[9],$rop[0],"pfusion",PFUSION."<br/>",DEG,"");
			print"<br/><br/>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
					<tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".PEB."</div>\n<br/>";
			//recherche des informations sur le champ pro_point_ebullition
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_point_ebullition'";
			//les résultats sont retournées dans la variable $result
			$result6=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result6->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[10],$rop[0],"pebulition",PEBULITION."<br/>",DEG,"");
			print"<br/>";
			//recherche des informations sur le champ pro_pression_pb
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_pression_pb'";
			//les résultats sont retournées dans la variable $result
			$result8=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result8->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[11],$rop[0],"pressionpb",PRESSIONPB."<br/>",ATM,"");
			print"</td>
					</tr>
				</table><br/>";
			print"<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".PURETESUB."</div>\n<br/>";
			if($rowselect[34]==0) $rowselect[34]="";
			$formulaire->ajout_text (3, $rowselect[34], 2, "purete", PURETE,"","");
			echo POURCENT;
			print"<br/>\n<br/>\n";
			$formulaire->ajout_text (21, $rowselect[35], 20, "methopurete", METHOPURETE,"","");
			print"</td>
			  </tr>
			</table>";

			$tabsup[1]=RETIRE;
			//##############  UV  #####################
			print"</td>\n<td colspan=\"2\">\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".UV."</div>\n<br/>";
			$sqluv="SELECT uv_text, uv_nom_fichier FROM produit P
			INNER JOIN uv U
			ON P.pro_id_uv=U.uv_id_uv
			WHERE pro_id_produit='".$_POST['id']."'";
			$resultuv =$dbh->query($sqluv);
			$rowuv =$resultuv->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_textarea ("donneesuv",47,$rowuv[0],12,true,DONNEESUV."<br/>");
			print"<br/>";
			$formulaire->ajout_file (30, "fileuv",true,CHARGEUV."<br/>","");
			if (!empty($rowuv[1])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=uv\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("supuv",$tabsup,'','',false);
			}
			//##############  SM  #####################
			print"</td>\n</tr>\n</table>\n</td>\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
					<tr>
					<td class=\"blocformulaire\">\n<div align=\"center\">".SM."</div>\n<br/>";
			$sqlsm="SELECT sm_text, sm_type, sm_nom_fichier FROM produit P
			INNER JOIN sm S
			ON P.pro_id_sm=S.sm_id_sm
			WHERE pro_id_produit='".$_POST['id']."'";
			$resultsm =$dbh->query($sqlsm);
			$rowsm =$resultsm->fetch(PDO::FETCH_NUM);
			$search= array('{','}');
			$rowsm[1]=str_replace($search,'',$rowsm[1]);
			if(empty($rowsm[0])) $rowsm[0]='';
			$formulaire->ajout_textarea ("donneessm",40,$rowsm[0],3,true,SM1."<br/>");
			print"<br/>";
			//recherche des informations sur le champ pro_sm_type
			$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_typesm';";
			//les résultats sont retournées dans la variable $result
			$result13=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row=$result13->fetch(PDO::FETCH_NUM);
			$traitement=new traitement_requete_sql($row[0]);
			$tab=$traitement->imprime();
			$formulaire->ajout_select (1,"smtype",$tab,false,$rowsm[1],SELECTSMTYPE,SMTYPE."<br/>",false,"");
			print"<br/>";
			$formulaire->ajout_file (30, "filesm",true,CHARGESM."<br/>","");
			if (!empty($rowsm[2])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=sm\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("supsm",$tabsup,'','',false);
			}
			print"</td>
				</tr>
				</table>";
			//##############  HRMS  #####################
			print"\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".HSM."</div>\n<br/>";
			$sqlhrms="SELECT hrms_text, hrms_type, hrms_nom_fichier FROM produit P
			INNER JOIN hrms H
			ON P.pro_id_hrms=H.hrms_id_hrms
			WHERE pro_id_produit='".$_POST['id']."'";
			$resulthrms =$dbh->query($sqlhrms);
			$rowhrms =$resulthrms->fetch(PDO::FETCH_NUM);
			$search= array('{','}');
			$rowhrms[1]=str_replace($search,'',$rowhrms[1]);
			if(empty($rowhrms[0])) $rowhrms[0]='';
			$formulaire->ajout_textarea ("donneeshrms",40,$rowhrms[0],3,true,HSM1."<br/>");
			print"<br/>";
			//recherche des informations sur le champ pro_sm_type
			$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_hrmstype';";
			//les résultats sont retournées dans la variable $result
			$result15=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$row=$result15->fetch(PDO::FETCH_NUM);
			$traitement=new traitement_requete_sql($row[0]);
			$tab=$traitement->imprime();
			$formulaire->ajout_select (1,"hrmstype",$tab,false,$rowhrms[1],SELECTHSMTYPE,HSMTYPE."<br/>",false,"");
			print"<br/>";
			$formulaire->ajout_file (30, "filehrms",true,CHARGEHSM."<br/>","");
			if (!empty($rowhrms[2])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=hrms\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("suphrms",$tabsup,'','',false);
			}
			print"</td>
				</tr>
				</table>";
			//##############  IR  #####################
			print"\n</td>\n<td>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".IR."</div>\n<br/>";
			$sqlir="SELECT ir_text, ir_nom_fichier FROM produit P
			INNER JOIN ir I
			ON P.pro_id_ir=I.ir_id_ir
			WHERE pro_id_produit='".$_POST['id']."'";
			$resultir =$dbh->query($sqlir);
			$rowir =$resultir->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_textarea ("donneesir",47,$rowir[0],14,true,DONNEESIR."<br/>");
			print"<br/>";
			$formulaire->ajout_file (30, "fileir",true,CHARGEIR."<br/>","");
			if (!empty($rowir[1])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=ir\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("supir",$tabsup,'','',false);
			}
			print"</td>
				</tr>
				</table>";
			print"\n</td>\n<td>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				  <tr>
					<td class=\"blocformulaire\">\n<div align=\"center\">".ALPHAD.ALPHA."</div>\n<br/>";
			//recherche des informations sur le champ pro_apha
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_apha'";
			//les résultats sont retournées dans la variable $result
			$result9=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result9->fetch(PDO::FETCH_NUM);
			if($rowselect[12]==0.0) $rowselect[12]="";
			$formulaire->ajout_text ($rop[0]+2,$rowselect[12],$rop[0],"alpha",ALPHA."<br/>","","");
			print"<br/>";
			//recherche des informations sur le champ pro_apha_temperature
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_apha_temperature'";
			//les résultats sont retournées dans la variable $result
			$result10=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result10->fetch(PDO::FETCH_NUM);
			if($rowselect[13]==0.0) $rowselect[13]="";
			$formulaire->ajout_text ($rop[0]+2,$rowselect[13],$rop[0],"alphatemp",ALPHATEMP."<br/>",DEG,"");
			print"<br/>";
			//recherche des informations sur le champ pro_apha_concentration
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_apha_concentration'";
			//les résultats sont retournées dans la variable $result
			$result11=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result11->fetch(PDO::FETCH_NUM);
			if($rowselect[14]==0.0) $rowselect[14]="";
			$formulaire->ajout_text ($rop[0]+2,$rowselect[14],$rop[0],"alphaconc",ALPHACONC."<br/>",MOL,"");
			print"<br/>";
			$formulaire->ajout_select (1,"alphasolvant",$tab2,false,$rowselect[15],ALPHASELECSOLV,ALPHASOLVANT."<br/>",false,"");

			print"</td>
				  </tr>
				</table>";
			print"\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				   <tr>
					 <td class=\"blocformulaire\">\n<div align=\"center\">".CCM."</div>\n<br/>";
			//recherche des informations sur le champ pro_rf
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_rf'";
			//les résultats sont retournées dans la variable $result
			$result16=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result16->fetch(PDO::FETCH_NUM);
			if($rowselect[16]==0.00) $rowselect[16]="";
			$formulaire->ajout_text ($rop[0]+1,$rowselect[16],$rop[0],"rf",CCMRF."<br/>","","");
			print"<br/>";
			$formulaire->ajout_text (27,$rowselect[17],256,"ccmsolvant",CCMSOLVANT."<br/>","","");
			print"</td>
				  </tr>
				</table>";
			//##############  RMNH  #####################
			print"\n</td>\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td colspan=\"3\">\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				<tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".SPECTRORMN."</div>\n<br/>";
			$sqlrmnh="SELECT rmnh_text, rmnh_nom_fichier FROM produit P
			INNER JOIN rmnh R
			ON P.pro_id_rmnh=R.rmnh_id_rmnh
			WHERE pro_id_produit='".$_POST['id']."'";
			$resultrmnh =$dbh->query($sqlrmnh);
			$rowrmnh =$resultrmnh->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_textarea ("donneesrmnh",52,$rowrmnh[0],12,true,DONNERRMN.RMNH.DEUXPOINTS."<br/>");
			print"<br/>";
			$formulaire->ajout_file (30, "filermnh",true,CHARGERRMN.RMNH.DEUXPOINTS."<br/>","");
			if (!empty($rowrmnh[1])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=rmnh\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("suprmnh",$tabsup,'','',false);
			}
			//##############  RMNC  #####################
			print"</td>
				  <td class=\"tabtransparent\">&nbsp;</td>
					 <td class=\"blocformulaire\">\n<div align=\"center\">".SPECTRORMN.RMNC."</div>\n<br/>";
			$sqlrmnc="SELECT rmnc_text, rmnc_nom_fichier FROM produit P
			INNER JOIN rmnc C
			ON P.pro_id_rmnc=C.rmnc_id_rmnc
			WHERE pro_id_produit='".$_POST['id']."'";
			$resultrmnc =$dbh->query($sqlrmnc);
			$rowrmnc =$resultrmnc->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_textarea ("donneesrmnc",52,$rowrmnc[0],12,true,DONNERRMN.RMNC.DEUXPOINTS."<br/>");
			print"<br/>";
			$formulaire->ajout_file (30, "filermnc",true,CHARGERRMN.RMNC.DEUXPOINTS."<br/>","");
			if (!empty($rowrmnc[1])) {
				print "<br/><a href=\"telecharge.php?id=".$_POST['id']."&rank=rmnc\" target=\"_blank\">".EXFICHIER."</a>";
				$formulaire->ajout_checkbox ("suprmnc",$tabsup,'','',false);
			}
			print"</td>
				  </tr>
				</table>";
			//********fin de la section analyse********

			//*********Section Bibliographie du formulaire***********
			print"</td>\n</tr>\n<tr>\n<td colspan=\"3\"><div class='hr click_bibliographie'>".BIBLIO." & ".OBSERVATION."</div><hr id='arrow_bibliographie' class='arrow click_bibliographie'>
				  <table class='hr_bibliographie' width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td width=\"50%\">
				  <h3>".BIBLIO."</h3><br/></td><td width=\"50%\"><h3>".OBSERVATION."</h3></td></tr>
				  <tr><td width=\"50%\">
				  <table width=\"250\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
				   <tr >
					 <td class=\"blocformulaire\">\n<div align=\"center\">".PUB."</div>\n<br/>";
			//recherche des informations sur le champ pro_doi
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_doi'";
			//les résultats sont retournées dans la variable $result
			$result18=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result18->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[18],$rop[0],"doi",DOI."<br/>","","");
			print"<br/>";
			//recherche des informations sur le champ pro_hal
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_hal'";
			//les résultats sont retournées dans la variable $result
			$result19=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result19->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[19],$rop[0],"hal",HAL."<br/>","","");
			print"</td>
					</tr>
					</table>\n<br/>";
			//recherche des informations sur le champ pro_cas
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_cas'";
			//les résultats sont retournées dans la variable $result
			$result20=$dbh->query($sql);
			//Les résultats son mis sous forme de tableau
			$rop=$result20->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop[0]+1,$rowselect[20],$rop[0],"cas",CAS."<br/>","","");
			print"</td>\n<td width=\"50%\">";

			$formulaire->ajout_textarea ("observation",55,$rowselect[27],12,true,OBSERVATION.DEUXPOINTS."<br/>");

			//********fin de la section Bibliographie********


			//ajout sous forme de champs caché des informations du formulaire précédant
			$formulaire->ajout_cache ($_POST['type'],"type");
			$formulaire->ajout_cache (rawurldecode($_POST['config']),"config");
			$formulaire->ajout_cache ($_POST['mol'],"mol");
			$formulaire->ajout_cache ($rowinchi[0],"inchi");
			$formulaire->ajout_cache ($rowinchikey[0],"inchikey");
			$formulaire->ajout_cache ($_POST['origimol'],"origimol");
			$formulaire->ajout_cache ($_POST['type'],"type");
			$formulaire->ajout_cache ($_POST['config'],"config");
			$sql="SELECT bingo.getWeight ('".$_POST['mol']."','molecular-weight');";
			$result22=$dbh->query($sql);
			$massemolaire=$result22->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_cache ($massemolaire[0],"massemol");
			$sql="SELECT bingo.Gross ('".$_POST['mol']."');";
			$result21=$dbh->query($sql);
			$formulebrute=$result21->fetch(PDO::FETCH_NUM);
			$formulebrute1=str_replace(' ','',$formulebrute[0]);
			$formulaire->ajout_cache ($formulebrute1,"formulebrute");
			$composition=pourcentelement ($massemolaire[0],$formulebrute[0]);
			$formulaire->ajout_cache ($composition,"composition");
			// $formulaire->ajout_cache ($_POST['logp'],"logp");
			// $formulaire->ajout_cache ($_POST['acceptorcount'],"acceptorcount");
			// $formulaire->ajout_cache ($_POST['rotatablebondcount'],"rotatablebondcount");
			// $formulaire->ajout_cache ($_POST['aromaticatomcount'],"aromaticatomcount");
			// $formulaire->ajout_cache ($_POST['aromaticbondcount'],"aromaticbondcount");
			// $formulaire->ajout_cache ($_POST['donorcount'],"donorcount");
			// $formulaire->ajout_cache ($_POST['asymmetricatomcount'],"asymmetricatomcount");
			$formulaire->ajout_cache ($_POST['id'],"id");
			$formulaire->ajout_cache ($_POST['etapmol'],"etapmol");
			$formulaire->ajout_cache ($_POST['unitmass'],"unitmass");
			//fin de l'ajout des champs cachés

			print"</td>\n</tr>\n</table>\n
			<tr>
			<td colspan=\"3\"><div class='hr click_annexe'>ANNEXE</div><hr id='arrow_annexe' class='arrow click_annexe'>
			<table class='hr_annexe' width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td width=\"50%\"><div id=\"fb-render\"></div>";

			$sql_para="SELECT pro_champsAnnexe FROM produit WHERE pro_id_produit='".$_POST['id']."'";
			$result_para = $dbh->query($sql_para);
			$rowPara=$result_para->fetch(PDO::FETCH_NUM);
			?>

			<script src="js/jquery.min.js"></script>
			<script src="js/jquery-ui.min.js"></script>
			<script src="js/form-builder.min.js"></script>
			<script src="js/form-render.min.js"></script>

			<script>
			/*
			This has been updated to use the new userData method available in formRender
			*/
			const getUserDataBtn = document.getElementById("get-user-data");
			const fbRender = document.getElementById("fb-render");
			const originalFormData =
			<?php
				if(isset($_POST["champsAnnexe"]))
					echo $_POST["champsAnnexe"];
				else
					echo $rowPara[0] ;
			?>;
			jQuery(function($) {
				const formData = JSON.stringify(originalFormData);

				$(fbRender).formRender({ formData });
			});

			</script>

			<?php
			print"
			</tr></table></table>\n<p align=\"right\">";
			unset($dbh);

			echo "<input id=\"champsAnnexe\" name=\"champsAnnexe\" type=\"hidden\" value=\"\">";

			$formulaire->ajout_button (SUBMIT,"","submit","onClick=\"GetSmiles(form,2)\"");
			print"</p>";
			//fin du formulaire

			$formulaire->fin();
			//fermeture de la connexion à la base de données
			unset($dbh);
			echo "<script>
					CKEDITOR.inline( 'modop' );
					CKEDITOR.inline( 'nomiupac' );
					CKEDITOR.inline( 'observation' );
					CKEDITOR.inline( 'donneesrmnc' );
					CKEDITOR.inline( 'donneesrmnh' );
					CKEDITOR.inline( 'donneesir' );
					CKEDITOR.inline( 'donneesuv' );
					CKEDITOR.inline( 'donneessm' );
					CKEDITOR.inline( 'donneeshrms' );
					CKEDITOR.inline( 'hsm' );
					CKEDITOR.inline( 'sm' );
				</script>";

				echo "
				<script>
					$('.hr_analyses').slideToggle();
					$('.hr_bibliographie').slideToggle();
					$('.hr_annexe').slideToggle();

					$('.click_analyses').click(function(){
						$('.hr_analyses').slideToggle();

						if (document.getElementById('arrow_analyses').style.borderWidth == '20px 20px 0px' || document.getElementById('arrow_analyses').style.borderWidth == ''){
							document.getElementById('arrow_analyses').style.borderWidth = '0px 20px 20px 20px';
							document.getElementById('arrow_analyses').style.borderColor = 'transparent transparent #99CC99 transparent';
						}
						else
						if (document.getElementById('arrow_analyses').style.borderWidth == '0px 20px 20px'){
							document.getElementById('arrow_analyses').style.borderWidth = '20px 20px 0 20px';
							document.getElementById('arrow_analyses').style.borderColor = '#99CC99 transparent transparent transparent';
						}
					});
					$('.click_bibliographie').click(function(){
						$('.hr_bibliographie').slideToggle();

						if (document.getElementById('arrow_bibliographie').style.borderWidth == '20px 20px 0px' || document.getElementById('arrow_bibliographie').style.borderWidth == ''){
							document.getElementById('arrow_bibliographie').style.borderWidth = '0px 20px 20px 20px';
							document.getElementById('arrow_bibliographie').style.borderColor = 'transparent transparent #99CC99 transparent';
						}
						else
						if (document.getElementById('arrow_bibliographie').style.borderWidth == '0px 20px 20px'){
							document.getElementById('arrow_bibliographie').style.borderWidth = '20px 20px 0 20px';
							document.getElementById('arrow_bibliographie').style.borderColor = '#99CC99 transparent transparent transparent';
						}
					});
					$('.click_annexe').click(function(){
						$('.hr_annexe').slideToggle();

						if (document.getElementById('arrow_annexe').style.borderWidth == '20px 20px 0px' || document.getElementById('arrow_annexe').style.borderWidth == ''){
							document.getElementById('arrow_annexe').style.borderWidth = '0px 20px 20px 20px';
							document.getElementById('arrow_annexe').style.borderColor = 'transparent transparent #99CC99 transparent';
						}
						else
						if (document.getElementById('arrow_annexe').style.borderWidth == '0px 20px 20px'){
							document.getElementById('arrow_annexe').style.borderWidth = '20px 20px 0 20px';
							document.getElementById('arrow_annexe').style.borderColor = '#99CC99 transparent transparent transparent';
						}
					});
				</script>
				";
		}
		else {
			$erreur=STRUC;
			include_once('formulsaisiemodif.php');
		}
	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');

function testprecaution ($inchimd5,$id_structure) {
	global $dbh;
	$tabpre="";
	$sql="SELECT lis_id_precaution FROM liste_precaution,structure WHERE str_inchi_md5='".$inchimd5."' and structure.str_id_structure=liste_precaution.lis_id_structure";
	$result=$dbh->query($sql);
	$nb=$result->rowCount();
	if ($nb>0) {
		$i=0;
		while($row=$result->fetch(PDO::FETCH_NUM)) {
			$tabpre[$i]=$row[0];
			$i++;
		}
	}
	else {
		$sql="SELECT lis_id_precaution FROM liste_precaution WHERE lis_id_structure='$id_structure'";
		$result1=$dbh->query($sql);
		$i=0;
		while($row1=$result1->fetch(PDO::FETCH_NUM)) {
			$tabpre[$i]=$row1[0];
			$i++;
		}
	}
	return $tabpre;
}
?>
