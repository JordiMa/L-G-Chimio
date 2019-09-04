# Changelog [L-g Chimio]
Tous les changements notables apportés à ce projet seront documentés dans ce fichier.

## v1.6 - 2019-09-03

### Ajouts :

- Gestion d'extractothèque
  - Insertion
  - Recherche
  - Modification
  - Importation
  - Exportation
- Ajout de champs dynamique

### Modifications :

- Correction de bugs divers


## v1.5.1 - 2019-06-11

### Ajouts :

- Nom utilisateur à renseigner pour obtenir un nouveau mot de passe (mot de passe oublié)
- Attribution de structures en renseignant le nom du chimiste
- Attribution de structures à un responsable
- Recherche par cahier de labo
- Option de numérotation automatique
- Possibilité de supprimer les structures

### Modifications :
- Correction de bug lors de l'importation de CSV de plaque sans masse
- Correction de bug lors de la modification de plaque
- Correction de bug lors de l'exportation des plaques
- Correction de bug lors de l'importation des résultats bio
- Correction de bug lors de l'importation de CSV pour les Tag Evotec
- Correction de bug lors de la modification d'utilisateur
- Correction de bug sur la gestion des résultats bio des plaques
- Correction de bug sur l'upgrade 1.4 vers 1.5
- Modification de la saisie des molécules, seulement le nom du chimiste est nécessaire pour les administrateurs


## v1.5 - 2019-04-02

### Ajouts :
- Alert doublon
- Wiki accessible depuis L-g Chimio
- Exportation multicritères (CSV/SDF)
  - Choix du format du fichier
  - Choix des filtres de sélection
  - Choix des champs à exporter
- Possibilité d'attribution des molécules par les administrateurs
- Module d'importation (SDF/RDF)
- Exportation via le module de recherche
- Date d'envoie chez Evotec
- Tag Evotec insoluble
- Contrôle de la structure
- Contrôle de la pureté
- Désactivation automatique des comptes chimiste (1 an après l'activation)
- Choix des champs obligatoire

### Modifications :
- Renforcement de la sécurité
- Modification de l'interface utilisateur
- Modification de la structure de la base de données
- Augmentation de la taille limite pour les envois de fichiers et de logo
- Correction de bug d'affichage de Mol v3000
- Correction de bug lors de d'insertion des numero CN
- Correction de bug lors de d'insertion de fichier CSV dans les resultat bio
- Correction de bug lors de la modification du mot de passe par un chimiste
- Correction de bug lors de l'insertion de CSV pour les tags Evotec
- Amélioration de la partie téléchargement fichier pour les résultats d'analyses (Spectrométrie, RMN)
