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
define ('TITREIMPORT','Importation de la numérotation de la Chimiothèque Nationale pour les produits en vrac');
define ('FICHIERIMPORTCSV','Fichier de type CSV :');
define ('AIDECN','Le fichier doit être de type CSV avec le numéro local ou unique, le numéro National et comme séparateur un ; .<br>exemple :<br>ICOA-FST-L-01A02;CN045955V<br>51545765;CN048942V<br>La taille du fichier ne doit pas excéder : '.ini_get('upload_max_filesize'));
define ('ERREFILECSV','Le fichier n\'est pas du type CSV ou celui-ci dépasse la taille maximum de '.ini_get('upload_max_filesize'));
define ('ERRORCSV','La tare : ');
define ('ERRORCSV1',' pour le produit : ');
define ('ERRORCSV2',' n\'a pas été entré dans la base de données.');
define ('TRANSREUSSI','Le fichier CSV que vous avez importé a été préparé et coupé en un ou plusieurs fichiers pour faciliter l\'importation');
define ('TRANSREUSSI1','Le fichier CSV que vous avez importé a été transféré avec succès dans la base de données.');
define ('IMPORTCN','Numéros CN');
define ('IMPORTTARE','Tare piluliers');
define ('TITRETARE','Importation de la tare des piluliers contenant les produits en vrac');
define ('AIDETARE','Le fichier doit être de type CSV avec le numéro local, la masse du pilulier en mg et comme séparateur un ; .<br>exemple :<br>ICOA-FST-L-01A02;1,2356<br>ICOA-FST-L-01A03;1,6589<br>La taille du fichier ne doit pas excéder : '.ini_get('upload_max_filesize'));
define ('LEFICHIER',' un fichier');
define ('TITREIMPORTLISTE','Importation des données tronçonnées en plusieurs fichiers à partir du fichier CSV');
define ('FICHERNB','Il y a ');
define ('FICHERNB1',' fichier(s) sur le serveur à charger dans la base de données');
define ('FILETRANSFERT','Transfert effectué');
define ('CONSEIL','Supprimez les fichiers par l\'intermédiaire du menu "Paramètres" puis "Maintenance" et cliquez sur "videz le répertoire" et recommencez l\'importation du fichier CSV. Si le problème persiste vérifiez la conformité du fichier CSV.');
define ('FILEVIDE','Le fichier téléchargé ne contient pas de données');
define ('IMPORTEVO','Tag Evotec');
define ('TITREEVO','Importation des numéros permanents (8 chiffres) des substances envoyées chez EVOTEC');
define ('AIDEVO','Le fichier doit être de type CSV avec le numéro permanent à 8 chiffres, la masse mis dans le pilulier en mg et comme séparateur un ; .<br>exemple :<br>49728921;1,2<br>63034206;1<br>La taille du fichier ne doit pas excéder : '.ini_get('upload_max_filesize'));
define ('ERROREVOCSV','La référence : ');
define ('ERROREVOCSV1',' avec une masse de : ');
define ('ERROREVOCSV2',' mg n\'a pas été entrée dans la base de données.');
define ('ERROREVOCSV3','La référence : ');
define ('ERROREVOCSV4',' avec une masse de : ');
define ('ERROREVOCSV5',' mg n\'a pas été entrée dans la base de données car c\'est un doublon de structure des références : ');
define ('ERROREVOCSV6',' mg n\'a pas été entrée dans la base de données car la référence n\'existe pas');
define ('DEFALK','Défalquer le stock avec la masse prélévée pour chaque molécule');
define ('SDFRDF','Fichier de type SDF ou RDF :');
define ('CORRECTIONSTRUCTURE', 'Correction des structures :');
define ('PENDANTIMPORT','Pendant l\'importation');
define ('APRESIMPORT','Après l\'importation');
define ('TITREIMPORTSDFRDF','Importation de fichier SDF et RDF');
define ('EXTENSIONFICHIER','Extension du fichier');
define ('MOLECULEATRAITER','MOLÉCULES À TRAITER');

?>
