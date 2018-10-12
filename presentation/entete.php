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
date_default_timezone_set('Europe/Paris');
echo "<!DOCTYPE html>";
echo "<html>
<head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; UTF-8\"/>
  <meta name=\"copyright\" content=\"Laurent ROBIN CNRS-Université d'Orléans 2011\">
  <meta name=\"author\" content=\"Laurent Robin (ICOA, Orléans)- Fanny Bonachera (Institut Pasteur, Lille)- Denis Charapoff (LEDSS, Grenoble)- Nicolas Foulon (LMASO, Lyon)- Philippe Jauffret (UGCN, Montpellier)- Jean-Christophe Jullian (Laboratoire de Pharmacognosie - Biocis, Châtenay-Malabry)- Aurélien Lesnard (CERMN, Caen) - Alain Montagnac (ICSN, Gif-sur-Yvette) - Jean-Marc Paris (ENSCP, Paris)- Julien Peyre (ICSN, Gif-sur-Yvette)- Nicolas Saettel (CERMN, Caen)- Kiet Tran (UGCN, Montpellier)\">";

if (isset($transfert)) {
	switch($menu) {
		case 1: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=saisie.php\">";
		break;
		case 2: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=modification.php\">";
		break;
		case 4: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=compte.php\">";
		break;
		case 6: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=creationplaque.php\">";
		break;
		case 7: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=importbio.php\">";
		break;
		case 9: {
					if($ssmenu==10) print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"8; URL=importation.php\">";
					//elseif ($ssmenu==11 and empty($erreur)) print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"8; URL=importationtare.php\">";
				}
		break;
		case 10: print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=utilisateurajout.php\">";
		break;
		case 11: {
					if ($ssmenu==10) print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=parametres.php\">";
					elseif ($ssmenu==11) print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=parametremaintenance.php\">";
					elseif ($ssmenu==12) print"<META HTTP-EQUIV=\"refresh\" CONTENT=\"4; URL=parametreproduit.php\">";
				}
		break;
	}
}
?>
  <title>Chimiothèque</title>
  <script src="ckeditor/ckeditor.js"></script>
  <style>
		/* Style the CKEditor element to look like a textfield */
		.cke_textarea_inline
		{
			padding: 10px;
			overflow: auto;
			background: #ffffff;
			height: 150px;
			border: 1px solid gray;
			-webkit-appearance: textfield;
		}
	</style>
  <style type="text/css" media="all">
         @import url(presentation/general.css);
  </style>
  <LINK REL="shortcut icon" HREF="presentation/chimiotheque.ico">
 

  <SCRIPT LANGUAGE="JavaScript">
	 <!--début du code source généré par Macromedia Dreamweaver 8	
      <!--
      function MM_swapImgRestore() { //v3.0
        var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
      }
      
      function MM_preloadImages() { //v3.0
        var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
          if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
      }
      
      function MM_findObj(n, d) { //v3.0
        var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
          d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
        if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
        for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
      }

      function MM_swapImage() { //v3.0
        var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
          if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
      }
      //-->
	  //-->fin du code source généré par Macromedia Dreamweaver 8
    </SCRIPT>


</head>
<body>
<table width="98%" height="780px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10%" colspan="2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="132"><a href="http://chimiotheque-nationale.org" target="_blank">
    <img src="images/logo_chimiotheque.png" width="132" alt="Chimioth&egrave;que nationale" border="0" /></a></td>
          <td align="center" valign="top" class=bandeau>
<?php
echo CHIMIOTHEQUE."<br/><br/>";
//appel le fichier de connexion à la base de données
require 'script/connectionb.php';
//préparation de la requète SQL
$sql = "SELECT para_nom_labo,para_logo FROM parametres";
//les résultats sont retournées dans la variable $result
$result =$dbh->query($sql);
$row=$result->fetch(PDO::FETCH_NUM);
print $row[0]."</td>\n<td align=\"right\" width=\"132\">\n<img src=\"".$row[1]."\" height=\"85\" border=\"0\" />";
unset($db);
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>