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
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
?>

<div class="divmenu">
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td class=cellulebleu>
				<?php	echo MENU; ?>
			</td>
		</tr>
		<tr align="left">
			<td height="100%" valign="top" class=celluleblanche style="padding-left: 5;">
				<br/>
				<a class="mnu" href="saisie.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_1','','images/pucerouge.gif',1)"><img name="img_1" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo SAISIE; ?></a><br/><br/>
				<a class="mnu" href="modification.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_2','','images/pucerouge.gif',1)"><img name="img_2" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo MODIF; ?></a><br/><br/>
				<?php if ($row[0]=='{RESPONSABLE}' or $row[0]=='{CHEF}'): ?>
					<a class="mnu" href="resultatbiorespon.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_6','','images/pucerouge.gif',1)"><img name="img_6" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo RESULTBIO; ?></a><br/><br/>
				<?php endif; ?>
				<a class="mnu" href="rechercher.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_3','','images/pucerouge.gif',1)"><img name="img_3" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo RECHERCHE; ?></a><br/><br/>
				<a class="mnu" href="compte.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_4','','images/pucerouge.gif',1)"><img name="img_4" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo COMPTE; ?></a><br/><br/>
				<a class="mnu" href="deconnexion.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_5','','images/pucerouge.gif',1)"><img name="img_5" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo DECONNEXION; ?></a><br/><br/>
			</td>
		</tr>
	</table>
</div>
<br/>
<br/>

<div class="divmenu">
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td class=cellulebleu>
				<?php	echo "Extractothèque"; ?>
			</td>
		</tr>
		<tr align="left">
			<td height="100%" valign="top" class=celluleblanche style="padding-left: 5;">
				<br/>
				<!-- a class="mnu" href="saisie_Extrait.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_14','','images/pucerouge.gif',1)"><img name="img_14" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php //echo SAISIE;?></a><br/><br/-->
				<a class="mnu" href="saisie_Extrait2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_18','','images/pucerouge.gif',1)"><img name="img_18" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo SAISIE; ?></a><br/><br/>
				<a class="mnu" href="modification_Extrait.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_15','','images/pucerouge.gif',1)"><img name="img_15" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo MODIF; ?></a><br/><br/>
				<a class="mnu" href="recherche_Extrait.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_16','','images/pucerouge.gif',1)"><img name="img_16" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo RECHERCHE; ?></a><br/><br/>
				<a class="mnu" href="importation_Extrait.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_17','','images/pucerouge.gif',1)"><img name="img_17" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo IMPORTATION; ?></a><br/><br/>
				<a class="mnu" href="exportation_Extrait.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_19','','images/pucerouge.gif',1)"><img name="img_19" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo EXPORTATION; ?></a><br/><br/>
			</td>
		</tr>
	</table>
</div>
<br/>
<br/>

<?php if ($row[0]=='{ADMINISTRATEUR}'): ?>

<div class="divmenu1">
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td class=cellulebleu>
				<?php echo ADMINISTRATION; ?>
			</td>
		</tr>
		<tr align="left">
			<td height="100%" valign="top" class=celluleblanche style="padding-left: 5;">
				<br/>
				<a class="mnu" href="plaques.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_6','','images/pucerouge.gif',1)"><img name="img_6" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo GESTIONPLAQUE; ?></a><br/><br/>
				<a class="mnu" href="resultatbio.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_7','','images/pucerouge.gif',1)"><img name="img_7" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo RESULTBIO; ?></a><br/><br/>
				<a class="mnu" href="exportation.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_8','','images/pucerouge.gif',1)"><img name="img_8" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo EXPORTATION; ?></a><br/><br/>
				<a class="mnu" href="importation.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_9','','images/pucerouge.gif',1)"><img name="img_9" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo IMPORTATION; ?></a><br/><br/>
				<a class="mnu" href="utilisateurs.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_10','','images/pucerouge.gif',1)"><img name="img_10" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo UTILISATEUR; ?></a><br/><br/>
				<a class="mnu" href="attributionstructures.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_11','','images/pucerouge.gif',1)"><img name="img_11" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo ATRIB_STRUCTURE; ?></a><br/><br/>
				<a class="mnu" href="parametres.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_12','','images/pucerouge.gif',1)"><img name="img_12" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo PARAMETRES; ?></a><br/><br/>
				<a class="mnu" href="param_extra.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img_13','','images/pucerouge.gif',1)"><img name="img_13" border="0" src="images/pucebleu.gif" width="9" height="9"> <?php echo "Extractothèque"; ?></a><br/><br/>
			</td>
		</tr>
	</table>
</div>

<?php endif; ?>

<?php
if (isset($menu)){
	echo "<script>document.getElementsByName('img_$menu')[0].src =  \"images/pucerouge.gif\"</script>";
	echo "<script>document.getElementsByName('img_$menu')[0].oSrc = \"images/pucerouge.gif\"</script>";
}
unset($dbh);
?>
