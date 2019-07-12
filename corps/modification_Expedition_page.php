<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Extrait.php\">Échantillon</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Condition.php\">Condition</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Specimen.php\">Specimen</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Taxonomie.php\">Taxonomie</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"modification_Expedition.php\">Expedition</a></td>
    </tr>
    </table><br/>";

if (isset($_GET['expedition'])) {
  $_POST['expedition'] = $_GET['expedition'];
}
// [JM - 05/07/2019] gestion des modification
  if(isset($_POST["type"])){
    switch ($_POST["type"]) {
      case 'expedition':
        $stmt = $dbh->prepare("UPDATE expedition SET exp_nom = :exp_nom, exp_contact = :exp_contact, pay_code_pays = :pay_code_pays WHERE exp_id = :exp_id");
        $stmt->bindParam(':exp_nom', $_POST['Nom']);
        $stmt->bindParam(':exp_contact', $_POST['Contact']);
        $stmt->bindParam(':pay_code_pays', $_POST['Pays']);
        $stmt->bindParam(':exp_id', $_POST['id']);
        $stmt->execute();
        break;

      default:
        // code...
        break;
    }

  }
  ?>

  <h3 align="center">Modification d'expedition</h3>
  <hr>

  <form id="myForm" action="" method="POST" enctype="multipart/form-data" style=" text-align: center;">
    <!-- [JM - 01/02/2019] Recherche du produit -->
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
        <td><input type="radio" name="expedition" value="'.urldecode($row[0]).'"';if (isset($_POST['expedition']) && $row[0] == $_POST['expedition']) echo "checked"; ;echo '></td>
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
    <input type="submit" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
    <br><br>
  </form>
  <hr>
  <?php

  if(isset($_POST['expedition'])){
    $sql_expedition =
    "SELECT * FROM expedition WHERE exp_id = '".$_POST['expedition']."';";

    $result_expedition = $dbh->query($sql_expedition);
    $row_expedition = $result_expedition->fetch(PDO::FETCH_NUM);
    // [JM - 05/07/2019] affichage des information liée à l'echantillon
    if (!empty($row_expedition[0])) {

      echo "<div style='text-align: center;'>";
      echo "<div class='hr click_expedition'>Expedition</div>";
      echo "<a class='btnFic' style=\"float: right;\" href=\"#modif_expedition\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/><strong>ID expedition : </strong>" .$row_expedition[0];
      echo "<br/>";
      echo "<br/><strong>Nom : </strong>" .$row_expedition[1];
      echo "<br/><strong>Contact : </strong>" .$row_expedition[2];
      echo "<br/><strong>Code pays : </strong>" .$row_expedition[3];
      echo "<br/>";
      echo "<br/>";
      echo "</div>";
      echo "</div>";

      // [JM - 05/07/2019] Creation de popup pour afficher la liste des fichiers

      //expedition
      echo '
      <div id="modif_expedition" class="overlay">
      <div id="popup_modif" class="popup">
      <h2>Expedition</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="expedition" value="'.$row_expedition[0].'">
        <input type="hidden" name="type" value="expedition">
        <input type="hidden" name="id" value="'.$row_expedition[0].'">
        <br/><strong>ID expedition : </strong>'.$row_expedition[0].'
        <br/><br/>
        Nom<br/><input type="text" name="Nom" value="'.$row_expedition[1].'"><br/><br/>
        Contact<br/><input type="text" name="Contact" value="'.$row_expedition[2].'">°C<br/><br/>
        pays<br/>
        <select name="Pays" required>
          <option value=""></option>';

          foreach ($dbh->query("SELECT * FROM Pays ORDER BY pay_code_pays") as $row) {
            echo '<option value="'.urldecode($row[0]).'"'; if ($row_expedition[3] == $row[0]) {echo "selected";} echo '>'.urldecode($row[1]).'</option>';
          }
        echo '
        </select>
        <br/><br/>
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
    $('#tab_expedition').DataTable({select: {style: 'single'}});

    $('#tab_expedition tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>