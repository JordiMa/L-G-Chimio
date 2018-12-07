a:201:{i:0;a:3:{i:0;s:14:"document_start";i:1;a:0:{}i:2;i:0;}i:1;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:0;}i:2;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:104:"## Systèmes requis
Le logiciel fonctionne avec un environnement LAPP (Linux, Apache, PostgreSQL, PHP). ";}i:2;i:1;}i:3;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:105;}i:4;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:105;}i:5;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:150:"Il sous Windows mais cet environnement n'est pas recommandé pour l'utilisation en production et en toute sécurité d'Apache, PHP et de PostgresSQL. ";}i:2;i:107;}i:6;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:257;}i:7;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:257;}i:8;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:259;}i:9;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:169:"Il est vivement recommandé d’utiliser cet environnement avec le cryptage SSL (https) et de mettre en place un firewall afin de filtrer les entrées sur votre serveur.";}i:2;i:261;}i:10;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:430;}i:11;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:432;}i:12;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:432;}i:13;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:434;}i:14;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:427:"*

## Installation
Dans ce tutoriel l'installation s’effectua sur un serveur CentOS 7.

### Apache
Vous pouvez facilement installer Apache à partir du référentiel officiel avec la commande ci-dessous:

> sudo yum install httpd

Après l'installation, exécutez les commandes suivantes pour démarrer votre service Apache et le faire fonctionner au démarrage:

> sudo systemctl start httpd

> sudo systemctl enable httpd

";}i:2;i:436;}i:15;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:863;}i:16;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:1:"*";}i:2;i:865;}i:17;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:866;}i:18;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:866;}i:19;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:87:"### PHP 5.6
Commande pour installer le package de configuration du référentiel Remi :";}i:2;i:868;}i:20;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:956;}i:21;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:956;}i:22;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:18:" sudo yum install ";}i:2;i:958;}i:23;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:54:"http://rpms.remirepo.net/enterprise/remi-release-7.rpm";i:1;N;}i:2;i:976;}i:24;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:1030;}i:25;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:1030;}i:26;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:32:"Commande pour installer php5.6 :";}i:2;i:1032;}i:27;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:1065;}i:28;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:1065;}i:29;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:39:" sudo yum install php56 php56-php-pgsql";}i:2;i:1067;}i:30;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:1106;}i:31;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:1106;}i:32;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:53:"Commande pour installer des paquets supplémentaires:";}i:2;i:1108;}i:33;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:1162;}i:34;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:1162;}i:35;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:31:" sudo yum install php56-php-xxx";}i:2;i:1164;}i:36;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:1195;}i:37;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:1195;}i:38;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:1197;}i:39;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:107:"*

### PostgreSQL 9.2.24

Utilisez les commandes suivantes pour installer PostgreSQL :

> sudo yum install ";}i:2;i:1199;}i:40;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:101:"http://mirror.centos.org/centos/7/updates/x86_64/Packages/postgresql-server-9.2.24-1.el7_5.x86_64.rpm";i:1;N;}i:2;i:1306;}i:41;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:21:"

> sudo yum install ";}i:2;i:1407;}i:42;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:99:"http://mirror.centos.org/centos/7/updates/x86_64/Packages/postgresql-libs-9.2.24-1.el7_5.x86_64.rpm";i:1;N;}i:2;i:1428;}i:43;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:21:"

> sudo yum install ";}i:2;i:1527;}i:44;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:102:"http://mirror.centos.org/centos/7/updates/x86_64/Packages/postgresql-contrib-9.2.24-1.el7_5.x86_64.rpm";i:1;N;}i:2;i:1548;}i:45;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:21:"

> sudo yum install ";}i:2;i:1650;}i:46;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:94:"http://mirror.centos.org/centos/7/updates/x86_64/Packages/postgresql-9.2.24-1Iel7_5.x86_64.rpm";i:1;N;}i:2;i:1671;}i:47;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:1493:"

Initialiser la base :

> sudo postgresql-setup initdb

Méthode d’authentification de l’utilisateur de la base de données :

> sudo vi /var/lib/pgsql/data/pg_hba.conf

Trouvez la section suivante :

    # IPv4 local connections:
    host    all             all             127.0.0.1/32           ident
    # IPv6 local connections:
    host    all             all             ::1/128                ident

