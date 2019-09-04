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
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Extrait.php\">Extrait</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
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
    <a id="noTextDecoration" name="Extrait" onclick="hideDiv();showDiv('Extrait');">Etape 1</a> -
    <a id="noTextDecoration" name="Purification" onclick="hideDiv();showDiv('Purification');">Etape 2</a> -
    <a id="noTextDecoration" name="echantillon" onclick="hideDiv();showDiv('echantillon');">Etape 3</a>
  </h2>

  <!-- [JM - 05/07/2019] Extrait -->
  <div name="divHide" id="Extrait" style="text-align: center;">
    <h1>Extrait</h1>
    <?php

    if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}") {
      $sql_autocomplete = "SELECT chi_nom, chi_prenom FROM chimiste Inner Join equipe on chimiste.chi_id_equipe = equipe.equi_id_equipe WHERE (chi_statut = '{CHIMISTE}' or chi_statut = '{RESPONSABLE}') AND chi_passif = FALSE order by chi_nom, chi_prenom";
      $result_autocomplete = $dbh->query($sql_autocomplete);

      $var_id_produit = "[";

      foreach ($result_autocomplete as $key => $value) {
      	$var_id_produit .=  '"'.$value[0]. " " .$value[1].'",';
      }
      $var_id_produit .= '""]';

      ?>
      <label>nom du chimiste *</label><br/>
      <div class="autocomplete">
        <input id="myInput" placeholder="Nom Prenom" type="text" name="chimiste" onfocus="this.select()" autofocus>
      </div>

    <?php } ?>
    <br/><br/>
    Code extrait *<br/><input type="text" name="Code_Extraits" value="" required><br/><br/>

    Solvants *<br/>
    <select name="Extrait_Solvants" required>
      <option value=""></option>
      <?php
        foreach ($dbh->query("select * from solvant") as $key => $value) {
          echo'<option value="'.$value[0].'">'.constant($value[1]).'</option>';
        }
      ?>
    </select><br/><br/>

    Disponible :<br/><input type="radio" name="Extrait_Disponibilité" value="TRUE" checked>Oui<br/>
    <input type="radio" name="Extrait_Disponibilité" value="FALSE">Non<br/><br/>
    <br/>
    Type extraction<br/><input type="text" name="Extrait_Type_extraction" value=""><a href="#" onmouseover="ddrivetip('<p align=\'center\'>Exemple : macération / liquide-liquide</p>')" onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a><br/><br/>
    Etat<br/><input type="text" name="Extrait_Etat" value=""><a href="#" onmouseover="ddrivetip('<p align=\'center\'>Exemple : conservé sec / en solution dans méthanol</p>')" onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a><br/><br/>
    Protocole<br/><textarea name="Extrait_Protocole" rows="5" cols="50"></textarea><br/><br/>
    Lieu de stockage<br/><textarea name="Extrait_Stockage" rows="5" cols="50"></textarea><br/><br/>
    Observation<br/><textarea name="Extrait_Observation" rows="5" cols="50"></textarea><br/><br/>

    licence<br/>
    <select name="Extrait_typ_id_type">
      <?php
        foreach ($dbh->query("select * from type") as $key => $value) {
          echo'<option value="'.$value[0].'">'.constant($value[1]).'</option>';
        }
      ?>
    </select><br/><br/>

    <button type="button" name="button" onclick="hideDiv();showDiv('Purification');">Suivant</button>
  </div>

  <!-- [JM - 05/07/2019] Purification -->
  <div name="divHide" id="Purification" style="text-align: center;">
    <h1>Purification</h1>
    <div style="text-align: left; width: fit-content; display: inline-block;">
      <input type="radio" name="chx_purif[]" value="Aucune">Aucune<br/>
      <input type="checkbox" name="chx_purif[]" value="HPLC phase inverse">HPLC phase inverse<br/>
      <input type="checkbox" name="chx_purif[]" value="HPLC phase normale">HPLC phase normale<br/>
      <input type="checkbox" name="chx_purif[]" value="Flash chromatographie sur phase inverse">Flash chromatographie sur phase inverse<br/>
      <input type="checkbox" name="chx_purif[]" value="Flash chromatographie sur phase normale">Flash chromatographie sur phase normale<br/>
      <input type="checkbox" name="chx_purif[]" value="Chromatographie d’exclusion">Chromatographie d’exclusion<br/>
      <input type="checkbox" name="chx_purif[]" value="Chromatographie échangeuse d’ion">Chromatographie échangeuse d’ion<br/>
      <input type="checkbox" name="chx_purif[]" value="Extraction d’alcaloides">Extraction d’alcaloides<br/>
      <input type="checkbox" name="chx_purif[]" value="Extraction lipides">Extraction lipides<br/>
      <input type="checkbox" name="chx_purif[]" value="autres">autres<br/>
      <br/>
    </div>
    <br/>


    <div id="container"></div>
    <button type="button" name="button" onclick="hideDiv();showDiv('Extrait');">Précédent</button>
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
          foreach ($dbh->query("SELECT * FROM echantillon ORDER BY ech_code_echantillon") as $row1) {
            echo '
            <tr>
            <td><input class="echantillon_nouveau specimen_nouveau expedition_existant" type="radio" name="echantillon_Code" value="'.urldecode($row1[0]).'"></td>
            <td>'.urldecode($row1[0]).'</td>
            <td>'.urldecode($row1[1]).'</td>
            <td>'.urldecode($row1[2]).'</td>
            <td>';if ($row1[3]) {echo "Oui";} else {echo "Non";} echo '</td>
            <td>'.urldecode($row1[4]).'</td>
            <td>'.urldecode($row1[5]).'</td>
            <td><a href="recherche_Echantillon.php?echantillon='.urldecode($row1[0]).'" target="_blank">Voir les détails</a></td>
            </tr>
            ';
          }
          ?>
        </tbody>
        </table>
        <button type="button" name="button" onclick="hideDiv();showDiv('Purification');">Précédent</button>
        <br/><br/>
        <input type="hidden" name="send" value="send">
        <input type="submit">
  </div>
