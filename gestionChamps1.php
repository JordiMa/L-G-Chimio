<script type="text/javascript" src="js/jquery.min.js"></script>
<?php
/*
Copyright Laurent ROBIN CNRS - Universit� d'Orl�ans 2011
Distributeur : UGCN - http://chimiotheque-nationale.org

Laurent.robin@univ-orleans.fr
Institut de Chimie Organique et Analytique
Universit� d�Orl�ans
Rue de Chartre � BP6759
45067 Orl�ans Cedex 2

Ce logiciel est un programme informatique servant � la gestion d'une chimioth�que de produits de synth�ses.

Ce logiciel est r�gi par la licence CeCILL soumise au droit fran�ais et respectant les principes de diffusion des logiciels libres.
Vous pouvez utiliser, modifier et/ou redistribuer ce programme sous les conditions de la licence CeCILL telle que diffus�e par le CEA,
 le CNRS et l'INRIA sur le site "http://www.cecill.info".

En contrepartie de l'accessibilit� au code source et des droits de copie, de modification et de redistribution accord�s par cette licence,
 il n'est offert aux utilisateurs qu'une garantie limit�e. Pour les m�mes raisons, seule une responsabilit� restreinte p�se sur l'auteur du
 programme, le titulaire des droits patrimoniaux et les conc�dants successifs.

A cet �gard l'attention de l'utilisateur est attir�e sur les risques associ�s au chargement, � l'utilisation, � la modification et/ou au d�veloppement
 et � la reproduction du logiciel par l'utilisateur �tant donn� sa sp�cificit� de logiciel libre, qui peut le rendre complexe � manipuler et qui le
r�serve donc � des d�veloppeurs et des professionnels avertis poss�dant des connaissances informatiques approfondies. Les utilisateurs sont donc
invit�s � charger et tester l'ad�quation du logiciel � leurs besoins dans des conditions permettant d'assurer la s�curit� de leurs syst�mes et ou de
 leurs donn�es et, plus g�n�ralement, � l'utiliser et l'exploiter dans les m�mes conditions de s�curit�.

Le fait que vous puissiez acc�der � cet en-t�te signifie que vous avez pris connaissance de la licence CeCILL, et que vous en avez accept� les
termes.
*/
include_once 'script/administrateur.php';
include_once 'script/secure.php';
include_once 'autoload.php';
include_once 'langues/'.$_SESSION['langue'].'/presentation.php';
include_once 'presentation/entete.php';
$menu=12;
include_once 'presentation/gauche.php';
require 'script/connectionb.php';
$sql="SELECT chi_statut,chi_id_chimiste,chi_id_equipe FROM chimiste WHERE chi_nom='".$_SESSION['nom']."'";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row =$result->fetch(PDO::FETCH_NUM);
if ($row[0]=='{ADMINISTRATEUR}') {

// [JM - 17/05/2019] si pas de nb champs sélectionner, on recherche les champs present dans la BDD
if (!isset($_GET['nb'])){
  $sql="SELECT * FROM \"champsAnnexe\"";
  //les résultats sont retournées dans la variable $result
  $result =$dbh->query($sql);
  $nbRow = 0;
  if ($result){
    // [JM - 17/05/2019] Defini le nombre de champs dans la BDD
    $nbRow = $result->rowCount();
    $url_result = "?nb=".$nbRow."&nb_req=".$nbRow."";
    // [JM - 17/05/2019] affichage des champs present dans la BDD en mode modification (envoie des info au formulaire via $_GET / URL)
    foreach ($result as $key => $value) {
      $lib = trim(substr($value[1], 0, strpos($value[1], ":")));
      if(strpos($value[1], 'type="text"')){
        $url_result .= "&M:type_champsAnnexe_".($key+1)."=text&M:lib_champsAnnexe_".($key+1)."=$lib";
      }
      if(strpos($value[1], 'type="number"')){
        $url_result .= "&M:type_champsAnnexe_".($key+1)."=nombre&M:lib_champsAnnexe_".($key+1)."=$lib";
      }
      if(strpos($value[1], '<textarea')){
        $url_result .= "&M:type_champsAnnexe_".($key+1)."=multi-text&M:lib_champsAnnexe_".($key+1)."=$lib";
        $rows_texarea = trim(substr($value[1], strpos($value[1], "rows=") +6,strpos($value[1], "cols=") - strpos($value[1], "rows=") - 8));
        $url_result .= "&M:nbLigne_champsAnnexe_".($key+1)."=$rows_texarea";
      }
      if(strpos($value[1], 'type="checkbox"')){
        $url_result .= "&M:type_champsAnnexe_".($key+1)."=checkbox&M:lib_champsAnnexe_".($key+1)."=$lib";
      }
      $url_result .="&M:ID_champsAnnexe_".($key+1)."=".$value[0];
    }
    echo"<script>window.location.replace('$url_result');</script>";
  }
}

?>
<div style="padding: 5px;">

<a class="btnlink" href="gestionChamps1.php">Réinitialiser le formulaire</a>

<form id="form1" name="form1" action="" method="GET">
  Nombre de champs :<br/>
  <input type="number" name="nb" min="<?php echo $_GET['nb_req']; ?>" max="10" style="width: 115;" value="<?php if(isset($_GET['nb'])) echo $_GET['nb']?>">
  <input type="hidden" name="nb_req" value="<?php echo $_GET['nb_req']; ?>">
  <?php
  if(isset($_GET['nb']) && $_GET['nb'] > 0){
    //echo '<hr><input type="hidden" name="nb" min="0" max="10" style="width: 115;" value="'.$_GET['nb'].'">';
    echo "<hr>";
    // [JM - 17/05/2019] liste des type de champs possible
    for ($i=1; $i < $_GET['nb']+1; $i++) {
      if(isset($_GET["M:type_champsAnnexe_".$i])){
        echo'Modification de champsAnnexe_'.$i.' :<br/><br/>Type :<br/>';
        echo
        '<select name="M:type_champsAnnexe_'.$i.'" onchange="document.form1.submit2.click();" disabled>
          <option value="">Sélectionner le type</option>
          <option value="text" ';if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] == "text") echo "selected";echo'>text</option>
          <option value="multi-text" ';if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] == "multi-text") echo "selected";echo'>text multiligne</option>
          <option value="nombre" ';if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] == "nombre") echo "selected";echo'>nombre</option>
          <option value="checkbox" ';if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] == "checkbox") echo "selected";echo'>case à cocher</option>
        </select>';
        echo '<input type="hidden" name="M:type_champsAnnexe_'.$i.'"value="'.$_GET['M:type_champsAnnexe_'.$i].'"/>';
      }
      else{
        echo'champsAnnexe_'.$i.' :<br/><br/>Type :<br/>';
        echo
      '<select name="type_champsAnnexe_'.$i.'" onchange="document.form1.submit2.click();">
        <option value="">Sélectionner le type</option>
        <option value="text" ';if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] == "text") echo "selected";echo'>text</option>
        <option value="multi-text" ';if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] == "multi-text") echo "selected";echo'>text multiligne</option>
        <option value="nombre" ';if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] == "nombre") echo "selected";echo'>nombre</option>
        <option value="checkbox" ';if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] == "checkbox") echo "selected";echo'>case à cocher</option>
      </select>';
      }
      echo'<br/><br/>';
      // [JM - 17/05/2019] libellé des champs
      if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] != ""){
        echo'libellé :<br>';
        echo'<input type="text" name="lib_champsAnnexe_'.$i.'" value="';if(isset($_GET['lib_champsAnnexe_'.$i])) echo $_GET['lib_champsAnnexe_'.$i];echo'">';
          // [JM - 17/05/2019] nombre de ligne pour type multiligne
          if($_GET['type_champsAnnexe_'.$i] == "multi-text"){
            echo'<br/><br/>';
            echo "Nombre de ligne afficher :<br>";
            echo'<input type="number" name="nbLigne_champsAnnexe_'.$i.'" min="2" max="15" value="';if(isset($_GET['nbLigne_champsAnnexe_'.$i])) echo $_GET['nbLigne_champsAnnexe_'.$i]; else echo "8"; ;echo'">';
          }
          echo'<br/><br/>';
      }
      // [JM - 17/05/2019] libellé des champs existant (mode modification)
      if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] != ""){
        echo'libellé :<br>';
        echo'<input type="text" name="M:lib_champsAnnexe_'.$i.'" value="';if(isset($_GET['M:lib_champsAnnexe_'.$i])) echo $_GET['M:lib_champsAnnexe_'.$i];echo'">';
          // [JM - 17/05/2019] nombre de ligne pour type multiligne existant (mode modification)
          if($_GET['M:type_champsAnnexe_'.$i] == "multi-text"){
            echo'<br/><br/>';
            echo "Nombre de ligne afficher :<br>";
            echo'<input type="number" name="M:nbLigne_champsAnnexe_'.$i.'" min="2" max="15" value="';if(isset($_GET['M:nbLigne_champsAnnexe_'.$i])) echo $_GET['M:nbLigne_champsAnnexe_'.$i]; else echo "8"; ;echo'">';
          }
          echo'<br/><br/>';
          echo '<input type="checkbox" name="supprimer'.$i.'"';if(isset($_GET['supprimer'.$i])) echo 'checked';echo'/>supprimer';
          echo '<input type="hidden" name="M:ID_champsAnnexe_'.$i.'" value= "'.$_GET['M:ID_champsAnnexe_'.$i].'" />';
      }
      echo "<hr>";
    }
  }
  echo '<input type="submit" name="submit2"><center><h2>Requêtes SQL</h2></center>';
  ?>
