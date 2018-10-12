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
define ('TITREIMPORT','Importing the numbering of the National Chemical Library for bulk');
define ('FICHIERIMPORTCSV','CSV file:');
define ('AIDECN','The file must be a CSV or unique local number, the number one National and separator ; .<br>example :<br>ICOA-FST-L-01A02;CN045955V<br>51545765;CN048942V<br>The file size must not exceed: '.ini_get('upload_max_filesize'));
define ('ERREFILECSV','The file is not the type CSV or it exceeds the maximum size of '.ini_get('upload_max_filesize'));
define ('ERRORCSV','Tare : ');
define ('ERRORCSV1',' for the product : ');
define ('ERRORCSV2',' has not been entered into the database.');
define ('TRANSREUSSI','CSV file that you imported was prepared and cut into one or more files for easy import.');
define ('TRANSREUSSI1','CSV file that you imported was transferred successfully in the database.');
define ('IMPORTCN','CN numbers');
define ('IMPORTTARE','tare pill');
define ('TITRETARE','Importing the tare of pill containing the bulk');
define ('AIDETARE','The file must be a CSV with the local number, the mass of the pill in milligrams and as a separator ; .<br>example :<br>ICOA-FST-L-01A02;1,2356<br>ICOA-FST-L-01A03;1,6589<br>The file size must not exceed: '.ini_get('upload_max_filesize'));
define ('LEFICHIER',' file');
define ('TITREIMPORTLISTE','Importing sectioned into several files from the CSV file data.');
define ('FICHERNB','There is/are ');
define ('FICHERNB1',' file(s) on the server to be loaded into the database');
define ('FILETRANSFERT','Transfer completed');
define ('CONSEIL','Delete the files via the "Settings" then "Maintenance" menu and click on "empty directory" and start again importing the CSV file. If the problem persists check the conformity of the CSV file.');
define ('FILEVIDE','The downloaded file contains no data');
define ('IMPORTEVO','Tag Evotec');
define ('TITREEVO','Import of permanent numbers (8 digits) of substances sent to EVOTEC');
define ('AIDEVO','The file must be of type CSV with the permanent number 8 digits, the mass put in the pillbox in mg and as separator one ; .<br>example :<br>49728921;1,2<br>63034206;1<br>The file size must not exceed: '.ini_get('upload_max_filesize'));
define ('ERROREVOCSV','The reference : ');
define ('ERROREVOCSV1',' with a mass of : ');
define ('ERROREVOCSV2','mg has not been entered in the database.');
define ('ERROREVOCSV3','The reference : ');
define ('ERROREVOCSV4',' with a mass of : ');
define ('ERROREVOCSV5',' mg has not been entered in the database because it is a duplicate structure of references:');
define ('ERROREVOCSV6',' mg has not been entered in the database because the reference does not exist');
define ('DEFALK','Defal the stock with the mass for each molecule');
?>