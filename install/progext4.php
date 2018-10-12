<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique - ICOA UMR7311
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
if (!empty($_POST["web"]) and !empty($_POST["reptemp"]) and !empty($_POST["servmysql"]) and !empty($_POST["passmysql"]) and !empty($_POST["loginmysql"]) and !empty($_POST["namebase"]))
	{
	if ($_POST["win"]==1) {
		$_POST["reptemp"]=AddSlashes($_POST["reptemp"]);
		$_POST["repp"]=AddSlashes($_POST["repp"]);
	}
	$text="<?php\n";
	$text.="if (!defined ('MYSQLSERVEURCHI')) define ('MYSQLSERVEURCHI','".$_POST["servmysql"]."');\n";
	$text.="if (!defined ('MYSQLLOGINCHI')) define ('MYSQLLOGINCHI','".$_POST["loginmysql"]."');\n";
	$text.="if (!defined ('MYSQLPASSCHI')) define ('MYSQLPASSCHI','".$_POST["passmysql"]."');\n";
	$text.="if (!defined ('MYSQLBDCHI')) define ('MYSQLBDCHI','".$_POST["namebase"]."');\n";
	$text.="try {
		\$dbh = new PDO('pgsql:host='.MYSQLSERVEURCHI.';dbname='.MYSQLBDCHI.'', MYSQLLOGINCHI, MYSQLPASSCHI);
		\$dbh->exec(\"SET CHARACTER SET utf8\");
		} catch (PDOException \$excep) {
			print \" Error! : \".\$excep->getMessage().\"<br />\";
			die();
		}";
	$text.="\n?>";
	$text1="<?php\n";
	$text1.="define('REPTEMP','".$_POST["reptemp"]."');\n";
	$text1.="define('ADRESSEWEB','".$_POST["web"]."');\n";
	$text1.="define('REPEPRINCIPAL','".$_POST["repp"]."');\n";
	$text1.="?>";
	if ($_POST["win"]==1) $_POST["repp"]=StripSlashes($_POST["repp"]);
	if($fp=fopen($_POST["repp"]."script/administrateur.php","w+")) {
		fwrite($fp,$text1);
		fclose($fp); 
		print"<p align=\"center\"><br/><b>Le fichier administrateur.php a été créé dans le répertoire ".$_POST["repp"]."script</b></p>";
		chmod($_POST["repp"]."script/administrateur.php",0444);
		$okfichier=1;
		}	
	else {
		print"<p align=\"center\"><br/><b>fichier administrateur.php impossible à créer dans le répertoire ".$_POST["repp"]."script</b></p>";
		$okfichier=0;
		}
	if($fp=fopen($_POST["repp"]."script/connectionb.php","w+")) {
		fwrite($fp,$text);
		fclose($fp); 
		print"<p align=\"center\"><br/><b>Le fichier connectionb.php a été créé dans le répertoire ".$_POST["repp"]."script</b></p>";
		chmod($_POST["repp"]."script/connectionb.php",0444);
		$okfichier1=1;
		}
	else {
		print"<p align=\"center\"><br/><b>fichier connectionb.php impossible à créer dans le répertoire ".$_POST["repp"]."script</b></p>";
		$okfichier1=0;
		}
	if ($okfichier==1 and $okfichier1==1) {
		print'<form  name="form1" method="post" action="etape6.php">
			<input type="hidden" name="repp" value="'.$_POST["repp"].'">';
		if ($_POST["win"]==1) print'<input type="hidden" name="win" value="'.$_POST["win"].'">';
		print'<p align="center"><input type="submit" name="Submit" value="&eacute;tape suivante" /></p>
			</form>';
		}
	else {
		if ($_POST["win"]==1) {
			$_POST["reptemp"]=StripSlashes($_POST["reptemp"]);
			$_POST["repp"]=StripSlashes($_POST["repp"]);
		}
		print'<p align="center" class="messagederreur">Vérifiez les droits d\'accès au répertoire : '.$_POST["repp"].'script, ce répertoire doit être autorisé en écriture</p>';
		print'<p align="center"><form  name="form1" method="post" action="etape5.php">
			<input type="hidden" name="repp" value="'.$_POST["repp"].'">';
		print'<input type="hidden" name="web" value="'.$_POST["web"].'">';
		print'<input type="hidden" name="reptemp" value="'.$_POST["reptemp"].'">';
		print'<input type="hidden" name="servmysql" value="'.$_POST["servmysql"].'">';
		print'<input type="hidden" name="passmysql" value="'.$_POST["passmysql"].'">';
		print'<input type="hidden" name="loginmysql" value="'.$_POST["loginmysql"].'">';
		print'<input type="hidden" name="namebase" value="'.$_POST["namebase"].'">';
		if ($_POST["win"]==1) print'<input type="hidden" name="win" value="'.$_POST["win"].'">';
		print'<input type="submit" name="Submit" value="Recharger" />
			</form></p>';		
		}	
	}
else
	{
	header ("location: ");
    exit;
	}	
?>