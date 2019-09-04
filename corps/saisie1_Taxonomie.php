<script src="./js/jquery.min.js"></script>
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
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";
*/
?>

<form name="myform" class="" action="" method="post" enctype="multipart/form-data">

  <!-- [JM - 05/07/2019] Taxonomie -->
  <div name="divHide" id="Taxonomie" style="text-align: center;">
      <h1>Taxonomie</h1>
      Type *<br/>
      <select name="Taxonomie_Type" required>
        <option value=""></option>
        <?php
        foreach ($dbh->query("SELECT * FROM type_taxonomie ORDER BY typ_tax_type") as $row) {
          echo '<option value="'.urldecode($row[0]).'">'.urldecode($row[1]).'</option>';
        }
        ?>
      </select><br/><br/>
      Phylum<br/><input type="text" name="Taxonomie_Phylum" value=""><br/><br/>
      classe<br/><input type="text" name="Taxonomie_classe" value=""><br/><br/>
      Ordre<br/><input type="text" name="Taxonomie_Ordre" value=""><br/><br/>
      Famille<br/><input type="text" name="Taxonomie_Famille" value=""><br/><br/>
      Genre *<br/><input type="text" name="Taxonomie_Genre" value="" required><br/><br/>
      Espece *<br/><input type="text" name="Taxonomie_Espece" value="" required><br/><br/>
      Sous-espece<br/><input type="text" name="Taxonomie_Sous-espece" value=""><br/><br/>
      <br/>
      Variete<br/><input type="text" name="Taxonomie_Variete" value=""><br/><br/>
      Sequencage<br/><input type="text" name="Taxonomie_Sequencage" value=""><br/><br/>
      Protocole<br/><textarea rows="5" cols="50" name="Taxonomie_Protocole"></textarea><br/><br/>
      Ref book<br/><input type="text" name="Taxonomie_RefBook" value=""><br/><br/>
      Fichier<br/><input type="file" accept="image/*, .pdf, .xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, .txt" name="Taxonomie_Fichier[]" value="" multiple><br/><br/>

      <input type="hidden" name="send" value="send">
      <input type="submit">
  </div>

</form>

<?php

// [JM - 05/07/2019] Insertion dans la base de données
if(isset($_POST['send']) && $_POST['send'] == 'send'){
  $dbh->beginTransaction();
  $erreur = "";
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
          if ($stmt->errorInfo()[0] == 23505) {
            $erreur .= ", car cette taxonomie existe déjà.";
          }
        }
        echo "<br/>";

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
hideDiv();showDiv("Taxonomie");

</script>

<?php if (isset($_POST['send'])): ?>
  <script type="text/javascript">
    hideDiv();
  </script>
<?php endif; ?>
