<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

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
class affiche_recherche {

  public $resultatsql;
  public $str_inchi_md5;
  public $formbrute;
  public $massemol;
  public $supinf;
  public $massexact;
  public $forbrutexact;
  public $page;
  public $nbrs;
  public $nbpage;
  public $typechimiste;
  public $chimiste;
  public $numero;
  public $recherche;
  public $valtanimoto;


  function __construct ($resultatsql,$str_inchi_md5,$formbrute,$massemol,$supinf,$massexact,$forbrutexact,$page,$nbrs,$nbpage,$typechimiste,$chimiste,$numero,$recherche,$valtanimoto) {
	  $this->resultatsql=$resultatsql;
    $this->str_inchi_md5=$str_inchi_md5;
    $this->formbrute=$formbrute;
    $this->massemol=$massemol;
    $this->supinf=$supinf;
    $this->massexact=$massexact;
    $this->forbrutexact=$forbrutexact;
    $this->page=$page;
    $this->nbrs=$nbrs;
    $this->nbpage=$nbpage;
    $this->typechimiste=$typechimiste;
    $this->chimiste=$chimiste;
    $this->numero=$numero;
	$this->recherche=$recherche;
	$this->valtanimoto=$valtanimoto;
  }

  function imprime() {
    if (empty ($this->resultatsql)) echo "<p align=\"center\"><br /><br /><strong>".RIEN."</strong></p>";
    else {
		//appel le fichier de connexion à la base de données
		require 'script/connectionb.php';
		echo "<script type=\"text/javascript\" language=\"javascript\" src=\"jsme/jsme.nocache.js\"></script>\n";
		print"<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n<tr>";
		$idstruc=0;
		$i=0; //compteur pour le nombre d'itérations pour une même structure
		$j=0; //compteur pour le nombre de lignes du tableau
		foreach($this->resultatsql as $key=>$element) {
				if ($element!=$idstruc) {
					$idstruc=$element;
					if ($i==0 and $j==0) print"<td width=\"20%\" align=\"center\">";  //cas pour la premier itération
					elseif ($i>0 and ($j%2)!=0) print"</table></div></td>\n<td width=\"20%\" align=\"center\">";  //cas de la deuxieme colonne
					elseif ($i>0 and ($j%2)==0) print"</table></div></td></tr>\n<tr>\n<td width=\"20%\" align=\"center\">"; // cas de changement de ligne
					$i++;
					$j++;
					$sql="SELECT str_mol,str_formule_brute,str_masse_molaire FROM structure WHERE str_id_structure=$element";
					$result2 = $dbh->query($sql);
					$rom =$result2->fetch(PDO::FETCH_NUM);
					$jme=new visualisationmoleculejme (200,250,$rom[0]);
					$jme->imprime();
					for ($k=0; $k<10; $k++) {
						$rom[1]=str_replace($k,"<SUB>".$k."</SUB>",$rom[1]);
					}
					print"<br/>".$rom[1]."<br/>".$rom[2]." ".GMOL."</td>\n<td width=\"30%\"><div style=\"width:300px; height:200px; overflow:auto; border:solid 1px black;\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\">\n";
					$sql= "SELECT chi_nom, chi_prenom, pro_date_entree, pro_numero, pro_masse, equi_nom_equipe, pro_unite_masse, pro_controle_purete, pro_controle_structure FROM produit,chimiste,equipe WHERE pro_id_produit=$key and produit.pro_id_chimiste=chimiste.chi_id_chimiste and (pro_id_equipe=equi_id_equipe OR pro_id_equipe is null)";
					$result1 = $dbh->query($sql);
					$ror = $result1->fetch(PDO::FETCH_NUM);

					$sql="SELECT chi_nom, chi_prenom FROM produit,chimiste WHERE pro_id_produit='$key' and produit.pro_id_responsable=chimiste.chi_id_chimiste";
					$result2 = $dbh->query($sql);
					$ror2 =$result2->fetch(PDO::FETCH_NUM);

					if ($_SESSION['langue']=='fr') $date = strftime ("%d-%m-%Y",strtotime($ror[2]));
					else $date=$ror[2];

					$search= array('{','}');
					$ror[6]=str_replace($search,'',$ror[6]);

					echo " <tr>
                  <td valign=\"top\">
                    ".$ror[3]." : ".$ror[4].constant($ror[6])."<br/>
                    ".$ror[0]." ".$ror[1].",&nbsp;".$date."<br/>
                    ".EQUI." ".$ror2[1]." ".$ror2[0]."<br/>";
                    if ($ror[7] == 0) echo "pureté contrôlée : Non contrôlée<br/>";
                    else if ($ror[7] == 1) echo "pureté contrôlée : Contrôle en cours<br/>";
                    else if ($ror[7] == 2) echo "pureté contrôlée : Contrôlée et validé<br/>";
                    else if ($ror[7] == 3) echo "pureté contrôlée : Contrôlée et invalidé<br/>";

                    if ($ror[8] == 0) echo "structure contrôlée : Non contrôlée<br/>";
                    else if ($ror[8] == 1) echo "structure contrôlée : Contrôle en cours<br/>";
                    else if ($ror[8] == 2) echo "structure contrôlée : Contrôlée et validé<br/>";
                    else if ($ror[8] == 3) echo "structure contrôlée : Contrôlée et invalidé<br/>";
            echo "</td>
                  <td align=\"right\" valign=\"middle\">
                    <form method=\"post\" action=\"fiche.php\">
      						    <input type=\"image\" src=\"images/lire.gif\" alt=\"".CONSULTER."\" title=\"".CONSULTER."\">
      						    <input type=\"hidden\" name=\"id\" value=\"$key\">
      						    <input type=\"hidden\" name=\"menu\" value=\"3\">
      						    <input type=\"hidden\" name=\"mol\" value=\"$this->str_inchi_md5\">
      						    <input type=\"hidden\" name=\"formbrute\" value=\"$this->formbrute\">
      						    <input type=\"hidden\" name=\"massemol\" value=\"$this->massemol\">
      						    <input type=\"hidden\" name=\"supinf\" value=\"$this->supinf\">
        						<input type=\"hidden\" name=\"massexac\" value=\"$this->massexact\">
        						<input type=\"hidden\" name=\"forbrutexact\" value=\"$this->forbrutexact\">
        						<input type=\"hidden\" name=\"numero\" value=\"$this->numero\">
        						<input type=\"hidden\" name=\"page\" value=\"$this->page\">
        						<input type=\"hidden\" name=\"nbrs\" value=\"$this->nbrs\">
        						<input type=\"hidden\" name=\"nbpage\" value=\"$this->nbpage\">
        						<input type=\"hidden\" name=\"typechimiste\" value=\"$this->typechimiste\">
        						<input type=\"hidden\" name=\"chimiste\" value=\"$this->chimiste\">
        						<input type=\"hidden\" name=\"recherche\" value=\"$this->recherche\">
								<input type=\"hidden\" name=\"valtanimoto\" value=\"$this->valtanimoto\">
      						  </form>
                  </td>
                </tr>";
				}
				else {
					$sql= "SELECT chi_nom, chi_prenom,pro_date_entree,pro_numero,pro_masse,pro_unite_masse, pro_controle_purete, pro_controle_structure FROM produit,chimiste WHERE pro_id_produit=$key and produit.pro_id_chimiste=chimiste.chi_id_chimiste";
					$result1 =$dbh->query($sql);
					$ror =$result1->fetch(PDO::FETCH_NUM);

					$sql="SELECT chi_nom, chi_prenom FROM produit,chimiste WHERE pro_id_produit='$key' and produit.pro_id_responsable=chimiste.chi_id_chimiste";
					$result2 = $dbh->query($sql);
					$ror2 =$result2->fetch(PDO::FETCH_NUM);
					$date=substr($ror[2],0,11);

					if ($_SESSION['langue']=='fr') $date = strftime ("%d-%m-%Y",strtotime($ror[2]));
					else $date=$ror[2];

					$search= array('{','}');
					$ror[5]=str_replace($search,'',$ror[5]);

          // [JM - 22/01/2019] affiche le contrôle de la pureté et de la strucutre
					echo "<tr>
                  <td valign=\"top\">
                  --<br/>
                  ".$ror[3]." - ".$ror[4].constant($ror[5])."<br/>
                  ".$ror[0]." ".$ror[1]."&nbsp;".$date."<br/>
                  ".EQUI." ".$ror2[1]." ".$ror2[0]."<br/>";
                  if ($ror[6] == 0) echo "pureté contrôlée : Non contrôlée<br/>";
                  else if ($ror[6] == 1) echo "pureté contrôlée : Contrôle en cours<br/>";
                  else if ($ror[6] == 2) echo "pureté contrôlée : Contrôlée et validé<br/>";
                  else if ($ror[6] == 3) echo "pureté contrôlée : Contrôlée et invalidé<br/>";

                  if ($ror[7] == 0) echo "structure contrôlée : Non contrôlée<br/>";
                  else if ($ror[7] == 1) echo "structure contrôlée : Contrôle en cours<br/>";
                  else if ($ror[7] == 2) echo "structure contrôlée : Contrôlée et validé<br/>";
                  else if ($ror[7] == 3) echo "structure contrôlée : Contrôlée et invalidé<br/>";
          echo "</td>
                  <td align=\"right\" valign=\"middle\">
                    <form method=\"post\" action=\"fiche.php\">
        						  <input type=\"image\" src=\"images/lire.gif\" alt=\"".CONSULTER."\" title=\"".CONSULTER."\">
        						  <input type=\"hidden\" name=\"id\" value=\"$key\">
        						  <input type=\"hidden\" name=\"menu\" value=\"3\">
        						  <input type=\"hidden\" name=\"mol\" value=\"$this->str_inchi_md5\">
        						  <input type=\"hidden\" name=\"formbrute\" value=\"$this->formbrute\">
        						  <input type=\"hidden\" name=\"massemol\" value=\"$this->massemol\">
        						  <input type=\"hidden\" name=\"supinf\" value=\"$this->supinf\">
        						  <input type=\"hidden\" name=\"massexac\" value=\"$this->massexact\">
        						  <input type=\"hidden\" name=\"forbrutexact\" value=\"$this->forbrutexact\">
        						  <input type=\"hidden\" name=\"numero\" value=\"$this->numero\">
        						  <input type=\"hidden\" name=\"page\" value=\"$this->page\">
        						  <input type=\"hidden\" name=\"nbrs\" value=\"$this->nbrs\">
        						  <input type=\"hidden\" name=\"nbpage\" value=\"$this->nbpage\">
        						  <input type=\"hidden\" name=\"typechimiste\" value=\"$this->typechimiste\">
        						  <input type=\"hidden\" name=\"chimiste\" value=\"$this->chimiste\">
        						  <input type=\"hidden\" name=\"recherche\" value=\"$this->recherche\">
								  <input type=\"hidden\" name=\"valtanimoto\" value=\"$this->valtanimoto\">
						        </form>
                  </td>
                </tr>";
				}
        //$this->listeID .= $ror[3] . ";";
			}
			//fermeture de la connexion à la base de données
			unset($dbhh);
			print"</table></div></td></tr>\n</table>";
      //$this->listeID = substr($this->listeID,0,-1);
		}
    }

    function getListeID(){
      require 'script/connectionb.php';

      $listeKey="";
      $listeID="";
      if (!empty ($this->resultatsql)){
        foreach($this->resultatsql as $key=>$element) {
          $listeKey .= "'" . $key . "',";
        }
        $listeKey = substr($listeKey,0,-1);

        $sql= "SELECT pro_numero FROM produit WHERE pro_id_produit in ($listeKey)";
        $result1 = $dbh->query($sql);
        foreach ($result1 as $key => $value) {
          $listeID .= $value[0] . ";";
        }
        $listeID = substr($listeID,0,-1);
      }
      return $listeID;
    }
}
?>
