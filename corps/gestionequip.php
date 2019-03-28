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
include_once 'langues/'.$_SESSION['langue'].'/lang_utilisateurs.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
	print"<script language=\"javascript\">
		function suppression(link,nom) {
		oks=confirm('".VCONFIRMSUP." '+nom+' ?');
		if (oks==true) {confirma=link.href += '&ok=1';}
		else {confirma=link.href += '&ok=0';}
		return(confirma);
		}
	</script>";
	print"<table width=\"492\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	  <tr>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurs.php\">".VISU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurajout.php\">".AJOU."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurdesa.php\">".DESA."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurreac.php\">".REAC."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"utilisateurmodif.php\">".MODIF."</a></td>
		<td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"equipegestion.php\">".GESTEQUIP."</a></td>
		</tr>
		</table><br/>";
	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_nom_equipe'";
    //les résultats sont retournées dans la variable $result2
    $result2=$dbh->query($sql);
    //Les résultats son mis sous forme de tableau
    $row2=$result2->fetch(PDO::FETCH_NUM);

	$sql="SELECT character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME='equi_initiale_numero'";
    //les résultats sont retournées dans la variable $result3
    $result3=$dbh->query($sql);
    //Les résultats son mis sous forme de tableau
    $row3=$result3->fetch(PDO::FETCH_NUM);

	if (!empty($_POST['nomeq']) and !empty($_POST['inieq']) and !empty($_POST['idequip'])){
		$sql="SELECT * FROM equipe WHERE (equi_nom_equipe='".$_POST['nomeq']."' or equi_initiale_numero='".$_POST['inieq']."') and equi_id_equipe!='".$_POST['idequip']."'";
		$selectequipe=$dbh->query($sql);
		$nbequipe=$selectequipe->rowCount();
		if ($nbequipe>0) print"<br/><br/><br/><p align=\"center\" class=\"messagederreur\">".ERREUREQUIPE."</p>";
		else {
			$sql="UPDATE equipe SET equi_nom_equipe='".$_POST['nomeq']."' ,equi_initiale_numero='".$_POST['inieq']."' WHERE equi_id_equipe='".$_POST['idequip']."';";
			$update=$dbh->exec($sql);
		}
	}
	elseif(isset($_POST['nomeq']) and isset($_POST['inieq']) and isset($_POST['idequip'])) {
		$erreur=EQUIERREUR;
		$_GET['idequip']=$_POST['idequip'];
	}
	if (!empty($_POST['nomequi']) and !empty($_POST['iniequi'])) {
		$sql="SELECT * FROM equipe WHERE equi_nom_equipe='".$_POST['nomequi']."' or equi_initiale_numero='".$_POST['iniequi']."'";
		$selectequipe=$dbh->query($sql);
		$nbequipe=$selectequipe->rowCount();
		if ($nbequipe>0) print"<br/><br/><br/><p align=\"center\" class=\"messagederreur\">".ERREUREQUIPE."</p>";
		else {
			$sql="INSERT INTO equipe (equi_nom_equipe,equi_initiale_numero) VALUES ('".$_POST['nomequi']."','".$_POST['iniequi']."');";
			$insertion=$dbh->exec($sql);
			unset ($_POST['nomequi'],$_POST['iniequi']);
		}	
	}
	elseif(isset($_POST['nomequi']) and isset($_POST['iniequi'])) $erreur1=EQUIERREUR;
	if (!empty($_GET['idequipp']) and $_GET['idequipp']>0 and $_GET['ok']==1) {
		$sql="DELETE FROM equipe where equi_id_equipe='".$_GET['idequipp']."'";
		$dele=$dbh->exec($sql);
	}
	echo "<H3>".MODEQUIP."</H3>";
	if (isset($erreur)) echo "<p class=\"messagederreur\">".$erreur."</p>";	
	print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td class=\"entete\">".EQUIPE."</td><td class=\"entete\">".INIEQUIPE."</td><td class=\"entete\">&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>";
	
    $sql="SELECT * FROM equipe ORDER BY equi_nom_equipe";
    $resultat=$dbh->query($sql);
	$col=1;
    while ($row=$resultat->fetch(PDO::FETCH_NUM)) {
		if (!empty($_GET['idequip']) and $_GET['idequip']==$row[0]) {
			$formulaire1=new formulaire ("equipe","equipegestion.php","POST",true);
			$formulaire1->affiche_formulaire();
			print"<tr><td>";
			$formulaire1->ajout_text ($row2[0]+1,$row[1],$row2[0],"nomeq","","","");
			print"</td><td>";
			$sql="SELECT * FROM produit WHERE pro_id_equipe='".$_GET['idequip']."'";
			$selectequipe=$dbh->query($sql);
			$nbequipe=$selectequipe->rowCount();
			if ($nbequipe==0) $formulaire1->ajout_text ($row3[0]+1,$row[2],$row3[0],"inieq","","","");
			else {
				echo $row[2];
				$formulaire1->ajout_cache ($row[2],"inieq");
			}
			print"</td><td>";
			$formulaire1->ajout_cache ($_GET['idequip'],"idequip");
			$formulaire1->ajout_button (SUBMIT,"","submit","");
			print"</td></tr>";
			$formulaire1->fin();
		}
		else {
			$sql="SELECT count(*) FROM chimiste WHERE chi_id_equipe='$row[0]'";
			$resultat1=$dbh->query($sql);
			$row1=$resultat1->fetch(PDO::FETCH_NUM);
			$sql="SELECT count(*) FROM produit WHERE pro_id_equipe='$row[0]'";
			$nbresultat2=$dbh->query($sql);
			$nbrow2=$nbresultat2->fetch(PDO::FETCH_NUM);
			print"<tr";
			if (!is_integer($col/2)) print" class=\"ligneutil\"";
			else print" class=\"ligneutil1\"";
			print"><td>$row[1]</td><td>$row[2]</td><td><a href=\"equipegestion.php?idequip=$row[0]\">".MODIFIER."</a>";
			if ($row1[0]==0 and $nbrow2[0]==0) print" ; <a href=\"equipegestion.php?idequipp=$row[0]\" onClick=\"suppression(this,'$row[1]')\">".SUPPRIMER."</a>";
			print"</td></tr>\n";	
		}
		$col++;
	}
    print"</table>";
	print"<hr>";
	echo "<H3>".AJEQUIPE."</H3>";
	if (isset($erreur1)) echo "<p class=\"messagederreur\">".$erreur1."</p>";
	if(!isset($_POST['nomequi'])) $_POST['nomequi']="";
	if(!isset($_POST['iniequi'])) $_POST['iniequi']="";	
	$formulaire=new formulaire ("ajequipe","equipegestion.php","POST",true);
    $formulaire->affiche_formulaire();
    $formulaire->ajout_text ($row2[0]+1,$_POST['nomequi'],$row2[0],"nomequi",NEWEQUIPE.DEUX."<br/>","","");
    print"<br/>";
    $formulaire->ajout_text ($row3[0]+1,$_POST['iniequi'],$row3[0],"iniequi",INIEQUIPE.DEUX."<br/>","","");
	print"<br/><br/>";
	$formulaire->ajout_button (SUBMIT,"","submit","");
	//fin du formulaire
    $formulaire->fin();
}
else require 'deconnexion.php';
unset($dbh);
?>