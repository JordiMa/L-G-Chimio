<script type="text/javascript" src="js/jquery.min.js"></script>
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

input.input-auto{
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=image].input-auto{
  padding: 0;
}

input[type=text].input-auto{
  background-color: #f1f1f1;
  width: 100%;
}

input[type=submit].input-auto{
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
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
  background-color: #fff;
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
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe, chi_password FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);

if ($row[0]=='{ADMINISTRATEUR}') {
$sql_autocomplete = "SELECT pro_numero FROM produit order by pro_numero";
$result_autocomplete = $dbh->query($sql_autocomplete);

$var_id_produit = "[";

foreach ($result_autocomplete as $key => $value) {
	$var_id_produit .=  '"'.$value[0].'",';
}
$var_id_produit .= '""]';


// [JM - 01/02/2019] Si l'administrateur a effectuée une recherche sur un produit
if (isset($_POST['produit'])){
	// [JM - 01/02/2019] on recherche les information du produit
	$sql_produit="SELECT pro_id_produit, pro_numero, equi_nom_equipe, resp.chi_nom AS resp_nom, resp.chi_prenom AS resp_prenom, chim.chi_nom AS chim_nom, chim.chi_prenom AS chim_prenom, typ_type, res_id_resultat, pro_date_entree, pos_id_produit
  FROM Produit
  LEFT JOIN equipe ON produit.pro_id_equipe = equipe.equi_id_equipe
  LEFT JOIN chimiste chim ON produit.pro_id_chimiste = chim.chi_id_chimiste
  LEFT JOIN chimiste resp ON produit.pro_id_responsable = resp.chi_id_chimiste
  LEFT JOIN type ON produit.pro_id_type = type.typ_id_type
  LEFT JOIN resultat ON produit.pro_id_produit = resultat.res_id_produit
  LEFT JOIN position ON produit.pro_id_produit = position.pos_id_produit
  Where pro_numero = '".$_POST['produit']."'";

	$result_produit = $dbh->query($sql_produit);
	$row1=$result_produit->fetch(PDO::FETCH_NUM);

  if (isset($_POST['suppr']))
	{
    if (isset($_POST["pass"]) && password_verify($_POST["pass"],$row[3])){
  		// [JM - 01/02/2019] demande de confirmation
  		echo '<script language="javascript">';
  		// [JM - 01/02/2019] Si l'utilisateur annule, l'opération est stoppé
  		echo 'if(confirm("Voulez vous vraiment supprimer le produit '.$row1[1].' ?")){
  		}
  		else{
  			window.stop();
  			history.back();
  		}';
  		echo '</script>';

      // [JM 2019] script de suppression

      $sql_plaque = "DELETE FROM position WHERE pos_id_produit = ".$row1[0];
      $sql_resultat = "DELETE FROM resultat WHERE res_id_produit = ".$row1[0];
      $sql_champsProduit = "DELETE FROM champsProduit WHERE pro_id_produit = ".$row1[0];
      $sql_produit = "DELETE FROM produit WHERE pro_id_produit = ".$row1[0];

      $res0 = $dbh->exec($sql_plaque);
      $res1 = $dbh->exec($sql_resultat);
      $res2 = $dbh->exec($sql_champsProduit);
      $res3 = $dbh->exec($sql_produit);
    }
    else {
      echo "<script>alert('Mot de passe invalide');</script>";
    }
	}
}

?>

<h3 align="center">Suppression de structure</h3>
<hr>
<div style="margin:10px;">
<form id="myForm" action="supprstructure.php" method="post">
	<!-- [JM - 01/02/2019] Recherche du produit -->
	<div class="autocomplete" style="width:325px;">
		<input class="input-auto" id="myInput" placeholder="<?php echo SAISIEIDPRODUIT; ?>" type="text" name="produit" <?php if (isset($_POST['produit'])) echo "value='".$_POST['produit']."'"; ?> onfocus="this.select()" autofocus>
	</div>
	<input class="input-auto" type="submit" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
	<br><br>
  </form>
  <form id="myForm" action="supprstructure.php" method="post">
	<?php
	if (isset($row1) && $row1 && isset($_POST['produit'])) {
    if (!isset($res1) && !isset($res2) )
  	{
      echo "<div id='info' name='info'>";
      echo "<strong>ID constant :</strong> " . $row1[0] . "<br/>";
      echo "<strong>ID local :</strong> " . $row1[1] . "<br/>";
      echo "<br/>";
      echo "<strong>Equipe :</strong> " . $row1[2] . "<br/>";
      echo "<strong>Responsable :</strong> " . $row1[3] . " " . $row1[4] . "<br/>";
      echo "<strong>Chimiste :</strong> " . $row1[5] . " " . $row1[6] . "<br/>";
      echo "<br/>";
      echo "<strong>Type :</strong> " . $row1[7] . "<br/>";
      echo "<br/>";
      echo "<strong>Resultat bio :</strong> ";
      if  ($row1[8])
        echo "<strong style='color: red;'>Oui</strong><br/>";
      else
        echo "Non<br/>";
      echo "<strong>En plaque :</strong> ";
        if  ($row1[10])
          echo "<strong style='color: red;'>Oui</strong><br/>";
        else
          echo "Non<br/>";
      echo "<br/>";
      echo "<strong>Date de saisie :</strong> " . $row1[9] . "";
      echo "</div>";
  		?>
      <input type="hidden" name="produit" <?php if (isset($_POST['produit'])) echo "value='".$_POST['produit']."'"; ?>>

      <br/><br/>
      <input type="checkbox" name="supprconfirm" value="supprconfirm" required>
      en cochant cette case je confirme la suppression du produit <strong style="color: red;"><?php echo $row1[1]; ?></strong>, et toutes les données liées à ce dernier.
      <br/><strong style="color: red;">&emsp;&emsp;Attention, la suppression est irréversible !</strong>

      <br/><br/><br/>
      <B style="color: red;"><?php echo MOTDEPASSE; ?> :</B>
      <br/>
      <input class="" type="password" name="pass" placeholder="<?php echo MOTDEPASSE; ?>" required>

      <br/><br/>
      <button name="suppr" value="suppr">Supprimer</button>
<?php
    }
    // [JM 2019] resultat de la suppression
    elseif ($res0 || $res1 || $res3 && isset($_POST['suppr'])) {
      if($res0)
        echo "<h2>Produit supprimé de la plaque</h2>";
      if($res1)
        echo "<h2>Résultat supprimé</h2>";
      if($res3)
        echo "<h2>Produit supprimé</h2>";
    }
    else {
      echo "<h2>Échec de la suppression</h2>";
    }
  }
  else if (isset($_POST['produit']))
  echo "<h2>Aucun résultat</h2>";
?>

</form>
</div>

<?php
}
else require 'deconnexion.php';
unset($dbh);
?>

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
