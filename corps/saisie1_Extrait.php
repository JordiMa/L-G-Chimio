<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
  background-color: #e9e9e9;
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

:required {
  border-color: orangered;
}

.table-pays {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.td-pays, .th-pays {
  border: 1px solid #ddd;
  padding: 8px;
}

.tr-pays:nth-child(even) {
  background-color: #f2f2f2;
}

.tr-pays:hover {
  background-color: #ddd;
}

.th-pays {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';

if(!isset($_POST["mol"])) $_POST["mol"]="";
if(!isset($_POST['equipe'])) $_POST['equipe']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['masse'])) $_POST['masse']="";
if(!isset($_POST['type'])) $_POST['type']="";
if(!isset($_POST['config'])) $_POST['config']="";
if(!isset($_POST['origimol'])) $_POST['origimol']="";
if(!isset($_POST['etapmol'])) $_POST['etapmol']="";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);

// [JM - 05/07/2019] liste des code echantillon
$var_id_echantillon = "[";
foreach ($dbh->query("SELECT ech_code_echantillon FROM echantillon order by ech_code_echantillon") as $key => $value) {
  $var_id_echantillon .=  '"'.$value[0].'",';
}
$var_id_echantillon .= '""]';

// [JM - 05/07/2019] liste des code specimen
$var_id_specimen = "[";
foreach ($dbh->query("SELECT spe_code_specimen FROM specimen order by spe_code_specimen") as $key => $value) {
  $var_id_specimen .=  '"'.$value[0].'",';
}
$var_id_specimen .= '""]';


