<script src="./js/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/datatables.min.css"/>
<script type="text/javascript" src="./presentation/DataTables/datatables.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/RowReorder-1.2.4/css/rowReorder.dataTables.css"/>
<script type="text/javascript" src="./presentation/DataTables/RowReorder-1.2.4/js/dataTables.rowReorder.js"></script>
<link rel="stylesheet" type="text/css" href="./presentation/DataTables/Select-1.3.0/css/select.dataTables.css"/>
<script type="text/javascript" src="./presentation/DataTables/Select-1.3.0/js/dataTables.select.js"></script>

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
print"<div id=\"dhtmltooltip\"></div>
<script language=\"javascript\" src=\"ttip.js\"></script>";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
/*
print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Extrait.php\">Extrait</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Condition.php\">Condition</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";
*/
?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">
  <!-- [JM - 05/07/2019] Menu de navigation -->
  <h2 style="text-align: center;">
    <a id="noTextDecoration" name="Specimen" onclick="hideDiv();showDiv('Specimen');">Etape 1</a> -
    <a id="noTextDecoration" name="Autorisation" onclick="hideDiv();showDiv('Autorisation');">Etape 2</a> -
    <a id="noTextDecoration" name="Taxonomie" onclick="hideDiv();showDiv('Taxonomie');">Etape 3</a> -
    <a id="noTextDecoration" name="Expedition" onclick="hideDiv();showDiv('Expedition');">Etape 4</a>
  </h2>

  <!-- [JM - 05/07/2019] Specimen -->
  <div name="divHide" id="Specimen" style="text-align: center;">
      <h1>Specimen</h1>
      Code *<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Code" value="" required><br/><br/>
      Date *<br/><input class="echantillon_nouveau specimen_nouveau" type="date" name="Specimen_Date" value="" required><br/><br/>
      Lieu de recolte *<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Lieu" value="" required><br/><br/>
      <br/>
      GPS<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_GPS" value=""><br/><br/>
      Observation<br/><textarea rows="5" cols="50" class="echantillon_nouveau specimen_nouveau" name="Specimen_Observation"></textarea><br/><br/>
      Collection<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collection" value=""><br/><br/>
      Contact<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Contact" value=""><br/><br/>
      Collecteur<br/><input class="echantillon_nouveau specimen_nouveau" type="text" name="Specimen_Collecteur" value=""><br/><br/>
      Fichier<br/><input class="echantillon_nouveau specimen_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Specimen_Fichier[]" value="" multiple><br/><br/>

      <button type="button" name="button" onclick="hideDiv();showDiv('Autorisation');">Suivant</button>

  </div>

  <!-- [JM - 05/07/2019] Autorisation -->
  <div name="divHide" id="Autorisation" style="text-align: center;">
      <h1>Autorisation</h1>
      Ne rien cocher si autorisation non nécessaire !
      <br/><br/>
        <table id="tab_Autorisation" class="display">
          <thead>
            <tr>
              <th></th>
              <th>Numéro d'autorisation</th>
              <th>Type d'autorisation</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach ($dbh->query("SELECT * FROM Autorisation") as $row) {
            echo '
            <tr>
            <td><input type="checkbox" name="Autorisation_choix[]" value="'.urldecode($row[0]).'"></td>
            <td>'.urldecode($row[0]).'</td>
            <td>'.urldecode($row[1]).'</td>
            </tr>
            ';
          }
          ?>
          </tbody>
        </table>
        <br/>
        <button type="button" name="button" onclick="hideDiv();showDiv('Specimen');">Précédent</button>
        <button type="button" name="button" onclick="hideDiv();showDiv('Taxonomie');">Suivant</button>

  </div>

  <!-- [JM - 05/07/2019] Taxonomie -->
  <div name="divHide" id="Taxonomie" style="text-align: center;">
      <h1>Taxonomie</h1>
        <table id="tab_Taxonomie" class="display">
          <thead>
            <tr>
              <th></th>
              <th>ID</th>
              <th>Type</th>
              <th>Genre</th>
              <th>Espece</th>
              <th>Sous-espece</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach ($dbh->query("SELECT tax_id, typ_tax_type, tax_genre, tax_espece, tax_sous_espece FROM taxonomie INNER JOIN type_taxonomie ON taxonomie.typ_tax_id = type_taxonomie.typ_tax_id ORDER BY typ_tax_type, tax_genre, tax_espece, tax_sous_espece") as $row) {
            echo '
            <tr>
            <td><input class="echantillon_nouveau specimen_nouveau taxonomie_existant" type="radio" name="taxonomie_choix" value="'.urldecode($row[0]).'"></td>
            <td>'.urldecode($row[0]).'</td>
            <td>'.urldecode($row[1]).'</td>
            <td>'.urldecode($row[2]).'</td>
            <td>'.urldecode($row[3]).'</td>
            <td>'.urldecode($row[4]).'</td>
            <td><a href="recherche_Taxonomie.php?taxonomie='.urldecode($row[0]).'" target="_blank">Voir les détails</a></td>
            </tr>
            ';
          }
          ?>
          </tbody>
        </table>
        <br/>
        <button type="button" name="button" onclick="hideDiv();showDiv('Autorisation');">Précédent</button>
        <button type="button" name="button" onclick="hideDiv();showDiv('Expedition');">Suivant</button>

  </div>

  <!-- [JM - 05/07/2019] Expedition-->
  <div name="divHide" id="Expedition" style="text-align: center;">
      <h1>Mission de récolte</h1>
        <table id="tab_Expedition" class="display">
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
          foreach ($dbh->query("SELECT * FROM expedition ORDER BY exp_nom") as $row) {
            echo '
            <tr>
            <td><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="expedition_choix" value="'.urldecode($row[0]).'"></td>
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
        <button type="button" name="button" onclick="hideDiv();showDiv('Taxonomie');">Précédent</button>
        <br/><br/>
      <input type="hidden" name="send" value="send">
      <input type="submit">
  </div>
</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  $dbh->beginTransaction();
  $erreur = "";

      $stmt = $dbh->prepare("INSERT INTO specimen (spe_code_specimen, spe_date_recolte, spe_lieu_recolte, spe_gps_recolte, spe_observation, spe_collection, spe_contact, spe_collecteur, tax_id, exp_id) VALUES (:spe_code_specimen, :spe_date_recolte, :spe_lieu_recolte, :spe_gps_recolte, :spe_observation, :spe_collection, :spe_contact, :spe_collecteur, :tax_id, :exp_id)");
      $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);
      $stmt->bindParam(':spe_date_recolte', $_POST['Specimen_Date']);
      $stmt->bindParam(':spe_lieu_recolte', $_POST['Specimen_Lieu']);
      $stmt->bindParam(':spe_gps_recolte', $_POST['Specimen_GPS']);
      $stmt->bindParam(':spe_observation', $_POST['Specimen_Observation']);
      $stmt->bindParam(':spe_collection', $_POST['Specimen_Collection']);
      $stmt->bindParam(':spe_contact', $_POST['Specimen_Contact']);
      $stmt->bindParam(':spe_collecteur', $_POST['Specimen_Collecteur']);
      $stmt->bindParam(':tax_id', $_POST['taxonomie_choix']);
      $stmt->bindParam(':exp_id', $_POST['expedition_choix']);
      $stmt->execute();
      if ($stmt->errorInfo()[0] != 00000) {
        $erreur .= "<br/>Erreur lors de l'insertion du specimen";
        if ($stmt->errorInfo()[0] == 23505) {
          $erreur .= ", car le code specimen ".$_POST['Specimen_Code']." existe déjà.";
        }
      }

      foreach ($_POST['Autorisation_choix'] as $key => $value) {
        $stmt = $dbh->prepare("INSERT INTO autorisation_specimen (aut_numero_autorisation, spe_code_specimen) VALUES (:aut_numero_autorisation, :spe_code_specimen)");
        $stmt->bindParam(':aut_numero_autorisation', $value);
        $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);
        $stmt->execute();
        if ($stmt->errorInfo()[0] != 00000) {
          $erreur .= "<br/>Erreur lors de l'insertion de l'autorisation";
        }
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

  // [JM - 05/07/2019] si il y a des erreur, on les affiche et annule l'insertion
  if ($erreur != "") {
    echo "<center><h3>".$erreur."</h3></center>";
    $dbh->rollBack();
  }
  // [JM - 05/07/2019] sinon, on confirme l'insertion
  else {
    echo "<center><h3>Données enregistrées</h3></center>";
    $dbh->commit();
    echo "<script>window.close();</script>";
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

/* [JM - 05/07/2019] initialise par défaut sur la partie Extrait*/
hideDiv();showDiv("Specimen");

$(document).ready(function() {
    $('#tab_Autorisation').DataTable({select: {style: 'api'}});
    $('#tab_Taxonomie').DataTable({select: {style: 'single'}});
    $('#tab_Expedition').DataTable({select: {style: 'single'}});

    $('#tab_Taxonomie tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });

    $('#tab_Expedition tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
