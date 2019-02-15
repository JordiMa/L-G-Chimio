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
define('CONFIG','Note sur la configuration :');
define('MASS','* Masse de produit disponible : ');
define('TYPE','* Type de structure : ');
define('CHARG','Charger la structure');
define('CHARGESTRUC','Rechercher une structure au format mol à charger');
define('DESSINSTRUC','Vous devez dessiner une molécules');
define('MASSABS',MASS.' doit être renseigné');
define('SELECCOULEUR','Sélectionnez une couleur');
define('COULEUR','Couleur du produit :');
define('NBPILLULIER','Numéro de pilulier :');
define('PURIFICATION','Type de purification :');
define('SELECPURIFICATION','Sélectionnez une purification');
define('ASPECT','Aspect :');
define('SELECASPECT','Sélectionnez l\'aspect');
define('NOM','Nom en nomenclature IUPAC (anglaise) :');
define('REFERENCECAHIER','Référence cahier de laboratoire ou thèse :');
define('CONTRAT','Sous contrat');
define('BREVET','Breveté');
define('LIBRE','Libre');
define('CONTRATDESC','Description du contrat :');
define('DUREE','Durée du contrat :');
define('AN','Année(s)');
define('NUMBREVET','Numéro du brevet :');
define('PRECAUTION','Précautions à prendre');
define('SOLVANTS','Solvants de solubilisation :');
define('MODOP','Mode opératoire :');
define('ANALYSE','Analyses');
define('ANAELEM','Analyse élémentaire :');
define('PFUSION','Point de fusion :');
define('PEB','Point d\'ébullition');
define('PEBULITION','Point d\'ébullition :');
define('PRESSIONPB','A pression de :');
define('ALPHA','α <sub>D</sub>');
define('ALPHATEMP','Température :');
define('ALPHACONC','Concentration :');
define('ALPHAD','mesure de l\'');
define('ALPHASOLVANT','Solvant :');
define('ALPHASELECSOLV','Sélectionnez le solvant :');
define('IR','spectrométrie Infrarouge');
define('DONNEESIR','IR :');
define('CHARGEIR','Fichier du spectre IR :');
define('UV','spectrométrie UV');
define('DONNEESUV','UV :');
define('CHARGEUV','Fichier du spectre UV :');
define('ACTIVITE','Activité Pharmacologique');
define('RECEPTEUR','Récepteur');
define('SM','Spectrométrie de Masse');
define('SM1','SM :');
define('SMTYPE','Source d\'ionisation :');
define('SELECTSMTYPE','Sélectionnez la source');
define('CHARGESM','Fichier du spectre SM :');
define('HSM','Spectrométrie de Masse haute résolution');
define('HSM1','HRMS :');
define('HSMTYPE','Source d\'ionisation :');
define('SELECTHSMTYPE','Sélectionnez la source');
define('CHARGEHSM','Fichier du spectre HRMS :');
define('CCM','CCM :');
define('CCMRF','Rf :');
define('CCMSOLVANT','Solvants utilisés :');
define('RMNH','RMN <SUP>1</SUP>H ');
define('SPECTRORMN','Spectrométrie ');
define('DONNERRMN','Données ');
define('CHARGERRMN','Fichier du spectre ');
define('RMNC','RMN <SUP>13</SUP>C ');
define('BIBLIO','Bibliographie');
define('PUB','Publication');
define('DOI','numéro DOI :');
define('HAL','numéro HAL :');
define('CAS','Référence CAS :');
define('DEG','°C');
define('NMOL','nmol');
define('MG','mg');
define('MOL','mol. L<sup>-1</sup>');
define('ATM','atm');
define('RENSEIGNE','doit être renseigné');
define('CHAMP','Le champ');
define('ERREURMASSE','doit contenir la masse en valeur entière');
define('RECRISTALISE','Votre produit ne peut pas avoir été prurifié par recristalisation ou précipitation et être sous forme d\'huile ou de liquide');
define('DISTILATION','Votre produit ne peut pas avoir été purifié par distillation et être sous une autre forme solide');
define('ERREURCHARGEMENT','Une erreur c\'est produite au téléchargement du fichier ');
define('SAUVDONNE','Données sauvegardées');
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
define ('RESINE','résines échangeuses d\'ions');
define('ACPI','APCI');
define('APPI','APPI');
define('CI','CI');
define('DCI','DCI');
define('EI','EI');
define('ESI','ESI');
define('MAL','MALDI');
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
define('INSTABLE','instable à la lumière');
define('IRRITANT','irritant');
define('LACRYMOGENE','lacrymogène');
define('HOTTE','manipulation indispensable sous hotte');
define('TOXIQUE','produit toxique');
define('DEGRADE','se dégrade');
define('SENSIBLE','sensible aux traces d\'acide');
define('ELECTROSTAT','solide électrostatique');
define('ARGON','stocker sous argon');
define('VOLATILE','volatil');
define('SUJET','chimiothèque');
define('BONJOUR','Bonjour');
define('ENTREE','a entré un produit dans la chimiothèque sous les réferences :');
define('LE','Le');
define('MRMME','M. ou Mme');
define('CORDIALEMENT','Cordialement.');
define('MESSAUTO','Courriel automatique envoyé par L-g-Chimio depuis l\'adresse :');
define('PLUSRECEPTION','Si vous ne souhaitez plus recevoir de courriels, vous pouvez désactiver cette fonction dans votre compte Chimiothèque.');
define('EXFICHIER','Voir le fichier existant');
define('OBSERVATION','Observations');
define('EQUIPE','* Equipe --- Responsable :');
define('SELECTEQUIPE','Sélectionez le couple équipe --- responsable');
define('EQUIPEABS','Le champ équipe doit être renseigné');
define('OBLIGATOIRE','* : champ obligatoire');
define('INCOL','Incolore');
define('INCON','Inconnue');
define('STRUC','Votre structure n\'a pas été traitée veuillez vérifier que celle-ci soit bien développée et ne présente pas d\'erreurs');
define('SYNTHESE','synthèse');
define('HEMISYNTHESE','hémisynthèse');
define('NATURELLE','naturelle');
define('INCONNUE','inconnue');
define('ORIGINEMOL','* Origine de la molécule :');
define('SELECTORIGINEMOL','Sélectionnez une origine');
define('ORIGABS','L\'origine de la molécule n\'est pas renseignée!');
define('PURETESUB','Pureté de la substance');
define('PURETE','Pureté mesurée :');
define('POURCENT','%');
define('METHOPURETE','Méthode de mesure de la pureté :');
define('RECOMMANDATION','Recommandations pour le dessin des structures');
define('DESSINSTRUC1','La structure que vous avez dessinée contient une abréviation ou une flèche de réaction, consultez les recommandations pour le dessin des structures');
define('INTERMEDIAIRE','intermédiaire de synthèse');
define('FINALE','molécule finale');
define('ETAPMOL','Etape de synthèse de la molécule :');
define('SELECTETAPMOL','Sélectionnez une étape');
define('ETAPGABS','L\'étape de synthèse de la molécule n\'est pas renseignée!');
define('QRCODE','Code-barre/Qrcode :');
define('QRCODE2','Code(s)-barre(s) ou Qrcode(s) : <br />(séparés par un retour à la ligne)');
if (!defined ('MESSAGEERREUR')) define('MESSAGEERREUR','<br>Message d\'erreur :<br>');
define('ERREUR_STRUCTURE','Echec de l\'insertion de la molécule dans la base de données');
define('ERREUR_PRODUIT','Echec de la modification du produit dans la base de données');
define('ERREUR_SOLUBILITE','Echec de la modification de la table solubilité dans la base de données');
define('ERREUR_MODIFICATIONS','Echec de la modification du suivie des modifications de la table produit dans la base de données');
define('ERREUR_PRECAUTION','Echec de la modification de la liste des précaution dans la base de données');
define('ERREUR_SUP_PRECAUTION','Echec de la suppression de la liste des précaution dans la base de données');
define('ERREUR_FICHIER','Echec de l\'insertion d\'un fichier d\'analyse dans la base de données');
define('ERREURATTENTION',' Erreurs se sont produites durant la modification de la fiche');
define('RETIRE','Retirer ce fichier');
?>
