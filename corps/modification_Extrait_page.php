<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<style>
* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;
}

/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}

div.autocomplete input, input.autocomplete  {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

div.autocomplete input[type=image] {
  padding: 0;
}

div.autocomplete input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}

input[type=submit].autocomplete  {
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9;
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important;
  color: #ffffff;
}

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
if (isset($_GET['echantillon'])) {
  $_POST['echantillon'] = $_GET['echantillon'];
}
// [JM - 05/07/2019] gestion des modification
  if(isset($_POST["type"])){
    switch ($_POST["type"]) {
      case 'Extraits':
        $stmt = $dbh->prepare("UPDATE extraits SET ext_solvant = :ext_solvant, ext_type_extraction = :ext_type_extraction, ext_etat = :ext_etat, ext_disponibilite = :ext_disponibilite, ext_protocole = :ext_protocole, ext_stockage = :ext_stockage, ext_observations = :ext_observations WHERE ext_id = :ext_id");
        $stmt->bindParam(':ext_solvant', $_POST['Solvant']);
        $stmt->bindParam(':ext_type_extraction', $_POST['TypeExtra']);
        $stmt->bindParam(':ext_etat', $_POST['Etat']);

        if (isset($_POST['Disponibilite'])) $_POST['Disponibilite'] = "TRUE"; else $_POST['Disponibilite'] = "FALSE";
        $stmt->bindParam(':ext_disponibilite', $_POST['Disponibilite']);

        $stmt->bindParam(':ext_protocole', $_POST['Protocole']);
        $stmt->bindParam(':ext_stockage', $_POST['Lieu']);
        $stmt->bindParam(':ext_observations', $_POST['Observations']);

        $stmt->bindParam(':ext_id', $_POST['id']);

        $stmt->execute();
        break;

      case 'fic_pur_suppr':
        $stmt = $dbh->prepare("DELETE FROM fichier_purification WHERE fic_id = :fic_id");
        $stmt->bindParam(':fic_id', $_POST['id']);
        $stmt->execute();
        break;

      case 'Purification':
        $stmt = $dbh->prepare("UPDATE purification SET pur_purification = :pur_purification, pur_ref_book = :pur_ref_book WHERE pur_id = :pur_id");
        $stmt->bindParam(':pur_purification', $_POST['purification']);
        $stmt->bindParam(':pur_ref_book', $_POST['ref_book']);
        $stmt->bindParam(':pur_id', $_POST['id']);
        $stmt->execute();

        if(isset($_FILES['fichier'])){
          foreach ($_FILES['fichier']['name'] as $key => $value) {
            if ($_FILES['fichier']['size'][$key] != 0) {
              $extension_fichier=strtolower(pathinfo($_FILES['fichier']['name'][$key], PATHINFO_EXTENSION));
              $fichier=file_get_contents($_FILES['fichier']['tmp_name'][$key]);
              $fichier=Base64_encode($fichier);

              $stmt = $dbh->prepare("INSERT INTO fichier_purification (fic_fichier, fic_type, pur_id) VALUES (:fic_fichier, :fic_type, :pur_id)");
              $stmt->bindParam(':fic_fichier', $fichier);
              $stmt->bindParam(':fic_type', $extension_fichier);
              $stmt->bindParam(':pur_id', $_POST['id']);
              $stmt->execute();
            }
          }
        }

        break;

      case 'Purification_add':
        $stmt = $dbh->prepare("INSERT INTO purification (pur_purification, pur_ref_book, ext_id) VALUES (:pur_purification, :pur_ref_book, :ext_id)");
        $stmt->bindParam(':pur_purification', $_POST['purification']);
        $stmt->bindParam(':pur_ref_book', $_POST['ref_book']);
        $stmt->bindParam(':ext_id', $_POST['id']);
        $stmt->execute();
        $pur_id = $dbh->lastInsertId();

        if(isset($_FILES['fichier'])){
          foreach ($_FILES['fichier']['name'] as $key => $value) {
            if ($_FILES['fichier']['size'][$key] != 0) {
              $extension_fichier=strtolower(pathinfo($_FILES['fichier']['name'][$key], PATHINFO_EXTENSION));
              $fichier=file_get_contents($_FILES['fichier']['tmp_name'][$key]);
              $fichier=Base64_encode($fichier);

              $stmt = $dbh->prepare("INSERT INTO fichier_purification (fic_fichier, fic_type, pur_id) VALUES (:fic_fichier, :fic_type, :pur_id)");
              $stmt->bindParam(':fic_fichier', $fichier);
              $stmt->bindParam(':fic_type', $extension_fichier);
              $stmt->bindParam(':pur_id', $pur_id);
              $stmt->execute();
            }
          }
        }

        break;

      case 'Echantillon':
        $stmt = $dbh->prepare("UPDATE echantillon SET ech_contact = :ech_contact, ech_publication_doi = :ech_publication_doi, ech_stock_disponibilite = :ech_stock_disponibilite, ech_stock_quantite = :ech_stock_quantite, ech_lieu_stockage = :ech_lieu_stockage WHERE ech_code_echantillon = :ech_code_echantillon");
        $stmt->bindParam(':ech_contact', $_POST['contact']);
        $stmt->bindParam(':ech_publication_doi', $_POST['DOI']);

        if (isset($_POST['Disponibilite'])) $_POST['Disponibilite'] = "TRUE"; else $_POST['Disponibilite'] = "FALSE";
        $stmt->bindParam(':ech_stock_disponibilite', $_POST['stock']);

        $stmt->bindParam(':ech_stock_quantite', $_POST['quantite']);
        $stmt->bindParam(':ech_lieu_stockage', $_POST['lieu']);

        $stmt->bindParam(':ech_code_echantillon', $_POST['id']);

        $stmt->execute();
        break;

      default:
        // code...
        break;
    }

  }

  // [JM - 05/07/2019] liste des code echantillon
  $sql_autocomplete = "SELECT ech_code_echantillon FROM echantillon order by ech_code_echantillon";
  $result_autocomplete = $dbh->query($sql_autocomplete);

  $var_id_echantillon = "[";

  foreach ($result_autocomplete as $key => $value) {
    $var_id_echantillon .=  '"'.$value[0].'",';
  }
  $var_id_echantillon .= '""]';

  ?>

  <h3 align="center">Modification d'échantillon</h3>
  <hr>

  <form id="myForm" action="" method="POST" style=" text-align: center;">
    <!-- [JM - 01/02/2019] Recherche du produit -->
    <div class="autocomplete" style="width:325px;">
      <input id="myInput" placeholder="code échantillon" type="text" name="echantillon" <?php if (isset($_POST['echantillon'])) echo "value='".$_POST['echantillon']."'"; ?> onfocus="this.select()" autofocus>
    </div>
    <input type="submit" class="autocomplete" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
    <br><br>
  </form>

  <?php

  if(isset($_POST['echantillon'])){
    $sql_echantillon =
    "SELECT * FROM Echantillon
    INNER JOIN specimen on specimen.spe_code_specimen = echantillon.spe_code_specimen
    INNER JOIN expedition on expedition.exp_id = specimen.exp_id
    INNER JOIN pays on pays.pay_code_pays = expedition.pay_code_pays
    INNER JOIN taxonomie on taxonomie.tax_ID = specimen.tax_ID
    INNER JOIN type_taxonomie on type_taxonomie.typ_tax_id = taxonomie.typ_tax_id
    INNER JOIN partie_organisme on partie_organisme.par_id = echantillon.par_id
    INNER JOIN condition on condition.con_id = echantillon.con_id
    WHERE Echantillon.ech_code_echantillon = '".$_POST['echantillon']."';
    ";

    $result_echantillon = $dbh->query($sql_echantillon);
    $row_echantillon = $result_echantillon->fetch(PDO::FETCH_NUM);
    // [JM - 05/07/2019] affichage des information liée à l'echantillon
    if (!empty($row_echantillon[0])) {
      echo "<div style=\"margin-left: 10px;\">";
      echo "<strong>Code echantillon : </strong>" .$row_echantillon[0];
      echo "<br/>";
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[1];
      echo "<br/>";
      echo "<br/><strong>DOI : </strong>" .$row_echantillon[2];
      echo "<br/>";
      echo "<br/><strong>Stock : </strong>"; if ($row_echantillon[3] == 1) echo "Oui"; else echo "Non";
      echo "<br/><strong>Quantité : </strong>" .$row_echantillon[4];
      echo "<br/><strong>Lieu de stockage : </strong>" .$row_echantillon[5];
      echo "<br/>";
      echo "<br/>";
      echo "<a class='btnFic' href=\"#modif_ech_".$row_echantillon[0]."\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo '
      <div id="modif_ech_'.$row_echantillon[0].'" class="overlay">
        <div id="popup_modif" class="popup">
          <h2>Code echantillon '.$row_echantillon[0].'</h2>
          <a class="close" href="#return">&times;</a>
          <div class="content">
            <form id="myForm" action="" method="POST">
              <input type="hidden" name="echantillon" value="'.$row_echantillon[0].'">
              <input type="hidden" name="type" value="Echantillon">
              <input type="hidden" name="id" value="'.$row_echantillon[0].'">
              <br/><strong>Contact : </strong><br/><input name="contact" type="text" value="'.$row_echantillon[1].'">
              <br/><br/><strong>DOI : </strong><br/><input name="DOI" type="text" value="'.$row_echantillon[2].'">
              <br/><br/><strong>Stock : </strong><br/><input name="stock" type="checkbox" '; if ($row_echantillon[3] == 1) echo "checked"; echo '>
              <br/><br/><strong>Quantité : </strong><br/><input name="quantite" type="text" value="'.$row_echantillon[4].'">
              <br/><br/><strong>Lieu de stockage : </strong><br/><input name="lieu" type="text" value="'.$row_echantillon[5].'">
              <br/><br/><input type="submit" style="float: right;">
            </form>
          </div>
        </div>
      </div>
      ';
      echo "</div>";

      echo "<div class='hr click_extraits'>Extraits</div>";

      echo "<div class='container'>";
      // [JM - 05/07/2019] cree une liste des extrait et de leur purification
      $req_extrait = "
      SELECT ext_ID, ext_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_nom, chi_prenom, equi_nom_equipe, ins_nom FROM extraits
      INNER JOIN chimiste ON chimiste.chi_id_chimiste = extraits.chi_id_chimiste
      LEFT OUTER JOIN equipe ON equipe.equi_id_equipe = chimiste.chi_id_equipe
      LEFT OUTER JOIN institut ON institut.ins_code_institut = equipe.ins_code_institut
      WHERE ech_code_echantillon = '".$_POST['echantillon']."'
      ORDER BY ext_ID";
      $query_extrait = $dbh->query($req_extrait);
      $resultat_extrait = $query_extrait->fetchALL(PDO::FETCH_NUM);

      $req_purif = "SELECT purification.pur_id, pur_purification, pur_ref_book, count(fic_id), ext_id FROM purification LEFT OUTER JOIN fichier_purification ON fichier_purification.pur_id = purification.pur_id GROUP BY purification.pur_id ORDER BY pur_id";
      $query_purif = $dbh->query($req_purif);
      $resultat_purif = $query_purif->fetchALL(PDO::FETCH_NUM);
      // [JM - 05/07/2019] affichage des resultat
      foreach ($resultat_extrait as $key => $value) {
        echo "<div class='extraits'>";
        echo "<a class='btnFic' style=\"float: right;\" href=\"#modif_ext_".$value[0]."\">Modifier</a>";
        echo "<strong>ID extrait : </strong>" .$value[0];
        echo "<br/>";
        echo "<br/><strong>Solvant : </strong>" .$value[1];
        echo "<br/><strong>Type d'extraction : </strong>" .$value[2];
        echo "<br/><strong>Etat : </strong>" .$value[3];
        echo "<br/>";
        echo "<br/><strong>Disponibilité : </strong>"; if ($value[4] == 1) echo "Oui"; else echo "Non";
        echo "<br/><strong>Protocole : </strong>" .$value[5];
        echo "<br/><strong>Lieu de stockage : </strong>" .$value[6];
        echo "<br/>";
        echo "<br/><strong>Observations : </strong>" .$value[7];
        echo "<br/>";
        echo "<br/><strong>Nom du chimiste : </strong>" .$value[8]. " " .$value[9] ;
        echo "<br/><strong>Equipe : </strong>" .$value[10];
        echo "<br/><strong>Institut : </strong>" .$value[11];
        echo "<div class='hr'>Purifications</div>";
        echo '
        <div id="modif_ext_'.$value[0].'" class="overlay">
          <div id="popup_modif" class="popup">
            <h2>ID Extraits '.$value[0].'</h2>
            <a class="close" href="#return">&times;</a>
            <div class="content">
              <form id="myForm" action="" method="POST">
                <input type="hidden" name="echantillon" value="'.$row_echantillon[0].'">
                <input type="hidden" name="type" value="Extraits">
                <input type="hidden" name="id" value="'.$value[0].'">
                <br/><strong>Solvant : </strong><br/><input name="Solvant" type="text" value="'.$value[1].'">
                <br/><br/><strong>Type d\'extraction : </strong><br/><input name="TypeExtra" type="text" value="'.$value[2].'">
                <br/><br/><strong>Etat : </strong><br/><input name="Etat" type="text" value="'.$value[3].'">
                <br/><br/><strong>Disponibilité : </strong><br/><input name="Disponibilite" type="checkbox" '; if ($value[4] == 1) echo "checked"; echo '>
                <br/><br/><strong>Protocole : </strong><br/><input name="Protocole" type="text" value="'.$value[5].'">
                <br/><br/><strong>Lieu de stockage : </strong><br/><input name="Lieu" type="text" value="'.$value[6].'">
                <br/><br/><strong>Observations : </strong><br/><input name="Observations" type="text" value="'.$value[7].'">
                <br/><br/><strong>Nom du chimiste : </strong>' .$value[8].' ' .$value[9].'
                <br/><br/><strong>Equipe : </strong>' .$value[10].'
                <br/><br/><strong>Institut : </strong>' .$value[11].'
                <br/><br/><input type="submit" style="float: right;">
              </form>
            </div>
          </div>
        </div>
        ';
        echo "
        <div style='max-height: 250px;overflow: auto; width: 100%;'>
        <table class=\"table-tableau\">
        <tr>
        <th>ID</th>
        <th>Purification</th>
        <th>Ref cahier de labo</th>
        <th>Fichiers</th>
        <th></th>
        </tr>
        ";

        foreach ($resultat_purif as $key1 => $value1) {
          if($value1[4] == $value[0]){
            if(isset($_POST['modif']) && $_POST['modif'] == "Purification" && $_POST['ID'] == $value1[0]){
      				echo '<form action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="echantillon" value="'.$row_echantillon[0].'">
              <input type="hidden" name="type" value="Purification">
              <input type="hidden" name="id" value="'.$value1[0].'">';
      				echo "
                <tr>
                  <td>".$value1[0]."</td>
                  <td><input style=\"width: 100%;\" name='purification' value='".$value1[1]."'></td>
                  <td><input style=\"width: 100%;\" name='ref_book' value='".$value1[2]."'></td>
                  <td><input style=\"width: 100%;\" type=\"file\" name=\"fichier[]\" accept=\"image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt, application/msword\" multiple></td>
                  <td><button type=\"submit\" name=\"envoi_modif\" value=\"type\" title=\"Envoyer\" style=\"border: 0px;padding: 0px;background: transparent;\"><img border=\"0\" src=\"images/ok.gif\" width=\"20\" height=\"20\" alt=\"valider\"></button> <a href=\"?echantillon=".$_POST['echantillon']."\"><img border=\"0\" src=\"images/pasok.gif\" width=\"20\" height=\"20\" alt=\"annuler\"></a></td>
                </tr>
      				";
      				echo '</form>';
      			}
      			else{
              echo '
              <tr>
                <td>'.$value1[0].'</td>
                <td>'.$value1[1].'</td>
                <td>'.$value1[2].'</td>
                <td><a href="#fic_pur_'.$value1[0].'">'.$value1[3].' Fichier(s)</a></td>
                <td>
                  <form id="btnModifForm'.$value1[0].'" method="POST" >
                    <input type="hidden" name="echantillon" value="'.$row_echantillon[0].'"/>
                    <input type="hidden" name="modif" value="Purification" />
                    <input type="hidden" name="ID" value="'.$value1[0].'"/>
                  </form>
                  <a href="#" onclick="document.getElementById(\'btnModifForm'.$value1[0].'\').submit()"><img border="0" src="images/modifier.gif" width="20" height="20" alt="modifier"/></a>
                </td>
              </tr>
              ';
            }
          }
        }
        ?>

        <form id="myForm2" action="" method="POST" enctype="multipart/form-data">
        <?php if (isset($_POST['Ajouter2'.$value[0]])): ?>
          <tr>
            <td></td>
            <input type="hidden" name="echantillon" value="<?php echo $row_echantillon[0]; ?>">
            <input type="hidden" name="type" value="Purification_add">
            <input type="hidden" name="id" value="<?php echo $value[0]; ?>">
            <td><input style="width: 100%;" name='purification'></td>
            <td><input style="width: 100%;" name='ref_book'></td>
            <td><input style="width: 100%;" type="file" name="fichier[]" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt, application/msword" multiple></td>
            <td><button type="submit" name="save" value="type" title="Envoyer" style="border: 0px;padding: 0px;background: transparent;"><img border="0" src="images/ok.gif" width="20" height="20" alt="valider"></button> <a href="?echantillon=<?php echo $_POST['echantillon'];?>"><img border="0" src="images/pasok.gif" width="20" height="20" alt="annuler"></a></td>
          </tr>
        <?php endif; ?>

        </table>

        <?php if (!isset($_POST['Ajouter2'.$value[0]]) && !isset($_POST['modif']) || (isset($_POST['modif']) && $_POST['modif'] != "Purification")): ?>
          <input type="hidden" name="echantillon" value="<?php echo $row_echantillon[0]; ?>">
          <input type="hidden" name="type" value="Purification">
          <input type="hidden" name="id" value="<?php echo $value1[0]; ?>">
          <input type="submit" name="Ajouter2<?php echo $value[0]; ?>" value="Ajouter">
        <?php endif; ?>
        </form>
        <?php

        echo "</div>";
        echo "</div>";
      }
      echo "</div>";
      ?>

      <?php
      echo "<hr>";
      echo "<br/>";
      echo "<div class='container'>";
      echo "<div class='infos'>";
      echo "<div class='hr click_specimen'>Specimen</div>";

      echo "<br/><strong>Code specimen : </strong>" .$row_echantillon[9];
      echo "<br/>";
      echo "<br/><strong>Date de recolte : </strong>" .$row_echantillon[10];
      echo "<br/><strong>Lieu de recolte : </strong>" .$row_echantillon[11];
      echo "<br/><strong>Position GPS : </strong>" .$row_echantillon[12];
      echo "<br/>";
      echo "<br/><strong>Observations : </strong>" .$row_echantillon[13];
      echo "<br/>";
      echo "<br/><strong>Collection : </strong>" .$row_echantillon[14];
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[15];
      echo "<br/><strong>Collecteur : </strong>" .$row_echantillon[16];
      echo "<br/><br/><a class='btnFic' href=\"#fic_spe_".$row_echantillon[9]."\">Voir les fichier</a>";
      echo "<br/>";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_expedition'>Expedition</div>";

      echo "<br/><strong>ID expedition : </strong>" .$row_echantillon[19];
      echo "<br/>";
      echo "<br/><strong>Nom : </strong>" .$row_echantillon[20];
      echo "<br/><strong>Contact : </strong>" .$row_echantillon[21];
      echo "<br/>";
      echo "<br/><strong>Pays : </strong>" .$row_echantillon[24];
      echo "<br/><strong>APA : </strong>";if ($row_echantillon[25] == 1) echo "Oui"; else echo "Non";
      echo "<br/><strong>Numero de permis : </strong>" .$row_echantillon[26];
      echo "<br/><strong>Collaboration : </strong>";if ($row_echantillon[27] == 1) echo "Oui"; else echo "Non";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_taxonomie'>Taxonomie</div>";

      echo "<br/><strong>ID taxonomie : </strong>" .$row_echantillon[28];
      echo "<br/>";
      echo "<br/><strong>Phylum : </strong>" .$row_echantillon[29];
      echo "<br/><strong>Classe : </strong>" .$row_echantillon[30];
      echo "<br/><strong>Ordre : </strong>" .$row_echantillon[31];
      echo "<br/><strong>Famille : </strong>" .$row_echantillon[32];
      echo "<br/><strong>Genre : </strong>" .$row_echantillon[33];
      echo "<br/><strong>Espece : </strong>" .$row_echantillon[34];
      echo "<br/><strong>Sous-espece : </strong>" .$row_echantillon[35];
      echo "<br/><strong>Varieté : </strong>" .$row_echantillon[36];
      echo "<br/>";
      echo "<br/><strong>Protocole : </strong>" .$row_echantillon[37];
      echo "<br/><strong>Sequence : </strong>" .$row_echantillon[38];
      echo "<br/><strong>Sequence ref cahier de labo : </strong>" .$row_echantillon[39];
      echo "<br/>";
      echo "<br/><strong>Type : </strong>" .$row_echantillon[42];
      echo "<br/><br/><a class='btnFic' href=\"#fic_tax_".$row_echantillon[28]."\">Voir les fichier</a>";
      echo "<br/>";
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_partie_organisme'>Partie organisme</div>";

      echo "<br/><strong>ID partie organisme : </strong>" .$row_echantillon[43];
      echo "<br/>";
      echo "<br/><strong>Origine : </strong>" .$row_echantillon[44];
      echo "<br/><strong>Partie : </strong>" .$row_echantillon[45]; //$row_echantillon[45] => FR; $row_echantillon[46] => EN
      echo "<br/>";
      echo "<br/><strong>Observations : </strong>" .$row_echantillon[47];
      echo "</div>";

      echo "<div class='infos'>";
      echo "<div class='hr click_condition'>Condition</div>";

      echo "<br/><strong>ID condition : </strong>" .$row_echantillon[48];
      echo "<br/>";
      echo "<br/><strong>Milieu : </strong>" .$row_echantillon[49];
      echo "<br/><strong>Temperature : </strong>" .$row_echantillon[50];
      echo "<br/><strong>Type de culture : </strong>" .$row_echantillon[51];
      echo "<br/><strong>Mode operatoir : </strong>" .$row_echantillon[52];
      echo "<br/>";
      echo "<br/><strong>Observations : </strong>" .$row_echantillon[53];
      echo "<br/><br/><a class='btnFic' href=\"#fic_con_".$row_echantillon[48]."\">Voir les fichier</a>";
      echo "<br/>";
      echo "</div>";
      echo "</div>";
      
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
            echo '
            <form id="btnSupprForm'.$value1[0].'" method="POST" >
              <input type="hidden" name="echantillon" value="'.$row_echantillon[0].'"/>
              <input type="hidden" name="type" value="fic_pur_suppr" />
              <input type="hidden" name="id" value="'.$value1[0].'"/>
            </form>
            ';
            $liste_fic_purif .='<li><a href="telecharge.php?id='.$value1[0].'&rankExtra=purification" target="_blank"> Fichier '.$value1[0].' : '.$value1[2].'</a> | <a href="#" onclick="if (confirm(\'Etes-vous sûr ?\')){document.getElementById(\'btnSupprForm'.$value1[0].'\').submit();}">Supprimer</a></li>';
          }
        }
        if ($liste_fic_purif != "") {
          echo $liste_fic_purif;
        }
        else {
          echo "Aucun fichier";
        }

        echo '
        </div>
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
      <div id="fic_tax_'.$row_echantillon[28].'" class="overlay">
      <div class="popup">
      <h2>Fichiers taxonomie '.$row_echantillon[28].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_tax = "";
      foreach ($dbh->query("SELECT * FROM fichier_taxonomie WHERE tax_id = '".$row_echantillon[28]."'") as $key => $value1) {
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
    else {
      echo "<center><h2>Aucun résultat trouvé</h2></center>";
    }
  }
}
else require 'deconnexion.php';
unset($dbh);
?>

<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
    var a, b, i, val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    var nb = 0;
    for (i = 0; i < arr.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        nb++;
        if (nb <= 15){
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            closeAllLists();
          });
          a.appendChild(b);
        }
      }
    }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) { //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      //e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var id_echantillon = <?php echo $var_id_echantillon;?>;


/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput"), id_echantillon);

$(document).ready(function() {
  $('table.display').DataTable();
} );
</script>