</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}") {
    $sql_chi="SELECT chi_id_chimiste FROM chimiste WHERE (chi_statut = '{CHIMISTE}' or chi_statut = '{RESPONSABLE}') AND chi_passif = 'false'	AND chi_nom || ' ' || chi_prenom = '".$_POST['chimiste']."'";
    $result_chi=$dbh->query($sql_chi);
    $row_chi = $result_chi->fetch(PDO::FETCH_NUM);
    $idchim = $row_chi[0];
  }
  else {
    $idchim = $row[1];
  }

  $dbh->beginTransaction();
  $erreur = "";

  $stmt = $dbh->prepare("INSERT INTO extraits (ext_Code_Extraits, ext_solvant, ext_type_extraction, ext_etat, ext_disponibilite, ext_protocole, ext_stockage, ext_observations, chi_id_chimiste, ech_code_echantillon, typ_id_type) VALUES (:ext_Code_Extraits, :ext_solvant, :ext_type_extraction, :ext_etat, :ext_disponibilite, :ext_protocole, :ext_stockage, :ext_observations, :chi_id_chimiste, :ech_code_echantillon, :typ_id_type)");
  $stmt->bindParam(':ext_Code_Extraits', $_POST['Code_Extraits']);
  $stmt->bindParam(':ext_solvant', $_POST['Extrait_Solvants']);
  $stmt->bindParam(':ext_type_extraction', $_POST['Extrait_Type_extraction']);
  $stmt->bindParam(':ext_etat', $_POST['Extrait_Etat']);
  $stmt->bindParam(':ext_disponibilite', $_POST['Extrait_Disponibilité']);
  $stmt->bindParam(':ext_protocole', $_POST['Extrait_Protocole']);
  $stmt->bindParam(':ext_stockage', $_POST['Extrait_Stockage']);
  $stmt->bindParam(':ext_observations', $_POST['Extrait_Observation']);

  $stmt->bindParam(':chi_id_chimiste', $idchim);//TODO chi_id_chimiste
  $stmt->bindParam(':ech_code_echantillon', $_POST['echantillon_Code']);
  $stmt->bindParam(':typ_id_type', $_POST['Extrait_typ_id_type']);

  $stmt->execute();
  if ($stmt->errorInfo()[0] != 00000) {
    $erreur .= "<br/>Erreur lors de l'insertion de l'extrait";
    print_r($stmt->errorInfo());
  }

  if(isset($_POST['chx_purif'])){
    foreach($_POST['chx_purif'] as $value){
      //$stmt = $dbh->prepare("INSERT INTO purification (pur_purification, pur_ref_book, ext_Code_Extraits) VALUES (:pur_purification, :pur_ref_book, :ext_Code_Extraits)");
      $stmt = $dbh->prepare("INSERT INTO purification (pur_purification, ext_Code_Extraits) VALUES (:pur_purification, :ext_Code_Extraits)");
      $stmt->bindParam(':pur_purification', $value);
      //$stmt->bindParam(':pur_ref_book', $_POST['Purification_RefBook'.($i+1)]);
      $stmt->bindParam(':ext_Code_Extraits', $_POST['Code_Extraits']);
      $stmt->execute();
      $pur_id = $dbh->lastInsertId();
      if ($stmt->errorInfo()[0] != 00000) {
        $erreur .= "<br/>Erreur lors de l'insertion de la purification ";
      }
    }
  }

    if(isset($_FILES['Purification_Fichier'])){
      foreach ($_FILES['Purification_Fichier']['name'] as $key => $value) {
        if ($_FILES['Purification_Fichier']['size'][$key] != 0) {
          $extension_fichier=strtolower(pathinfo($_FILES['Purification_Fichier']['name'][$key], PATHINFO_EXTENSION));
          $fichier=file_get_contents($_FILES['Purification_Fichier']['tmp_name'][$key]);
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
hideDiv();showDiv("Extrait");

$(document).ready(function() {
    $('#tab_echantillon').DataTable({select: {style: 'single'}});

    $('#tab_echantillon tr').click(function() {
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
var id_produit = <?php echo $var_id_produit;?>;

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput"), id_produit);
</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
