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
  font-size: small;
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
  word-break: break-all;
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

#popup_modif.popup {
  margin: 30px auto;
  padding: 20px;
  background: #fff;
  border-radius: 5px;
  width: 30%;
  position: relative;
  transition: all 5s ease-in-out;
  top: 0%;
}

#popup_select.popup {
  margin: 30px auto;
  padding: 20px;
  background: #fff;
  border-radius: 5px;
  width: 95%;
  position: relative;
  transition: all 5s ease-in-out;
  top: 0%;
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

#popup_modif.popup .content {
  max-height: 80%;
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
include_once 'langues/'.$_SESSION['langue'].'/lang_export.php';

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';

if ($row[0]=='{ADMINISTRATEUR}') {

  print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Extrait.php\">Extraits</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Echantillon.php\">Échantillon</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Condition.php\">Condition</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"modification_Specimen.php\">Specimen</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Taxonomie.php\">Taxonomie</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Expedition.php\">Mission de récolte</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_autorisation.php\">Autorisation</a></td>
    </tr>
    </table><br/>";

if (isset($_GET['specimen'])) {
  $_POST['specimen'] = $_GET['specimen'];
}
// [JM - 05/07/2019] gestion des modification
  if(isset($_POST["type"])){
    switch ($_POST["type"]) {
      case 'fic_spe_suppr':
        $stmt = $dbh->prepare("DELETE FROM fichier_specimen WHERE fic_id = :fic_id");
        $stmt->bindParam(':fic_id', $_POST['id']);
        $stmt->execute();
        break;

      case 'specimen':
        $stmt = $dbh->prepare("UPDATE specimen SET spe_date_recolte = :spe_date_recolte, spe_lieu_recolte = :spe_lieu_recolte, spe_gps_recolte = :spe_gps_recolte, spe_observation = :spe_observation, spe_collection = :spe_collection, spe_contact = :spe_contact, spe_collecteur = :spe_collecteur WHERE spe_code_specimen = :spe_code_specimen");
        $stmt->bindParam(':spe_date_recolte', $_POST['Specimen_Date']);
        $stmt->bindParam(':spe_lieu_recolte', $_POST['Specimen_Lieu']);
        $stmt->bindParam(':spe_gps_recolte', $_POST['Specimen_GPS']);
        $stmt->bindParam(':spe_observation', $_POST['Specimen_Observation']);
        $stmt->bindParam(':spe_collection', $_POST['Specimen_Collection']);
        $stmt->bindParam(':spe_contact', $_POST['Specimen_Contact']);
        $stmt->bindParam(':spe_collecteur', $_POST['Specimen_Collecteur']);
        $stmt->bindParam(':spe_code_specimen', $_POST['id']);
        $stmt->execute();

        if(isset($_FILES['fichier'])){
          foreach ($_FILES['fichier']['name'] as $key => $value) {
            if ($_FILES['fichier']['size'][$key] != 0) {
              $extension_fichier=strtolower(pathinfo($_FILES['fichier']['name'][$key], PATHINFO_EXTENSION));
              $fichier=file_get_contents($_FILES['fichier']['tmp_name'][$key]);
              $fichier=Base64_encode($fichier);

              $stmt = $dbh->prepare("INSERT INTO fichier_specimen (fic_fichier, fic_type, spe_code_specimen) VALUES (:fic_fichier, :fic_type, :spe_code_specimen)");
              $stmt->bindParam(':fic_fichier', $fichier);
              $stmt->bindParam(':fic_type', $extension_fichier);
              $stmt->bindParam(':spe_code_specimen', $_POST['id']);
              $stmt->execute();

              print_r($stmt->errorInfo());
            }
          }
        }

        break;

      case 'taxonomie':
        $stmt = $dbh->prepare("UPDATE specimen SET tax_id = :tax_id WHERE spe_code_specimen = :spe_code_specimen");
        $stmt->bindParam(':tax_id', $_POST['id']);
        $stmt->bindParam(':spe_code_specimen', $_POST['specimen']);

        $stmt->execute();
        break;

      case 'autorisation':
        $stmt = $dbh->prepare("DELETE FROM autorisation_specimen WHERE spe_code_specimen = :spe_code_specimen");
        $stmt->bindParam(':spe_code_specimen', $_POST['specimen']);
        $stmt->execute();
        foreach ($_POST['id'] as $key => $value) {
          $stmt = $dbh->prepare("INSERT INTO autorisation_specimen (aut_numero_autorisation, spe_code_specimen) VALUES (:aut_numero_autorisation, :spe_code_specimen)");
          $stmt->bindParam(':aut_numero_autorisation', $value);
          $stmt->bindParam(':spe_code_specimen', $_POST['specimen']);
          $stmt->execute();
        }
        break;

      case 'expedition':
        $stmt = $dbh->prepare("UPDATE specimen SET exp_id = :exp_id WHERE spe_code_specimen = :spe_code_specimen");
        $stmt->bindParam(':exp_id', $_POST['id']);
        $stmt->bindParam(':spe_code_specimen', $_POST['specimen']);

        $stmt->execute();
        break;

      default:
        // code...
        break;
    }
    echo '<script>window.location.replace("modification_Specimen.php?specimen='.$_POST['specimen'].'");</script>';
  }
  ?>

  <h3 align="center">Modification de specimen</h3>
  <hr>

  <form id="myForm" action="" method="POST" style=" text-align: center;">
    <!-- [JM - 01/02/2019] Recherche du produit -->
    <table id="tab_specimen" class="display">
      <thead>
      <tr>
        <th></th>
        <th>Code</th>
        <th>Date</th>
        <th>Lieu</th>
        <th>GPS</th>
        <th style="width: 35%;">Observation</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($dbh->query("SELECT * FROM specimen ORDER BY spe_code_specimen") as $row) {
        echo '
        <tr>
        <td><input type="radio" name="specimen" value="'.urldecode($row[0]).'"';if (isset($_POST['specimen']) && $row[0] == $_POST['specimen']) echo "checked"; ;echo '></td>
        <td>'.urldecode($row[0]).'</td>
        <td>'.urldecode($row[1]).'</td>
        <td>'.urldecode($row[2]).'</td>
        <td>'.urldecode($row[3]).'</td>
        <td style="width: 35%;">'.urldecode($row[4]).'</td>
        </tr>
        ';
      }
      ?>
    </tbody>
    </table>
    <br/>
    <input type="submit" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
    <br><br>
  </form>
  <hr>
  <?php

  if(isset($_POST['specimen'])){
    $sql_specimen =
    "SELECT * FROM specimen
    INNER JOIN expedition on expedition.exp_id = specimen.exp_id
    INNER JOIN pays on pays.pay_code_pays = expedition.pay_code_pays
    INNER JOIN taxonomie on taxonomie.tax_ID = specimen.tax_ID
    INNER JOIN type_taxonomie on type_taxonomie.typ_tax_id = taxonomie.typ_tax_id
    WHERE specimen.spe_code_specimen = '".$_POST['specimen']."';
    ";

    $result_specimen = $dbh->query($sql_specimen);
    $row_specimen = $result_specimen->fetch(PDO::FETCH_NUM);
    // [JM - 05/07/2019] affichage des information liée au specimen
    if (!empty($row_specimen[0])) {
      echo "<div style=\"margin-left: 10px;\">";
      echo "<a class='btnFic' href=\"#modif_spe\" style=\"float: right;\">Modifier</a>";
      echo "<br/><strong>Code specimen : </strong>" .$row_specimen[0];
      echo "<br/>";
      echo "<br/><strong>Date de recolte : </strong>" .$row_specimen[1];
      echo "<br/><strong>Lieu de recolte : </strong>" .$row_specimen[2];
      echo "<br/><strong>Position GPS : </strong>" .$row_specimen[3];
      echo "<br/>";
      echo "<br/><strong>Observations : </strong><br/>" .$row_specimen[4];
      echo "<br/>";
      echo "<br/><strong>Collection : </strong>" .$row_specimen[5];
      echo "<br/><strong>Contact : </strong>" .$row_specimen[6];
      echo "<br/><strong>Collecteur : </strong>" .$row_specimen[7];
      echo "<br/><br/><a class='btnFic' href=\"#fic_spe\">Voir les fichiers</a>";
      echo "<br/>";
      echo "<br/>";
      echo '
      <div id="modif_spe" class="overlay">
        <div id="popup_modif" class="popup">
          <h2>specimen '.$row_specimen[0].'</h2>
          <a class="close" href="#return">&times;</a>
          <div class="content">
            <form id="myForm" action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="specimen" value="'.$row_specimen[0].'">
              <input type="hidden" name="type" value="specimen">
              <input type="hidden" name="id" value="'.$row_specimen[0].'">
              Date *<br/><input class="echantillon_nouveau specimen_nouveau" type="date" name="Specimen_Date" value="'.$row_specimen[1].'" required><br/><br/>
              Lieu de recolte *<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Lieu" value="'.$row_specimen[2].'" required><br/><br/>
              <br/>
              GPS<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_GPS" value="'.$row_specimen[3].'"><br/><br/>
              Observation<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Observation" value="'.$row_specimen[4].'"><br/><br/>
              Collection<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collection" value="'.$row_specimen[5].'"><br/><br/>
              Contact<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Contact" value="'.$row_specimen[6].'"><br/><br/>
              Collecteur<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collecteur" value="'.$row_specimen[7].'"><br/><br/>
                      Fichier<br/><input class="echantillon_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="fichier[]" multiple><br/><br/>
              <br/><br/><input type="submit" style="float: right;">
            </form>
          </div>
        </div>
      </div>
      ';
      echo "</div>";

      echo "<hr>";

      //Autorisation
      $req_aut = "SELECT * FROM autorisation_specimen Inner JOIN autorisation ON autorisation_specimen.aut_numero_autorisation = autorisation.aut_numero_autorisation WHERE spe_code_specimen = '".$_POST['specimen']."'";
      $query_aut = $dbh->query($req_aut);
      $resultat_aut = $query_aut->fetchALL(PDO::FETCH_NUM);
      
        echo "<div class='hr'>Autorisation</div>";
        echo "<a class='btnFic' href=\"#modif_autorisation\" style=\"float: right;\">Modifier</a>";
        echo "<div style='max-height: 250px;overflow: auto; width: 100%;'>
        <table class=\"table-tableau\">
        <tr>
        <th>Numéro d'autorisation</th>
        <th>Type d'autorisation</th>
        </tr>
        ";
        $array_aut = array();
        foreach ($resultat_aut as $key1 => $value1) {
            echo "
            <tr>
            <td>".$value1[0]."</td>
            <td>".$value1[3]."</td>
            </tr>
            ";
            $array_aut[] = $value1[0];
        }
        echo "</table>";
        echo "</div>";
        echo "<div class='container'>";
        echo "</div>";
        echo "<br/>";


      echo "<div class='container'>";

      echo "<div class='infos'>";
      echo "<div class='hr click_expedition'>Mission de recolte</div>";
      echo "<a class='btnFic' href=\"#modif_expedition\" style=\"float: right;\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/><strong>ID expedition : </strong>" .$row_specimen[10];
      echo "<br/>";
      echo "<br/><strong>Nom : </strong>" .$row_specimen[11];
      echo "<br/><strong>Contact : </strong>" .$row_specimen[12];
      echo "<br/>";
      echo "<br/><strong>Pays : </strong>" .$row_specimen[15];
      echo "<br/><strong>Collaboration : </strong>";if ($row_specimen[16] == 1) echo "Oui"; else echo "Non";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_taxonomie'>Taxonomie</div>";
      echo "<a class='btnFic' href=\"#modif_taxonomie\" style=\"float: right;\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/><strong>ID taxonomie : </strong>" .$row_specimen[17];
      echo "<br/>";
      echo "<br/><strong>Phylum : </strong>" .$row_specimen[18];
      echo "<br/><strong>Classe : </strong>" .$row_specimen[19];
      echo "<br/><strong>Ordre : </strong>" .$row_specimen[20];
      echo "<br/><strong>Famille : </strong>" .$row_specimen[21];
      echo "<br/><strong>Genre : </strong>" .$row_specimen[22];
      echo "<br/><strong>Espece : </strong>" .$row_specimen[23];
      echo "<br/><strong>Sous-espece : </strong>" .$row_specimen[24];
      echo "<br/><strong>Varieté : </strong>" .$row_specimen[25];
      echo "<br/>";
      echo "<br/><strong>Protocole : </strong>" .$row_specimen[26];
      echo "<br/><strong>Sequence : </strong>" .$row_specimen[27];
      echo "<br/><strong>Sequence ref cahier de labo : </strong>" .$row_specimen[28];
      echo "<br/>";
      echo "<br/><strong>Type : </strong>" .$row_specimen[31];
      echo "<br/><br/><a class='btnFic' href=\"#fic_tax\">Voir les fichiers</a>";
      echo "<br/>";
      echo "</div>";


      // [JM - 05/07/2019] Creation de popup pour afficher la liste des fichiers

      //Specemen
      echo '
      <div id="fic_spe_" class="overlay">
      <div class="popup">
      <h2>Fichiers specimen '.$row_specimen[0].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_spe = "";
      foreach ($dbh->query("SELECT * FROM fichier_specimen WHERE spe_code_specimen = '".$row_specimen[0]."'") as $key => $value1) {
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

      echo '
      <div id="modif_taxonomie" class="overlay">
      <div id="popup_select" class="popup">
      <h2>Taxonomie</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST">
        <input type="hidden" name="specimen" value="'.$row_specimen[0].'">
        <input type="hidden" name="type" value="taxonomie">
      ';
      ?>
      <table id="tab_taxonomie" class="display">
        <thead>
        <tr>
          <th></th>
          <th>ID</th>
          <th>Type</th>
          <th>Genre</th>
          <th>Espece</th>
          <th>Sous-espece</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($dbh->query("SELECT tax_id, typ_tax_type, tax_genre, tax_espece, tax_sous_espece FROM taxonomie INNER JOIN type_taxonomie ON taxonomie.typ_tax_id = type_taxonomie.typ_tax_id ORDER BY typ_tax_type, tax_genre, tax_espece, tax_sous_espece") as $row) {
          echo '
          <tr>
          <td><input type="radio" name="id" value="'.urldecode($row[0]).'"></td>
          <td>'.urldecode($row[0]).'</td>
          <td>'.urldecode($row[1]).'</td>
          <td>'.urldecode($row[2]).'</td>
          <td>'.urldecode($row[3]).'</td>
          <td>'.urldecode($row[4]).'</td>
          </tr>
          ';
        }
        ?>
      </tbody>
      </table>
      <br/>
      <center><input type="submit"></center>
      <?php
      echo '
      </form>
      </div>
      </div>
      ';

      echo '
      <div id="modif_autorisation" class="overlay">
      <div id="popup_select" class="popup">
      <h2>Autorisation</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST">
        <input type="hidden" name="specimen" value="'.$row_specimen[0].'">
        <input type="hidden" name="type" value="autorisation">
      ';
      ?>
      <table id="tab_autorisation" class="display">
        <thead>
        <tr>
          <th></th>
          <th>ID</th>
          <th>Type</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($dbh->query("SELECT * FROM autorisation") as $row) {
          echo '
          <tr>
          <td><input type="checkbox" name="id[]" value="'.urldecode($row[0]).'"'; if(in_array(urldecode($row[0]), $array_aut)) echo "checked"; echo '></td>
          <td>'.urldecode($row[0]).'</td>
          <td>'.urldecode($row[1]).'</td>
          </tr>
          ';
        }
        ?>
      </tbody>
      </table>
      <br/>
      <center><input type="submit"></center>
      <?php
      echo '
      </form>
      </div>
      </div>
      ';

      //Taxonomie
      echo '
      <div id="fic_tax" class="overlay">
      <div class="popup">
      <h2>Fichiers taxonomie '.$row_specimen[17].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_tax = "";
      foreach ($dbh->query("SELECT * FROM fichier_taxonomie WHERE tax_id = '".$row_specimen[17]."'") as $key => $value1) {
          $liste_tax .='<li><a href="#"> Fichier '.$value1[0].' : '.$value1[2].'</a></li>';
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

      //specimen
      echo '
      <div id="fic_spe" class="overlay">
      <div class="popup">
      <h2>Fichiers specimen '.$row_specimen[0].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_tax = "";
      foreach ($dbh->query("SELECT * FROM fichier_specimen WHERE spe_code_specimen = '".$row_specimen[0]."'") as $key => $value1) {
        echo '
        <form id="btnSupprForm'.$value1[0].'" method="POST" >
          <input type="hidden" name="specimen" value="'.$row_specimen[0].'"/>
          <input type="hidden" name="type" value="fic_spe_suppr" />
          <input type="hidden" name="id" value="'.$value1[0].'"/>
        </form>
        ';
        $liste_tax .='<li><a href="telecharge.php?id='.$value1[0].'&rankExtra=specimen" target="_blank"> Fichier '.$value1[0].' : '.$value1[2].'</a> | <a href="#" onclick="if (confirm(\'Etes-vous sûr ?\')){document.getElementById(\'btnSupprForm'.$value1[0].'\').submit();}">Supprimer</a></li>';

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


      echo '
      <div id="modif_expedition" class="overlay">
      <div id="popup_select" class="popup">
      <h2>Conditions</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST">
        <input type="hidden" name="specimen" value="'.$row_specimen[0].'">
        <input type="hidden" name="type" value="expedition">
      ';
      ?>
      <table id="tab_expedition" class="display">
        <thead>
        <tr>
          <th></th>
          <th>ID</th>
          <th>Nom</th>
          <th>Contact</th>
          <th>Code pays</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($dbh->query("SELECT * FROM expedition ORDER BY exp_id") as $row) {
          echo '
          <tr>
          <td><input type="radio" name="id" value="'.urldecode($row[0]).'"></td>
          <td>'.urldecode($row[0]).'</td>
          <td>'.urldecode($row[1]).'</td>
          <td>'.urldecode($row[2]).'</td>
          <td>'.urldecode($row[3]).'</td>
          </tr>
          ';
        }
        ?>
      </tbody>
      </table>
      <br/>
      <center><input type="submit"></center>
      <?php
      echo '
      </form>
      </div>
      </div>
      ';

    }
    else {
      echo "<center><h2>Aucun résultat trouvé</h2></center>";
    }
  }
}
else require 'deconnexion.php';
unset($dbh);
?>

<script>
$(document).ready(function() {
    $('#tab_specimen').DataTable({select: {style: 'single'}});

    $('#tab_specimen tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });

    $('#tab_autorisation').DataTable({select: {style: 'api'}});

    $('#tab_taxonomie').DataTable({select: {style: 'single'}});

    $('#tab_taxonomie tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });

    $('#tab_expedition').DataTable({select: {style: 'single'}});

    $('#tab_expedition tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>
