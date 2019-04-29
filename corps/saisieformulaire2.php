<script type="text/javascript" src="js/jquery.min.js"></script>

﻿<?php
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
include_once 'massemolaire.php';
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';

include 'numero.php';

if (!empty($_POST['mol']) && $_POST['masse']!="") {

	if(!isset($_POST['couleur'])) $_POST['couleur']="";
	if(!isset($_POST['purification'])) $_POST['purification']="";
	if(!isset($_POST['aspect'])) $_POST['aspect']="";
	if(!isset($_POST['precaution'])) $_POST['precaution']="";
	if(!isset($_POST['ref'])) $_POST['ref']="";
	if(!isset($_POST['modop'])) $_POST['modop']="";
	if(!isset($_POST['contrat'])) $_POST['contrat']="";
	if(!isset($_POST['duree'])) $_POST['duree']="";
	if(!isset($_POST['couleur'])) $_POST['couleur']="";
	if(!isset($_POST['anaelem'])) $_POST['anaelem']="";
	if(!isset($_POST['pfusion'])) $_POST['pfusion']="";
	if(!isset($_POST['pebulition'])) $_POST['pebulition']="";
	if(!isset($_POST['pressionpb'])) $_POST['pressionpb']="";
	if(!isset($_POST['donneesuv'])) $_POST['donneesuv']="";
	if(!isset($_POST['donneesir'])) $_POST['donneesir']="";
	if(!isset($_POST['donneessm'])) $_POST['donneessm']="";
	if(!isset($_POST['smtype'])) $_POST['smtype']="";
	if(!isset($_POST['donneeshrms'])) $_POST['donneeshrms']="";
	if(!isset($_POST['hrmstype'])) $_POST['hrmstype']="";
	if(!isset($_POST['alpha'])) $_POST['alpha']="";
	if(!isset($_POST['alphasolvant'])) $_POST['alphasolvant']="";
	if(!isset($_POST['alphatemp'])) $_POST['alphatemp']="";
	if(!isset($_POST['alphaconc'])) $_POST['alphaconc']="";
	if(!isset($_POST['rf'])) $_POST['rf']="";
	if(!isset($_POST['ccmsolvant'])) $_POST['ccmsolvant']="";
	if(!isset($_POST['observation'])) $_POST['observation']="";
	if(!isset($_POST['cas'])) $_POST['cas']="";
	if(!isset($_POST['hal'])) $_POST['hal']="";
	if(!isset($_POST['doi'])) $_POST['doi']="";
	if(!isset($_POST['donneesrmnh'])) $_POST['donneesrmnh']="";
	if(!isset($_POST['donneesrmnc'])) $_POST['donneesrmnc']="";
	if(!isset($_POST['numerocomplet'])) $_POST['numerocomplet']="";
	if(!isset($_POST['numbrevet'])) $_POST['numbrevet']="";
	if(!isset($_POST['purete'])) $_POST['purete']="";
	if(!isset($_POST['methopurete'])) $_POST['methopurete']="";
	if(!isset($_POST['qrcode'])) $_POST['qrcode']="";
	if(!isset($_POST['nomiupac'])) $_POST['nomiupac']="";

	$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
	$result22=$dbh->query($sql);
	$row22 =$result22->fetch(PDO::FETCH_NUM);
	if ($row22[0]=="{ADMINISTRATEUR}" or $row22[0]=="{CHEF}") {
		$tabequipe=explode ('/',$_POST['equipe']);
		$equipe=$tabequipe[0];
	}
	else $equipe=$row22[2];

	//recherche de la masse limite de stockage
	$sql="SELECT para_stock,para_numerotation FROM parametres";
	$result21=$dbh->query($sql);
	$row21=$result21->fetch(PDO::FETCH_NUM);
	if ($_POST['masse']>=$row21[0]) $typenumero=1;
	else  $typenumero=2;

    if ($row21[1]=="AUTO") {
		if(!isset($tab23)) $tab23=NULL;
        //recherche des parametres du numero definient par l'administrateur
        $sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='$typenumero' ORDER BY num_id_numero";
        $resultat24=$dbh->query($sql);
        while ($row24=$resultat24->fetch(PDO::FETCH_NUM)) {
			$tab24[]=$row24[0];
        }

        if (in_array("{BOITE}",$tab24) and in_array("{COORDONEE}",$tab24)) {
			//recherche de la liste des numéros pour une équipe et un type (libre, contrat, brevet) donné
			$sql="SELECT pro_num_boite,pro_num_position FROM produit WHERE pro_id_equipe='$equipe' and pro_id_type='".$_POST['type']."' and pro_num_boite<>'0' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
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
			$sql="SELECT pro_num_boite,pro_num_incremental FROM produit WHERE pro_id_equipe='$equipe' and pro_id_type='".$_POST['type']."' and pro_num_boite<>'0' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
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
				$sql="SELECT pro_num_incremental FROM produit WHERE pro_id_equipe='$equipe' and pro_id_type='".$_POST['type']."' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental";
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
				$sql="SELECT pro_num_sansmasse FROM produit WHERE pro_id_equipe='$equipe' and pro_id_type='".$_POST['type']."' ORDER BY pro_num_boite,pro_num_position,pro_num_incremental,pro_num_sansmasse";
				$result23=$dbh->query($sql);
				$o=0;
				while($row23=$result23->fetch(PDO::FETCH_NUM)) {
					$tab23[$o]=$row23[0];
					$o++;
				}
				$numoincremental="";
			}
        }
        $nbtab23=count($tab23);
        $o=0;
        $numeroassemble=numero($typenumero);

		//vidange de la table temporaire
        $sql="DELETE FROM numerotation_temporaire WHERE nume_date<>'".date("Y-m-d")."'";
        $deletenum=$dbh->query($sql);

        //insertion du numéro dans la table temporaire
        while ($o<1) {
			if ($nbtab23==0) {
				$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$_POST['type']."','$equipe','".date("Y-m-d")."')";
				$insertnum=$dbh->query($sql);
				if (!empty($insertnum))  $o=1;
				else $numeroassemble=numero($typenumero);
			}
			elseif (!in_array($numeroassemble,$tab23)) {
				$sql="INSERT INTO numerotation_temporaire (nume_tempo,nume_type,nume_equipe,nume_date) VALUES ('$numeroassemble','".$_POST['type']."','$equipe','".date("Y-m-d")."')";
				$insertnum=$dbh->query($sql);
				if (!empty($insertnum))  $o=1;
				else $numeroassemble=numero($typenumero);
			}
			else $numeroassemble=numero($typenumero);
		}
    }

	$_POST['masse']=trim($_POST['masse']);
	// $tabinchi=preg_split("[\n]",$_POST["inchi"]);
	// $_POST["inchi"]=str_replace("\r","",$_POST["inchi"]);
	// $_POST["inchimd5"]=str_replace("InChIKey=","",$_POST["inchimd5"]);

	if ($_POST["mol"]!="") {
		//javascript de vérification des champs obligatoires
		echo"
<script language=\"JavaScript\">
		function contains(onechar, lstring)
		{
			retval = false
			for (var i = 1; i <= lstring.length; i++)
			{
				if (lstring.substring(i, i + 1) == onechar)
				{
					retval = true
					break
				}
			}
			return retval
		}

		function selection(lstring)
		{
			retvale = false
			for (var i = 1; i < lstring.length; i++)
			{
				if (document.chimiotheque.D5.options[i].text == document.chimiotheque.T3.value)
				{
					retvale = true
					break
				}
			}
			return retvale
		}

		function GetSmiles(theForm, valeurformu)
		{
			if (valeurformu == 2)
			{
				document.saisie2.action = \"transfert.php\";
				var i = 0;
				var top = true;
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

				if (CKEDITOR.instances.nomiupac.getData() == \"\" && document.saisie2.config_nomenclature.value== '1')
				{
					alert(\"".CHAMP." \'".NOM."\' ".RENSEIGNE."\");
				}
				else
				{
					if (document.saisie2.masse.value == \"\")
					{
						alert(\"".CHAMP." \'".MASS."\' ".RENSEIGNE."\");
					}
					else {
						if (document.saisie2.numerocomplet.value == \"\") {
							alert(\"".CHAMP." \'".NBPILLULIER."\' ".RENSEIGNE."\");
						}
						else
						{
							if ((isNaN(document.saisie2.masse.value)) || (contains(\".\",saisie2.masse.value)))
							{
								alert(\"".CHAMP." \'".MASS."\' ".ERREURMASSE."\");
							}
							else
							{
								if (document.saisie2.couleur.value == \"\" && document.saisie2.config_couleur.value== '1')
								{
									alert(\"".CHAMP." \'".COULEUR."\' ".RENSEIGNE."\");
								}
								else
								{
									if (document.saisie2.ref.value == \"\" && document.saisie2.config_refCahier.value== '1')
									{
										alert(\"".CHAMP." \'".REFERENCECAHIER."\' ".RENSEIGNE."\");
									}
									else
									{
										if (document.saisie2.aspect.value == \"\" && document.saisie2.config_aspect.value== '1')
										{
											alert(\"".CHAMP." \'".ASPECT."\' ".RENSEIGNE."\");
										}
										else
										{
											if (document.saisie2.purification.value == \"\" && document.saisie2.config_typePurif.value== '1')
											{
												alert(\"".CHAMP." \'".PURIFICATION."\' ".RENSEIGNE."\");
											}
											else
											{
												if ((document.saisie2.purification.value == \"".RECRISTALLISATION. "\" || document.saisie2.purification.value==\"".PRECIPITATION."\") && (document.saisie2.aspect.value==\"".LIQUIDE."\"))
												{
													alert(\"".RECRISTALISE."\");
												}
												else
												{
													if (document.saisie2.purification.value == \"".DISTILLATION."\" && document.saisie2.aspect.value==\"".SOLIDE."\")
													{
														alert(\"".DISTILATION."\");
													}
													else
													{
														if (!top)
														{
															alert(\"".CHAMP." \'".SOLVANTS."\' ".RENSEIGNE."\");
														}
														else
														{
															var verifRequired = true;
															var all = document.getElementsByClassName(\"fld-required\");

															for (var i=0, max=all.length; i < max; i++) {
																	if(document.getElementsByClassName(\"fld-required\")[i].checked){
																		if(document.getElementsByClassName(\"fld-required\")[i].parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByClassName(\"form-control\")[0].value == \"\"){
																			verifRequired = false;
																			alert('\'' + document.getElementsByClassName(\"fld-required\")[i].parentElement.parentElement.parentElement.parentElement.parentElement.getElementsByClassName(\"field-label\")[0].innerHTML + '\' n\'est pas renseigné (Volet déroulant \'ANNEXE\')' );
																		}
																	}
															}
															document.getElementById('champsAnnexe').value = window.JSON.stringify(formBuilder.actions.getData('json', true));
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
			}
			else
			{
				if (valeurformu == 1)
				{
					if ((document.saisie2.masse.value >= 5 && document.saisie2.massehold.value < 5) || (document.saisie2.masse.value < 5 && document.saisie2.massehold.value >= 5))
					{
						document.saisie2.action = \"saisie2.php\";
						document.getElementById('champsAnnexe').value = window.JSON.stringify(formBuilder.actions.getData('json', true));
						theForm.submit();
					}
				}
			}
		}
</script>";
		//fin du javascript

		//affichage des erreurs du formulaire après traitement par traitement.php
		if (isset($erreur)) echo $erreur;
		//fin de l'affichage des erreurs
		print OBLIGATOIRE."<br/>";
		//initialisation du formulaire
		$formulaire=new formulaire ("saisie2","","POST",true);
		$formulaire->affiche_formulaire();
		// Verification des champs obligatoires
		print"<input type='hidden' name='config_couleur' value='".$config_data['couleur']."'>";
		print"<input type='hidden' name='config_typePurif' value='".$config_data['typePurif']."'>";
		print"<input type='hidden' name='config_aspect' value='".$config_data['aspect']."'>";
		print"<input type='hidden' name='config_refCahier' value='".$config_data['refCahier']."'>";
		print"<input type='hidden' name='config_nomenclature' value='".$config_data['nomenclature']."'>";
		print"<input type='hidden' name='config_solvantsDeSolubilisation' value='".$config_data['solvantsDeSolubilisation']."'>";

		//sélection des couleur dans la base de données
		$sql = "SELECT * FROM couleur";
		//les résultats sont retournées dans la variable $result
		$result =$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		while($row =$result->fetch(PDO::FETCH_NUM)) {
			$tab[$row[0]]=$row[1];
		}
		print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
			  <td>";
		echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
		$jme=new visualisationmoleculejme (300,300,$_POST['mol']);
		$jme->imprime();
		print"</td>\n<td>\n";
		if ($row21[1]=="AUTO") {
			//définition du numéro réservé
			$numerocomplet="";
			$sql="SELECT num_type,num_valeur FROM numerotation WHERE num_parametre='$typenumero' ORDER BY num_id_numero";
			$resultat25=$dbh->query($sql);
			while ($row25=$resultat25->fetch(PDO::FETCH_NUM)) {
				if ($row25[0]=="{FIXE}") $numerocomplet.=$row25[1];
				elseif ($row25[0]=="{EQUIPE}") {
					$sql="SELECT equi_initiale_numero FROM equipe WHERE equi_id_equipe='$equipe'";
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
		elseif ($row21[1]=="MANU") {
			//recherche des informations sur le champ pro_numero
			$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_numero'";
			//les résultats sont retournées dans la variable $result
			$result25=$dbh->query($sql);
			//Les résultats sont mis sous forme de tableau
			$rop25=$result25->fetch(PDO::FETCH_NUM);
			$formulaire->ajout_text ($rop25[0], $_POST['numerocomplet'], $rop25[0], "numerocomplet", "* ".NBPILLULIER."<br/>","","");
			print"<br/><br/>";
		}

		$formulaire->ajout_textarea ("qrcode",25,$_POST["qrcode"],4,true,QRCODE2."<br/>");
		print"<br/><br/>";
		$formulaire->ajout_text (5, $_POST['masse'], 5, "masse", MASS."<br/>",constant($_POST['unitmass']),"onBlur=GetSmiles(form,1)");

		$formulaire->ajout_cache ($_POST['masse'],"massehold");
		print"<br/><br/>";
		$formulaire->ajout_select (1,"couleur",$tab,false,$_POST['couleur'],SELECCOULEUR,COULEUR."<br/>",true,"");
		print"<br/><br/>";

		//recherche des informations sur le champ pro_purification
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_purification';";
		//les résultats sont retournées dans la variable $result1
		$result1=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$row=$result1->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row[0]);
		$tab=$traitement->imprime();
		$formulaire->ajout_select (1,"purification",$tab,false,$_POST['purification'],SELECPURIFICATION,PURIFICATION."<br/>",false,"");
		print"<br/><br/>";

		//recherche des informations sur le champ pro_aspect
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_aspect';";
		//les résultats sont retournées dans la variable $result
		$result2=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result2->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($rop[0]);
		$tab=$traitement->imprime();
		//if (isset($_POST['purification'])) $valaspect=constant($_POST['aspect']);
		//else $valaspect="";
		$formulaire->ajout_select (1,"aspect",$tab,false,$_POST['aspect'],SELECASPECT,ASPECT."<br/>",false,"");
		print"<br/><br/>";

		$sql="SELECT * FROM precaution ORDER BY pre_precaution ASC";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		while($row =$result4->fetch(PDO::FETCH_NUM)) {
			$tab1[$row[0]]=constant($row[1]);
		}
		//recherche si la structure existe et si celle-ci a déjà des précautions attribuées
		// transformation en Inchikey via Bingo
		$sql="SELECT Bingo.InchI('".$_POST["mol"]."','')";
		$resultinchi=$dbh->query($sql);
		$rowinchi=$resultinchi->fetch(PDO::FETCH_NUM);

		$sql="SELECT bingo.InChIKey ('".$rowinchi[0]."')";
		$resultinchikey=$dbh->query($sql);
		$rowinchikey=$resultinchikey->fetch(PDO::FETCH_NUM);

		$sql="SELECT lis_id_precaution FROM structure,liste_precaution,precaution WHERE str_inchi_md5='".$rowinchikey[0]."' and structure.str_id_structure=liste_precaution.lis_id_structure";
		$resultprecaution=$dbh->query($sql);
		$num_res_precaution=$resultprecaution->rowCount();
		if ($num_res_precaution>0) {
			while($rowprec=$resultprecaution->fetch(PDO::FETCH_NUM)) {
				$tabpre[$rowprec[0]]=$rowprec[0];
			}
		}
		else $tabpre=$_POST['precaution'];

		$formulaire->ajout_select (3,"precaution",$tab1,true,$tabpre,"",PRECAUTION."<br/>",false,"");
		print"<br/><br/>";

		//recherche des informations sur le champ pro_ref_cahier_labo
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_ref_cahier_labo'";
		//les résultats sont retournées dans la variable $result
		$result3=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result3->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text (intval($rop[0]/1.5), $_POST['ref'], $rop[0], "ref", REFERENCECAHIER."<br/>","","");

		print"</td>\n<td>";
		//recherche la liste des solvants sur la table solvant
		$sql="SELECT * FROM solvant ORDER BY sol_solvant ASC";
		//les résultats sont retournées dans la variable $result
		$result5=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		while($row1 =$result5->fetch(PDO::FETCH_NUM)) {
			$tab2[$row1[0]]=constant($row1[1]);
		}
		//recherche de solvants sur la table solvant
		$sql="SELECT count(sol_id_solvant) FROM solvant";
		//les résultats sont retournées dans la variable $result
		$resultsol=$dbh->query($sql);
		$isolvant=0;
		while($countsol=$resultsol->fetch(PDO::FETCH_NUM)) {
			for ($i=0; $i<$countsol[0]; $i++) {
				if (!empty ($_POST["solvant$i"])) {
					$tabsolvant[$isolvant]=$_POST["solvant$i"];
					$isolvant++;
				}
			}
		}
		if (empty($tabsolvant) or !isset($tabsolvant)) $tabsolvant='';
		$formulaire->ajout_checkbox ("solvant",$tab2,$tabsolvant,SOLVANTS."<br/>",false);

		print"</td>\n</tr>\n<tr valign=\"top\">\n<td>\n";
		$formulaire->ajout_textarea ("nomiupac",30,$_POST["nomiupac"],12,true,NOM."<br/>");

		print"</td>\n<td";
		if ($_POST['type']==1) print" colspan=\"2\"";
		print">\n";
		$formulaire->ajout_textarea ("modop",47,$_POST['modop'],12,true,MODOP."<br/>");

		if ($_POST['type']>1) {
			print"\n</td>\n<td>";
			if ($_POST['type']==2) {
				$formulaire->ajout_textarea ("contrat",20,$_POST['contrat'],9,true,CONTRATDESC."<br/>");
				print"<br/>";
				$formulaire->ajout_text (3,$_POST['duree'],10,"duree",DUREE."<br/>",AN,"");
			}
			if ($_POST['type']==3) $formulaire->ajout_text (20,$_POST['numbrevet'],"","numbrevet",NUMBREVET."<br/>","","");
		}

		//*********Section analyse du formulaire***********
		print"</td>\n</tr>\n<tr>\n<td colspan=\"3\"><div class='hr click_analyses'>".ANALYSE."</div><hr id='arrow_analyses' class='arrow click_analyses'></td>\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td><h3>".ANALYSE."</h3><br/>";
		//recherche des informations sur le champ pro_analyse_elem_trouve
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_analyse_elem_trouve'";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result4->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$formulaire->ajout_text (45,$_POST['anaelem'],$rop[0],"anaelem",ANAELEM."<br/>","","");
		print"<br/><br/>";
		//recherche des informations sur le champ pro_point_fusion
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_point_fusion'";
		//les résultats sont retournées dans la variable $result
		$result7=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result7->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$formulaire->ajout_text ($rop[0]+1,$_POST['pfusion'],$rop[0],"pfusion",PFUSION."<br/>",DEG,"");
		print"<br/><br/>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".PEB."</div>\n<br/>";
		//recherche des informations sur le champ pro_point_ebullition
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_point_ebullition'";
		//les résultats sont retournées dans la variable $result
		$result6=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$rop=$result6->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$formulaire->ajout_text ($rop[0]+1,$_POST['pebulition'],$rop[0],"pebulition",PEBULITION."<br/>",DEG,"");
		print"<br/>";
		//recherche des informations sur le champ pro_pression_pb
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_pression_pb'";
		//les résultats sont retournées dans la variable $result
		$result8=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result8->fetch(PDO::FETCH_NUM);
		//traitement du resultat afin de retourner la taille maximale du champ
		$formulaire->ajout_text ($rop[0]+1,$_POST['pressionpb'],$rop[0],"pressionpb",PRESSIONPB."<br/>",ATM,"");
		print"</td>
			  </tr>
			</table><br/>";
		print"<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".PURETESUB."</div>\n<br/>";
		$formulaire->ajout_text (4, $_POST['purete'], 15, "purete", PURETE,"","");
		echo POURCENT;
		print"<br/>\n<br/>\n";
		$formulaire->ajout_text (21, $_POST['methopurete'], 20, "methopurete", METHOPURETE,"","");
		print"</td>
			  </tr>
			</table>";
		print"</td>\n<td colspan=\"2\"><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".UV."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneesuv",75,$_POST['donneesuv'],15,true,DONNEESUV."<br/>");
		print"<br/>";
		$formulaire->ajout_file (30, "fileuv",true,CHARGEUV."<br/>","");
		print"</table></td>\n";

		print"\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".SM."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneessm",40,$_POST['donneessm'],3,true,SM1."<br/>");
		print"<br/>";
		//recherche des informations sur le champ pro_sm_type
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_typesm';";
		//les résultats sont retournées dans la variable $result
		$result13=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$row=$result13->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row[0]);
		$tab=$traitement->imprime();
		$formulaire->ajout_select (1,"smtype",$tab,false,$_POST['smtype'],SELECTSMTYPE,SMTYPE."<br/>",false,"");
		print"<br/>";
		$formulaire->ajout_file (30, "filesm",true,CHARGESM."<br/>","");
		print"</td>
			  </tr>
			</table>";
		print"\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".HSM."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneeshrms",40,$_POST['donneeshrms'],3,true,HSM1."<br/>");
		print"<br/>";
		//recherche des informations sur le champ pro_sm_type
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_hrmstype';";
		//les résultats sont retournées dans la variable $result
		$result15=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$row=$result15->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row[0]);
		$tab=$traitement->imprime();
		$formulaire->ajout_select (1,"hrmstype",$tab,false,$_POST['hrmstype'],SELECTHSMTYPE,HSMTYPE."<br/>",false,"");
		print"<br/>";
		$formulaire->ajout_file (30, "filehrms",true,CHARGEHSM."<br/>","");
		print"</td>
			  </tr>
			</table>";
		print"\n</td>\n<td>\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			  <tr>
				<td class=\"blocformulaire\">\n<div align=\"center\">".IR."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneesir",47,$_POST['donneesir'],14,true,DONNEESIR."<br/>");
		print"<br/>";
		$formulaire->ajout_file (30, "fileir",true,CHARGEIR."<br/>","");
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
		//Les résultats sont mis sous forme de tableau
		$rop=$result9->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+2,$_POST['alpha'],$rop[0],"alpha",ALPHA."<br/>","","");
		print"<br/>";
		//recherche des informations sur le champ pro_apha_temperature
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_apha_temperature'";
		//les résultats sont retournées dans la variable $result
		$result10=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result10->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+2,$_POST['alphatemp'],$rop[0],"alphatemp",ALPHATEMP."<br/>",DEG,"");
		print"<br/>";
		//recherche des informations sur le champ pro_apha_concentration
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_apha_concentration'";
		//les résultats sont retournées dans la variable $result
		$result11=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$rop=$result11->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+2,$_POST['alphaconc'],$rop[0],"alphaconc",ALPHACONC."<br/>",MOL,"");
		print"<br/>";
		$formulaire->ajout_select (1,"alphasolvant",$tab2,false,$_POST['alphasolvant'],ALPHASELECSOLV,ALPHASOLVANT."<br/>",false,"");

		print"</td>
			  </tr>
			</table><br/>";
		print"\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			   <tr>
				 <td class=\"blocformulaire\">\n<div align=\"center\">".CCM."</div>\n<br/>";
		//recherche des informations sur le champ pro_rf
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_rf'";
		//les résultats sont retournées dans la variable $result
		$result16=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$rop=$result16->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+1,$_POST['rf'],$rop[0],"rf",CCMRF."<br/>","","");
		print"<br/>";
		$formulaire->ajout_text (27,$_POST['ccmsolvant'],256,"ccmsolvant",CCMSOLVANT."<br/>","","");
		print"</td>
				</tr>
			  </table>";
		print"\n</td>\n</tr>\n<tr class='hr_analyses' valign=\"top\">\n<td colspan=\"3\">\n<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">
			   <tr>
				 <td class=\"blocformulaire\">\n<div align=\"center\">".SPECTRORMN."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneesrmnh",52,$_POST['donneesrmnh'],12,true,DONNERRMN.RMNH.DEUXPOINTS."<br/>");
		print"<br/>";
		$formulaire->ajout_file (30, "filermnh",true,CHARGERRMN.RMNH.DEUXPOINTS."<br/>","");
		print"</td>
			  <td class=\"tabtransparent\">&nbsp;</td>
				 <td class=\"blocformulaire\">\n<div align=\"center\">".SPECTRORMN.RMNC."</div>\n<br/>";
		$formulaire->ajout_textarea ("donneesrmnc",52,$_POST['donneesrmnc'],12,true,DONNERRMN.RMNC.DEUXPOINTS."<br/>");
		print"<br/>";
		$formulaire->ajout_file (30, "filermnc",true,CHARGERRMN.RMNC.DEUXPOINTS."<br/>","");
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
		$formulaire->ajout_text ($rop[0]+1,$_POST['doi'],$rop[0],"doi",DOI."<br/>","","");
		print"<br/>";
		//recherche des informations sur le champ pro_hal
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_hal'";
		//les résultats sont retournées dans la variable $result
		$result19=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$rop=$result19->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+1,$_POST['hal'],$rop[0],"hal",HAL."<br/>","","");
		print"</td>
				</tr>
			  </table>\n<br/>";
		//recherche des informations sur le champ pro_cas
		$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='pro_cas'";
		//les résultats sont retournées dans la variable $result
		$result20=$dbh->query($sql);
		//Les résultats son mis sous forme de tableau
		$rop=$result20->fetch(PDO::FETCH_NUM);
		$formulaire->ajout_text ($rop[0]+1,$_POST['cas'],$rop[0],"cas",CAS."<br/>","","");
		print"</td>\n<td width=\"50%\">";
		//********fin de la section Bibliographie********


		$formulaire->ajout_textarea ("observation",52,$_POST['observation'],12,true,OBSERVATION.DEUXPOINTS."<br/>");
		//ajout sous forme de champs caché des informations du formulaire précédant
		$formulaire->ajout_cache ($_POST['mol'],"mol");
		$formulaire->ajout_cache ($rowinchi[0],"inchi");
		$formulaire->ajout_cache ($rowinchikey[0],"inchikey");
		$formulaire->ajout_cache ($_POST['type'],"type");
		$formulaire->ajout_cache ($_POST['config'],"config");
		$formulaire->ajout_cache ($_POST['origimol'],"origimol");
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

		// $_POST['logp']=str_replace(",",".",$_POST['logp']);
		// $formulaire->ajout_cache ($_POST['logp'],"logp");
		// $formulaire->ajout_cache ($_POST['acceptorcount'],"acceptorcount");
		// $formulaire->ajout_cache ($_POST['rotatablebondcount'],"rotatablebondcount");
		// $formulaire->ajout_cache ($_POST['aromaticatomcount'],"aromaticatomcount");
		// $formulaire->ajout_cache ($_POST['aromaticbondcount'],"aromaticbondcount");
		// $formulaire->ajout_cache ($_POST['donorcount'],"donorcount");
		// $formulaire->ajout_cache ($_POST['asymmetricatomcount'],"asymmetricatomcount");
		$formulaire->ajout_cache ($_POST['etapmol'],"etapmol");
		$formulaire->ajout_cache ($_POST['unitmass'],"unitmass");


		if ($row22[0]=="{ADMINISTRATEUR}" or $row22[0]=="{CHEF}") {
			$tabequipe=explode ("/",$_POST['equipe']);
			$formulaire->ajout_cache ($tabequipe[0],"equipe");
			$formulaire->ajout_cache ($tabequipe[1],"responsable");
			if ($row22[0]=="{ADMINISTRATEUR}")
				$formulaire->ajout_cache ($tabequipe[2],"chimiste");
		}
		//fin de l'ajout des champs cachés

		//fermeture de la connexion à la base de données

		print"</td>\n</tr>\n</table>\n
		<tr>
		<td colspan=\"3\"><div class='hr click_annexe'>ANNEXE</div><hr id='arrow_annexe' class='arrow click_annexe'>
		<table class='hr_annexe' width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td width=\"50%\"><div id=\"fb-editor\"></div>";

		$sql_para="SELECT para_champs FROM parametres WHERE para_id_parametre = 1";
		$result_para = $dbh->query($sql_para);
		$rowPara=$result_para->fetch(PDO::FETCH_NUM);
		?>

		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/form-builder.min.js"></script>

		<script>
			jQuery(function($) {
				var options = {
						i18n: {
							locale: 'fr-FR'
						},
						disableFields: [
							'file',
							'hidden',
							'button'
						],
						disabledAttrs: [
							'className',
							'access',
							'name'
						],
						disabledActionButtons: [
							'data',
							'save',
							'clear'
					]
					},
					$fbTemplate = $(document.getElementById('fb-editor'));
					formBuilder = $fbTemplate.formBuilder(options);

					var formData =
					<?php
						if(isset($_POST["champsAnnexe"]))
							echo $_POST["champsAnnexe"];
						else
								echo $rowPara[0];
					?>;
					setTimeout(function(){ formBuilder.actions.setData(formData); }, 1000);
			});
		</script>
		<script>
					var filehrms = document.getElementById('filehrms');
					var filesm = document.getElementById('filesm');
					var fileir = document.getElementById('fileir');
					var fileuv = document.getElementById('fileuv');
					var filermnh = document.getElementById('filermnh');
					var filermnc = document.getElementById('filermnc');

					filehrms.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier HRMS est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};

					filesm.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier SM est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};

					fileir.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier IR est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};

					fileuv.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier UV est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};

					filermnh.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier RMNH est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};

					filermnc.onchange = function() {
						if(this.files[0].size > 1048576){
						   alert("le fichier RMNC est trop grand (max 1Mo) !");
						   this.value = "";
						};
					};
				</script>


		<?php
		print"
		</tr></table></table>\n<p align=\"right\">";
		unset($dbh);

		echo "<input id=\"champsAnnexe\" name=\"champsAnnexe\" type=\"hidden\" value=\"\">";

		$formulaire->ajout_button (SUBMIT,"","button","onClick=\"GetSmiles(form,2)\"");
		print"</p>";
		//fin du formulaire

		$formulaire->fin();
		echo "<script>
					CKEDITOR.inline( 'modop' );
					CKEDITOR.inline( 'nomiupac' );
					CKEDITOR.inline( 'observation' );
					CKEDITOR.inline( 'donneesrmnc' );
					CKEDITOR.inline( 'donneesrmnh' );
					CKEDITOR.inline( 'donneesir' );
					CKEDITOR.inline( 'donneesuv' );
					CKEDITOR.inline( 'hsm' );
					CKEDITOR.inline( 'sm' );
				</script>\n";

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
		include_once('saisie1.php');
    }
}
else include_once('saisie1.php');
?>