Modifiez la méthode d'authentification des connexions locales IPv4 à md5:

    # IPv4 local connections:
    host    all             all             127.0.0.1/32            md5
    # IPv6 local connections:
    host    all             all             ::1/128                 md5

Sauvegarder et quitter:

    :wq

Configurez les adresses d’écoute PostgreSQL:

> sudo vi /var/lib/pgsql/data/postgresql.conf

Trouver:

    #listen_addresses = 'localhost'

modifiez-le pour:

    listen_addresses = '*'

Trouver:

    #port = 5432

modifiez-le pour:

    port = 5432

Sauvegarder et quitter:

    :wq

Démarrer PostgreSQL : 

> sudo systemctl start postgresql

Lancement automatique au démarrage du serveur Centos :

> sudo systemctl enable postgresql

Configurer les informations d'identification de l'utilisateur de la base de données:

Par défaut, le programme PostgreSQL va créer un utilisateur de base de données `postgres`. Cependant, pour des raisons de sécurité, vous devez créer un autre utilisateur de base de données pour la connexion à distance.

Il faut ";}i:2;i:1765;}i:48;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:3258;}i:49;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:33:"créer un utilisateur spécifique";}i:2;i:3260;}i:50;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:3293;}i:51;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:90:" « ex : `chimio_user` » avec un mot de passe pour votre base de données chimiothèque. ";}i:2;i:3295;}i:52;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:3385;}i:53;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:61:"Celui-ci doit avoir des droits que sur cette base de données";}i:2;i:3387;}i:54;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:3448;}i:55;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:5:", et ";}i:2;i:3450;}i:56;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:3455;}i:57;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:27:"créez une base de données";}i:2;i:3457;}i:58;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:3484;}i:59;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:331:" (exemple : `chimiotheque`) qui va contenir votre chimiothèque

> sudo -u postgres psql

    CREATE USER chimio_user CREATEDB CREATEUSER ENCRYPTED PASSWORD 'mot_de_passe';
    CREATE DATABASE nom_de_votre_base_de_donnée OWNER chimio_user;
    GRANT ALL PRIVILEGES ON DATABASE nom_de_votre_base_de_donnée TO chimio_user;
    \q

";}i:2;i:3486;}i:60;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:3817;}i:61;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:1:"*";}i:2;i:3819;}i:62;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:3820;}i:63;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:3820;}i:64;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:14:"### PhpPgAdmin";}i:2;i:3822;}i:65;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:3836;}i:66;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:3836;}i:67;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:47:"Installez phpPgAdmin avec la commande suivante:";}i:2;i:3838;}i:68;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:3886;}i:69;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:3886;}i:70;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:28:" sudo yum install phpPgAdmin";}i:2;i:3888;}i:71;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:3916;}i:72;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:3916;}i:73;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:62:"Puis configurez phpPgAdmin comme accessible de l’extérieur:";}i:2;i:3918;}i:74;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:3981;}i:75;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:3981;}i:76;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:42:" sudo vi /etc/httpd/conf.d/phpPgAdmin.conf";}i:2;i:3983;}i:77;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:4025;}i:78;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4025;}i:79;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:10:"Remplacer:";}i:2;i:4027;}i:80;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4038;}i:81;a:3:{i:0;s:12:"preformatted";i:1;a:1:{i:0;s:15:"  Require local";}i:2;i:4038;}i:82;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4038;}i:83;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:5:"avec:";}i:2;i:4058;}i:84;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4064;}i:85;a:3:{i:0;s:12:"preformatted";i:1;a:1:{i:0;s:21:"  Require all granted";}i:2;i:4064;}i:86;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4064;}i:87;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:10:"Remplacer:";}i:2;i:4090;}i:88;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4101;}i:89;a:3:{i:0;s:12:"preformatted";i:1;a:1:{i:0;s:15:"  Deny from all";}i:2;i:4101;}i:90;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4101;}i:91;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:5:"avec:";}i:2;i:4121;}i:92;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4127;}i:93;a:3:{i:0;s:12:"preformatted";i:1;a:1:{i:0;s:16:"  Allow from all";}i:2;i:4127;}i:94;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4127;}i:95;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:23:"Sauvegarder et quitter:";}i:2;i:4148;}i:96;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4172;}i:97;a:3:{i:0;s:12:"preformatted";i:1;a:1:{i:0;s:5:"  :wq";}i:2;i:4172;}i:98;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4172;}i:99;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:43:"Rechargez les services PostgreSQL et httpd:";}i:2;i:4182;}i:100;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:4226;}i:101;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:4226;}i:102;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:44:" sudo systemctl start postgresql-9.5.service";}i:2;i:4228;}i:103;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:4272;}i:104;a:3:{i:0;s:10:"quote_open";i:1;a:0:{}i:2;i:4273;}i:105;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:36:" sudo systemctl reload httpd.service";}i:2;i:4275;}i:106;a:3:{i:0;s:11:"quote_close";i:1;a:0:{}i:2;i:4311;}i:107;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:4311;}i:108;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:4313;}i:109;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:37:"*

