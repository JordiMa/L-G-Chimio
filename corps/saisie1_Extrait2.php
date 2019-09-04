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

.button {
  display: inline-block;
  border-radius: 4px;
  background-color: #3399cc;
  border: none;
  color: #FFFFFF;
  text-align: center;
  padding: 15px;
  width: 50%;
  transition: all 0.5s;
  cursor: pointer;
  font-size: medium;
}

.button:disabled {
  background-color: darkgrey;
}

.button:not(:disabled) span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button:not(:disabled) span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover:not(:disabled) span {
  padding-right: 25px;
}

.button:hover:not(:disabled) span:after {
  opacity: 1;
  right: 0;
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

/*print"<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet1.gif\"><a class=\"onglet\" href=\"saisie_Extrait.php\">Extrait</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Echantillon.php\">Échantillon</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Condition.php\">Condition</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Specimen.php\">Specimen</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Taxonomie.php\">Taxonomie</a></td>
  <td width=\"82\" height=\"23\" align=\"center\" valign=\"middle\" background=\"images/onglet.gif\"><a class=\"onglet\" href=\"saisie_Expedition.php\">Expedition</a></td>
  </tr>
  </table><br/>";*/
?>
<form action="" method="GET" style="text-align: center; margin: 10px; padding: 10px; border-style: ridge;">

Je veux saisir un(e) :
<select name="choix" onchange="submit()" required>
  <option value=""></option>
  <option value="Extrait" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Extrait") echo "selected";?> >Extrait</option>
  <option value="Echantillon" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Echantillon") echo "selected";?> >Echantillon</option>
  <option value="Condition" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Condition") echo "selected";?> >Condition</option>
  <option value="Specimen" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Specimen") echo "selected";?> >Specimen</option>
  <option value="Autorisation" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Autorisation") echo "selected";?> >Autorisation</option>
  <option value="Taxonomie" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Taxonomie") echo "selected";?> >Taxonomie</option>
  <option value="Expedition" <?php if(isset($_GET['choix']) && $_GET['choix'] == "Expedition") echo "selected";?> >Mission de récolte</option>
</select>
<?php
  $aide_Global = "
  <div style=\'text-align: justify;\'>
    <p>
      <B style=\'color: indianred;\'>un spécimen, dont on décrit la taxonomie et la mission de récolte dont il provient, peut donner plusieurs échantillons selon la partie d\'organisme décrite, qui selon les conditions d\'extraction donneront plusieurs extraits.<br/></B>

      <li>Mission de récolte :</li>
      Représente le pays ainsi que votre contact durant la récolte.

      <br/><br/>

      <li>Taxonomie :</li>
      Description et identification de l\'organisme vivant.

      <br/><br/>

      <li>Spécimen :</li>
      Date et lieu de la récolte, observation et identification de l\'organisme récolté.<br/>
      <B style=\'color: indianred;\'>Attention : La taxonomie et la mission de récolte devront être renseigné avant.</B>

      <br/><br/>

      <li>Condition :</li>
      Condition de culture de l\'échantillon.

      <br/><br/>

      <li>Échantillon :</li>
      Description de l\'échantillon, lieu de stockage, quantité disponible.<br/>
      <B style=\'color: indianred;\'>Attention :le spécimen devra être renseigné avant.</B>

      <br/><br/>

      <li>Extrait :</li>
      Parti de l\'échantillon. description de l\'extrait, solvant utiliser, type d\'extraction, stockage.<br/>
      Issue de l\'extraction d\'un echantillon<br/>
      <B style=\'color: indianred;\'>Attention : L\'échantillon devra être renseigné avant.</B>

    </p>
  </div>
  ";
  $aide_Global = str_replace(array("\r\n","\n"), '', $aide_Global);

  $aide_Echantillon = "
  <div style=\'text-align: justify;\'>
    <p>
      Description de l\'échantillon, lieu de stockage, quantité disponible.<br/>
      <br/>
      <B style=\'color: indianred;\'>Attention : La condition et le spécimen devront être renseigné avant.</B>
    </p>
  </div>
  ";
  $aide_Echantillon = str_replace(array("\r\n","\n"), '', $aide_Echantillon);

  $aide_Condition = "
  <div style=\'text-align: justify;\'>
    <p>
      Condition de culture de l\'échantillon.
    </p>
  </div>
  ";
  $aide_Condition = str_replace(array("\r\n","\n"), '', $aide_Condition);

  $aide_Specimen = "
  <div style=\'text-align: justify;\'>
    <p>
      Date et lieu de la récolte, observation et identification de l\'organisme récolté.<br/>
      <br/>
      <B style=\'color: indianred;\'>Attention : La taxonomie et la mission de récolte devront être renseigné avant.</B>
    </p>
  </div>
  ";
  $aide_Specimen = str_replace(array("\r\n","\n"), '', $aide_Specimen);

  $aide_autorisation = "
  <div style=\'text-align: justify;\'>
    <p>
      rentrer le n° autorisation et le type (APA, partenariat, ...)
    </p>
  </div>
  ";
  $aide_autorisation = str_replace(array("\r\n","\n"), '', $aide_autorisation);

  $aide_Taxonomie = "
  <div style=\'text-align: justify;\'>
    <p>
      Description et identification de l\'organisme vivant.
    </p>
  </div>
  ";
  $aide_Taxonomie = str_replace(array("\r\n","\n"), '', $aide_Taxonomie);

  $aide_Expedition = "
  <div style=\'text-align: justify;\'>
    <p>
      Représente le pays ainsi que votre contact durant la récolte.
    </p>
  </div>
  ";
  $aide_Expedition = str_replace(array("\r\n","\n"), '', $aide_Expedition);
?>
<a href="#" onmouseover="ddrivetip('<?php echo $aide_Global; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

<?php if (isset($_GET['choix']) && $_GET['choix'] == "Extrait"): ?>
  <br/><br/>
  L'echantillon est-il existant ?
  <select name="echantillon_existe" onchange="submit()" required>
    <option value=""></option>
    <option value="Oui" <?php if(isset($_GET['echantillon_existe']) && $_GET['echantillon_existe'] == "Oui") echo "selected";?> >Oui</option>
    <option value="Non" <?php if(isset($_GET['echantillon_existe']) && $_GET['echantillon_existe'] == "Non") echo "selected";?> >Non</option>
  </select>
  <a href="recherche_Echantillon.php" target="_blank">(Voir les existants)</a>
  <a href="#" onmouseover="ddrivetip('<?php echo $aide_Echantillon; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>


  <?php if (isset($_GET['echantillon_existe']) && $_GET['echantillon_existe'] == "Non"): ?>
    <br/><br/>
    Voulez-vous créer une nouvelle condition ?
    <select name="condition_existe" onchange="submit()" required>
      <option value=""></option>
      <option value="Oui" <?php if(isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Oui") echo "selected";?> >Non</option>
      <option value="Non" <?php if(isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Non") echo "selected";?> >Oui</option>
    </select>
    <a href="recherche_Condition.php" target="_blank">(Voir les existants)</a>
    <a href="#" onmouseover="ddrivetip('<?php echo $aide_Condition; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

    <br/><br/>
    Le specimen est-il existant ?
    <select name="specimen_existe" onchange="submit()" required>
      <option value=""></option>
      <option value="Oui" <?php if(isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Oui") echo "selected";?> >Oui</option>
      <option value="Non" <?php if(isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non") echo "selected";?> >Non</option>
    </select>
    <a href="recherche_Specimen.php" target="_blank">(Voir les existants)</a>
    <a href="#" onmouseover="ddrivetip('<?php echo $aide_Specimen; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>


    <?php if (isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non"): ?>
      <br/><br/>
      Une autorisation est-elle nécessaire ?
      <select name="autorisation_necessaire" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

      <?php if (isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui"): ?>
        <br/><br/>
        L'autorisation est-elle existante ?
        <select name="autorisation_existe" onchange="submit()" required>
          <option value=""></option>
          <option value="Oui" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Oui") echo "selected";?> >Oui</option>
          <option value="Non" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non") echo "selected";?> >Non</option>
        </select>
        <a href="recherche_autorisation.php" target="_blank">(Voir les existants)</a>
        <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
      <?php endif; ?>

      <br/><br/>
      La taxonomie est-elle existante ?
      <select name="taxonomie_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Taxonomie.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Taxonomie; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

      <br/><br/>
      La mission de récolte est-elle existante ?
      <select name="expedition_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Expedition.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Expedition; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['choix']) && $_GET['choix'] == "Echantillon"): ?>
    <br/><br/>
    Voulez-vous créer une nouvelle condition ?
    <select name="condition_existe" onchange="submit()" required>
      <option value=""></option>
      <option value="Oui" <?php if(isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Oui") echo "selected";?> >Non</option>
      <option value="Non" <?php if(isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Non") echo "selected";?> >Oui</option>
    </select>
    <a href="recherche_Condition.php" target="_blank">(Voir les existants)</a>
    <a href="#" onmouseover="ddrivetip('<?php echo $aide_Condition; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

    <br/><br/>
    Le specimen est-il existant ?
    <select name="specimen_existe" onchange="submit()" required>
      <option value=""></option>
      <option value="Oui" <?php if(isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Oui") echo "selected";?> >Oui</option>
      <option value="Non" <?php if(isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non") echo "selected";?> >Non</option>
    </select>
    <a href="recherche_Specimen.php" target="_blank">(Voir les existants)</a>
    <a href="#" onmouseover="ddrivetip('<?php echo $aide_Specimen; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

    <?php if (isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non"): ?>
      <br/><br/>
      Une autorisation est-elle nécessaire ?
      <select name="autorisation_necessaire" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

      <?php if (isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui"): ?>
        <br/><br/>
        L'autorisation est-elle existante ?
        <select name="autorisation_existe" onchange="submit()" required>
          <option value=""></option>
          <option value="Oui" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Oui") echo "selected";?> >Oui</option>
          <option value="Non" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non") echo "selected";?> >Non</option>
        </select>
        <a href="recherche_autorisation.php" target="_blank">(Voir les existants)</a>
        <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
      <?php endif; ?>

      <br/><br/>
      La taxonomie est-elle existante ?
      <select name="taxonomie_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Taxonomie.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Taxonomie; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

      <br/><br/>
      La mission de récolte est-elle existante ?
      <select name="expedition_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Expedition.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Expedition; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
  <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['choix']) && $_GET['choix'] == "Specimen"): ?>
    <br/><br/>
    Une autorisation est-elle nécessaire ?
    <select name="autorisation_necessaire" onchange="submit()" required>
      <option value=""></option>
      <option value="Oui" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui") echo "selected";?> >Oui</option>
      <option value="Non" <?php if(isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Non") echo "selected";?> >Non</option>
    </select>
    <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

    <?php if (isset($_GET['autorisation_necessaire']) && $_GET['autorisation_necessaire'] == "Oui"): ?>
      <br/><br/>
      L'autorisation est-elle existante ?
      <select name="autorisation_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_autorisation.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_autorisation; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
    <?php endif; ?>

      <br/><br/>
      La taxonomie est-elle existante ?
      <select name="taxonomie_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Taxonomie.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Taxonomie; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>

      <br/><br/>
      La mission de récolte est-elle existante ?
      <select name="expedition_existe" onchange="submit()" required>
        <option value=""></option>
        <option value="Oui" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Oui") echo "selected";?> >Oui</option>
        <option value="Non" <?php if(isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non") echo "selected";?> >Non</option>
      </select>
      <a href="recherche_Expedition.php" target="_blank">(Voir les existants)</a>
      <a href="#" onmouseover="ddrivetip('<?php echo $aide_Expedition; ?>');"  onmouseout="hideddrivetip()"><img style="position: absolute;" border="0" src="images/aide.gif"></a>
<?php endif; ?>
<br/><br/>
<input type="submit" name="btn_submit">
</form>

<?php
if (isset($_GET['btn_submit'])){
  $array = array();
  switch ($_GET['choix']){
  	case 'Extrait':
  		$array[] = 'Extrait';
  		if(isset($_GET['echantillon_existe']) && $_GET['echantillon_existe'] == "Non"){
        $array[] = 'Echantillon';
  			if(isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Non"){
  				$array[] = 'Condition';
  			}
  			if (isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non"){
  				$array[] = 'Specimen';
          if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non"){
            $array[] = 'Autorisation';
          }
  				if (isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non"){
  					$array[] = 'Taxonomie';
  				}
  				if (isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non"){
  					$array[] = 'Mission de récolte';
  				}
  			}
  		}
  		break;

  	case 'Echantillon':
  		$array[] = 'Echantillon';
  		if (isset($_GET['condition_existe']) && $_GET['condition_existe'] == "Non"){
  			$array[] = 'Condition';
  		}
  		if (isset($_GET['specimen_existe']) && $_GET['specimen_existe'] == "Non"){
  			$array[] = 'Specimen';
        if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non"){
          $array[] = 'Autorisation';
        }
  			if (isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non"){
  				$array[] = 'Taxonomie';
  			}
  			if (isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non"){
  				$array[] = 'Mission de récolte';
  			}
  		}
  		break;

  	case 'Condition':
  		$array[] = 'Condition';
  		break;

  	case 'Specimen':
  		$array[] = 'Specimen';
      if(isset($_GET['autorisation_existe']) && $_GET['autorisation_existe'] == "Non"){
        $array[] = 'Autorisation';
      }
  		if (isset($_GET['taxonomie_existe']) && $_GET['taxonomie_existe'] == "Non"){
  			$array[] = 'Taxonomie';
  		}
  		if (isset($_GET['expedition_existe']) && $_GET['expedition_existe'] == "Non"){
  			$array[] = 'Mission de récolte';
  		}
  		break;

    case 'Autorisation':
    	$array[] = 'Autorisation';
    	break;

  	case 'Taxonomie':
  		$array[] = 'Taxonomie';
  		break;

  	case 'Expedition':
  		$array[] = 'Mission de récolte';
  		break;
  }

  $array = array_reverse($array);
  $nb = count($array);
  foreach ($array as $key => $value) {
    echo '<center>';
    echo '<button class="button" id="'.($key).'" name="btn_'.$value.'" onclick="saisie(\''.$value.'\','.$key.', '.$nb.')" disabled><span>Etape ' .($key+1). ': ' . $value.'</span></button>';
    echo '</center>';
    echo "<br/>";
  }
}
?>
<center><h2 id="fini" style="display: none;">La procédure de saisie est finie</h2></center>

<br/><br/><br/>
<center><img src=".\images\extra2.png" alt="" width="80%"></center>
<br/><br/><br/>

<script>
document.getElementById(0).disabled = false;
  function saisie(nom, num, max) {

    if (nom == "Mission de récolte") {
      nom = "Expedition";
    }

    var popup = window.open("saisie_"+nom+".php", "", "fullscreen");
     if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight)
     {
       popup.moveTo(0,0);
       popup.resizeTo(screen.availWidth, screen.availHeight);
     }

    for (var i = 0; i < max; i++) {
      document.getElementById(i).disabled = true;
    }
    if (num+1<max) {
      document.getElementById(num+1).disabled = false;
    }
    else {
      $("#fini").css('display', '');
    }
  }
</script>
