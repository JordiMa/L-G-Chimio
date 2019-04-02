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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';

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
	//les résultats sont retournées dans la variable $result
	$result1 =$dbh->query($sql);
	$row1 =$result1->fetch(PDO::FETCH_NUM);
	//vérification du statut de l'utilisateur
	if (($row[1]==$row1[1]) or ($row[0]=="{RESPONSABLE}" and $row[2]==$row1[0]) or ($row[0]=="{CHEF}" and in_array($row1[0],$tab5)) or $row[0]=="{ADMINISTRATEUR}") {
		print"\n<script language=\"JavaScript\">
			function GetSmiles(theForm){
				if(document.saisie.masse.value==\"\"){alert(\"".MASSABS."\");}
				else {
					if (document.saisie.etapmol.value==\"\"){alert(\"".ETAPGABS."\");}
					else {
						if (document.JME.smiles()=='') {alert(\"".DESSINSTRUC."\");}
						else {
							document.saisie.mol.value=document.JME.molFile();
							theForm.submit();
						}
					}
				}
			}
          </script>\n";

		require 'script/connectionb.php';
		$sql="SELECT str_mol,pro_masse,typ_type,pro_id_type,pro_configuration,pro_origine_substance,pro_etape_mol,pro_unite_masse, pro_controle_structure,pro_controle_purete, pro_date_controle_purete, pro_numero, pro_num_constant from produit,structure,type where pro_id_produit='".$_POST['id']."' and produit.pro_id_structure=structure.str_id_structure and produit.pro_id_type=type.typ_id_type";
		$result2 =$dbh->query($sql);
		$row2 =$result2->fetch(PDO::FETCH_NUM);

		if ($row[0]=="{ADMINISTRATEUR}") {
			// [JM - 01/02/2019] bouton pour réattribuer la structure à un autre chimiste
			print"<a class='btnlink' target='_blank' href='attributionstructures.php?produit=".$row2[11]."&Rechercher=Rechercher'>Réattribuer la structure à un chimiste</a>";
		}
		$formulaire1=new formulaire ("saisie","saisiemodif2.php","POST",true);
		$formulaire1->affiche_formulaire();
		print"<br/><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td width=\"500\" height=\"500\">";
		if (!empty($_POST["mol"])) $row2[0]=$_POST["mol"];
		if(isset($erreur)) print"<p class=\"erreur\">$erreur</p>";
		$jsme=new dessinmoleculejsme(460,450,$row2[0]);
		$jsme->imprime();
		print"<td>";
		print OBLIGATOIRE."<br/><br/>";

		//recherche des informations sur le champ pro_origine_substance
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_originesubstance';";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$row4=$result4->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row4[0]);
		$tab4=$traitement->imprime();
		$search= array('{','}');
		$row2[5]=str_replace($search,'',$row2[5]);
		$formulaire1->ajout_select (1,"origimol",$tab4,false,$row2[5],SELECTORIGINEMOL,ORIGINEMOL,false,"");
		print"<br/>\n<br/>\n";

		//recherche des informations sur le champ pro_etape_mol
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_etapemol';";
		//les résultats sont retournées dans la variable $result
		$result5=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$row5=$result5->fetch(PDO::FETCH_NUM);
		$traitement5=new traitement_requete_sql($row5[0]);
		$tab5=$traitement5->imprime();
		$search= array('{','}');
		$row2[6]=str_replace($search,'',$row2[6]);
		$formulaire1->ajout_select (1,"etapmol",$tab5,false,$row2[6],SELECTETAPMOL,ETAPMOL,false,"");
		print"<br/>\n<br/>\n";

		$formulaire1->ajout_text (5, $row2[1], 5, "masse", MASS,"","");
		$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE constraint_NAME='contrainte_unitemasse';";
		//les résultats sont retournées dans la variable $result
		$result4=$dbh->query($sql);
		//Les résultats sont mis sous forme de tableau
		$row4=$result4->fetch(PDO::FETCH_NUM);
		$traitement=new traitement_requete_sql($row4[0]);
		$tab4=$traitement->imprime();
		$formulaire1->ajout_select (1,"unitmass",$tab4,false,$row2[7],"","",false,"");
		print"<br/>\n<br/>\n";
		if ($row[0]=="{ADMINISTRATEUR}") {
			$sql="SELECT typ_id_type,typ_type FROM type";
			$result3 = $dbh->query($sql);
			while($row3 = $result3->fetch(PDO::FETCH_NUM)) {
				$tab[$row3[0]]=constant($row3[1]);
			}
			$formulaire1->ajout_select (1,"type",$tab,false,$row2[3],"",TYPE,false,"");

			//if ($row2[9]){
				print
					"<br><br>
						<label for='chx_purete'>Pureté contrôlée :</label>
						<select id='chx_purete' name='chx_purete'>
						<option value='0' "; if ($row2[9] == 0) echo "selected"; echo ">Non contrôlée</option>
						<option value='1' "; if ($row2[9] == 1) echo "selected"; echo ">Contrôle en cours</option>
						<option value='2' "; if ($row2[9] == 2) echo "selected"; echo ">Contrôlée et validé</option>
						<option value='3' "; if ($row2[9] == 3) echo "selected"; echo ">Contrôlée et invalidé</option>
					</select>";


			//if ($row2[8]){
					print
						"<br><br>
							<label for='chx_structure'>Structure contrôlée :</label>
							<select id='chx_structure' name='chx_structure'>
								<option value='0' "; if ($row2[8] == 0) echo "selected"; echo ">Non contrôlée</option>
								<option value='1' "; if ($row2[8] == 1) echo "selected"; echo ">Contrôle en cours</option>
								<option value='2' "; if ($row2[8] == 2) echo "selected"; echo ">Contrôlée et validé</option>
								<option value='3' "; if ($row2[8] == 3) echo "selected"; echo ">Contrôlée et invalidé</option>
						</select>";

						$sql_evotec = "select evo_date_envoie, evo_insoluble from evotec where evo_numero_permanent = $row2[12]";
						$result_evotec = $dbh->query($sql_evotec);
						$row_evotec = $result_evotec->fetch(PDO::FETCH_NUM);

						if (count($row_evotec) > 1 ){
							echo "<input type='hidden' id='chezEvo' name='chezEvo' value=1 />";
							echo "<input type='hidden' id='pro_num_constant' name='pro_num_constant' value='$row2[12]' />";
							echo "<br/><br/><label>" .DATE_ENVOIE_EVOTEC . "</label> " .$row_evotec[0];
							echo "<br/><br/><label>Insoluble chez Evotec ?</label> ";
							echo "<input type='checkbox' id='evo_insoluble' name='evo_insoluble' ";
							if ($row_evotec[1])
								echo "checked ";
							echo "/>";
						}

		}
		else {
			print"<strong>".TYPE."</strong> ".constant ($row2[2]);
			$formulaire1->ajout_cache ($row2[3],"type");
		}
		print"<br/>\n<br/>\n";

		$formulaire1->ajout_cache ("","mol");
		// $formulaire1->ajout_cache ("","inchi");
		// $formulaire1->ajout_cache ("","inchimd5");
		// $formulaire1->ajout_cache ("","massemol");
		// $formulaire1->ajout_cache ("","formulebrute");
		// $formulaire1->ajout_cache ("","nom");
		// $formulaire1->ajout_cache ("","logp");
		// $formulaire1->ajout_cache ("","donorcount");
		// $formulaire1->ajout_cache ("","acceptorcount");
		// $formulaire1->ajout_cache ("","composition");
		// $formulaire1->ajout_cache ("","aromaticatomcount");
		// $formulaire1->ajout_cache ("","aromaticbondcount");
		// $formulaire1->ajout_cache ("","rotatablebondcount");
		// $formulaire1->ajout_cache ("","asymmetricatomcount");
		$formulaire1->ajout_cache ($_POST['id'],"id");
		print"</td>\n</tr>\n<tr>\n<td>";
		print"<a href=\"images/CNBrochure.pdf\" target=\"_blank\"><strong>".RECOMMANDATION."</strong></a><br/><br/>";
		$formulaire1->ajout_text (45, $row2[4], 256, "config", CONFIG,"","");
		print"</td>\n<td align=\"right\">\n";
		$formulaire1->ajout_button (SUBMIT,"","button","onClick=\"GetSmiles(form)\"");
		print"</td>\n</tr>\n</table>";
		$formulaire1->fin();
		//fermeture de la connexion à la base de données
		unset($dbh);

	}
	else include_once('presentatio.php');
}
else include_once('presentatio.php');
?>