### Bingo

Une fois votre serveur ";}i:2;i:4315;}i:110;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:4352;}i:111;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:14:"PostgreSQL 9.2";}i:2;i:4354;}i:112;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:4368;}i:113;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:86:" fonctionnel, vous devez ajouter l’outil Bingo que vous pouvez télécharger ici :

";}i:2;i:4370;}i:114;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:58:"http://lifescience.opensource.epam.com/download/bingo.html";i:1;N;}i:2;i:4456;}i:115;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:132:"

Une fois Bingo téléchargé et décompressé suivez le manuel d’installation en fonction de votre système d’exploitation :

";}i:2;i:4514;}i:116;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:78:"http://lifescience.opensource.epam.com/bingo/installation-manual-postgres.html";i:1;N;}i:2;i:4646;}i:117;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:780:"

Une fois que vous avez exécuté le fichier bingo-pg-install avec l’extension sh pour Linux ou bat pour Windows, associez les outils Bingo à la base de données créé plus tôt avec la commande SQL :

> psql -U postgres -d nom_de_votre_base_de_donnée -f bingo_install.sql

Cette association est effectuée via le compte administrateur : postgres. Une fois cette association effectuée, vous devez changer les droits sur le schema Bingo ainsi créé. Pour cela utilisez les commandes si dessous dans votre base de données (ces commandes doivent être exécutées avec le compte administrateur) :

    grant usage on schema bingo to chimio_user;
    grant select on table bingo.bingo_config to chimio _user;
    grant select on table bingo.bingo_tau_config to chimio_user;

