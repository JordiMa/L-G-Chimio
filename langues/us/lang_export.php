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
define ('EXPEQUIPE','Exporting selective SDF');
define ('EXPTYPE','Exporting the SDF type');
define ('SELECTEQUIP','Select a team');
define ('EQUIPE','Team :');
define ('SELECTPERS','Select a person');
define ('PERSO','User :');
define ('SELECTTYP','Select a type');
define ('TYPE','Molecules type :');
define ('LIBRE','Free');
define ('CONTRAT','Contract');
define ('BREVET','Patent');
define ('ENVOI','To send by email');
define ('MASSE','Mass available :');
define ('MG','mg');
define ('REUSSI','The sending of the email has been successful, you can close this window');
define ('ECHEC','Failed to send email');
define ('TELE','Download SDF file');
define ('MAIL','You send the file via email');
define ('MAIL1','Send file by mail to the National Chemical Library');
define ('PLAQUE','and products plate but not in bulk');
define ('SDF','SDF');
define ('CSV','CSV');
define ('BOITETO','Weighing from one or more complete chemical library boxes:');
define ('LISTETO','Weighing from a list of product numbers:');
define ('SUBMITCSV','Download CSV File');
define ('ALEATOIRE','Mix the products randomly');
define ('SEPARATEUR','Separator used for the list:');
define ('ESPACE','Space');
define ('RLIGNE','Return');
define ('DOUBLONS','Remove products already sent to EVOTEC and duplicates of structure');
define ('ALTERNATIVE','Add identical products as weighing alternative');

define ('SELECTCHAMPSEXPORT', 'Select the fields to export :');
define ('INFOBULEEXPORT','If nothing is checked, the fields exported by default will be :<br><br>- Identifier<br>- Constant number<br>- Inchi<br>- Mass<br>- Plate number<br>- At evotec (yes/no)<br>- Purity<br>- Method of measuring purity<br>- Origin of the substance');
define ('SELECT_TYPE', 'Type');
define ('SELECT_EQUIPE', 'Team');
define ('SELECT_RESPONSABLE', 'Manager');
define ('SELECT_CHIMISTE', 'Chemist');
define ('SELECT_COULEUR', 'Color');
define ('SELECT_PURETE', 'Purity');
define ('SELECT_PURIFICATION', 'Purification');
define ('SELECT_MASSE', 'Mass');
define ('SELECT_ASPECT', 'Aspect');
define ('SELECT_DATESAISIE', 'Entry date');
define ('SELECT_REFLABO', 'Reference lab notebook');
define ('SELECT_OBSERVATION', 'Observation');
define ('SELECT_IDENTIFICATEUR', 'Identifier');
define ('SELECT_NUMCONSTANT', 'Constant number');
define ('SELECT_INCHI', 'Inchi');
define ('SELECT_POINTFUSION', 'Fusion point');
define ('SELECT_POINTEBULLITION', 'Boiling point');
define ('SELECT_METHODEMESSUREPURETE', 'Method of measuring purity');
define ('SELECT_NUMCN', 'CN number');
define ('SELECT_ORIGINESUBSTANCE', 'Origin of the substance');
define ('SELECT_QRCODE', 'QR code');
define ('SELECT_CONTROLEPURETE', 'Controlled purity (yes/no)');
define ('SELECT_DATECONTROLEPURETE', 'Purity check date');
define ('SELECT_CONTROLESTRUCTURE', 'Controlled structure (yes/no)');
define ('SELECT_NUMPLAQUE', 'Plate number');
define ('SELECT_EVOTEC', 'At evotec (yes/no)');
define ('SELECT_CHAMPSSOUHAITE','Select the desired fields');
define ('SELECTCRITERESEXPORT','Select your selection criteria :');
define ('SELECT_UTILISATEUR', 'User');
define ('SELECT_TYPECONTRACT', 'Type of contract');
define ('SELECT_MASSEDISPO', 'Mass available');
define ('SELECT_PLAQUENOVRAC', 'Products in plate but not in bulk');
define ('SELECT_CHEZEVOTEC', 'Only products at Evotec');
define ('SELECT_PASCHEZEVOTEC', 'Only products that are not at Evotec');
define ('SELECT_LESDEUX', 'All');
define ('SELECT_SOLUBLE', 'Soluble products only (Evotec)');
define ('SELECT_INSOLUBLE', 'Insoluble products only(Evotec)');
define ('SELECT_LISTEID', 'From a list of identifiers');
define ('SELECTEQUIPEEXPORT', 'Select a team :');
define ('SELECTUTILISATEUREXPORT', 'Select a user :');
define ('SELECTCONTRACTEXPORT', 'Select a type of contract :');
define ('SELECTLISTEIDEXPORT', 'List of identifiers :');
define ('MOTDEPASSE', 'Password');
define ('ZERORESULTAT', 'No result found');
define ('UNRESULTAT', 'One result found');
define ('XRESULTAT', 'results found');
define ('LISTERESULTAT', 'Local identification number of the compounds :');
define ('SELECTRESPONSABLEEXPORT','Select a manager :');
define ('SELECTCHIMISTEEXPORT','Select a chimist :');
define ('SAISIEIDPRODUIT', 'Please enter the local product identifier :');
define ('RECHERCHER', 'Search');
define ('SAVEOK', 'Save done');
define ('SAVEECHEC', 'Save failed');
define ('CONFIRMSAVE', 'Do you want to save the changes ?');
define ('TOUT', 'All');
define ('CHAMP', 'field');
define ('SELECTIONNE','selected');
?>
