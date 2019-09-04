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
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Specimen.php\">Specimen</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Taxonomie.php\">Taxonomie</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"modification_Expedition.php\">Mission de récolte</a></td>
    <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"modification_autorisation.php\">Autorisation</a></td>
    </tr>
    </table><br/>";

if (isset($_GET['autorisation'])) {
  $_POST['autorisation'] = $_GET['autorisation'];
}
// [JM - 05/07/2019] gestion des modification
  if(isset($_POST["type"])){
    switch ($_POST["type"]) {
      case 'autorisation':
        $stmt = $dbh->prepare("UPDATE autorisation SET aut_numero_autorisation = :aut_numero_autorisation, aut_type = :aut_type WHERE aut_numero_autorisation = :aut_numero_autorisation1");
        $stmt->bindParam(':aut_numero_autorisation', $_POST['aut_numero']);
        $stmt->bindParam(':aut_type', $_POST['aut_type']);
        $stmt->bindParam(':aut_numero_autorisation1', $_POST['id']);
        $stmt->execute();

        break;

      default:
        // code...
        break;
    }
    echo '<script>window.location.replace("modification_autorisation.php?autorisation='.$_POST['aut_numero'].'");</script>';
  }
  ?>

  <h3 align="center">Modification des autorisations</h3>
  <hr>

  <form id="myForm" action="" method="POST" enctype="multipart/form-data" style=" text-align: center;">
    <!-- [JM - 01/02/2019] Recherche de l'autorisation -->
    <table id="tab_autorisation" class="display">
      <thead>
      <tr>
        <th></th>
        <th>Numéro d'autorisation</th>
        <th>Type d'autorisation</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($dbh->query("SELECT * FROM autorisation ORDER BY aut_numero_autorisation") as $row) {
        echo '
        <tr>
        <td><input type="radio" name="autorisation" value="'.urldecode($row[0]).'"';if (isset($_POST['autorisation']) && $row[0] == $_POST['autorisation']) echo "checked"; echo '></td>
        <td>'.urldecode($row[0]).'</td>
        <td>'.urldecode($row[1]).'</td>
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

  if(isset($_POST['autorisation'])){
    $sql_autorisation =
    "SELECT * FROM autorisation WHERE aut_numero_autorisation = '".$_POST['autorisation']."';";

    $result_autorisation = $dbh->query($sql_autorisation);
    $row_autorisation = $result_autorisation->fetch(PDO::FETCH_NUM);
    // [JM - 05/07/2019] affichage des information liée à l'autorisation
    if (!empty($row_autorisation[0])) {

      echo "<div style='text-align: center;'>";
      echo "<div class='hr click_autorisation'>Autorisation</div>";
      echo "<a class='btnFic' style=\"float: right;\" href=\"#modif_autorisation\">Modifier</a>";
      echo "<br/>";
      echo "<br/>";
      echo "<br/><strong>Numéro d'autorisation : </strong>" .$row_autorisation[0];
      echo "<br/><strong>Type d'autorisation : </strong>" .$row_autorisation[1];
      echo "<br/>";
      echo "<br/>";
      echo "</div>";
      echo "</div>";

      // [JM 08/2019] popup de modification
      //autorisation
      echo '
      <div id="modif_autorisation" class="overlay">
      <div id="popup_modif" class="popup">
      <h2>Autorisations</h2>
      <a class="close" href="#return">&times;</a>
      <form id="myForm" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="autorisation" value="'.$row_autorisation[0].'">
        <input type="hidden" name="type" value="autorisation">
        <input type="hidden" name="id" value="'.$row_autorisation[0].'">
        <br/><strong>Numéro d\'autorisation actuel : </strong>'.$row_autorisation[0].'
        <br/><br/>
        Numéro d\'autorisation<br/><input type="text" name="aut_numero" value="'.$row_autorisation[0].'"><br/><br/>
        Type d\'autorisation<br/><input type="text" name="aut_type" value="'.$row_autorisation[1].'"><br/><br/>
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
    $('#tab_autorisation').DataTable({select: {style: 'single'}});

    $('#tab_autorisation tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>