?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">
  <!-- [JM - 05/07/2019] Menu de navigation -->
  <h2 style="text-align: center;">
    <a id="noTextDecoration" name="Extrait" onclick="hideDiv();showDiv('Extrait');">Extrait</a> -
    <a id="noTextDecoration" name="Purification" onclick="hideDiv();showDiv('Purification');">Purification</a> -
    <a id="noTextDecoration" name="echantillon" onclick="hideDiv();showDiv('echantillon');">Echantillon</a> -
    <a id="noTextDecoration" name="Condition" onclick="hideDiv();showDiv('Condition');">Condition</a> -
    <a id="noTextDecoration" name="Specimen" onclick="hideDiv();showDiv('Specimen');">Specimen</a> -
    <a id="noTextDecoration" name="Taxonomie" onclick="hideDiv();showDiv('Taxonomie');">Taxonomie</a> -
    <a id="noTextDecoration" name="Expedition" onclick="hideDiv();showDiv('Expedition');">Expedition</a>
  </h2>

  <!-- [JM - 05/07/2019] Extrait -->
  <div name="divHide" id="Extrait" style="text-align: center;">
    <h1>Extrait</h1>
    Solvants *<br/><input type="text" name="Extrait_Solvants" value="" required><br/><br/>
    Disponibilité *<br/><input type="checkbox" name="Extrait_Disponibilité" value=""><br/><br/>
    <br/>
    Type extraction<br/><input type="text" name="Extrait_Type_extraction" value=""><br/><br/>
    Etat<br/><input type="text" name="Extrait_Etat" value=""><br/><br/>
    Protocole<br/><input type="text" name="Extrait_Protocole" value=""><br/><br/>
    Stockage<br/><input type="text" name="Extrait_Stockage" value=""><br/><br/>
    Observation<br/><input type="text" name="Extrait_Observation" value=""><br/><br/>

    <button type="button" name="button" onclick="hideDiv();showDiv('Purification');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Purification -->
  <div name="divHide" id="Purification" style="text-align: center;">
    <h1>Purification</h1>
    Ajouter <input type="number" id="nbPurification" name="nbPurification" value=0 min=0 max=5 onchange="addFields();"> purification(s)<br/><br/>

    <div id="container"></div>

    <button type="button" name="button" onclick="hideDiv();showDiv('echantillon');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Echantillon -->
  <div name="divHide" id="echantillon" style="text-align: center;">
    <input type="radio" name="echantillon" value="echantillon_nouveau" checked>
    <div style="width: 25%; display: inline-table;">
      <h1>Nouveau échantillon</h1>
      Code *<br/><input class="echantillon_nouveau" type="text" name="echantillon_Code" value="" required><br/><br/>
      Disponibilité *<br/><input class="echantillon_nouveau" type="checkbox" name="echantillon_Disponibilité" value=""><br/><br/>
      Quantité *<br/><input class="echantillon_nouveau" type="text" name="echantillon_Quantité" value="" required><br/><br/>
      Lieu *<br/><input class="echantillon_nouveau" type="text" name="echantillon_Lieu" value="" required><br/><br/>
      Partie d'organisme *<br/>
      <select name="echantillon_Partie" class="echantillon_nouveau" required>
        <option value=""></option>
        <?php
        foreach ($dbh->query("SELECT * FROM partie_organisme ORDER BY par_fr") as $row) {
          echo '<option value="'.$row[0].'">'.$row[2].'</option>';
        }
        ?>
      </select><br/><br/>
      <br/>
      Contact<br/><input class="echantillon_nouveau" type="text" name="echantillon_Contact" value=""><br/><br/>
      DOI<br/><input class="echantillon_nouveau" type="text" name="echantillon_DOI" value=""><br/><br/>
    </div>

    <div style="margin-left: 11.75%;width: 12.5%;display: inline-table;border-left: 6px solid green;height: 430px; margin-top: 20px;">
      &nbsp;
    </div>

    <input type="radio" name="echantillon" value="echantillon_existant">
    <div style="width: 25%; display: inline-table; height: 275px;">
      <h1>Échantillon existant</h1>

      <div style="margin-top: 50%;">
        <label>Code echantillon</label><br/>
        <div class="autocomplete">
          <input class="echantillon_existant" id="myInput" type="text" name="echantillon_Code" disabled>
        </div>
      </div>
    </div>
    <div style="margin: 10px;">
      <button id="btnNext_echantillon" type="button" name="button" onclick="hideDiv();showDiv('Condition');">Suivant</button>
      <input type="submit" style="display: none;" id="btnSumbit_echantillon">
    </div>
  </div>

  <!-- [JM - 05/07/2019] condition -->
  <div name="divHide" id="Condition" style="text-align: center;">
    <h1>Condition</h1>
    Milieu<br/><input class="echantillon_nouveau" type="text" name="Condition_Milieu" value=""><br/><br/>
    Temperature<br/><input class="echantillon_nouveau" type="number" step="any" name="Condition_Temperature" value=""><br/><br/>
    Type de culture<br/><input class="echantillon_nouveau" type="text" name="Condition_Type" value=""><br/><br/>
    Mode opératoire<br/><input class="echantillon_nouveau" type="text" name="Condition_ModeOp" value=""><br/><br/>
    Observation<br/><input class="echantillon_nouveau" type="text" name="Condition_Observation" value=""><br/><br/>
    Fichier<br/><input class="echantillon_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Condition_Fichier[]" multiple><br/><br/>

    <button type="button" name="button" onclick="hideDiv();showDiv('Specimen');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Specimen -->
  <div name="divHide" id="Specimen" style="text-align: center;">
    <input class="echantillon_nouveau" type="radio" name="Specimen" value="specimen_nouveau" checked>
    <div style="width: 25%; display: inline-table;">
      <h1>Nouveau specimen</h1>
      Code *<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Code" value="" required><br/><br/>
      Date *<br/><input class="echantillon_nouveau specimen_nouveau" type="date" name="Specimen_Date" value="" required><br/><br/>
      Lieu *<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Lieu" value="" required><br/><br/>
      <br/>
      GPS<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_GPS" value=""><br/><br/>
      Observation<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Observation" value=""><br/><br/>
      Collection<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collection" value=""><br/><br/>
      Contact<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Contact" value=""><br/><br/>
      Collecteur<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collecteur" value=""><br/><br/>
      Fichier<br/><input class="echantillon_nouveau specimen_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Specimen_Fichier[]" value="" multiple><br/><br/>

    </div>
    <div style="margin-left: 11.75%;width: 12.5%;display: inline-table;border-left: 6px solid green;height: 430px; margin-top: 20px;">
      &nbsp;
    </div>
    <input class="echantillon_nouveau" type="radio" name="Specimen" value="specimen_existant">
    <div style="width: 25%; display: inline-table; height: 275px;">
      <h1>Specimen existant</h1>

      <div style="margin-top: 50%;">
        <label>Code specimen</label><br/>
        <div class="autocomplete">
          <input class="specimen_existant" id="myInput2" type="text" name="Specimen_Code" disabled>
        </div>
      </div>
    </div>
    <div style="margin: 10px;">
      <button id="btnNext_specimen" type="button" name="button" onclick="hideDiv();showDiv('Taxonomie');">Suivant</button>
      <input type="submit" style="display: none;" id="btnSumbit_specimen">
    </div>
  </div>

  <!-- [JM - 05/07/2019] Taxonomie -->
  <div name="divHide" id="Taxonomie" style="text-align: center;">
    <input class="echantillon_nouveau specimen_nouveau" type="radio" name="taxonomie" value="taxonomie_nouveau" checked>
    <div style="width: 25%; display: inline-table;">
      <h1>Nouvelle taxonomie</h1>
      Type *<br/>
      <select class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" name="Taxonomie_Type" required>
        <option value=""></option>
        <?php
        foreach ($dbh->query("SELECT * FROM type_taxonomie ORDER BY typ_tax_type") as $row) {
          echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        ?>
      </select><br/><br/>
      Phylum<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Phylum" value=""><br/><br/>
      classe<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_classe" value=""><br/><br/>
      Ordre<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Ordre" value=""><br/><br/>
      Famille<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Famille" value=""><br/><br/>
      Genre *<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Genre" value="" required><br/><br/>
      Espece *<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Espece" value="" required><br/><br/>
      Sous-espece<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Sous-espece" value=""><br/><br/>
      <br/>
      Variete<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Variete" value=""><br/><br/>
      Protocole<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Protocole" value=""><br/><br/>
      Sequencage<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_Sequencage" value=""><br/><br/>
      Ref book<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="text" name="Taxonomie_RefBook" value=""><br/><br/>
      Fichier<br/><input class="echantillon_nouveau specimen_nouveau taxonomie_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Taxonomie_Fichier[]" value="" multiple><br/><br/>
    </div>
    <div style="margin-left: 11.75%;width: 12.5%;display: inline-table;border-left: 6px solid green;height: 700px; margin-top: 20px;">
      &nbsp;
    </div>
    <input class="echantillon_nouveau specimen_nouveau" type="radio" name="taxonomie" value="taxonomie_existant">
    <div style="width: 25%; display: inline-table; height: 275px;">
      <h1>Taxonomie existante</h1>
      <div style="height: 600px; overflow: auto; width: 100%;">
        <label>taxonomie</label><br/>
        <table class="table-pays">
          <tr class="tr-pays">
            <th class="th-pays" width="5%"></th>
            <th class="th-pays" width="5%">ID</th>
            <th class="th-pays" width="30%">Genre</th>
            <th class="th-pays" width="30%">Espece</th>
            <th class="th-pays" width="30%">Sous-espece</th>
          </tr>
          <?php
          foreach ($dbh->query("SELECT tax_id, tax_genre, tax_espece, tax_sous_espece FROM taxonomie ORDER BY tax_genre, tax_espece, tax_sous_espece") as $row) {
            echo '
            <tr class="tr-pays">
            <td class="td-pays"><input class="echantillon_nouveau specimen_nouveau taxonomie_existant" type="radio" name="taxonomie_choix" value="'.$row[0].'" disabled></td>
            <td class="td-pays">'.$row[0].'</td>
            <td class="td-pays">'.$row[1].'</td>
            <td class="td-pays">'.$row[2].'</td>
            <td class="td-pays">'.$row[3].'</td>
            </tr>
            ';
          }
          ?>
        </table>
      </div>
    </div>
    <div style="margin: 10px;">
      <button type="button" name="button" onclick="hideDiv();showDiv('Expedition');">Suivant</button>
    </div>
  </div>

  <!-- [JM - 05/07/2019] Expedition-->
  <div name="divHide" id="Expedition" style="text-align: center;">
    <input class="echantillon_nouveau specimen_nouveau" type="radio" name="expedition" value="expedition_nouveau" checked>
    <div style="width: 25%; display: inline-table;">
      <h1>Nouvelle expédition</h1>
      Nom<br/><input class="echantillon_nouveau specimen_nouveau expedition_nouveau" type="text" name="Expedition_Nom" value=""><br/><br/>
      Contact<br/><input class="echantillon_nouveau specimen_nouveau expedition_nouveau" type="text" name="Expedition_Contact" value=""><br/><br/>
      Pays *<br/>
      <select class="echantillon_nouveau specimen_nouveau expedition_nouveau" name="Expedition_Pays" required>
        <option value=""></option>
        <?php
        foreach ($dbh->query("SELECT * FROM Pays ORDER BY pay_code_pays") as $row) {
          echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        ?>
      </select><br/><br/>
    </div>
    <div style="margin-left: 11.75%;width: 12.5%;display: inline-table;border-left: 6px solid green;height: 300px; margin-top: 20px;">
      &nbsp;
    </div>
    <input class="echantillon_nouveau specimen_nouveau" type="radio" name="expedition" value="expedition_existant">
    <div style="width: 25%; display: inline-table; height: 275px;">
      <h1>Expédition existante</h1>
      <div style="height: 400px; overflow: auto; width: 100%;">
        <label>Expédition</label><br/>
        <table class="table-pays">
          <tr class="tr-pays">
            <th class="th-pays" width="5%"></th>
            <th class="th-pays" width="5%">ID</th>
            <th class="th-pays" width="30%">Nom</th>
            <th class="th-pays" width="30%">Contact</th>
            <th class="th-pays" width="30%">Code pays</th>
          </tr>
          <?php
          foreach ($dbh->query("SELECT * FROM expedition ORDER BY exp_nom") as $row) {
            echo '
            <tr class="tr-pays">
            <td class="td-pays"><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="expedition_choix" value="'.$row[0].'" disabled></td>
            <td class="td-pays">'.$row[0].'</td>
            <td class="td-pays">'.$row[1].'</td>
            <td class="td-pays">'.$row[2].'</td>
            <td class="td-pays">'.$row[3].'</td>
            </tr>
            ';
          }
          ?>
        </table>
      </div>
    </div>
    <div style="margin: 10px;">
      <input type="hidden" name="send" value="send">
      <input type="submit">
    </div>
  </div>

</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  $dbh->beginTransaction();
  $erreur = "";
  if(isset($_POST['echantillon']) && $_POST['echantillon'] == "echantillon_nouveau"){
    if(isset($_POST['Specimen']) && $_POST['Specimen'] == "specimen_nouveau"){
      if(isset($_POST['expedition']) && $_POST['expedition'] == "expedition_nouveau"){
        $stmt = $dbh->prepare("INSERT INTO expedition (exp_nom, exp_contact, pay_code_pays) VALUES (:exp_nom, :exp_contact, :pay_code_pays)");
        $stmt->bindParam(':exp_nom', $_POST['Expedition_Nom']);
        $stmt->bindParam(':exp_contact', $_POST['Expedition_Contact']);
        $stmt->bindParam(':pay_code_pays', $_POST['Expedition_Pays']);
        $stmt->execute();
        $exp_id = $dbh->lastInsertId();
        if ($stmt->errorInfo()[0] != 00000) {
          $erreur .= "<br/>Erreur lors de l'insertion de l'expedition";
        }
      }
      else {
        $exp_id = $_POST['expedition_choix'];
      }

      if(isset($_POST['taxonomie']) && $_POST['taxonomie'] == "taxonomie_nouveau"){
        $stmt = $dbh->prepare("INSERT INTO taxonomie (tax_phylum, tax_classe, tax_ordre, tax_famille, tax_genre, tax_espece, tax_sous_espece, tax_variete, tax_protocole, tax_sequencage, tax_seq_ref_book, typ_tax_id) VALUES (:tax_phylum, :tax_classe, :tax_ordre, :tax_famille, :tax_genre, :tax_espece, :tax_sous_espece, :tax_variete, :tax_protocole, :tax_sequencage, :tax_seq_ref_book, :typ_tax_id)");
        $stmt->bindParam(':tax_phylum', $_POST['Taxonomie_Phylum']);
        $stmt->bindParam(':tax_classe', $_POST['Taxonomie_classe']);
        $stmt->bindParam(':tax_ordre', $_POST['Taxonomie_Ordre']);
        $stmt->bindParam(':tax_famille', $_POST['Taxonomie_Famille']);
        $stmt->bindParam(':tax_genre', $_POST['Taxonomie_Genre']);
        $stmt->bindParam(':tax_espece', $_POST['Taxonomie_Espece']);
        $stmt->bindParam(':tax_sous_espece', $_POST['Taxonomie_Sous-espece']);
        $stmt->bindParam(':tax_variete', $_POST['Taxonomie_Variete']);
        $stmt->bindParam(':tax_protocole', $_POST['Taxonomie_Protocole']);
        $stmt->bindParam(':tax_sequencage', $_POST['Taxonomie_Sequencage']);
        $stmt->bindParam(':tax_seq_ref_book', $_POST['Taxonomie_RefBook']);
        $stmt->bindParam(':typ_tax_id', $_POST['Taxonomie_Type']);
        $stmt->execute();
        $tax_id = $dbh->lastInsertId();
        if ($stmt->errorInfo()[0] != 00000) {
          $erreur .= "<br/>Erreur lors de l'insertion de la taxonomie";
        }
        echo "<br/>";

        if(isset($_FILES['Taxonomie_Fichier'])){
          foreach ($_FILES['Taxonomie_Fichier']['name'] as $key => $value) {
            if ($_FILES['Taxonomie_Fichier']['size'][$key] != 0) {
              $extension_fichier=strtolower(pathinfo($_FILES['Taxonomie_Fichier']['name'][$key], PATHINFO_EXTENSION));
              $fichier=file_get_contents($_FILES['Taxonomie_Fichier']['tmp_name'][$key]);
              $fichier=Base64_encode($fichier);

              $stmt = $dbh->prepare("INSERT INTO fichier_taxonomie (fic_fichier, fic_type, tax_id) VALUES (:fic_fichier, :fic_type, :tax_id)");
              $stmt->bindParam(':fic_fichier', $fichier);
              $stmt->bindParam(':fic_type', $extension_fichier);
              $stmt->bindParam(':tax_id', $tax_id);
              $stmt->execute();
              if ($stmt->errorInfo()[0] != 00000) {
                $erreur .= "<br/>Erreur lors de l'insertion des fichiers de la taxonomie";
              }
            }
          }
        }
      }
      else {
        $tax_id = $_POST['taxonomie_choix'];
      }

      $stmt = $dbh->prepare("INSERT INTO specimen (spe_code_specimen, spe_date_recolte, spe_lieu_recolte, spe_gps_recolte, spe_observation, spe_collection, spe_contact, spe_collecteur, tax_id, exp_id) VALUES (:spe_code_specimen, :spe_date_recolte, :spe_lieu_recolte, :spe_gps_recolte, :spe_observation, :spe_collection, :spe_contact, :spe_collecteur, :tax_id, :exp_id)");
      $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);
      $stmt->bindParam(':spe_date_recolte', $_POST['Specimen_Date']);
      $stmt->bindParam(':spe_lieu_recolte', $_POST['Specimen_Lieu']);
      $stmt->bindParam(':spe_gps_recolte', $_POST['Specimen_GPS']);
      $stmt->bindParam(':spe_observation', $_POST['Specimen_Observation']);
      $stmt->bindParam(':spe_collection', $_POST['Specimen_Collection']);
      $stmt->bindParam(':spe_contact', $_POST['Specimen_Contact']);
      $stmt->bindParam(':spe_collecteur', $_POST['Specimen_Collecteur']);
      $stmt->bindParam(':tax_id', $tax_id);
      $stmt->bindParam(':exp_id', $exp_id);
      $stmt->execute();
      if ($stmt->errorInfo()[0] != 00000) {
        $erreur .= "<br/>Erreur lors de l'insertion du specimen";
      }

      if(isset($_FILES['Specimen_Fichier'])){
        foreach ($_FILES['Specimen_Fichier']['name'] as $key => $value) {
          if ($_FILES['Specimen_Fichier']['size'][$key] != 0) {
            $extension_fichier=strtolower(pathinfo($_FILES['Specimen_Fichier']['name'][$key], PATHINFO_EXTENSION));
            $fichier=file_get_contents($_FILES['Specimen_Fichier']['tmp_name'][$key]);
            $fichier=Base64_encode($fichier);

            $stmt = $dbh->prepare("INSERT INTO fichier_specimen (fic_fichier, fic_type, spe_code_specimen) VALUES (:fic_fichier, :fic_type, :spe_code_specimen)");
            $stmt->bindParam(':fic_fichier', $fichier);
            $stmt->bindParam(':fic_type', $extension_fichier);
            $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);
            $stmt->execute();
            if ($stmt->errorInfo()[0] != 00000) {
              $erreur .= "<br/>Erreur lors de l'insertion des fichiers du specimen";
            }
          }
        }
      }

    }
    $stmt = $dbh->prepare("INSERT INTO condition (con_milieu, con_temperature, con_type_culture, con_mode_operatoir, con_observation) VALUES (:con_milieu, :con_temperature, :con_type_culture, :con_mode_operatoir, :con_observation)");
    $stmt->bindParam(':con_milieu', $_POST['Condition_Milieu']);
    if($_POST['Condition_Temperature'] == "")
      $_POST['Condition_Temperature'] = -999999;
    $stmt->bindParam(':con_temperature', $_POST['Condition_Temperature']);
    $stmt->bindParam(':con_type_culture', $_POST['Condition_Type']);
    $stmt->bindParam(':con_mode_operatoir', $_POST['Condition_ModeOp']);
    $stmt->bindParam(':con_observation', $_POST['Condition_Observation']);
    $stmt->execute();
    $con_id = $dbh->lastInsertId();
    if ($stmt->errorInfo()[0] != 00000) {
      $erreur .= "<br/>Erreur lors de l'insertion de la condition";
    }

    if(isset($_FILES['Condition_Fichier'])){
      foreach ($_FILES['Condition_Fichier']['name'] as $key => $value) {
        if ($_FILES['Condition_Fichier']['size'][$key] != 0) {
          $extension_fichier=strtolower(pathinfo($_FILES['Condition_Fichier']['name'][$key], PATHINFO_EXTENSION));
          $fichier=file_get_contents($_FILES['Condition_Fichier']['tmp_name'][$key]);
          $fichier=Base64_encode($fichier);

          $stmt = $dbh->prepare("INSERT INTO fichier_conditions (fic_fichier, fic_type, con_id) VALUES (:fic_fichier, :fic_type, :con_id)");
          $stmt->bindParam(':fic_fichier', $fichier);
          $stmt->bindParam(':fic_type', $extension_fichier);
          $stmt->bindParam(':con_id', $con_id);
          $stmt->execute();
          if ($stmt->errorInfo()[0] != 00000) {
            $erreur .= "<br/>Erreur lors de l'insertion des fichiers de la condition";
          }
        }
      }
    }

    $stmt = $dbh->prepare("INSERT INTO echantillon (ech_code_echantillon, ech_contact, ech_publication_doi, ech_stock_disponibilite, ech_stock_quantite, ech_lieu_stockage, par_id, spe_code_specimen, con_id) VALUES (:ech_code_echantillon, :ech_contact, :ech_publication_doi, :ech_stock_disponibilite, :ech_stock_quantite, :ech_lieu_stockage, :par_id, :spe_code_specimen, :con_id)");
    $stmt->bindParam(':ech_code_echantillon', $_POST['echantillon_Code']);
    $stmt->bindParam(':ech_contact', $_POST['echantillon_Contact']);
    $stmt->bindParam(':ech_publication_doi', $_POST['echantillon_DOI']);
    if (isset($_POST['echantillon_Disponibilité'])) $_POST['echantillon_Disponibilité'] = "TRUE"; else $_POST['echantillon_Disponibilité'] = "FALSE";
    $stmt->bindParam(':ech_stock_disponibilite', $_POST['echantillon_Disponibilité']);
    $stmt->bindParam(':ech_stock_quantite', $_POST['echantillon_Quantité']);
    $stmt->bindParam(':ech_lieu_stockage', $_POST['echantillon_Lieu']);
    $stmt->bindParam(':par_id', $_POST['echantillon_Partie']);
    $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);
    $stmt->bindParam(':con_id', $con_id);
    $stmt->execute();

    if ($stmt->errorInfo()[0] != 00000) {
      $erreur .= "<br/>Erreur lors de l'insertion de l'echantillon";
    }

  }

  $stmt = $dbh->prepare("INSERT INTO extraits (ext_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_id_chimiste, ech_code_echantillon) VALUES (:ext_solvant, :ext_type_extraction, :ext_etat, :ext_disponibilite, :ext_protocole, :ext_stockage, :ext_observations, :chi_id_chimiste, :ech_code_echantillon)");
  $stmt->bindParam(':ext_solvant', $_POST['Extrait_Solvants']);
  $stmt->bindParam(':ext_type_extraction', $_POST['Extrait_Type_extraction']);
  $stmt->bindParam(':ext_etat', $_POST['Extrait_Etat']);
  if (isset($_POST['Extrait_Disponibilité'])) $_POST['Extrait_Disponibilité'] = "TRUE"; else $_POST['Extrait_Disponibilité'] = "FALSE";
  $stmt->bindParam(':ext_disponibilite', $_POST['Extrait_Disponibilité']);
  $stmt->bindParam(':ext_protocole', $_POST['Extrait_Protocole']);
  $stmt->bindParam(':ext_stockage', $_POST['Extrait_Stockage']);
  $stmt->bindParam(':ext_observations', $_POST['Extrait_Observation']);
  $idchim = "1";
  $stmt->bindParam(':chi_id_chimiste', $idchim);//TODO chi_id_chimiste
  $stmt->bindParam(':ech_code_echantillon', $_POST['echantillon_Code']);
  $stmt->execute();
  $ext_id = $dbh->lastInsertId();
  if ($stmt->errorInfo()[0] != 00000) {
    $erreur .= "<br/>Erreur lors de l'insertion de l'extrait";
  }

  for ($i=0; $i < $_POST['nbPurification']; $i++) {
    $stmt = $dbh->prepare("INSERT INTO purification (pur_purification, pur_ref_book, ext_id) VALUES (:pur_purification, :pur_ref_book, :ext_id)");
    $stmt->bindParam(':pur_purification', $_POST['Purification_Purification'.($i+1)]);
    $stmt->bindParam(':pur_ref_book', $_POST['Purification_RefBook'.($i+1)]);
    $stmt->bindParam(':ext_id', $ext_id);
    $stmt->execute();
    $pur_id = $dbh->lastInsertId();
    if ($stmt->errorInfo()[0] != 00000) {
      $erreur .= "<br/>Erreur lors de l'insertion de la purification ".$i;
    }

    if(isset($_FILES['Purification_Fichier'.($i+1)])){
      foreach ($_FILES['Purification_Fichier'.($i+1)]['name'] as $key => $value) {
        if ($_FILES['Purification_Fichier'.($i+1)]['size'][$key] != 0) {
          $extension_fichier=strtolower(pathinfo($_FILES['Purification_Fichier'.($i+1)]['name'][$key], PATHINFO_EXTENSION));
          $fichier=file_get_contents($_FILES['Purification_Fichier'.($i+1)]['tmp_name'][$key]);
          $fichier=Base64_encode($fichier);

          $stmt = $dbh->prepare("INSERT INTO fichier_purification (fic_fichier, fic_type, pur_id) VALUES (:fic_fichier, :fic_type, :pur_id)");
          $stmt->bindParam(':fic_fichier', $fichier);
          $stmt->bindParam(':fic_type', $extension_fichier);
          $stmt->bindParam(':pur_id', $pur_id);
          $stmt->execute();
          if ($stmt->errorInfo()[0] != 00000) {
            $erreur .= "<br/>Erreur lors de l'insertion des fichier de la purification ".$i;
          }
        }
      }
    }
  }
  // [JM - 05/07/2019] si il y a des erreur, on les affiche et annule l'insertion
  if ($erreur != "") {
    echo "<center><h3>".$erreur."</h3></center>";
    $dbh->rollBack();
  }
  // [JM - 05/07/2019] sinon, on confirme l'insertion
  else {
    echo "<center><h3>Données enregistrées</h3></center>";
    $dbh->commit();
  }
}
?>
<script type="text/javascript">
/* [JM - 05/07/2019] cache toute les partie du formulaire */
function hideDiv(){
  $("Div[name='divHide']").css('display', 'none');
  $("a#noTextDecoration").css('text-decoration', 'none');
};

