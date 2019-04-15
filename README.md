# Demo : Gestion de serveurs
## webservice + client

### Requis
Apache 2.4 est requis minimalement pour que les configurations fonctionnent (`conf/apache2/seedbox.conf`).

PHP 5.4.0 est également requis minimalement pour que le framework Yii2 fonctionne.

### Installation

#### Apache config
Les configs apache se trouvent dans le fichier `conf/apache2/seedbox.conf`. Il est important de remplacer les placeholders dans le fichier par les bonnes informations selon l'emplacement des fichiers du projet sur votre serveur. Vous trouverez les fichiers nécessaires pour les certificats SSL dans les dossiers `conf/ssl/mgmtservers` et `conf/ssl/zoneadmin`. Les fichiers ca.pem dans ces dossiers vous serviront pour autoriser l'_authority_ qui a délivré ces certificats (voir plus loin). 

Une fois que les path sont modifiées dans le fichier de configurations, il suffit de :

```
sudo a2ensite seedbox.conf
sudo service apache2 restart
```

Il reste à ajouter `mgmtservers.dev` et `zoneadmin.dev` dans vos hostnames et de les faire pointer vers 127.0.0.1 (ou toute autre configuration de votre côté! ). 

#### Base de données
Le fichier mgmtservers.sql dans le dossier `conf/mysql` contient le schéma de la base de données dont vous aurez besoin ainsi que quelques données (les données de la table User sont importantes). Installez cette base de données et aller modifier le fichier `rest/config/db.php` pour relier votre nouvelle base de données. L'application `client` ne nécessite pas de base de données.

#### Autoriser les Authorities sur votre navigateur
Il reste maintenant à faire reconnaitre les autorités qui ont signé les deux certificats SSL (client et rest) par votre navigateur. Pour Chrome la procédure est ici : https://support.google.com/chrome/a/answer/6342302?hl=en. Sur les autres navigateurs, la procédure est assez semblable et simple. Les deux autorités à faire reconnaître sont : `conf/ssl/mgmtservers/ca.pem` et `conf/ssl/zoneadmin/ca.pem`.

### Notes
* L'application cliente n'utilise pas de base de données, mais devrait définitivement en utiliser une pour garder en mémoire les utilisateurs autorisés. Pour le moment, c'est un tableau dans le code ce qui est très peu pratique et non sécuritaire.
* Les utilisateurs tests sont admin/admin et demo/demo. admin a un token valide pour se connecter au webservice alors que demo non. demo recoit donc toujours un message d'erreur.
* Évidemment, ceci n'est qu'un démo et l'application est loin d'être assez sécuritaire et de démontrer tout ce qu'une application du genre devrait pouvoir faire.
* __N'hésitez pas à me donner vos commentaires ou à m'écrire pour me poser des questions soit au niveau du code, du fonctionnement de l'application ou de l'installation et de la configuration.__

__Merci!__
__Fred__