";}i:2;i:4724;}i:118;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:5504;}i:119;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:1:"*";}i:2;i:5506;}i:120;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5507;}i:121;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5507;}i:122;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:59:"## L-G-Chimio 
### Téléchargez l’application L-g-Chimio";}i:2;i:5509;}i:123;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5568;}i:124;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5568;}i:125;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:7:"❗️ ";}i:2;i:5570;}i:126;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:5577;}i:127;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:66:"La version ici présente sur GitHub est en cours de développement";}i:2;i:5579;}i:128;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:5645;}i:129;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:9:" ❗️  ";}i:2;i:5647;}i:130;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5656;}i:131;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5656;}i:132;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:94:"Une fois le fichier zip obtenu, procédez à sa décompression avec une application adéquate.";}i:2;i:5658;}i:133;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5752;}i:134;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5752;}i:135;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:112:"Placez le répertoire `chimiotheque` avec les fichiers décompressés dans le répertoire du serveur web Apache.";}i:2;i:5754;}i:136;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5866;}i:137;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5866;}i:138;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:48:"Sous Linux dans le répertoire `/var/www/html/`.";}i:2;i:5868;}i:139;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:5916;}i:140;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:5916;}i:141;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:61:"connectez-vous via un navigateur web à votre serveur web : `";}i:2;i:5918;}i:142;a:3:{i:0;s:12:"externallink";i:1;a:2:{i:0;s:38:"http://votre_serveur_web/chimiotheque/";i:1;N;}i:2;i:5979;}i:143;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:2:"`.";}i:2;i:6017;}i:144;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6019;}i:145;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6019;}i:146;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:62:"Vous allez arriver sur la page d’installation de L-g-Chimio.";}i:2;i:6021;}i:147;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6083;}i:148;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6083;}i:149;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:184:"Après avoir cliqué sur le bouton `Première étape`, vous arrivez sur la validation de la Licence CeCILL. Une fois acceptée, vous arrivez à la première étape de l’installation.";}i:2;i:6085;}i:150;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6269;}i:151;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6269;}i:152;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:118:"### Première étape
Celle-ci permet de définir et de vérifier les répertoires d’installation de l’application.";}i:2;i:6271;}i:153;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6389;}i:154;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6389;}i:155;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:133:"Une fois ces vérifications effectuer vous pouvez en cliquant sur le bouton `Etape suivante`, passez à la suite de l’installation.";}i:2;i:6391;}i:156;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6524;}i:157;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6524;}i:158;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:135:"### Deuxième étape
Cette étape permet de définir les paramètres d’accès au serveur PostgreSQL et le nom de la base de données.";}i:2;i:6526;}i:159;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:6661;}i:160;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:6661;}i:161;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:6663;}i:162;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:9:"Attention";}i:2;i:6665;}i:163;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:6674;}i:164;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:55:", pour que cela fonctionne, l’utilisateur PostgreSQL ";}i:2;i:6676;}i:165;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:6731;}i:166;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:30:"doit posséder un mot de passe";}i:2;i:6733;}i:167;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:6763;}i:168;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:199:". En effet, par sécurité, il est impératif que le compte utilisateur servant pour la connexion à la base de données PostgreSQL soit protégé. Les droits de ce compte doivent être au minimum : ";}i:2;i:6765;}i:169;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:6964;}i:170;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:74:"`SELECT`, `INSERT`, `UPDATE`, `DELETE`, `CREATE`, `FILE`, `INDEX`, `ALTER`";}i:2;i:6966;}i:171;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:7040;}i:172;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:1:".";}i:2;i:7042;}i:173;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:7043;}i:174;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:7043;}i:175;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:63:"Une fois les paramètres entrés, cliquez sur `vérification`. ";}i:2;i:7045;}i:176;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:7108;}i:177;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:7108;}i:178;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:376:"Si vous obtenez des croix rouges alors le nom d’utilisateur et/ou le mot de passe sont erronés. Le champ `Serveur PostgreSQL` est par défaut renseigné avec `localhost`. Cela signifie que le serveur PostgreSQL est sur la même machine que le serveur web Apache. Si le serveur PostgreSQL n’est pas sur la même machine alors renseignez dans le champ `Serveur PostgreSQL` ";}i:2;i:7110;}i:179;a:3:{i:0;s:11:"strong_open";i:1;a:0:{}i:2;i:7486;}i:180;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:14:"l’adresse IP";}i:2;i:7488;}i:181;a:3:{i:0;s:12:"strong_close";i:1;a:0:{}i:2;i:7502;}i:182;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:14:" de celle-ci. ";}i:2;i:7504;}i:183;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:7518;}i:184;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:7518;}i:185;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:200:"Si après la vérification vous avez des croix rouge alors les données renseignées sont erronées.
Vous aurez également un message d’erreur serveur, juste au-dessus du champ `serveur Postgresql`.";}i:2;i:7520;}i:186;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:7720;}i:187;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:7720;}i:188;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:176:"### Troisième étape
Cette étape est celle de la création de la base de données avec l’ensemble des tables, si tout se passe bien vous pouvez passer à l'étape suivante.";}i:2;i:7722;}i:189;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:7898;}i:190;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:7898;}i:191;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:105:"S’il y a un problème durant la création des tables alors vous obtenez un retour des erreurs en rouge.";}i:2;i:7900;}i:192;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:8005;}i:193;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:8005;}i:194;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:21:"### Quatrième étape";}i:2;i:8007;}i:195;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:8028;}i:196;a:3:{i:0;s:6:"p_open";i:1;a:0:{}i:2;i:8028;}i:197;a:3:{i:0;s:6:"entity";i:1;a:1:{i:0;s:3:"...";}i:2;i:8030;}i:198;a:3:{i:0;s:5:"cdata";i:1;a:1:{i:0;s:0:"";}i:2;i:8033;}i:199;a:3:{i:0;s:7:"p_close";i:1;a:0:{}i:2;i:8033;}i:200;a:3:{i:0;s:12:"document_end";i:1;a:0:{}i:2;i:8033;}}