/* [JM - 05/07/2019] affiche toute les partie du formulaire */
function ShowAllDiv(){
  $("Div[name='divHide']").css('display', '');
};

/* [JM - 05/07/2019] affiche la partie sélectionnée */
function showDiv(id){
  $("Div#"+id).css('display', '');
  $("a[name='"+id+"']").css('text-decoration', 'underline');
};

$(document).ready(function(){
  /* [JM - 05/07/2019] savegarde de leur propriété initiate */
  var onClick_Condition = document.getElementsByName("Condition")[0].onclick;
  var onClick_Specimen = document.getElementsByName("Specimen")[0].onclick;
  var onClick_Taxonomie = document.getElementsByName("Taxonomie")[0].onclick;
  var onClick_Expedition = document.getElementsByName("Expedition")[0].onclick;

  /* [JM - 05/07/2019] Selon le choix [nouveau ou existant], active ou déactive la suite du formulaire */
  $('input[type=radio]').click(function(){
    if(this.value == 'echantillon_nouveau'){
      var all = document.getElementsByClassName("echantillon_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }

      var all = document.getElementsByClassName("echantillon_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      $("a[name='Condition']").css('text-decoration', 'none'); $("a[name='Condition']").css('color', 'black'); document.getElementsByName("Condition")[0].onclick = onClick_Condition;
      $("a[name='Specimen']").css('text-decoration', 'none'); $("a[name='Specimen']").css('color', 'black'); document.getElementsByName("Specimen")[0].onclick = onClick_Specimen;
      $("a[name='Taxonomie']").css('text-decoration', 'none'); $("a[name='Taxonomie']").css('color', 'black'); document.getElementsByName("Taxonomie")[0].onclick = onClick_Taxonomie;
      $("a[name='Expedition']").css('text-decoration', 'none'); $("a[name='Expedition']").css('color', 'black'); document.getElementsByName("Expedition")[0].onclick = onClick_Expedition;
      $("button#btnNext_echantillon").css('display', '');
      $("input#btnSumbit_echantillon").css('display', 'none');
    }
    else if(this.value == 'echantillon_existant'){
      var all = document.getElementsByClassName("echantillon_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      var all = document.getElementsByClassName("echantillon_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }
      $("a[name='Condition']").css('text-decoration', 'line-through'); $("a[name='Condition']").css('color', 'lightgray'); document.getElementsByName("Condition")[0].onclick = "";
      $("a[name='Specimen']").css('text-decoration', 'line-through'); $("a[name='Specimen']").css('color', 'lightgray'); document.getElementsByName("Specimen")[0].onclick = "";
      $("a[name='Taxonomie']").css('text-decoration', 'line-through'); $("a[name='Taxonomie']").css('color', 'lightgray'); document.getElementsByName("Taxonomie")[0].onclick = "";
      $("a[name='Expedition']").css('text-decoration', 'line-through'); $("a[name='Expedition']").css('color', 'lightgray'); document.getElementsByName("Expedition")[0].onclick = "";
      $("button#btnNext_echantillon").css('display', 'none');
      $("input#btnSumbit_echantillon").css('display', '');

    }

    if(this.value == 'specimen_nouveau'){
      var all = document.getElementsByClassName("specimen_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }

      var all = document.getElementsByClassName("specimen_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      $("a[name='Taxonomie']").css('text-decoration', 'none'); $("a[name='Taxonomie']").css('color', 'black'); document.getElementsByName("Taxonomie")[0].onclick = onClick_Taxonomie;
      $("a[name='Expedition']").css('text-decoration', 'none'); $("a[name='Expedition']").css('color', 'black'); document.getElementsByName("Expedition")[0].onclick = onClick_Expedition;
      $("button#btnNext_specimen").css('display', '');
      $("input#btnSumbit_specimen").css('display', 'none');
    }
    else if(this.value == 'specimen_existant'){
      var all = document.getElementsByClassName("specimen_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      var all = document.getElementsByClassName("specimen_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }

      $("a[name='Taxonomie']").css('text-decoration', 'line-through'); $("a[name='Taxonomie']").css('color', 'lightgray'); document.getElementsByName("Taxonomie")[0].onclick = "";
      $("a[name='Expedition']").css('text-decoration', 'line-through'); $("a[name='Expedition']").css('color', 'lightgray'); document.getElementsByName("Expedition")[0].onclick = "";
      $("button#btnNext_specimen").css('display', 'none');
      $("input#btnSumbit_specimen").css('display', '');
    }

    if(this.value == 'taxonomie_nouveau'){
      var all = document.getElementsByClassName("taxonomie_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }

      var all = document.getElementsByClassName("taxonomie_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }
    }
    else if(this.value == 'taxonomie_existant'){
      var all = document.getElementsByClassName("taxonomie_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      var all = document.getElementsByClassName("taxonomie_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }
    }

    if(this.value == 'expedition_nouveau'){
      var all = document.getElementsByClassName("expedition_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }

      var all = document.getElementsByClassName("expedition_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }
    }
    else if(this.value == 'expedition_existant'){
      var all = document.getElementsByClassName("expedition_nouveau");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = true;
      }

      var all = document.getElementsByClassName("expedition_existant");
      for (var i = 0; i < all.length; i++) {
        all[i].disabled = false;
      }
    }

  });
});

/* [JM - 05/07/2019] Ajoute des champs correspondant au nombre de purification choisie */
function addFields(){
  var number = document.getElementById("nbPurification").value;
  var container = document.getElementById("container");
  while (container.hasChildNodes()) {
    container.removeChild(container.lastChild);
  }
  for (i=0;i<number;i++){
    container.appendChild(document.createTextNode("Purification " + (i+1) + " *"));
    container.appendChild(document.createElement("br"));
    var input = document.createElement("input");
    input.name = "Purification_Purification" + (i+1);
    input.type = "text";
    input.setAttribute("required","required");
    container.appendChild(input);
    container.appendChild(document.createElement("br"));
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("Ref book " + (i+1)));
    container.appendChild(document.createElement("br"));
    var input = document.createElement("input");
    input.name = "Purification_RefBook" + (i+1);
    input.type = "text";
    container.appendChild(input);
    container.appendChild(document.createElement("br"));
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("Fichier " + (i+1)));
    container.appendChild(document.createElement("br"));
    var input = document.createElement("input");
    input.name = "Purification_Fichier" + (i+1) + "[]";
    input.type = "file";
    input.setAttribute("multiple","multiple");
    input.setAttribute("accept","image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt");
    container.appendChild(input);
    container.appendChild(document.createElement("br"));
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createElement("hr"));
  }
};

/* [JM - 05/07/2019] initialise par défaut sur la partie Extrait*/
hideDiv();showDiv("Extrait");

</script>

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
var id_specimen = <?php echo $var_id_specimen;?>;


/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput"), id_echantillon);
autocomplete(document.getElementById("myInput2"), id_specimen);
</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
