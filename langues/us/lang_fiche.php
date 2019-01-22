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
define('MASS', 'Mass of product available : ');
define('NOM','Name :');
define('DATE','Date of entry :');
define('COULEUR','Product Color :');
define('MASSEMOL','Molecular mass :');
define('GMOL','g.mol<sup>-1</sup>');
define('MG','mg');
define('PURIFICATION','Type of purification :');
define('REFE','Reference laboratory notebook :');
define('MASSE','Mass of product available :');
define('FORMULEBRUTE','Formula :');
define('ASPECT','Product aspect :');
define('MODOP','Procedure :');
define('TYPE','Product Type :');
define('ANALYSETHEO','Basic theoretical analysis :');
define('ANALYSEEXP','Basic experimental analysis :');
define('OBSERVATIONS','Comments :');
define('PF','Melting point :');
define('PEB','boiling point :');
define('PRESSION','pressure ');
define('ATM','atm');
define('PRECAUTION','caution(s) :');
define('DOI','DOI number :');
define('HAL','HAL number :');
define('CAS','CAS Reference :');
define('SOLVANT','Solvent(s) :');
define('STRUCTURE','Structure');
define('ANALYSE','Analyzes');
define('ANABIO','results');
define('UV','UV spectrometry :');
define('FICHIERTEL','Download file');
define('SM','Mass Spectrometry :');
define('SMTYPE','Ionization source :');
define('HRMS','High resolution mass spectrometry :');
define('RMNC','RMN <SUP>13</SUP>C :');
define('RMNH','RMN <SUP>1</SUP>H :');
define('IR','Infrared spectrometry :');
define('ALPHA','<span class="stylesymbole">a</span> <sub>D</sub> :');
define('ALPHATEMP','Temperature :');
define('DEG','°C');
define('ALPHACONC','Concentration :');
define('ALPHASOLVANT','Solvent :');
define('CCM','CCM :');
define('CCMRF','Rf :');
define('CCMSOLVANT','Solvents used :');
define('RIEN','There are no results');
define('CIBLE','Target');
define('ACTIF','Active');
define('RESULTATS','Results');
define('COMMENTAIRE','Comments');
define('ACTIVITE','% Activity');
define('IC','IC<sub>50</sub> in nM');
define('EC','EC<sub>50</sub> in nM');
define('AUTRE','Another result');
define('CONTRAT','Under contract');
define('BREVET','Patented');
define('LIBRE','Free');
define('CONTRATDESC','Contract description :');
define('DUREE','Contract period :');
define('NUMBREVET','Patent number :');
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
define('PRECIPITATION','precipitation');
define('RECRISTALLISATION','recrystallization');
define('RESINE','ion exchange resins');
define('APCI','APCI');
define('APPI','APPI');
define('CI','CI');
define('DCI','DCI');
define('EI','EI');
define('ESI','ESI');
define('MALDI','MALDI');
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
define('INSTABLE','unstable Light');
define('IRRITANT','irritant');
define('LACRYMOGENE','lachrymatory');
define('HOTTE','manipulation required under hood');
define('TOXIQUE','toxic product');
define('DEGRADE','degrades itself');
define('SENSIBLE','sensitive to traces of acid');
define('ELECTROSTAT','strong electrostatic');
define('ARGON','store under argon');
define('VOLATILE','volatile');
define('CONFIG','Note on setting :');
define('CHANGEMENT','History');
define('HISTORIQUE','Changes on this page');
define('QUI','Who');
define('QUAND','Date of change');
define('CHAMPS','Fields');
define('ANCIEN','Old Value');
define('FICHIER','File');
define('AUCUNEVAL','No value');
define('MODIFIE','Amended');
define('OBSERVATION','changed');
define('NUMERO','Number :');
define('NUMEROCONS','constant Number :');
define('INCOL','Colourless');
define('INCON','Unknown');
define('RETOUR','Back');
define('LOGP','Logp :');
define('ACCEPTORCOUNT','Number of acceptors :');
define('ROTATABLEBONDCOUNT','Number of rotable bond :');
define('AROMATICATOMCOUNT','Number of aromatic atoms :');
define('AROMATICBONDCOUNT','Number of aromatic bonds :');
define('DONORCOUNT','Number of donors :');
define('ASYMETRICATOMCOUNT','Number of atoms asymmetric :');
define('LIPINSKY','Check the Lipinski rules :');
define('OUI','Yes');
define('NON','No');
define('ND','off limits');
define('PLAQUE','Present in plate(s)');
define ('LABO','Laboratory :');
define ('CONCEN','Concentration :');
define ('PROTOCOL','Test protocol :');
define ('MOL','mol. L<sup>-1</sup>');
define('SYNTHESE','Synthesis');
define('HEMISYNTHESE','Hemi-synthesis');
define('NATURELLE','Natural');
define('INCONNUE','Unknown');
define('ORIGINEMOL','Origin of the molecule :');
define('PURETE','Purity measured :');
define('POURCENT','%');
define('METHOPURETE','Method for measuring the purity :');
define('NUMEROCN','Chimiothèque Nationale number :');
define('IMPORTTARE','Tare weight of pill :');
define ('POURINHI','% inhibition');
define('ETAPESYNT','The step of synthesis :');
define('INTERMEDIAIRE','intermediate of synthesis');
define('FINALE','final molecule');
define('QRCODE','Barcode/qrcode :');
define('NMOL','nmol');
define('CONTROLE_STRUCT','Controlled structure : ');
define('CONTROLE_PURETE','Controlled purity : ');
define('DATE_CONTROLE_PURETE','Date of control of purity : ');
define('DATE_ENVOIE_EVOTEC','Date sent to EVOTEC : ');
?>
