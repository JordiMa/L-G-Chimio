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
  justify-content: center;
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
  width: 35%;
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
  width: 50%;
  display: inline-block;
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
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Specimen.php\">Specimen</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"modification_Taxonomie.php\">Taxonomie</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Expedition.php\">Mission de récolte</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_autorisation.php\">Autorisation</a></td>
    </tr>
    </table><br/>";

if (isset($_GET['taxonomie'])) {
  $_POST['taxonomie'] = $_GET['taxonomie'];
}
// [JM - 05/07/2019] gestion des modification
  if(isset($_POST["type"])){
    switch ($_POST["type"]) {
      case 'taxonomie':
        $stmt = $dbh->prepare("UPDATE taxonomie SET tax_phylum = :tax_phylum, tax_classe = :tax_classe, tax_ordre = :tax_ordre, tax_famille = :tax_famille, tax_genre = :tax_genre, tax_espece = :tax_espece, tax_sous_espece = :tax_sous_espece, tax_variete = :tax_variete, tax_protocole = :tax_protocole, tax_sequencage = :tax_sequencage, tax_seq_ref_book = :tax_seq_ref_book, typ_tax_id = :typ_tax_id WHERE tax_id = :tax_id");
        $stmt->bindParam(':tax_phylum', $_POST['Phylum']);
        $stmt->bindParam(':tax_classe', $_POST['Classe']);
        $stmt->bindParam(':tax_ordre', $_POST['Ordre']);
        $stmt->bindParam(':tax_famille', $_POST['Famille']);
        $stmt->bindParam(':tax_genre', $_POST['Genre']);
        $stmt->bindParam(':tax_espece', $_POST['Espece']);
        $stmt->bindParam(':tax_sous_espece', $_POST['Sous-espece']);
        $stmt->bindParam(':tax_variete', $_POST['Variete']);
        $stmt->bindParam(':tax_protocole', $_POST['Protocole']);
        $stmt->bindParam(':tax_sequencage', $_POST['Sequence']);
        $stmt->bindParam(':tax_seq_ref_book', $_POST['ref_book']);
        $stmt->bindParam(':typ_tax_id', $_POST['Taxonomie_Type']);
        $stmt->bindParam(':tax_id', $_POST['id']);
        $stmt->execute();

        if(isset($_FILES['fichier'])){
          foreach ($_FILES['fichier']['name'] as $key => $value) {
            if ($_FILES['fichier']['size'][$key] != 0) {
              $extension_fichier=strtolower(pathinfo($_FILES['fichier']['name'][$key], PATHINFO_EXTENSION));
              $fichier=file_get_contents($_FILES['fichier']['tmp_name'][$key]);
              $fichier=Base64_encode($fichier);

              $stmt = $dbh->prepare("INSERT INTO fichier_taxonomie (fic_fichier, fic_type, tax_id) VALUES (:fic_fichier, :fic_type, :tax_id);");
              $stmt->bindParam(':fic_fichier', $fichier);
              $stmt->bindParam(':fic_type', $extension_fichier);
              $stmt->bindParam(':tax_id', $_POST['id']);
              $stmt->execute();
            }
          }
        }
        break;

      case 'fic_tax_suppr':
        $stmt = $dbh->prepare("DELETE FROM fichier_taxonomie WHERE fic_id = :fic_id");
        $stmt->bindParam(':fic_id', $_POST['id']);
        $stmt->execute();
        break;

      default:
        // code...
        break;
    }
    echo '<script>window.location.replace("modification_Taxonomie.php?taxonomie='.$_POST['taxonomie'].'");</script>';
  }
  ?>

  <h3 align="center">Modification de taxonomie</h3>
  <hr>

  <form id="myForm" action="" method="POST" enctype="multipart/form-data" style=" text-align: center;">
    <!-- [JM - 07/2019] Recherche de la taxonomie -->
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
        <td><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="taxonomie" value="'.urldecode($row[0]).'"';if (isset($_POST['taxonomie']) && $row[0] == $_POST['taxonomie']) echo "checked"; ;echo '></td>
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
    <input type="submit" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
    <br><br>
  </form>
  <hr>
  <?php

  if(isset($_POST['taxonomie'])){
    $sql_taxonomie =
    "SELECT * FROM taxonomie INNER JOIN type_taxonomie on type_taxonomie.typ_tax_id = taxonomie.typ_tax_id WHERE tax_id = '".$_POST['taxonomie']."';";

    $result_taxonomie = $dbh->query($sql_taxonomie);
    $row_taxonomie = $result_taxonomie->fetch(PDO::FETCH_NUM);
    // [JM - 05/07/2019] affichage des information liée à l'echantillon
    if (!empty($row_taxonomie[0])) {
      // TODO:
      echo "<div style='text-align: center;'>";
      echo "<div class='hr click_taxonomie'>Taxonomie</div>";
      echo "<a class='btnFic' style=\"float: right;\" href=\"#modif_taxonomie\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/><strong>ID taxonomie : </strong>" .$row_taxonomie[0];
      echo "<br/>";
      echo "<br/><strong>Phylum : </strong>" .$row_taxonomie[1];
      echo "<br/><strong>Classe : </strong>" .$row_taxonomie[2];
      echo "<br/><strong>Ordre : </strong>" .$row_taxonomie[3];
      echo "<br/><strong>Famille : </strong>" .$row_taxonomie[4];
      echo "<br/><strong>Genre : </strong>" .$row_taxonomie[5];
      echo "<br/><strong>Espece : </strong>" .$row_taxonomie[6];
      echo "<br/><strong>Sous-espece : </strong>" .$row_taxonomie[7];
      echo "<br/><strong>Varieté : </strong>" .$row_taxonomie[8];
      echo "<br/>";
      echo "<br/><strong>Protocole : </strong>" .$row_taxonomie[9];
      echo "<br/><strong>Sequence : </strong>" .$row_taxonomie[10];
      echo "<br/><strong>Ref cahier de labo : </strong>" .$row_taxonomie[11];
      echo "<br/>";
      echo "<br/><strong>Type : </strong>" .$row_taxonomie[14];
      echo "<br/><br/><a class='btnFic' href=\"#fic_tax\">Voir les fichiers</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/>";
      echo "</div>";
      echo "</div>";

      // [JM - 05/07/2019] Creation de popup pour afficher la liste des fichiers

      //condition
      echo '
      <div id="fic_tax" class="overlay">
      <div class="popup">
      <h2>Fichiers conditions '.$row_taxonomie[0].'</h2>
      <a class="close" href="#return">&times;</a>
      <div class="content">
      ';
      $liste_con = "";
      foreach ($dbh->query("SELECT * FROM fichier_taxonomie WHERE tax_id = '".$row_taxonomie[0]."'") as $key => $value1) {
        echo '
        <form id="btnSupprForm'.$value1[0].'" method="POST" >
          <input type="hidden" name="taxonomie" value="'.$row_taxonomie[0].'"/>
          <input type="hidden" name="type" value="fic_tax_suppr" />
          <input type="hidden" name="id" value="'.$value1[0].'"/>
        </form>
        ';
        $liste_con .='<li><a href="telecharge.php?id='.$value1[0].'&rankExtra=taxonomie" target="_blank"> Fichier '.$value1[0].' : '.$value1[2].'</a> | <a href="#" onclick="if (confirm(\'Etes-vous sûr ?\')){document.getElementById(\'btnSupprForm'.$value1[0].'\').submit();}">Supprimer</a></li>';

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

      echo '
      <div id="modif_taxonomie" class="overlay">
      <div id="popup_select" class="popup">
      <h2>Taxonomie</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST" enctype="multipart/form-data" style="text-align: center;">
        <input type="hidden" name="taxonomie" value="'.$row_taxonomie[0].'">
        <input type="hidden" name="type" value="taxonomie">
        <input type="hidden" name="id" value="'.$row_taxonomie[0].'">
        <br/><strong>ID taxonomie : </strong>'.$row_taxonomie[0].'
        <br/>
        <div class="container">
          <div class="content"><br/><strong>Phylum</strong><br/><input type="text" name="Phylum" value="'.$row_taxonomie[1].'"><br/></div>
          <div class="content"><br/><strong>Classe</strong><br/><input type="text" name="Classe" value="'.$row_taxonomie[2].'"><br/></div>
          <div class="content"><br/><strong>Ordre</strong><br/><input type="text" name="Ordre" value="'.$row_taxonomie[3].'"><br/></div>
          <div class="content"><br/><strong>Famille</strong><br/><input type="text" name="Famille" value="'.$row_taxonomie[4].'"><br/></div>
          <div class="content"><br/><strong>Genre</strong><br/><input type="text" name="Genre" value="'.$row_taxonomie[5].'"><br/></div>
          <div class="content"><br/><strong>Espece</strong><br/><input type="text" name="Espece" value="'.$row_taxonomie[6].'"><br/></div>
          <div class="content"><br/><strong>Sous-espece</strong><br/><input type="text" name="Sous-espece" value="'.$row_taxonomie[7].'"><br/></div>
          <div class="content"><br/><strong>Varieté</strong><br/><input type="text" name="Variete" value="'.$row_taxonomie[8].'"><br/></div>
          <div class="content"><br/><strong>Protocole</strong><br/><input type="text" name="Protocole" value="'.$row_taxonomie[9].'"><br/></div>
          <div class="content"><br/><strong>Sequence</strong><br/><input type="text" name="Sequence" value="'.$row_taxonomie[10].'"><br/></div>
          <div class="content"><br/><strong>ref cahier de labo : </strong><br/><input type="text" name="ref_book" value="'.$row_taxonomie[11].'"><br/></div>
          <div class="content"><br/><strong>Type</strong><br/>

          <select name="Taxonomie_Type" required>
            <option value=""></option>
            ';
            foreach ($dbh->query("SELECT * FROM type_taxonomie ORDER BY typ_tax_type") as $row) {
              echo '<option value="'.urldecode($row[0]).'"'; if ($row_taxonomie[12] == $row[0]) {echo "selected";} echo '>'.urldecode($row[1]).'</option>';
            }
            echo '
          </select><br/></div>
        <br/>
        </div>
        <br/>
        Fichier<br/><input type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="fichier[]" multiple><br/><br/>
        <br/>
      <center><input type="submit"></center>
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
    $('#tab_taxonomie').DataTable({select: {style: 'single'}});

    $('#tab_taxonomie tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>
