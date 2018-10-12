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
define ('AJOU','Ajouter');
define ('DESA','désactiver');
define ('VISU','Visualiser');
define ('NOM','Nom');
define ('PRENOM','Prénom');
define ('LANG','Langue');
define ('EQUIPE','Équipe');
define ('RESPON','Responsable');
define ('AUTO','Autorisation');
define ('COURRIEL','Courriel');
define ('RECEP','Retour');
define ('NON','Non');
define ('OUI','Oui');
define ('STATUT','Statut');
define ('ACTIF','Actif');
define ('PASSIF','Désactivé');
define ('DEUX',' :');
define ('SELECEQUIPE','Sélectionnez une équipe');
define ('ETAPE1','Étape suivante ou Sauvegarde');
define ('ETAPE2','Sauvegarder');
define ('RESPONSABLE','Responsable');
define ('SELECRESPON','Sélectionnez un Responsable');
define ('RENSEIGNE','doit être renseigné');
define ('CHAMP','Le champ');
define ('NEWEQUIPE','Nouvelle équipe');
define ('INIEQUIPE','Initiales ou numéro de l\'équipe');
define ('OU','Ou');
define ('SAUVDONNE','Utilisateur enregistré, un courriel avec le mot de passe lui a été envoyé');
define ('DESAC','Désactiver');
define ('MODIFIER','Modifier');
define ('REAC','Réactiver');
define ('CHIMISTE','Chimiste');
define ('CHEF','Chef');
define ('ADMINISTRATEUR','Administrateur');
define ('LANGUE','Langue');
define ('SELECTLANGUE','Sélectionnez la langue');
define ('SELECTSTATUT','Sélectionnez le statut');
define ('AIDESTATU','Vous devez créer en tout premier les responsables, cela permet de générer les équipes. Ensuite vous pouvez créer les chimistes pour chaque équipe.');
define ('GESTEQUIP','Équipes');
define ('MODEQUIP','Modifier une équipe');
define ('AJEQUIPE','Ajouter une équipe');
define ('EQUIERREUR','Les champs \'équipe\' et \'initiales ou numéro de l\'équipe\' doivent être renseignés');
define ('SELECHEF','Sélectionnez un chef');
define ('MESSMODIFUTIL','Vous, ne pouvez pas modifier le statut de ce responsable car cette équipe ne dispose pas d\'autres responsables.');
define ('MANQURESPO','Attention cet utilisateur n\'est pas relié à un responsable');
define ('SUPPRIMER','Supprimer');
define ('VCONFIRMSUP','Voulez-vous réellement supprimer');
define ('ERREUREQUIPE','Le nom d\'équipe ou les initiales existe déjà veuillez modifier votre saisie.');
define ('ERREURNOM','Le nom d\'utilisateur existe déjà veuillez modifier votre saisie.');
define ('ECHECSAUVDONNE','Echec de l\'enregistrement de l\'utilisateur');
?>