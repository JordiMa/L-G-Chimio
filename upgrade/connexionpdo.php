<?php
/*
Copyright Laurent ROBIN CNRS - Université d'Orléans 2011 
Distributeur : UGCN - http://chimiotheque-nationale.enscm.fr

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
?>
<tr>
    <td bgcolor="#FFFFFF" align="center" valign="middle" width="100%" colspan="2">
<h3>Mise à jour de L-g-<I>Chimio</I> de la version 1.1 à la 1.3.2 vers la version 1.3.2.1</h3>
<p>( Réservé à l'administrateur )</p>
<br><br><br><br>	
<table width="150" border="0" cellspacing="0" cellpadding="0" >
  <tr align="center">
    <td class=cellulebleu>
<?php
echo LOGIN;
?>
    </td>
  </tr>
  <tr>
    <td class=celluleblanche>
	<script language="javascript" src="../md5.js"></script>
	<script language="javascript">
	<!--
	  function CResponsemd() {
		str = document.conec.password_chimiste.value;
		document.conec.reponse.value = MD5(str,document.conec.name_chimiste.value);
		document.conec.password_chimiste.value = "";
		conec.submit();
	  }
	// -->
	</script>

<?php
if (isset ($message)) print"<p align=\"center\" class=messagederreur>".constant($message)."</p>";
$formulaire=new formulaire ("conec","sessionpdo.php","POST",true);
$formulaire->affiche_formulaire();
$formulaire->ajout_text (20, "", 20,"name_chimiste",NAMEPASS,"","");
$formulaire->ajout_password (20, "", 50,"password_chimiste",PASSWORD,"");
$formulaire->ajout_cache ("","reponse");
print"<p align=\"center\">";
$formulaire->ajout_button (LOGIN,"","button","onClick=\"CResponsemd(); return false;\"");
print"</p>";
$formulaire->fin();
?>
    </td>
  </tr>
</table>