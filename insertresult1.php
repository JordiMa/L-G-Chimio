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

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {
  print"<table width=\"164\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"resultatbio.php\">".CONSULTER."</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"importbio.php\">".IMPORTER."</a></td>
    </tr>
    </table><br/>";


  $formulaire=new formulaire ("cible","insertcsv1.php","POST",true);
  $formulaire->affiche_formulaire();

  $tab[1]=ACTIF;
  $tab[2]=INACTIF;

  $i=0;
  print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
  while ($i<$_POST['nbcol']) {
    if ($_POST['col'.$i]==3) {
      $y=0;
      $uniquecol=array_unique ($_SESSION['tabval'.$i]);
      foreach ($uniquecol as $elem) {
		if (!isset($_POST["elem".$elem])) $_POST["elem".$elem]=""; 
        print"<tr align=\"center\"><td valign=\"middle\"><h4>$elem&nbsp;&nbsp;&nbsp;&nbsp;".CORRESPOND."</h4></td><td>";
        $formulaire->ajout_select (1,"elem".$elem,$tab,false,$_POST["elem".$elem],SELEC,"",false,"");
        $_SESSION['elem'][$y]="elem".$elem;
        print"</td></tr>\n";
        $y++;
      }
    }
  $i++;
  }
  print"</table>";
  for($i=0;$i<$_POST['nbcol'];$i++) {
    $formulaire->ajout_cache ($_POST["col".$i],"col".$i);
  }
  $formulaire->ajout_cache ($_POST['nbcol'],"nbcol");
  $formulaire->ajout_cache ($_POST["cible"],"cible");
  $formulaire->ajout_cache ($_POST["labo"],"labo");
  $formulaire->ajout_cache ($_POST["jour"],"jour");
  $formulaire->ajout_cache ($_POST["mois"],"mois");
  $formulaire->ajout_cache ($_POST["annee"],"annee");
  $formulaire->ajout_cache ($_POST["molref"],"molref");
  $formulaire->ajout_cache ($_POST["resulref"],"resulref");
  $formulaire->ajout_cache ($_POST["uniteref"],"uniteref");
  $formulaire->ajout_cache (true,"param");


  $formulaire->ajout_button (SUBMIT,"","submit","");
  $formulaire->fin();
}
else require 'deconnexion.php';
unset($dbh);
?>