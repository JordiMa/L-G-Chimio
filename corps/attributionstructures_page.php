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

input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=image] {
  padding: 0;
}

input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}

input[type=submit] {
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

if ($row[0]=='{ADMINISTRATEUR}') {
$sql_autocomplete = "SELECT pro_numero FROM produit order by pro_numero";
$result_autocomplete = $dbh->query($sql_autocomplete);

$var_id_produit = "[";

foreach ($result_autocomplete as $key => $value) {
	$var_id_produit .=  '"'.$value[0].'",';
}
$var_id_produit .= '""]';


// [JM - 01/02/2019] Si l'administrateur a effectuée une recherche sur un produit
if (isset($_GET['produit'])){
	// [JM - 01/02/2019] on recherche les information du produit
	$sql_produit="SELECT pro_id_produit, pro_numero, pro_id_equipe, pro_id_responsable, pro_id_chimiste FROM produit WHERE pro_numero='".$_GET['produit']."'";
	$result_produit = $dbh->query($sql_produit);
	$row1=$result_produit->fetch(PDO::FETCH_NUM);

	// [JM - 01/02/2019] l'equipe correspondante
	$sql_equipe="SELECT equi_id_equipe, equi_nom_equipe FROM equipe";
	$result_equipe = $dbh->query($sql_equipe);

	// [JM - 01/02/2019] le responsable
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe = -1";
	if (!empty($row1[2]))
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe =".$row1[2];
	if (isset($_GET['equipe']) && !empty($_GET['equipe']))
		// [JM - 01/02/2019] Si l'utilisateur selectionne une autre equipe, on recherche les responsables correspondant
		$sql_responsable="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_statut='{RESPONSABLE}' AND chi_id_equipe =".$_GET['equipe'];
	$result_responsable = $dbh->query($sql_responsable);

	// [JM - 01/02/2019] et le chimiste
	$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste";
	if (!empty($row1[3]))
		$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_responsable =".$row1[3];
	if (isset($_GET['responsable']) && !empty($_GET['responsable']))
		// [JM - 01/02/2019] Si l'utilisateur selectionne un autre responsable, on recherche les chimistes correspondant
		$sql_chimiste="SELECT chi_id_chimiste, chi_nom, chi_prenom FROM chimiste WHERE chi_id_responsable =".$_GET['responsable'];
	$result_chimiste = $dbh->query($sql_chimiste);
}

?>

<h3 align="center">Attribution de structure</h3>
<hr>

<form id="myForm" action="attributionstructures.php" method="get">
	<!-- [JM - 01/02/2019] Recherche du produit -->
	<div class="autocomplete" style="width:325px;">
		<input id="myInput" placeholder="<?php echo SAISIEIDPRODUIT; ?>" type="text" name="produit" <?php if (isset($_GET['produit'])) echo "value='".$_GET['produit']."'"; ?> onfocus="this.select()" autofocus>
	</div>
	<input type="submit" name="Rechercher" id="Rechercher" value="<?php echo RECHERCHER;?>">
	<br><br>
	<?php
	if (isset($row1) && $row1 && isset($_GET['produit'])) {
		//  [JM - 01/02/2019] sauvegarde les valeur de la recherche
		if (isset($_GET['Rechercher'])){
			echo "<input type='hidden' name='equipe' value='".$row1[2]."'>";
			echo "<input type='hidden' name='responsable' value='".$row1[3]."'>";
			echo "<input type='hidden' name='chimiste' value='".$row1[4]."'>";
			echo '<script>document.forms.myForm.submit();</script>';
		}
		?>

		<?php echo SELECTEQUIPEEXPORT;?><br>
		<select name="equipe" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les equipes dans une liste box -->
			<?php
					foreach ($result_equipe as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['equipe']) && $_GET['equipe'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]."</option>";
					}
			?>
		</select><br><br>

		<?php echo SELECTRESPONSABLEEXPORT;?><br>
		<select name="responsable" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les responsables dans une liste box -->
			<?php
					foreach ($result_responsable as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['responsable']) && $_GET['responsable'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]." ".$value[2]."</option>";
					}
			?>
		</select><br><br>

		<?php echo SELECTCHIMISTEEXPORT;?><br>
		<select name="chimiste" size="4" onchange="this.form.submit()" style="width: 150px;">
			<!-- [JM - 01/02/2019] Affiche les chimistes dans une liste box -->
			<?php
					foreach ($result_chimiste as $key => $value) {
						echo "<option onclick='this.form.submit()' value='".$value[0]."'"; if(isset($_GET['chimiste']) && $_GET['chimiste'] == $value[0]) echo "selected='selected'"; echo ">".$value[1]." ".$value[2]."</option>";
					}
			?>
		</select><br><br>

		<input type="image" name="save" value="download" src="images/charge.gif" alt="Sauvegarder" title="Sauvegarder">
		<label id="lab_save" for="save"></label>
	<?php }  ?>

</form>

<?php

	if (isset($_GET['save_x']))
	{
		// [JM - 01/02/2019] demande de confirmation
		echo '<script language="javascript">';
		// [JM - 01/02/2019] Si l'utilisateur annule, l'opération est stoppé
		/*echo 'if(confirm("'.CONFIRMSAVE.'")){

		}
		else{
			window.stop();
			history.back();
		}';*/
		echo '</script>';
		if(!isset($_GET['equipe']) || !isset($_GET['responsable'])){
			$sql_no_select = "SELECT chi_id_equipe,	chi_id_responsable FROM chimiste WHERE chi_id_chimiste =".$_GET['chimiste'];
			$result_no_select = $dbh->query($sql_no_select);
			$row_no=$result_no_select->fetch(PDO::FETCH_NUM);
			$_GET['equipe'] = $row_no[0];
			$_GET['responsable'] = $row_no[1];
		}

		// [JM - 01/02/2019] effectue la modification dans la base de donnée
		$update = "UPDATE produit SET pro_id_equipe = ".$_GET['equipe'].", pro_id_responsable = ".$_GET['responsable'].", pro_id_chimiste = ".$_GET['chimiste']." WHERE pro_numero = '".$_GET['produit']."'";
		$update1=$dbh->exec($update);

		if ($update1){
			echo "
			<script>
				document.getElementById('lab_save').innerHTML = '".SAVEOK."';
			</script>";

		}
		else{
			echo "
			<script>
				document.getElementById('lab_save').innerHTML = '".SAVEECHEC."';
			</script>";
		}
	}

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