</form>

<textarea name="name" rows="15" style="width: 100%;">
<?php
// [JM - 17/05/2019] Requete pour ajout/modification/suppression des champs
if(isset($_GET['submit2'])){
  $req = "";
  for ($i=1; $i < $_GET['nb']+1; $i++) {
    $valueReq = "";
    if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] != ""){
      $req .= "\n\n/*$i*/\nINSERT INTO \"champsAnnexe\" (\"HTML\") values (E'";

      if(isset($_GET['lib_champsAnnexe_'.$i])){
        $valueReq .= $_GET['lib_champsAnnexe_'.$i] . " :";
      }
      if($_GET['type_champsAnnexe_'.$i] == "text"){
        $valueReq .= '<br/><input type="text" name="champsAnnexe_'.uniqid().'">';
      }
      if($_GET['type_champsAnnexe_'.$i] == "nombre"){
        $valueReq .= '<br/><input type="number" name="champsAnnexe_'.uniqid().'">';
      }
      if($_GET['type_champsAnnexe_'.$i] == "multi-text" && isset($_GET['nbLigne_champsAnnexe_'.$i])){
        $valueReq .= '<br/><textarea rows="'.$_GET['nbLigne_champsAnnexe_'.$i].'" cols="80" name="champsAnnexe_'.uniqid().'">'.htmlentities('</textarea>');
      }
      if($_GET['type_champsAnnexe_'.$i] == "checkbox"){
        $id_chx = uniqid();
        $valueReq .= '<input type="hidden" value="false" name="champsAnnexe_'.$id_chx.'">';
        $valueReq .= '<input type="checkbox" value="true" name="champsAnnexe_'.$id_chx.'">';
      }
      $valueReq = addslashes($valueReq);
      $valueReq .= "<br/><br/>";
      $req .= $valueReq . "');";
    }
    if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] != ""){
      if (!isset($_GET['supprimer'.$i])){
        $req .= "\n\n/*$i*/\nUPDATE \"champsAnnexe\" values set \"HTML\" = E'";

        if(isset($_GET['M:lib_champsAnnexe_'.$i])){
          $valueReq .= $_GET['M:lib_champsAnnexe_'.$i] . " :";
        }
        if($_GET['M:type_champsAnnexe_'.$i] == "text"){
          $valueReq .= '<br/><input type="text" name="champsAnnexe_'.uniqid().'">';
        }
        if($_GET['M:type_champsAnnexe_'.$i] == "nombre"){
          $valueReq .= '<br/><input type="number" name="champsAnnexe_'.uniqid().'">';
        }
        if($_GET['M:type_champsAnnexe_'.$i] == "multi-text" && isset($_GET['M:nbLigne_champsAnnexe_'.$i])){
          $valueReq .= '<br/><textarea rows="'.$_GET['M:nbLigne_champsAnnexe_'.$i].'" cols="80" name="champsAnnexe_'.uniqid().'">'.htmlentities('</textarea>');
        }
        if($_GET['M:type_champsAnnexe_'.$i] == "checkbox"){
          $id_chx = uniqid();
          $valueReq .= '<input type="hidden" value="false" name="champsAnnexe_'.$id_chx.'">';
          $valueReq .= '<input type="checkbox" value="true" name="champsAnnexe_'.$id_chx.'">';
        }

        $valueReq .= "<br/><br/>";
        $req .= $valueReq . "' WHERE \"ID\" = ". $_GET['M:ID_champsAnnexe_'.$i]. ";";
      }
      else {
        $req .= "\n\n/*$i*/\nDELETE FROM \"champsProduit\" WHERE \"cha_ID\" = ". $_GET['M:ID_champsAnnexe_'.$i]. ";";
        $req .= "\nDELETE FROM \"champsAnnexe\" WHERE \"ID\" = ". $_GET['M:ID_champsAnnexe_'.$i]. ";";
      }

    }
    $valueReq = addslashes($valueReq);
  }
  echo $req;
}
?>
</textarea>

