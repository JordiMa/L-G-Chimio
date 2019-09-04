<script src="./js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/datatables.min.css"/>
<script type="text/javascript" src="./presentation/DataTables/datatables.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/RowReorder-1.2.4/css/rowReorder.dataTables.css"/>
<script type="text/javascript" src="./presentation/DataTables/RowReorder-1.2.4/js/dataTables.rowReorder.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/Select-1.3.0/css/select.dataTables.css"/>
<script type="text/javascript" src="./presentation/DataTables/Select-1.3.0/js/dataTables.select.js"></script>
<style>

table.table-tableau {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table.table-tableau td, th {
  border: 1px solid #ddd;
  padding: 8px;
}

table.table-tableau tr:nth-child(even) {
  background-color: #f2f2f2;
}

table.table-tableau tr:hover {
  background-color: #ddd;
}

table.table-tableau th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}

div.extraits{
  width: 32%;
  display: inline-block;
  margin: 5px;
  vertical-align: top;
  padding: 5px;
  border-color: #3399CC;
  border-top-style: solid;
  border-right-style: dashed;
  border-bottom-style: dashed;
  border-left-style: solid;
  text-align: justify;
}

div.container{
  display: flex;
  flex-direction: row;
  justify-content: normal;
  flex-wrap: wrap;
}

.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.7);
  transition: opacity 250ms;
  visibility: hidden;
  opacity: 0;
}

.overlay:target {
  visibility: visible;
  opacity: 1;
  z-index: 1;
}

.popup {
  margin: 70px auto;
  padding: 20px;
  background: #fff;
  border-radius: 5px;
  width: 30%;
  position: relative;
  transition: all 5s ease-in-out;
  top: 25%;
}

.popup h2 {
  margin-top: 0;
  color: #333;
  font-family: Tahoma, Arial, sans-serif;
}
.popup .close {
  position: absolute;
  top: 20px;
  right: 30px;
  transition: all 200ms;
  font-size: 30px;
  font-weight: bold;
  text-decoration: none;
  color: #333;
}
.popup .close:hover {
  color: darkblue;
}
.popup .content {
  max-height: 30%;
  overflow: auto;
}

@media screen and (max-width: 700px){
  .box{
    width: 70%;
  }
  .popup{
    width: 70%;
  }
}

.infos {
  width: 48%;
  text-align: center;
  margin: 1%;
  vertical-align: top;
}

a.btnFic {
  font-size: small;
  background-color: silver;
  color: black;
  border: 2px solid green;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
}

a.btnFic:hover {
  background-color: green;
  color: white;
}

