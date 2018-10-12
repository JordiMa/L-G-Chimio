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
define ('CREA','Création');
define ('GESTION','Gestion');
define ('VISU','Visualiser');
define ('ACETATETYLE','acétate d\'éthyle');
define ('ACETONE','acétone');
define ('ACETONITRILE','acétonitrile');
define ('BENZENE','benzène');
define ('CHOLOROFORME','chloroforme');
define ('DICHLO','dichlorométhane');
define ('DMF','DMF');
define ('DMSO','DMSO');
define ('EAU','eau');
define ('ETHANOL','éthanol');
define ('ETHERPET','éther de pétrole');
define ('ETHERETHYL','éther éthylique');
define ('INCONNU','inconnu');
define ('INSOLUBLE','insoluble');
define ('METHANOL','méthanol');
define ('PYRIDINE','pyridine');
define ('THF','THF');
define ('TOLUENE','toluène');
define ('PLAQUERA','Plaque \'Fille\' de la plaque \'Mère\'N°:');
define ('NUMERO','Numéro de la plaque à usage interne :');
define ('NUMEROEVOTEC','Numéro de la plaque à usage externe :');
define ('NUMEROLOT','Nouveau lot d\'appartenance :');
define ('LOTANCIEN','Lot existant :');
define ('CONCENTRATION','Concentration moyenne :');
define ('SOLVANT','Solvant utilisé :');
define ('MOL','mol. L'.'<sup>-1</sup>');
define ('DATE','Date de création :');
define ('VOLUME','Volume de la plaque :');
define ('ML','mL');
define ('MIL','µL');
define ('OU','ou');
define ('CHAMPNUM','Le champ Numéro de plaque n\'est pas renseigné');
define ('CHAMPCON','Le champ Concentration moyenne n\'est pas renseigné');
define ('CHAMPVOL','Le champ Volume n\'est pas renseigné');
define ('SELECPLA','Sélectionnez');
define ('SELECTEQUIPE','Sélectionnez une équipe');
define ('EQUIPE','Equipes :');
define ('CHIMISTE','Chimiste :');
define ('SELECTCHIMISTE','Sélectionnez un chimiste');
define ('NUMEROPRO','Numéro d\'identification');
define ('SELECTNUMERO','Sélectionnez un Numéro');
define ('SELECTBOITE','Sélectionnez une Boite');
define ('BOITETO','Mise en plaque d\'une boite de chimiothèque complète :');
define ('SAUVEGARDE','Sauvegarde');
define ('CHARGERCOO','Charger un fichier au format CSV :');
define ('DEVELOPPE','Développer');
define ('SELECTCIBLE','Sélectionnez une cible');
define ('DESCRIPT','Description :');
define ('ANCIBLE','Cibles répertoriées :');
define ('NVCIBLE','Nouvelle cible :');
define ('CIBLE','Nom de la cible :');
define ('LABO','Laboratoire :');
define ('CONCEN','Concentration :');
define ('PROTOCOL','Protocole du test :');
define ('TEST','Test biologique :');
define ('DATEENV','Date d\'envoi :');
define ('RESULTAT','Résultats biologiques :');
define ('TESTCOUR','Test biologique en cours');
define ('TESTRESU','test biologique effectué, résultats reçus');
define ('SELECTPLAQUE','Sélectionnez une plaque');
define ('PLAQUEMERE','Liste des plaques mères :');
define ('AIDECSV','Vous pouvez utiliser un fichier au format csv pour insérer les données.<br> Le fichier doit contenir le numéro du puits dans la plaque et le numéro du produit (soit le numéro unique à 8 chiffre soit le numéro de pilulier).'.'<br><br>exemple : <br>\'ICOA-FST-L-01A02\';\'A02\'<br>\'ICOA-SBR-L-02D05\';\'A03\'<br>\'ICOA-PBU-L-02D06\';\'A04\'<br>...<br>\'ICOA-SBR-L-06H03\';\'H09\'<br>\'ICOA-SRR-L-03D03\';\'H10\'<br>\'ICOA-PBU-L-04C11\';\'H11\'<br>');
define ('AIDECSV1','Vous pouvez utiliser un fichier au format csv pour insérer les données.<br> Le fichier doit contenir le numéro du puits dans la plaque, le numéro du produit (soit le numéro unique à 8 chiffre soit le numéro de pilulier) et la masse exacte de produit par puits en mg.'.'<br><br>exemple : <br>\'ICOA-FST-L-01A02\';\'A02\';\'1,2\'<br>\'ICOA-SBR-L-02D05\';\'A03\';\'0,9\'<br>\'ICOA-PBU-L-02D06\';\'A04\';\'1,0\'<br>...<br>\'ICOA-SBR-L-06H03\';\'H09\';\'1,0\'<br>\'ICOA-SRR-L-03D03\';\'H10\';\'1,1\'<br>\'ICOA-PBU-L-04C11\';\'H11\';\'1,2\'<br>');
define ('PUITVIDE','Cliquez sur le puits pour le faire correspondre à une structure ou chargez par le formulaire un fichier de données CSV');
define ('PUITVIDE1','Cliquez sur le puits pour le faire correspondre à une structure');
define ('MASSE','Masse de produit par puits :');
define ('SELECTMASSE','Sélectionnez');
define ('MASSEMOY','Masse moyenne');
define ('MASSEEXA','Masse exacte');
define ('MG','mg');
define ('MASSEPROD','Masse de ce produit dans le puits :');
define ('CHAMPMASSPROD','Le champ \''.MASSEPROD.'\' n\'est pas renseigné');
define ('DEFALK','Défalquer le stock avec cette masse pour chaque molécule de la plaque');
define ('CHAMPMASS','Le champ Masse moyenne de produit n\'est pas renseigné');
define ('VOLUMEPRE','Volume prélevé dans la plaque ');
define ('DPOINT',' :');
define ('REDUIRE','Réduire');
define ('RETOUR','Retour');
define ('LISTE','Exportation au format CSV');
define ('ATTDEFALK','Si le stock d\'une substance tombe à 0mg et que le paramétrage de la numérotation est sur automatique, alors le numéro sera changé');
define ('MOD','Modifier');
define ('SUP','Supprimer');
define ('SUPCONFIRM','Voulez-vous supprimer la plaque : ');
define ('ATTENTION','Toutes les modifications des puits sur cette page n\'effectuera pas de modifications de la masse du stockage du produit en vrac. C\'est à vous de modifier la fiche du produit correspondant.');
?>