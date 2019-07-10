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
print"<div id=\"dhtmltooltip\"></div>
<script language=\"javascript\" src=\"ttip.js\"></script>";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);


print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Extrait.php\">Extrait</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Condition.php\">Condition</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";

?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">

  <!-- [JM - 05/07/2019] condition -->
  <div name="divHide" id="Condition" style="text-align: center;">
    <h1>Condition</h1>
    Milieu<br/><input class="echantillon_nouveau" type="text" name="Condition_Milieu" value=""><br/><br/>
    Temperature<br/><input class="echantillon_nouveau" type="number" step="any" name="Condition_Temperature" value="">°C<br/><br/>
    Type de culture<br/><input class="echantillon_nouveau" type="text" name="Condition_Type" value=""><br/><br/>
    Mode opératoire<br/><input class="echantillon_nouveau" type="text" name="Condition_ModeOp" value=""><br/><br/>
    Observation<br/><input class="echantillon_nouveau" type="text" name="Condition_Observation" value=""><br/><br/>
    Fichier<br/><input class="echantillon_nouveau" type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Condition_Fichier[]" multiple><br/><br/>

    <input type="hidden" name="send" value="send">
    <input type="submit">
  </div>

</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  $dbh->beginTransaction();
  $erreur = "";

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

/* [JM - 05/07/2019] initialise par défaut sur la partie Extrait*/
hideDiv();showDiv("Condition");

</script>


<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
