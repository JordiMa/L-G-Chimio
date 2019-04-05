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
define ('EXPEQUIPE','Exportation sélective au format SDF');
define ('EXPTYPE','Exportation par type au format SDF');
define ('SELECTEQUIP','Sélectionnez une équipe');
define ('EQUIPE','Equipe :');
define ('SELECTPERS','Sélectionnez une personne');
define ('PERSO','Utilisateur :');
define ('SELECTTYP','Sélectionnez un type');
define ('TYPE','Type de molécules :');
define ('LIBRE','Libre');
define ('CONTRAT','Contrat');
define ('BREVET','Brevet');
define ('ENVOI','Envoyer par courriel');
define ('MASSE','Masse disponible :');
define ('MG','mg');
define ('REUSSI','L\'envoie du courriel a réussi, vous pouvez fermer cette fenêtre');
define ('ECHEC','Echec de l\'envoie du courriel');
define ('TELE','Télécharger le fichier SDF');
define ('MAIL','Vous envoyer le fichier par Courriel');
define ('MAIL1','Envoyer le fichier par Courriel à la Chimiothèque Nationale');
define ('PLAQUE','et produits en plaque mais pas en vrac');
define ('SDF','SDF');
define ('CSV','CSV');
define ('BOITETO','Pesées à partir d\'une ou des boite(s) de chimiothèque complète :');
define ('LISTETO','Pesées à partir d\'une liste de numéros de produits :');
define ('SUBMITCSV','Télécharger le Fichier CSV');
define ('ALEATOIRE','Mélanger les produits aléatoirement');
define ('SEPARATEUR','Séparateur utilisé pour la liste :');
define ('ESPACE','Espace');
define ('RLIGNE','Retour à la ligne');
define ('DOUBLONS','Enlever les produits déjà envoyés chez EVOTEC et les doublons de structure');
define ('ALTERNATIVE','Ajouter les produits identiques comme alternative de pesée');

define ('SELECTCHAMPSEXPORT', 'Sélectionnez les champs à exporter :');
define ('INFOBULEEXPORT','Si rien n\'est coché, les champs exporter par défaut sont :<br><br>- Identificateur<br>- Numéro constant<br>- Inchi<br>- Masse<br>- Numéro de plaque<br>- Chez evotec (oui/non)<br>- Pureté<br>- Methode de mesure de la purete<br>- Origine de la substance');
define ('SELECT_TYPE', 'Type');
define ('SELECT_EQUIPE', 'Equipe');
define ('SELECT_RESPONSABLE', 'Responsable');
define ('SELECT_CHIMISTE', 'Chimiste');
define ('SELECT_COULEUR', 'Couleur');
define ('SELECT_PURETE', 'Pureté');
define ('SELECT_PURIFICATION', 'Purification');
define ('SELECT_MASSE', 'Masse');
define ('SELECT_ASPECT', 'Aspect');
define ('SELECT_DATESAISIE', 'Date de saisie');
define ('SELECT_REFLABO', 'Reference cahier de labo');
define ('SELECT_OBSERVATION', 'Observation');
define ('SELECT_IDENTIFICATEUR', 'Identificateur');
define ('SELECT_NUMCONSTANT', 'Numéro constant');
define ('SELECT_INCHI', 'Inchi');
define ('SELECT_POINTFUSION', 'Point de fusion');
define ('SELECT_POINTEBULLITION', 'Point d\'ebullition');
define ('SELECT_METHODEMESSUREPURETE', 'Methode de mesure de la purete');
define ('SELECT_NUMCN', 'Numéro CN');
define ('SELECT_ORIGINESUBSTANCE', 'Origine de la substance');
define ('SELECT_QRCODE', 'QR code');
define ('SELECT_CONTROLEPURETE', 'Pureté contrôlée (oui/non)');
define ('SELECT_DATECONTROLEPURETE', 'Date de contrôle pureté');
define ('SELECT_CONTROLESTRUCTURE', 'Structure contrôlée (oui/non)');
define ('SELECT_NUMPLAQUE', 'Numéro de plaque');
define ('SELECT_EVOTEC', 'Chez evotec (oui/non)');
define ('SELECT_CHAMPSSOUHAITE','Sélectionnez les champs souhaité');
define ('SELECTCRITERESEXPORT','Sélectionnez vos critères de sélection :');
define ('SELECT_UTILISATEUR', 'Utilisateur');
define ('SELECT_TYPECONTRACT', 'Type de contrat');
define ('SELECT_MASSEDISPO', 'Masse disponible');
define ('SELECT_PLAQUENOVRAC', 'Produits en plaque mais pas en vrac');
define ('SELECT_CHEZEVOTEC', 'Seulement les produits présents chez Evotec');
define ('SELECT_PASCHEZEVOTEC', 'Seulement les produits qui ne sont pas chez Evotec');
define ('SELECT_LESDEUX', 'Les deux');
define ('SELECT_SOLUBLE', 'Seulement les produits soluble (Evotec)');
define ('SELECT_INSOLUBLE', 'Seulement les produits insoluble (Evotec)');
define ('SELECT_LISTEID', 'Depuis une liste d\'identificateurs');
define ('SELECTEQUIPEEXPORT', 'Sélectionnez une équipe :');
define ('SELECTUTILISATEUREXPORT', 'Sélectionnez un utilisateur :');
define ('SELECTCONTRACTEXPORT', 'Sélectionnez un type de contrat :');
define ('SELECTLISTEIDEXPORT', 'Liste d\'identificateurs :');
define ('MOTDEPASSE', 'Mot de passe');
define ('ZERORESULTAT', 'Aucun résultat trouvé');
define ('UNRESULTAT', 'Un résultat trouvé');
define ('XRESULTAT', 'résultats trouvés');
define ('LISTERESULTAT', 'Numero d\'identification local des composés :');
define ('SELECTRESPONSABLEEXPORT','Sélectionnez un responsable :');
define ('SELECTCHIMISTEEXPORT','Sélectionnez un chimiste :');
define ('SAISIEIDPRODUIT', 'Veuillez saisir l\'identifiant local du produit :');
define ('RECHERCHER', 'Rechercher');
define ('SAVEOK', 'Sauvegarde effectuée');
define ('SAVEECHEC', 'Echec de la sauvegarde');
define ('CONFIRMSAVE', 'Voulez vous enregistrer les modifications apportées ?');
define ('TOUT', 'Tout');
define ('CHAMP', 'champ');
define ('SELECTIONNE','sélectionné');
?>
