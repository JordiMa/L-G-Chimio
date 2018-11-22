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
define('DEUXPOINTS',' :');
define('CONFIG','Note on configuration :');
define('MASS', '* Mass of product available : ');
define('TYPE','* Structure Type : ');
define('CHARG', 'Load structure');
define('CHARGESTRUC', 'Find a mol format to be loaded');
define('DESSINSTRUC','You must draw a molecule');
define('MASSABS',MASS.' should be informed');
define('SELECCOULEUR','Select a color');
define('COULEUR','Product Color :');
define('NBPILLULIER','Pill number  :');
define('PURIFICATION','* Type of purification :');
define('SELECPURIFICATION','Select a purification');
define('ASPECT','* Aspect :');
define('SELECASPECT','Select the aspect');
define('NOM','* Name in IUPAC nomenclature (English) :');
define('REFERENCECAHIER','Laboratory notebook or thesis reference :');
define('CONTRAT','Under contract');
define('BREVET','Patented');
define('LIBRE','Free');
define('CONTRATDESC','Description of contract :');
define('DUREE','Contract Period :');
define('AN','Year(s)');
define('NUMBREVET','Patent number :');
define('PRECAUTION','Precautions to take');
define('SOLVANTS','* Solvent solubilization :');
define('MODOP','Procedure :');
define('ANALYSE','Analysis');
define('ANAELEM','Elemental analysis :');
define('PFUSION','Melting point :');
define('PEB','boiling point');
define('PEBULITION','boiling point :');
define('PRESSIONPB','With pressure of :');
define('ALPHA','α <sub>D</sub>');
define('ALPHATEMP','Temperature :');
define('ALPHACONC','Concentration :');
define('ALPHAD','measure');
define('ALPHASOLVANT','Solvent:');
define('ALPHASELECSOLV','Select the solvent :');
define('IR','Infrared spectrometry');
define('DONNEESIR','IR :');
define('CHARGEIR','Fichier du spectre IR :');
define('UV','spectrométrie UV');
define('DONNEESUV','UV :');
define('CHARGEUV','UV spectrum file :');
define('ACTIVITE','Pharmacological activity');
define('RECEPTEUR','Receiver');
define('SM','Mass Spectrometry');
define('SM1','SM :');
define('SMTYPE','Ionization source :');
define('SELECTSMTYPE','Select the source');
define('CHARGESM','SM spectrum file :');
define('HSM','High resolution mass spectrometry');
define('HSM1','HRMS :');
define('HSMTYPE','Ionization source :');
define('SELECTHSMTYPE','Select the source');
define('CHARGEHSM','HRMS spectrum file :');
define('CCM','CCM :');
define('CCMRF','Rf :');
define('CCMSOLVANT','Solvents used :');
define('RMNH','RMN <SUP>1</SUP>H');
define('SPECTRORMN','Spectrometry ');
define('DONNERRMN','Data ');
define('CHARGERRMN','Spectrum file ');
define('RMNC','RMN <SUP>13</SUP>C');
define('BIBLIO','Bibliography');
define('PUB','Publication');
define('DOI','DOI number :');
define('HAL','HAL number :');
define('CAS','CAS Reference :');
define('DEG','°C');
define('NMOL','nmol');
define('MG','mg');
define('MOL','mol. L<sup>-1</sup>');
define('ATM','atm');
define('RENSEIGNE','should be informed');
define('CHAMP','The field');
define('ERREURMASSE', 'must contain the mass value');
define('RECRISTALISE','Your product may not have been prurifié by recristalisation or precipitation and the form of oil or liquid');
define('DISTILATION','Your product can not be purified by distillation and any other solid form');
define('ERREURCHARGEMENT','An error occurred downloading this file ');
define('SAUVDONNE','Saved Data');
define('GOMME','gum');
define('HUILE','oil');
define('LIQUIDE','liquid');
define('MOUSSE','foam');
define('SOLIDE','solid');
define('AUCUNE','no');
define('COLONNE','column');
define('DISTILLATION','distillation');
define('EXTRACTION','extraction');
define('FILTRATION','filtration');
define('FILTRATIONCEL','filtration on Celite');
define('HPLC','HPLC');
define('PRECIPITATION','Precipitation');
define('RECRISTALLISATION','recrystallization');
define('RESINE','ion exchange resins');
define('ACPI','APCI');
define('APPI','APPI');
define('CI','CI');
define('DCI','DCI');
define('EI','EI');
define('ESI','ESI');
define('MAL','MALDI');
define('FAB','FAB');
define('ACETATETYLE','ethyl acetate');
define('ACETONE','acetone');
define('ACETONITRILE','acetonitrile');
define('BENZENE','benzene');
define('CHOLOROFORME','chloroform');
define('DICHLO','dichloromethane');
define('DMF','DMF');
define('DMSO','DMSO');
define('EAU','eau');
define('ETHANOL','ethanol');
define('ETHERPET','petroleum ether');
define('ETHERETHYL','ethyl ether');
define('INCONNU','unknown');
define('INSOLUBLE','unsolvable');
define('METHANOL','methanol');
define('PYRIDINE','pyridine');
define('THF','THF');
define('TOLUENE','toluene');
define('FRIGO','conservation in the refrigerator');
define('HYGROSCOPIQUE','hygroscopic');
define('INFLAMABLE','flammable');
define('INSTABLE','unstable light');
define('IRRITANT','irritant');
define('LACRYMOGENE','lachrymatory');
define('HOTTE','manipulation required under hood');
define('TOXIQUE','toxic product');
define('DEGRADE','degrades');
define('SENSIBLE','sensitive to traces of acid');
define('ELECTROSTAT','electrostatic solid');
define('ARGON','store under argon');
define('VOLATILE','volatile');
define('SUJET','chemical library');
define('BONJOUR','Hello');
define('ENTREE','entered the product in the chemical library under references:');
define('LE','The');
define('MRMME','Mr or Mrs');
define('CORDIALEMENT','Best regards.');
define('MESSAUTO','Automatic email sent by Lg-Chimio from the address:');
define('PLUSRECEPTION','If you no longer wish to receive emails, you can disable this feature in your Chemical Library account.');
define('EXFICHIER','View existing file');
define('OBSERVATION','Comments');
define('EQUIPE','* Team --- manager:');
define('SELECTEQUIPE','Select a pair team --- manager');
define('EQUIPEABS','The field team must be informed');
define('OBLIGATOIRE','* : required field');
define('INCOL','Colourless');
define('INCON','Unknown');
define('STRUC','Your structure has not been processed please make sure it is well developed and shows no errors');
define('SYNTHESE','synthesis');
define('HEMISYNTHESE','hemi-synthesis');
define('NATURELLE','natural');
define('INCONNUE','unknown');
define('ORIGINEMOL','* Molecule origin');
define('SELECTORIGINEMOL','Select an origin');
define('ORIGABS','The origin of the molecule is blank!');
define('PURETESUB','Purity of the substance');
define('PURETE','Purity measured :');
define('POURCENT','%');
define('METHOPURETE','Method for measuring the purity :');
define('RECOMMANDATION','Recommendations for drawing the structures');
define('DESSINSTRUC1','The structure you drew contains an abbreviation or a reaction arrow, see the recommendations for their drawing');
define('INTERMEDIAIRE','intermediate of synthesis');
define('FINALE','final molecule');
define('ETAPMOL','* The step of synthesis of the molecule :');
define('SELECTETAPMOL','Select a step');
define('ETAPGABS','The step of synthesis of the molecule is blank!');
define('QRCODE','Barcode/qrcode :');
define('QRCODE2','Barcode/qrcode :');
if (!defined ('MESSAGEERREUR')) define('MESSAGEERREUR','<br>Error message:<br>');
define('ERREUR_STRUCTURE','Failed insertion of the molecule in the database');
define('ERREUR_PRODUIT','Failed to change the product in the database');
define('ERREUR_SOLUBILITE','Failed to change solubility of the table in the database');
define('ERREUR_MODIFICATIONS','Failed followed modification changes the table item in database');
define('ERREUR_PRECAUTION','Failed to change the list of precaution in the database');
define('ERREUR_SUP_PRECAUTION','Failed to delete the list of precaution in the database');
define('ERREUR_FICHIER','Failure of the insertion of a analysis file in the database');
define('ERREURATTENTION',' Errors occurred while modifying the record');
define('RETIRE','Remove this file');
?>
