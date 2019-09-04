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
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Condition.php\">Condition</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";
*/
?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">
  <!-- [JM - 05/07/2019] Menu de navigation -->
  <h2 style="text-align: center;">
    <a id="noTextDecoration" name="echantillon" onclick="hideDiv();showDiv('echantillon');">Etape 1</a> -
    <a id="noTextDecoration" name="Condition" onclick="hideDiv();showDiv('Condition');">Etape 2</a> -
    <a id="noTextDecoration" name="Specimen" onclick="hideDiv();showDiv('Specimen');">Etape 3</a>
  </h2>

  <!-- [JM - 05/07/2019] Echantillon -->
  <div name="divHide" id="echantillon" style="text-align: center;">
      <h1>échantillon</h1>
      Code *<br/><input type="text" name="echantillon_Code" value="" required><br/><br/>
      Disponible :<br/><input type="radio" name="echantillon_Disponibilité" value="TRUE" checked>Oui<br/>
      <input type="radio" name="echantillon_Disponibilité" value="FALSE">Non<br/><br/>
      Quantité *<br/><input type="number" min="0" step="any" name="echantillon_Quantité" value="" required> g<br/><br/>
      Lieu de stockage *<br/><textarea name="echantillon_Lieu" rows="5" cols="50" required></textarea><br/><br/>
      Partie d'organisme *<br/>
      <select name="echantillon_Partie" required>
        <option value=""></option>
        <?php
        foreach ($dbh->query("SELECT * FROM partie_organisme ORDER BY par_fr") as $row) {
          echo '<option value="'.urldecode($row[0]).'">'.urldecode($row[2]).'</option>';
        }
        ?>
      </select><br/><br/>
      <br/>
      Contact<br/><input type="text" name="echantillon_Contact" value=""><br/><br/>
      DOI<br/><input type="text" name="echantillon_DOI" value=""><br/><br/>

      <button type="button" name="button" onclick="hideDiv();showDiv('Condition');">Suivant</button>
    </div>


  <!-- [JM - 05/07/2019] condition -->
  <div name="divHide" id="Condition" style="text-align: center;">
    <h1>Condition</h1>
    <input type="radio" name="condition_choix" value="NULL" checked> Aucune condition
    <br/><br/>
    OU
    <br/><br/>
        <table id="tab_Condition" class="display">
          <thead>
          <tr>
            <th></th>
            <th>ID</th>
            <th>Milieu</th>
            <th>Temperature</th>
            <th>Type de culture</th>
            <th>Mode operatoir</th>
            <th>Observation</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($dbh->query("SELECT * FROM condition ORDER BY con_id") as $row) {
            echo '
            <tr>
            <td><input type="radio" name="condition_choix" value="'.urldecode($row[0]).'"></td>
            <td>'.urldecode($row[0]).'</td>
            <td>'.urldecode($row[1]).'</td>
            <td>'.urldecode($row[2]).'</td>
            <td>'.urldecode($row[3]).'</td>
            <td>'.urldecode($row[4]).'</td>
            <td>'.urldecode($row[5]).'</td>
            </tr>
            ';
          }
          ?>
        </tbody>
        </table>
        <br/>
    <button type="button" name="button" onclick="hideDiv();showDiv('echantillon');">Précédent</button>
    <button type="button" name="button" onclick="hideDiv();showDiv('Specimen');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Specimen -->
  <div name="divHide" id="Specimen" style="text-align: center;">
      <h1>Specimen</h1>

      <table id="tab_Specimen" class="display">
        <thead>
        <tr>
          <th></th>
          <th>Code</th>
          <th>Date</th>
          <th>Lieu</th>
          <th>GPS</th>
          <th style="width: 35%;">Observation</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($dbh->query("SELECT * FROM specimen ORDER BY spe_code_specimen") as $row) {
          echo '
          <tr>
          <td><input type="radio" name="Specimen_Code" value="'.urldecode($row[0]).'"></td>
          <td>'.urldecode($row[0]).'</td>
          <td>'.urldecode($row[1]).'</td>
          <td>'.urldecode($row[2]).'</td>
          <td>'.urldecode($row[3]).'</td>
          <td style="width: 35%;">'.urldecode($row[4]).'</td>
          <td><a href="recherche_Specimen.php?specimen='.urldecode($row[0]).'" target="_blank">Voir les détails</a></td>
          </tr>
          ';
        }
        ?>
      </tbody>
      </table>
        <button type="button" name="button" onclick="hideDiv();showDiv('Condition');">Précédent</button>
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

    $stmt = $dbh->prepare("INSERT INTO echantillon (ech_code_echantillon, ech_contact, ech_publication_doi, ech_stock_disponibilite, ech_stock_quantite, ech_lieu_stockage, par_id, spe_code_specimen, con_id) VALUES (:ech_code_echantillon, :ech_contact, :ech_publication_doi, :ech_stock_disponibilite, :ech_stock_quantite, :ech_lieu_stockage, :par_id, :spe_code_specimen, :con_id)");
    $stmt->bindParam(':ech_code_echantillon', $_POST['echantillon_Code']);
    $stmt->bindParam(':ech_contact', $_POST['echantillon_Contact']);
    $stmt->bindParam(':ech_publication_doi', $_POST['echantillon_DOI']);
    $stmt->bindParam(':ech_stock_disponibilite', $_POST['echantillon_Disponibilité']);
    $stmt->bindParam(':ech_stock_quantite', $_POST['echantillon_Quantité']);
    $stmt->bindParam(':ech_lieu_stockage', $_POST['echantillon_Lieu']);
    $stmt->bindParam(':par_id', $_POST['echantillon_Partie']);
    $stmt->bindParam(':spe_code_specimen', $_POST['Specimen_Code']);

    if ($_POST['condition_choix'] == "NULL")
      $_POST['condition_choix'] = NULL;
    $stmt->bindParam(':con_id', $_POST['condition_choix']);

    $stmt->execute();

    if ($stmt->errorInfo()[0] != 00000) {
      $erreur .= "<br/>Erreur lors de l'insertion de l'echantillon";
      if ($stmt->errorInfo()[0] == 23505) {
        $erreur .= ", car le code echantillon ".$_POST['echantillon_Code']." existe déjà.";
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
hideDiv();showDiv("echantillon");

$(document).ready(function() {
    $('#tab_Condition').DataTable({select: {style: 'single'}});

    $('#tab_Condition tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });

    $('#tab_Specimen').DataTable({select: {style: 'single'}});

    $('#tab_Specimen tr').click(function() {
      $(this).find('td input:radio').prop('checked', true);
    });

});
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
var id_specimen = <?php echo $var_id_specimen;?>;


/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput2"), id_specimen);
</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
