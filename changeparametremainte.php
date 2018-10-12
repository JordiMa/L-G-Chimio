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
include_once 'autoload.php';
include_once 'protection.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'langues/'.$_SESSION['langue'].'/lang_parametre.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
  if (isset($_POST['vide'])) {
    $sql="SELECT para_logo FROM parametres";
    $result1=$dbh->query($sql);
    $row1=$result1->fetch(PDO::FETCH_NUM);
	//vérifie si on est sous un système windows modification / pour éviter que le fichier du logo soit effacé sous windows
	if(preg_match("/:/",getcwd()))  $row1[0]=preg_replace('/\//','\\',$row1[0]);
    $folder=dir(REPTEMP);
    while ($fichier=$folder->read()) {
      if ($fichier<>'..' and $fichier<>'.' and REPTEMP.$fichier<>REPEPRINCIPAL.$row1[0] and $fichier<>"index.php") unlink(REPTEMP.$fichier);
    }
	$menu=11;
	$ssmenu=11;
  }
  else $menu=11;
  if (!isset($erreur)) $transfert=true;
  include_once 'presentation/entete.php';
  include_once 'presentation/gauche.php';
  if (isset($erreur)) include_once 'formulaireparamainte.php';
  elseif (!empty($update)) print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".SAUVDONNE."</p>";
  elseif (isset($_POST['vide'])) print "<br/><br/><br/><br/><br/><br/><br/><br/><p align=\"center\" class=\"sauvegarde\">".VIDETEMP."</p>";
}
else require 'deconnexion.php';
unset($dbh);
include_once 'presentation/pied.php';
?>