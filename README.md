
# Prime-Fitness - ECF Decembre 2022

<hr />
<img src="https://prime-fitness.site/Assets/Images/logo.svg" alt="logo eco it" width="500px" height="auto" />

<hr />


## Table des matières
- [Fichier Annexe](#fichier-annexe)
- [Lien](#lien)
- [Conditions requises d'utilisation](#condition-requise-dutilisation)
- [Récupération du projet](#recuperation-du-projet)
- [Installation](#installation)
- [Création de la base de données](#creation-de-la-base-de-donnes)
- [Modification des fichiers de configuration](#modification-des-fichiers-de-configuration)
- [Tester le projet localement](#tester-le-projet-localement)
- [Tester le projet en ligne](#tester-le-projet-en-ligne)

 ***

# Fichier Annexe

Les documents sont disponibles dans le dossier Documents :

* Charte graphique
* Manuel d'utilisation
* Documentation technique

 ***

# Lien
Voici les liens a visité en complement
* **Site en ligne à l'adresse :** https://prime-fitness.site
* **Use case :** https://www.figma.com/file/LgCqWMVgANqpoSc4EVTj1T/USE-case?node-id=10%3A32
* **WireFrame desktop :** https://www.figma.com/file/Z2X37vMDveTrZ8azD7xayc/Wireframe?node-id=0%3A1
* **wireFrame Mobile :** https://www.figma.com/file/Z2X37vMDveTrZ8azD7xayc/Wireframe?node-id=3%3A1514

 ***

# Condition requise d'utilisation
* PHP 7.4 ou supérieur
* Apache ou Nginx
* MySql ou MariaDB

    
# Recuperation du projet
Placer vous dans le dossier public de votre installation et faite
```
$ git clone https://github.com/Paul-NA/prime-fitness
```

 ***

# Installation


## Creation de la base de données
>Ouvrez le dossier Application/BDD vous y trouverez un fichier **_SQL.sql_**
>si vous ouvez ce fichier vous trouverez les commandes pour installer les tables manuellement.<br />
sinon vous pouvez ouvrir un gestionnaire de base de donnée et importé le fichier et tout sont contenue (Table et donnée).

## Modification des fichiers de configuration

Ouvrez le fichier Application/Config/envDatabase.php, celui-ci contiens toutes les informations nécessaires a la 
connexion à la base de données

```php
<?php
/**
 * Paramétrage de connexion à la base de donnée
 */
const DATABASE_DBNAME   = 'prime-fitness';
const DATABASE_HOST     = 'localhost';
const DATABASE_USER     = 'login';
const DATABASE_PASSWORD = 'password';

const DEBUG_SQL = false;
```

Ouvrez le fichier Application/Config/envWebsite.php, celui-ci contiens toutes les informations nécessaires au site
vous pouvez modifier l'adresse du site ainsi que le mail Expériteur et un nom 

```php
<?php
/**
 * Url du site par défaut
 */
const URI_ROOT = 'http://localhost/prime-fitness';

/**
 * Fichier configuration pour les emails
 */
const EMAIL_SYSTEM = 'no-reply@prime-fitness.site';
const EMAIL_NAME = 'Prime-Fitness';

// En mode dev false, en condition réelle on passe à true pour envoyé les mails
const SEND_EMAIL = false;

/**
 * Nombre d'item par page (dans /partner/list)
 */
const NUMBER_ITEM_PER_PAGE = 5;
```

***

# Tester le projet localement 

Pour essayer le projet localement vous devez vous rendre sur l'url par défaut du site (le dossier racine) après installation.

```
Login admin par défaut : admin@yopmail.com
Mot de passe admin par défaut : E54Ffzyr!re
```

***

# Tester le projet en ligne

rendez vous à l'addresse : https://prime-fitness.site

```
login admin et mot de passe par défaut identique que precedement
```

 ***