<hr>
<center><h2>Aperçu</h2></center>

<?php
if(isset($_GET['submit2'])){
  for ($i=1; $i < $_GET['nb']+1; $i++) {
    if(isset($_GET['type_champsAnnexe_'.$i]) && $_GET['type_champsAnnexe_'.$i] != ""){
      if(isset($_GET['lib_champsAnnexe_'.$i])){
        echo $_GET['lib_champsAnnexe_'.$i] . " :";
      }
      if($_GET['type_champsAnnexe_'.$i] == "text"){
        echo '<br/><input type="text" name="champsAnnexe_'.$i.'">';
      }

      if($_GET['type_champsAnnexe_'.$i] == "nombre"){
        echo '<br/><input type="number" name="champsAnnexe_'.$i.'">';
      }

      if($_GET['type_champsAnnexe_'.$i] == "multi-text"){
        echo '<br/><textarea rows="'.$_GET['nbLigne_champsAnnexe_'.$i].'" cols="80" name="champsAnnexe_'.$i.'"></textarea>';
      }

      if($_GET['type_champsAnnexe_'.$i] == "checkbox"){
        echo '<input type="checkbox" name="champsAnnexe_'.$i.'">';
      }
    }

    if(isset($_GET['M:type_champsAnnexe_'.$i]) && $_GET['M:type_champsAnnexe_'.$i] != ""){
      if (!isset($_GET['supprimer'.$i])){
        if(isset($_GET['M:lib_champsAnnexe_'.$i])){
          echo $_GET['M:lib_champsAnnexe_'.$i] . " :";
        }
        if($_GET['M:type_champsAnnexe_'.$i] == "text"){
          echo '<br/><input type="text" name="champsAnnexe_'.$i.'">';
        }

        if($_GET['M:type_champsAnnexe_'.$i] == "nombre"){
          echo '<br/><input type="number" name="champsAnnexe_'.$i.'">';
        }

        if($_GET['M:type_champsAnnexe_'.$i] == "multi-text"){
          echo '<br/><textarea rows="'.$_GET['M:nbLigne_champsAnnexe_'.$i].'" cols="80" name="champsAnnexe_'.$i.'"></textarea>';
        }

        if($_GET['M:type_champsAnnexe_'.$i] == "checkbox"){
          echo '<input type="checkbox" name="champsAnnexe_'.$i.'">';
        }
      }
  }
    echo "<br/><br/>";
  }
}
?>
</div>
<?php
};
include_once 'presentation/pied.php';
?>
