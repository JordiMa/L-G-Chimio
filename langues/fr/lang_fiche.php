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
define('MASS','Masse de produit disponible : ');
define('NOM','Nom :');
define('DATE','Date d\'entrée :');
define('COULEUR','Couleur du produit :');
define('MASSEMOL','Masse molaire :');
define('GMOL','g.mol'.'<sup>-1</sup>');
define('MG','mg');
define('PURIFICATION','Type de purification :');
define('REFE','Référence du cahier de laboratoire :');
define('MASSE','Masse de produit disponible :');
define('FORMULEBRUTE','Formule brute :');
define('ASPECT','Aspect du produit :');
define('MODOP','Mode opératoire :');
define('TYPE','Type du produit :');
define('ANALYSETHEO','Analyse élèmentaire théorique :');
define('ANALYSEEXP','Analyse élèmentaire expérimentale :');
define('OBSERVATIONS','Observations :');
define('PF','Point de fusion :');
define('PEB','Point d\'ébullition :');
define('PRESSION','à pression de ');
define('ATM','atm');
define('PRECAUTION','Précaution(s) :');
define('DOI','numéro DOI :');
define('HAL','numéro HAL :');
define('CAS','Référence CAS :');
define('SOLVANT','Solvant(s) :');
define('STRUCTURE','Structure');
define('ANALYSE','Analyses');
define('ANABIO','Résultats Bio');
define('UV','spectrométrie UV :');
define('FICHIERTEL','Télécharger le fichier');
define('SM','Spectrométrie de Masse :');
define('SMTYPE','Source d\'ionisation :');
define('HRMS','Spectrométrie de Masse haute résolution :');
define('RMNC','RMN <SUP>13</SUP>C :');
define('RMNH','RMN <SUP>1</SUP>H :');
define('IR','spectrométrie Infrarouge :');
define('ALPHA','α <sub>D</sub> :');
define('ALPHATEMP','Température :');
define('DEG','°C');
define('ALPHACONC','Concentration :');
define('ALPHASOLVANT','Solvant :');
define('CCM','CCM :');
define('CCMRF','Rf :');
define('CCMSOLVANT','Solvants utilisés :');
define('RIEN','Il n\'y a aucun résultat');
define('CIBLE','Cible');
define('ACTIF','Actif');
define('RESULTATS','Résultats');
define('COMMENTAIRE','Commentaires');
define('ACTIVITE','% d\'activité');
define('IC','IC<sub>50</sub> en nM');
define('EC','EC<sub>50</sub> en nM');
define('AUTRE','Autre résultat');
define('CONTRAT','Sous contrat');
define('BREVET','Breveté');
define('LIBRE','Libre');
define('CONTRATDESC','Description du contrat :');
define('DUREE','Durée du contrat :');
define('NUMBREVET','Numéro du brevet :');
define('GOMME','gomme');
define('HUILE','huile');
define('LIQUIDE','liquide');
define('MOUSSE','mousse');
define('SOLIDE','solide');
define('AUCUNE','aucune');
define('COLONNE','colonne');
define('DISTILLATION','distillation');
define('EXTRACTION','extraction');
define('FILTRATION','filtration');
define('FILTRATIONCEL','filtration sur célite');
define('HPLC','HPLC');
define('PRECIPITATION','précipitation');
define('RECRISTALLISATION','recristallisation');
define('RESINE','résines échangeuses d\'ions');
define('APCI','APCI');
define('APPI','APPI');
define('CI','CI');
define('DCI','DCI');
define('EI','EI');
define('ESI','ESI');
define('MALDI','MALDI');
define('FAB','FAB');
define('ACETATETYLE','acétate d\'éthyle');
define('ACETONE','acétone');
define('ACETONITRILE','acétonitrile');
define('BENZENE','benzène');
define('CHOLOROFORME','chloroforme');
define('DICHLO','dichlorométhane');
define('DMF','DMF');
define('DMSO','DMSO');
define('EAU','eau');
define('ETHANOL','éthanol');
define('ETHERPET','éther de pétrole');
define('ETHERETHYL','éther éthylique');
define('INCONNU','inconnu');
define('INSOLUBLE','insoluble');
define('METHANOL','méthanol');
define('PYRIDINE','pyridine');
define('THF','THF');
define('TOLUENE','toluène');
define('FRIGO','conservation au frigo');
define('HYGROSCOPIQUE','hygroscopique');
define('INFLAMABLE','inflammable');
define('INSTABLE','instable à la Lumière');
define('IRRITANT','irritant');
define('LACRYMOGENE','lacrymogène');
define('HOTTE','manipulation indispensable sous hotte');
define('TOXIQUE','produit toxique');
define('DEGRADE','se dégrade');
define('SENSIBLE','sensible aux traces d\'acide');
define('ELECTROSTAT','solide électrostatique');
define('ARGON','stocker sous argon');
define('VOLATILE','volatil');
define('CONFIG','Note sur la configuration :');
define('CHANGEMENT','Historique');
define('HISTORIQUE','Modifications effectuées sur cette fiche');
define('QUI','Qui');
define('QUAND','Date de la modification');
define('CHAMPS','Champs');
define('ANCIEN','Ancienne valeur');
define('FICHIER','Fichier');
define('AUCUNEVAL','Aucune valeur');
define('MODIFIE','Modifié');
define('OBSERVATION','Observations');
define('NUMERO','Numéro :');
define('NUMEROCONS','Numéro constant:');
define('INCOL','Incolore');
define('RETOUR','Retour');
define('LOGP','Logp :');
define('ACCEPTORCOUNT','Nombre d\'accepteurs :');
define('ROTATABLEBONDCOUNT','Nombre de liaisons rotatives:');
define('AROMATICATOMCOUNT','Nombre d\'atomes aromatiques :');
define('AROMATICBONDCOUNT','Nombre de liaisons aromatiques :');
define('DONORCOUNT','Nombre de donneurs :');
define('ASYMETRICATOMCOUNT','Nombre d\'atomes asymétriques :');
define('LIPINSKY','Vérifie les règles de Lipinski :');
define('OUI','oui');
define('NON','Non');
define('ND','hors limites');
define('PLAQUE','Présent en plaque(s) :');
define('LABO','Laboratoire :');
define('CONCEN','concentration :');
define('PROTOCOL','Protocole du test :');
define('MOL','mol. L<sup>-1</sup>');
define('SYNTHESE','Synthèse');
define('HEMISYNTHESE','Hémisynthèse');
define('NATURELLE','Naturelle');
define('INCONNUE','Inconnue');
define('ORIGINEMOL','Origine de la molécule :');
define('PURETE','Pureté mesurée :');
define('POURCENT','%');
define('METHOPURETE','Méthode de mesure de la pureté :');
define('NUMEROCN','Numéro Chimiothèque Nationale :');
define('IMPORTTARE','Tare du pilulier :');
define('POURINHI','% inhibition');
define('ETAPESYNT','Etape de synthèse :');
define('INTERMEDIAIRE','intermédiaire de synthèse');
define('FINALE','molécule finale');
define('QRCODE','Code barre/Qrcode :');
define('NMOL','nmol');
?>