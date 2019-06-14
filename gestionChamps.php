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

  if (isset($_POST['champs'])){
    $sql="UPDATE parametres SET para_champs = '".$_POST['champs']."' WHERE para_id_parametre = 1";
    $update=$dbh->exec($sql);
  }

  $sql_para="SELECT para_champs FROM parametres WHERE para_id_parametre = 1";
  $result_para = $dbh->query($sql_para);
  $rowPara=$result_para->fetch(PDO::FETCH_NUM);

?>


 <div class="language-selector" style="text-align: center;">
    <a id="fr-FR">Français</a> - <a id="en-US">English</a>
 </div>

  <div id="fb-editor"></div>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/form-builder.min.js"></script>

  <script>
    jQuery(function($) {
      var options = {
          i18n: {
            locale: 'fr-FR'
          },
          disableFields: [
            'file',
            'hidden',
            'button'
          ],
          disabledAttrs: [
            'className',
            'access',
            'name'
          ],
          onSave: function(formData) {
            document.getElementById('champs').value = formBuilder.actions.getData('json', true);
            document.getElementById("formChamps").submit();
          }
        },
        $fbTemplate = $(document.getElementById('fb-editor'));
        formBuilder = $fbTemplate.formBuilder(options);

        var formData = <?php echo $rowPara[0] ?>;
        setTimeout(function(){ formBuilder.actions.setData(formData); }, 500);

      $('.language-selector a').click(function() {
        var lang = this.id;
        formBuilder.actions.setLang(lang)
      });
    });
  </script>

  <form id="formChamps" method="post">
    <input id="champs" type="hidden" name="champs" value="">
  </form>

<?php

};
include_once 'presentation/pied.php';
?>
