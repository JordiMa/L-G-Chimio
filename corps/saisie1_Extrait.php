<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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

print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Extrait.php\">Extrait</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Condition.php\">Condition</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";

?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">
  <!-- [JM - 05/07/2019] Menu de navigation -->
  <h2 style="text-align: center;">
    <a id="noTextDecoration" name="Extrait" onclick="hideDiv();showDiv('Extrait');">Etape 1</a> -
    <a id="noTextDecoration" name="Purification" onclick="hideDiv();showDiv('Purification');">Etape 2</a> -
    <a id="noTextDecoration" name="echantillon" onclick="hideDiv();showDiv('echantillon');">Etape 3</a>
  </h2>

  <!-- [JM - 05/07/2019] Extrait -->
  <div name="divHide" id="Extrait" style="text-align: center;">
    <h1>Extrait</h1>
    Solvants *<br/><input type="text" name="Extrait_Solvants" value="" required><br/><br/>
    Disponibilité<br/><input type="checkbox" name="Extrait_Disponibilité" value=""><br/><br/>
    <br/>
    Type extraction<br/><input type="text" name="Extrait_Type_extraction" value=""><a href="#" onmouseover="ddrivetip('<p align=\'center\'>Exemple : macération / liquide-liquide</p>')" onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a><br/><br/>
    Etat<br/><input type="text" name="Extrait_Etat" value=""><a href="#" onmouseover="ddrivetip('<p align=\'center\'>Exemple : conservé sec / en solution dans méthanol</p>')" onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a><br/><br/>
    Protocole<br/><input type="text" name="Extrait_Protocole" value=""><br/><br/>
    Lieu de stockage<br/><input type="text" name="Extrait_Stockage" value=""><br/><br/>
    Observation<br/><input type="text" name="Extrait_Observation" value=""><br/><br/>

    <button type="button" name="button" onclick="hideDiv();showDiv('Purification');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Purification -->
  <div name="divHide" id="Purification" style="text-align: center;">
    <h1>Purification <a href="#" onmouseover="ddrivetip('<p align=\'center\'>Exemple : oui / non ou HPLC / silice / phase inverse</p>')" onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a></h1>
    Ajouter <input type="number" id="nbPurification" name="nbPurification" value=0 min=0 max=5 onchange="addFields();"> purification(s)<br/><br/>

    <div id="container"></div>

    <button type="button" name="button" onclick="hideDiv();showDiv('echantillon');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Echantillon -->
  <div name="divHide" id="echantillon" style="text-align: center;">
      <h1>Échantillon</h1>
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
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($dbh->query("SELECT * FROM echantillon ORDER BY ech_code_echantillon") as $row) {
            echo '
            <tr>
            <td><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="echantillon_Code" value="'.urldecode($row[0]).'"></td>
            <td>'.urldecode($row[0]).'</td>
            <td>'.urldecode($row[1]).'</td>
            <td>'.urldecode($row[2]).'</td>
            <td>';if ($row[3]) {echo "Oui";} else {echo "Non";} echo '</td>
            <td>'.urldecode($row[4]).'</td>
            <td>'.urldecode($row[5]).'</td>
            <td><a href="recherche_extrait.php?echantillon='.urldecode($row[0]).'" target="_blank">Voir les détails</a></td>
            </tr>
            ';
          }
          ?>
        </tbody>
        </table>
        <br/>
        <input type="hidden" name="send" value="send">
        <input type="submit">
  </div>
</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  $dbh->beginTransaction();
  $erreur = "";

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

$(document).ready(function() {
    $('#tab_echantillon').DataTable({select: {style: 'single'}});

    $('#tab_echantillon tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });
});
</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