</style>

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
//include_once 'protection.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';

  print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_Extrait.php\">Extrait</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"recherche_Echantillon.php\">Échantillon</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_Condition.php\">Condition</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_Specimen.php\">Specimen</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_Taxonomie.php\">Taxonomie</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_Expedition.php\">Mission de récolte</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"recherche_autorisation.php\">Autorisation</a></td>
    </tr>
    </table><br/>";

  ?>
  <h3 align="center">Recherche d'échantillon</h3>
  <hr>

  <form id="myForm" action="" method="get" style=" text-align: center;">
    <!-- [JM - 01/02/2019] Recherche de l'echantillon -->
    <table id="tab_echantillon" class="display">
      <thead>
      <tr>
        <th></th>
        <th>Code</th>
        <th>Contact</th>
        <th>DOI</th>
        <th>Disponibilité</th>
        <th>Quantité</th>
        <th>Lieu de stockage</th>
      </tr>
    </thead>
    <tbody>
      <?php
      /*
      if ($row[0]=='{CHIMISTE}') {
        $sql_recherche = "
        SELECT * FROM echantillon
        INNER JOIN extraits on extraits.ech_code_echantillon = echantillon.ech_code_echantillon
        WHERE extraits.chi_id_chimiste = ".$row[1]."
        ORDER BY echantillon.ech_code_echantillon
        ";
      }
      elseif ($row[0]=='{RESPONSABLE}') {
        $sql_recherche = "
        SELECT echantillon.ech_code_echantillon, ech_contact, ech_publication_doi, ech_stock_disponibilite, ech_stock_quantite, ech_lieu_stockage, par_id, spe_code_specimen, con_id
        FROM echantillon
        INNER JOIN extraits on extraits.ech_code_echantillon = echantillon.ech_code_echantillon
        INNER JOIN chimiste on extraits.chi_id_chimiste = chimiste.chi_id_chimiste
        WHERE (chi_id_responsable = ".$row[1]."  or chimiste.chi_id_chimiste = ".$row[1].")
        GROUP BY echantillon.ech_code_echantillon
        ORDER BY echantillon.ech_code_echantillon;
        ";
      }
      elseif ($row[0]=='{CHEF}') {
        $sql_recherche = "
        SELECT echantillon.ech_code_echantillon, ech_contact, ech_publication_doi, ech_stock_disponibilite, ech_stock_quantite, ech_lieu_stockage, par_id, spe_code_specimen, con_id FROM echantillon
        INNER JOIN extraits on extraits.ech_code_echantillon = echantillon.ech_code_echantillon
        INNER JOIN chimiste as chim ON chim.chi_id_chimiste = extraits.chi_id_chimiste
        INNER JOIN chimiste as res ON res.chi_id_chimiste = chim.chi_id_responsable
        WHERE res.chi_id_responsable = ".$row[1]."
        GROUP BY echantillon.ech_code_echantillon
        ORDER BY echantillon.ech_code_echantillon;
        ";
      }
      elseif ($row[0]=='{ADMINISTRATEUR}') {
        $sql_recherche = "
        SELECT * FROM echantillon
        ORDER BY echantillon.ech_code_echantillon
        ";
      }*/
      $sql_recherche = "
      SELECT * FROM echantillon
      ORDER BY echantillon.ech_code_echantillon
      ";

      foreach ($dbh->query($sql_recherche) as $row_r) {
        echo '
        <tr>
        <td><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="echantillon" value="'.urldecode($row_r[0]).'"';if (isset($_GET['echantillon']) && $row_r[0] == $_GET['echantillon']) echo "checked"; ;echo '></td>
        <td>'.urldecode($row_r[0]).'</td>
        <td>'.urldecode($row_r[1]).'</td>
        <td>'.urldecode($row_r[2]).'</td>
        <td>';if ($row_r[3]) {echo "Oui";} else {echo "Non";} echo '</td>
        <td>'.urldecode($row_r[4]).' g</td>
        <td>'.urldecode($row_r[5]).'</td>
        </tr>
        ';
      }
      ?>
    </tbody>
    </table>
    <br/>
    <input type="submit" name="Rechercher" id="Rechercher" value="Rechercher">
    <br><br>
  </form>
  <hr>
  <?php

  if(isset($_GET['echantillon'])){
    $sql_echantillon =
    "SELECT * FROM Echantillon
    INNER JOIN specimen on specimen.spe_code_specimen = echantillon.spe_code_specimen
    INNER JOIN expedition on expedition.exp_id = specimen.exp_id
    INNER JOIN pays on pays.pay_code_pays = expedition.pay_code_pays
    INNER JOIN taxonomie on taxonomie.tax_ID = specimen.tax_ID
    INNER JOIN type_taxonomie on type_taxonomie.typ_tax_id = taxonomie.typ_tax_id
    INNER JOIN partie_organisme on partie_organisme.par_id = echantillon.par_id
    Left outer JOIN condition on condition.con_id = echantillon.con_id
    WHERE Echantillon.ech_code_echantillon = '".$_GET['echantillon']."';
    ";

    $result_echantillon = $dbh->query($sql_echantillon);
    $row_echantillon = $result_echantillon->fetch(PDO::FETCH_NUM);
    //print_r($row_echantillon);

    // [JM - 05/07/2019] affiche toutes les données liée à l'echantillon
    if (!empty($row_echantillon[0])) {
      echo "<div style=\"margin-left: 10px;\">";
      echo "<strong>Code echantillon : </strong>" .$row_echantillon[0];
      echo "<br/>";
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[1];
      echo "<br/>";
      echo "<br/><strong>DOI : </strong>" .$row_echantillon[2];
      echo "<br/>";
      echo "<br/><strong>Stock : </strong>"; if ($row_echantillon[3] == 1) echo "Oui"; else echo "Non";
      echo "<br/><strong>Quantité : </strong>" .$row_echantillon[4]. ' g';
      echo "<br/><strong>Lieu de stockage : </strong>" .$row_echantillon[5];
      echo "<br/>";
      echo "<br/>";
      echo "</div>";

      echo "<div class='hr click_extraits'>Extraits</div>";

      echo "<div class='container'>";
      // [JM - 05/07/2019] cree une liste des extrait et de leur purification
      /*$req_extrait = "
      SELECT extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, count(pur_id) FROM extraits
      INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
      INNER JOIN Solvant on extraits.ext_solvant = Solvant.sol_id_solvant
      LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
      LEFT JOIN purification on extraits.ext_Code_Extraits = purification.ext_Code_Extraits
      WHERE ech_code_echantillon = '".$_GET['echantillon']."'
      GROUP BY extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe;";
      */
      if ($row[0]=='{CHIMISTE}') {
        //AND extraits.chi_id_chimiste = ".$row[1]."
        $req_extrait = "
        SELECT extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, count(pur_id), typ_type FROM extraits
        INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
        INNER JOIN Solvant on extraits.ext_solvant = Solvant.sol_id_solvant
        INNER JOIN type on extraits.typ_id_type = type.typ_id_type
        LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
        LEFT JOIN purification on extraits.ext_Code_Extraits = purification.ext_Code_Extraits
        WHERE ech_code_echantillon = '".$_GET['echantillon']."'
        AND extraits.chi_id_chimiste = ".$row[1]."
        GROUP BY extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, typ_type;";
      }
      elseif ($row[0]=='{RESPONSABLE}') {
        //AND (chimiste.chi_id_responsable = ".$row[1]." or chimiste.chi_id_chimiste = ".$row[1].")
        $req_extrait = "
        SELECT extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, count(pur_id), typ_type FROM extraits
        INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
        INNER JOIN Solvant on extraits.ext_solvant = Solvant.sol_id_solvant
        INNER JOIN type on extraits.typ_id_type = type.typ_id_type
        LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
        LEFT JOIN purification on extraits.ext_Code_Extraits = purification.ext_Code_Extraits
        WHERE ech_code_echantillon = '".$_GET['echantillon']."'
        AND (chimiste.chi_id_responsable = ".$row[1]." or chimiste.chi_id_chimiste = ".$row[1].")
        GROUP BY extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, typ_type ;";
      }
      elseif ($row[0]=='{CHEF}') {
        //AND res.chi_id_responsable = ".$row[1]."
        $req_extrait = "
        SELECT extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chimiste.chi_nom, chimiste.chi_prenom, equi_nom_equipe, count(pur_id), typ_type FROM extraits
        INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
        INNER JOIN chimiste res ON chimiste.chi_id_responsable = res.chi_id_chimiste
        INNER JOIN type on extraits.typ_id_type = type.typ_id_type
        INNER JOIN Solvant on extraits.ext_solvant = Solvant.sol_id_solvant
        LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
        LEFT JOIN purification on extraits.ext_Code_Extraits = purification.ext_Code_Extraits
        WHERE ech_code_echantillon = '".$_GET['echantillon']."'
        AND res.chi_id_responsable = ".$row[1]."
        GROUP BY extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chimiste.chi_nom, chimiste.chi_prenom, equi_nom_equipe, typ_type;";
      }
      elseif ($row[0]=='{ADMINISTRATEUR}') {
        $req_extrait = "
        SELECT extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, count(pur_id), typ_type FROM extraits
        INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
        INNER JOIN Solvant on extraits.ext_solvant = Solvant.sol_id_solvant
        INNER JOIN type on extraits.typ_id_type = type.typ_id_type
        LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
        LEFT JOIN purification on extraits.ext_Code_Extraits = purification.ext_Code_Extraits
        WHERE ech_code_echantillon = '".$_GET['echantillon']."'
        GROUP BY extraits.ext_Code_Extraits, sol_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, typ_type;";
      }


      $query_extrait = $dbh->query($req_extrait);
      $resultat_extrait = $query_extrait->fetchALL(PDO::FETCH_NUM);

      $req_purif = "SELECT purification.pur_id, pur_purification, pur_ref_book, count(fic_id), ext_Code_Extraits FROM purification LEFT OUTER JOIN fichier_purification ON fichier_purification.pur_id = purification.pur_id GROUP BY purification.pur_id";
      $query_purif = $dbh->query($req_purif);
      $resultat_purif = $query_purif->fetchALL(PDO::FETCH_NUM);
      // [JM - 05/07/2019] affichage des resultat
      foreach ($resultat_extrait as $key => $value) {
        echo "<div class='extraits'>";
        echo "<strong>ID extrait : </strong>" .$value[0];
        echo "<br/><strong>Solvant : </strong>" .constant($value[1]);
        echo "<br/><strong>Type d'extraction : </strong>" .$value[2];
        echo "<br/><strong>Etat : </strong>" .$value[3];
        echo "<br/><strong>Disponibilité : </strong>"; if ($value[4] == 1) echo "Oui"; else echo "Non";
        echo "<br/><strong>Protocole : </strong>" .$value[5];
        echo "<br/><strong>Lieu de stockage : </strong>" .$value[6];
        echo "<br/><strong>observations : </strong>" .$value[7];
        echo "<br/><strong>Nom du chimiste : </strong>" .$value[8]. " " .$value[9] ;
        echo "<br/><strong>Equipe : </strong>" .$value[10];
        echo "<br/><br/><strong>Licence : </strong>" .constant($value[12]);
        if ($value[11] != 0) {
          echo "<div class='hr'>Purifications</div>";
          echo "
          <div style='max-height: 250px;overflow: auto; width: 100%;'>
          <table class=\"table-tableau\">
          <tr>
          <th>ID</th>
          <th>Purification</th>
          <th>Ref cahier de labo</th>
          <th>Fichiers</th>
          </tr>
          ";

          foreach ($resultat_purif as $key1 => $value1) {
            if($value1[4] == $value[0]){
              echo "
              <tr>
              <td>".$value1[0]."</td>
              <td>".$value1[1]."</td>
              <td>".$value1[2]."</td>
              <td><a href=\"#fic_pur_".$value1[0]."\">".$value1[3]." Fichier(s)</a></td>
              </tr>
              ";
            }
          }
          echo "</table>";
          echo "</div>";
        }
        echo "</div>";
      }
      echo "</div>";

      echo "<hr>";

      //Autorisation
      $req_aut = "SELECT * FROM autorisation_specimen Inner JOIN autorisation ON autorisation_specimen.aut_numero_autorisation = autorisation.aut_numero_autorisation WHERE spe_code_specimen = '".$row_echantillon[9]."'";
      $query_aut = $dbh->query($req_aut);
      $resultat_aut = $query_aut->fetchALL(PDO::FETCH_NUM);
      if($resultat_aut){
        echo "<div class='hr'>Autorisation</div>";
        echo "<div style='max-height: 250px;overflow: auto; width: 100%;'>
        <table class=\"table-tableau\">
        <tr>
        <th>Numéro d'autorisation</th>
        <th>Type d'autorisation</th>
        </tr>
        ";
        foreach ($resultat_aut as $key1 => $value1) {
            echo "
            <tr>
            <td>".$value1[0]."</td>
            <td>".$value1[3]."</td>
            </tr>
            ";
        }
        echo "</table>";
        echo "</div>";
        echo "<div class='container'>";
        echo "</div>";
        echo "<br/>";
      }

      echo "<div class='container'>";
      echo "<div class='infos'>";
      echo "<div class='hr click_specimen'>Specimen</div>";

      echo "<br/><strong>Code specimen : </strong>" .$row_echantillon[9];
      echo "<br/>";
      echo "<br/><strong>Date de recolte : </strong>" .$row_echantillon[10];
      echo "<br/><strong>Lieu de recolte : </strong>" .$row_echantillon[11];
      echo "<br/><strong>Position GPS : </strong>" .$row_echantillon[12];
      echo "<br/>";
      echo "<br/><strong>Observation : </strong>" .$row_echantillon[13];
      echo "<br/>";
      echo "<br/><strong>Collection : </strong>" .$row_echantillon[14];
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[15];
      echo "<br/><strong>Collecteur : </strong>" .$row_echantillon[16];
      echo "<br/><br/><a class='btnFic' href=\"#fic_spe_".$row_echantillon[9]."\">Voir les fichiers</a>";
      echo "<br/>";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_expedition'>Mission de récolte</div>";

      echo "<br/><strong>ID expedition : </strong>" .$row_echantillon[19];
      echo "<br/>";
      echo "<br/><strong>Nom : </strong>" .$row_echantillon[20];
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[21];
      echo "<br/>";
      echo "<br/><strong>Pays : </strong>" .$row_echantillon[24];
      echo "<br/><strong>Collaboration : </strong>";if ($row_echantillon[25] == 1) echo "Oui"; else echo "Non";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_taxonomie'>Taxonomie</div>";

      echo "<br/><strong>ID taxonomie : </strong>" .$row_echantillon[26];
      echo "<br/>";
      echo "<br/><strong>Phylum : </strong>" .$row_echantillon[27];
      echo "<br/><strong>Classe : </strong>" .$row_echantillon[28];
      echo "<br/><strong>Ordre : </strong>" .$row_echantillon[29];
      echo "<br/><strong>Famille : </strong>" .$row_echantillon[30];
      echo "<br/><strong>Genre : </strong>" .$row_echantillon[31];
      echo "<br/><strong>Espece : </strong>" .$row_echantillon[32];
      echo "<br/><strong>Sous-espece : </strong>" .$row_echantillon[33];
      echo "<br/><strong>Varieté : </strong>" .$row_echantillon[34];
      echo "<br/>";
      echo "<br/><strong>Protocole : </strong>" .$row_echantillon[35];
      echo "<br/><strong>Sequence : </strong>" .$row_echantillon[36];
      echo "<br/><strong>Sequence ref cahier de labo : </strong>" .$row_echantillon[37];
      echo "<br/>";
      echo "<br/><strong>Type : </strong>" .$row_echantillon[40];
      echo "<br/><br/><a class='btnFic' href=\"#fic_tax_".$row_echantillon[26]."\">Voir les fichiers</a>";
      echo "<br/>";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_partie_organisme'>Partie organisme</div>";

      echo "<br/><strong>ID partie organisme : </strong>" .$row_echantillon[41];
      echo "<br/>";
      echo "<br/><strong>Origine : </strong>" .$row_echantillon[42];
      echo "<br/><strong>Partie : </strong>" .$row_echantillon[43]; //$row_echantillon[45] => FR; $row_echantillon[46] => EN
      echo "<br/>";
      echo "<br/><strong>Observation : </strong>" .$row_echantillon[45];
      echo "</div>";

      if ($row_echantillon[46]) {
        echo "<div class='infos'>";
        echo "<div class='hr click_condition'>Condition</div>";
        echo "<br/><strong>ID condition : </strong>" .$row_echantillon[46];
        echo "<br/>";
        echo "<br/><strong>Milieu : </strong>" .$row_echantillon[47];
        echo "<br/><strong>Temperature : </strong>" .$row_echantillon[48].'°C';
        echo "<br/><strong>Type de culture : </strong>" .$row_echantillon[49];
        echo "<br/><strong>Mode operatoir : </strong>" .$row_echantillon[50];
        echo "<br/>";
        echo "<br/><strong>Observation : </strong>" .$row_echantillon[51];
        echo "<br/><br/><a class='btnFic' href=\"#fic_con_".$row_echantillon[46]."\">Voir les fichiers</a>";
        echo "<br/>";
        echo "</div>";
        echo "</div>";
      }
      // [JM - 05/07/2019] Creation de popup pour afficher la liste des fichiers

      //Purification
      $req_fic_purif = "SELECT * FROM fichier_purification";
      $query_fic_purif = $dbh->query($req_fic_purif);
      $resultat_fic_purif = $query_fic_purif->fetchALL(PDO::FETCH_NUM);

      foreach ($resultat_purif as $key => $value) {
        echo '
        <div id="fic_pur_'.$value[0].'" class="overlay">
        <div class="popup">
        <h2>Fichiers purification '.$value[0].'</h2>
        <a class="close" href="#return">&times;</a>
        <div class="content">
        ';
        $liste_fic_purif = "";
        foreach ($resultat_fic_purif as $key => $value1) {
          if ($value[0] == $value1[3]) {
            $liste_fic_purif .='<li><a href="telecharge.php?id='.$value1[0].'&rankExtra=purification" target="_blank"> Fichier '.$value1[0].' : '.$value1[2].'</a></li>';
          }
        }
        if ($liste_fic_purif != "") {
          echo $liste_fic_purif;
        }
        else {
          echo "Aucun fichier";
        }

        echo '</div>
        </div>
        </div>
        ';
      }


      //Specemen
      echo '
      <div id="fic_spe_'.$row_echantillon[9].'" class="overlay">
      <div class="popup">
      <h2>Fichiers specimen '.$row_echantillon[9].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_spe = "";
      foreach ($dbh->query("SELECT * FROM fichier_specimen WHERE spe_code_specimen = '".$row_echantillon[9]."'") as $key => $value1) {
        if ($value[0] == $value1[3]) {
          $liste_spe .='<li><a href="#"> Fichier '.$value1[0].' : '.$value1[2].'</a></li>';
        }
      }
      if ($liste_spe != "") {
        echo $liste_spe;
      }
      else {
        echo "Aucun fichier";
      }

      echo '</div>
      </div>
      </div>
      ';

      //Taxonomie
      echo '
      <div id="fic_tax_'.$row_echantillon[26].'" class="overlay">
      <div class="popup">
      <h2>Fichiers taxonomie '.$row_echantillon[26].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_tax = "";
      foreach ($dbh->query("SELECT * FROM fichier_taxonomie WHERE tax_id = '".$row_echantillon[26]."'") as $key => $value1) {
        if ($value[0] == $value1[3]) {
          $liste_tax .='<li><a href="#"> Fichier '.$value1[0].' : '.$value1[2].'</a></li>';
        }
      }
      if ($liste_tax != "") {
        echo $liste_tax;
      }
      else {
        echo "Aucun fichier";
      }

      echo '</div>
      </div>
      </div>
      ';
      if ($row_echantillon[48]) {
        //condition
        echo '
        <div id="fic_con_'.$row_echantillon[48].'" class="overlay">
        <div class="popup">
        <h2>Fichiers conditions '.$row_echantillon[48].'</h2>
        <a class="close" href="#return">&times;</a>
        <div class="content">
        ';
        $liste_con = "";

        foreach ($dbh->query("SELECT * FROM fichier_conditions WHERE con_id = '".$row_echantillon[48]."'") as $key => $value1) {
          if ($value[0] == $value1[3]) {
            $liste_con .='<li><a href="#"> Fichier '.$value1[0].' : '.$value1[2].'</a></li>';
          }
        }
        if ($liste_con != "") {
          echo $liste_con;
        }
        else {
          echo "Aucun fichier";
        }

        echo '</div>
        </div>
        </div>
        ';
      }
    }
    else {
      echo "<center><h2>Aucun résultat trouvé</h2></center>";
    }
  }

unset($dbh);
?>

<script>
$(document).ready(function() {
    $('#tab_echantillon').DataTable({select: {style: 'single'}});

    $('#tab_echantillon tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>
