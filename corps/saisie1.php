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
include_once 'langues/'.$_SESSION['langue'].'/lang_formulaire.php';

if(!isset($_POST["mol"])) $_POST["mol"]="";
if(!isset($_POST['equipe'])) $_POST['equipe']="";
if(!isset($_POST['chimiste'])) $_POST['chimiste']="";
if(!isset($_POST['masse'])) $_POST['masse']="";
if(!isset($_POST['type'])) $_POST['type']="";
if(!isset($_POST['config'])) $_POST['config']="";
if(!isset($_POST['origimol'])) $_POST['origimol']="";
if(!isset($_POST['etapmol'])) $_POST['etapmol']="";

//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);

print"\n<script language=\"JavaScript\">
function GetSmiles(theForm){
  if(document.saisie.masse.value==\"\"){alert(\"".MASSABS."\");}
  else {
	if (document.saisie.origimol.value==\"\"){alert(\"".ORIGABS."\");}
	else {
		if (document.saisie.etapmol.value==\"\" && document.saisie.config_etapeSynthese.value== '1'){alert(\"".ETAPGABS."\");}";
if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}")  print"else {
                                         if (document.saisie.equipe.value==\"\"){alert(\"".EQUIPEABS."\");}";
print"  else {
    if (document.JME.smiles()=='') {alert(\"".DESSINSTRUC."\");}
    else {
			document.saisie.mol.value=document.JME.molFile();
			var resultat=value=document.JME.molFile().indexOf('STY');
			var resultat1=value=document.JME.molFile().indexOf('\$RXN');
			if (resultat==-1 && resultat1==-1) {
				theForm.submit();
			}
			else {
				alert(\"".DESSINSTRUC1."\");
			}";
if ($row[0]=="{ADMINISTRATEUR}" or $row[0]=="{CHEF}")  print"}";
print"    }
		}
	}
  }
}

</script>\n";

$formulaire1=new formulaire ("saisie","saisie2.php","POST",true);
$formulaire1->affiche_formulaire();
print"<input type='hidden' name='config_etapeSynthese' value='".$config_data['etapeSynthese']."'>";

print"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n<td width=\"500\" height=\"500\">";
if(isset($erreur)) print"<p class=\"erreur\">$erreur</p>";

$jsme=new dessinmoleculejsme(460,450,$_POST['mol']);
$jsme->imprime();

print"<td>".OBLIGATOIRE."<br/><br/><br/>";

if ($row[0]=="{CHEF}") {
	$sql="SELECT equi_id_equipe, equi_nom_equipe, chi_nom, chi_prenom, chi_id_chimiste FROM equipe, chimiste WHERE equi_id_equipe IN (SELECT chi_id_equipe FROM chimiste WHERE chi_id_responsable = '".$row[1]."' GROUP BY chi_id_equipe) AND chi_statut = '{RESPONSABLE}' AND chi_id_equipe = equi_id_equipe AND chi_id_responsable = '".$row[1]."' ORDER BY equi_nom_equipe";
	$result1=$dbh->query($sql);
	while($row1 = $result1->fetch(PDO::FETCH_NUM)) {
		$tab1[$row1[0]."/".$row1[4]]=$row1[1]." --- ".$row1[3]." ".$row1[2];
    }
	$formulaire1->ajout_select (1,"equipe",$tab1,false,$_POST['equipe'],SELECTEQUIPE,EQUIPE,false,"");
	print"<br/>\n<br/>\n";
}

if ($row[0]=="{ADMINISTRATEUR}") {
  $sql_autocomplete = "SELECT chi_nom, chi_prenom FROM chimiste WHERE chi_statut = '{CHIMISTE}' AND chi_passif = FALSE order by chi_nom, chi_prenom";
  $result_autocomplete = $dbh->query($sql_autocomplete);

  $var_id_produit = "[";

  foreach ($result_autocomplete as $key => $value) {
  	$var_id_produit .=  '"'.$value[0]. " " .$value[1].'",';
  }
  $var_id_produit .= '""]';

	$sql='SELECT equi_id_equipe, equi_nom_equipe, res.chi_id_chimiste, res.chi_nom, res.chi_prenom, chim.chi_id_chimiste ,chim.chi_nom, chim.chi_prenom FROM chimiste AS "chim" Inner Join chimiste AS "res" on res.chi_id_chimiste = chim.chi_id_responsable Inner Join equipe on chim.chi_id_equipe = equipe.equi_id_equipe WHERE res.chi_statut=\'{RESPONSABLE}\'';
	$result1=$dbh->query($sql);
	$nbresult1=$result1->rowCount();
	if ($nbresult1>0) {
		while($row1 = $result1->fetch(PDO::FETCH_NUM)) {
			$tab1[$row1[0]."/".$row1[2]."/".$row1[5]]=$row1[1]." --- ".$row1[3]." ".$row1[4]." --- ".$row1[6]." ".$row1[7];
		}
	}
	else $tab1="";
  ?>
  <label>* nom du chimiste :</label>
  <div class="autocomplete">
    <input id="myInput" placeholder="Nom Prenom" type="text" name="produit" onfocus="this.select()" autofocus>
  </div>
  <?php
	//$formulaire1->ajout_select (1,"equipe",$tab1,false,$_POST['equipe'], EQU_RES_CHI, EQU_RES_CHI ." :",false,"");
	print"<br/>\n<br/>\n";
}

//recherche des informations sur le champ pro_origine_substance
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_originesubstance';";
//les résultats sont retournées dans la variable $result
$result3=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row3=$result3->fetch(PDO::FETCH_NUM);
$traitement=new traitement_requete_sql($row3[0]);
$tab3=$traitement->imprime();
$sql="SELECT para_origin_defaut FROM parametres";
$resultpara =$dbh->query($sql);
$rowpara=$resultpara->fetch(PDO::FETCH_NUM);
$formulaire1->ajout_select (1,"origimol",$tab3,false,$rowpara[0],SELECTORIGINEMOL,ORIGINEMOL,false,"");
print"<br/>\n<br/>\n";

//recherche des informations sur le champ pro_etape_mol
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_etapemol';";
//les résultats sont retournées dans la variable $result
$result4=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row4=$result4->fetch(PDO::FETCH_NUM);
$traitement4=new traitement_requete_sql($row4[0]);
$tab4=$traitement4->imprime();

$formulaire1->ajout_select (1,"etapmol",$tab4,false,$_POST['etapmol'],SELECTETAPMOL,ETAPMOL,false,"");
print"<br/>\n<br/>\n";

$formulaire1->ajout_text (5, $_POST['masse'], 5, "masse", MASS,"","");
$sql="SELECT check_clause FROM INFORMATION_SCHEMA.check_constraints WHERE  constraint_NAME='contrainte_unitemasse';";
//les résultats sont retournées dans la variable $result
$result4=$dbh->query($sql);
//Les résultats sont mis sous forme de tableau
$row4=$result4->fetch(PDO::FETCH_NUM);
$traitement=new traitement_requete_sql($row4[0]);
$tab4=$traitement->imprime();
$formulaire1->ajout_select (1,"unitmass",$tab4,false,"MG","","",false,"");
$sql="SELECT typ_id_type,typ_type FROM type";
$result2 = $dbh->query($sql);
while($row2= $result2->fetch(PDO::FETCH_NUM)) {
	$tab2[$row2[0]]=constant ($row2[1]);
	}
unset($dbh);
print"<br/>\n<br/>\n";
$formulaire1->ajout_select (1,"type",$tab2,false,$_POST['type'],"",TYPE,false,"");
$formulaire1->ajout_cache ("","mol");
//$formulaire1->ajout_cache ("","inchikey");
// $formulaire1->ajout_cache ("","inchimd5");
// $formulaire1->ajout_cache ("","massemol");
// $formulaire1->ajout_cache ("","formulebrute");
// $formulaire1->ajout_cache ("","nom");
// $formulaire1->ajout_cache ("","logp");
// $formulaire1->ajout_cache ("","donorcount");
// $formulaire1->ajout_cache ("","acceptorcount");
// $formulaire1->ajout_cache ("","composition");
// $formulaire1->ajout_cache ("","aromaticatomcount");
// $formulaire1->ajout_cache ("","aromaticbondcount");
// $formulaire1->ajout_cache ("","rotatablebondcount");
// $formulaire1->ajout_cache ("","asymmetricatomcount");
print"</td>\n</tr>\n<tr>\n<td>\n";
print"<a href=\"images/CNBrochure.pdf\" target=\"_blank\"><strong>".RECOMMANDATION."</strong></a><br/><br/>";
$formulaire1->ajout_text (45, $_POST['config'], 256, "config", CONFIG,"","");
print"</td><td  align=\"right\">\n";
$formulaire1->ajout_button (SUBMIT,"","button","onClick=\"GetSmiles(form)\"");
print"</td>\n</tr>\n</table>";
$formulaire1->fin();
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
