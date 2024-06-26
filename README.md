<h1 align="center">Forum POO-PHP-MVC</h1>
<h3 align="center"></h3>

<p align="left">Réalisation d'un Forum qui permettra une communication souple et intuitive entre les utilisateurs autorisés à y accéder.</p>

- SQL POO **Forum**

<h3 align="left">Fonctionnement de l'application:</h3>
<p align="left">

- La page d'accueil proposera un formulaire de connexion et un lien vers une page d'inscription.
- Une connexion préalable est obligatoire pour consulter tout ou partie du forum.
- Les visiteurs peuvent consulter les sujets et leur réponse sans restriction, ces sujets sont listés en première page et triés par date de création dans l'ordre antéchronologique.

</p>

<p align="left">

<h3 align="left">Technologies de l'application</h3>

- HTML, CSS, JS, JQuery, UIKIT langages de présentation (côté client).
- PHP en langage d'interpretation.
- PDO (PHP Data Object) pour l'exploitation de la base de données.
- MySQL 

<h3 align="left">Sécurisation</h3>

- Encryptage des données personnel AES-256-cbc avec clé privée et IV (prévoir durée jusqu'à effacement total) en cas de suppression de compte (offre anssi une possibilité de retour en arrière).
- hashage des données utilisateur, permettant de conserver topics et posts associés.
- possibilité de restaurer les données encryptés, lors de la récupération d'un compte.
- lutte contre la faille XSCF eXtended System Control Facility, XSS Cross-site Scripting, SQL
- intercepteur d'email et échange avec l'utilisateur sécurisé par token

<h3 align="left">Sécurisation à venir</h3>

- système d'éffacement des lignes dont la validité arrive a terme, par un cron.php
- lutte contre la faille upload

</p>

<h3 align="left">NOTE :</h3>

Utilisez la base de données fournie dans le dépot et modifiez le fichier si besoin

> app\DAO.php

```php
abstract class DAO{

    private static $host   = 'mysql:host=127.0.0.1;port=3306';
    private static $dbname = 'forum';
    private static $dbuser = 'root';
    private static $dbpass = '';

    private static $bdd;

```

<h3 align="left">Schémas:v2</h3>
<p align="left">

> Réalisation des schémas conceptuels de données :

 ![MCD](https://github.com/AnthonyM68/forum/blob/main/MCDv2.jpg)
 ![UML](https://github.com/AnthonyM68/forum/blob/main/UMLv2.jpg)
 ![MLD](https://github.com/AnthonyM68/forum/blob/main/MLDv2.jpg)
</p>

<h3 align="center">Languages and Tools:</h3>
<p align="center"> <a href="https://www.w3schools.com/css/" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original-wordmark.svg" alt="css3" width="40" height="40"/> </a> <a href="https://www.w3.org/html/" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original-wordmark.svg" alt="html5" width="40" height="40"/> </a> <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg" alt="javascript" width="40" height="40"/> </a> <a href="https://www.mysql.com/" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg" alt="mysql" width="40" height="40"/> </a><a href="https://www.php.net" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="40" height="40"/> </a> <a href="https://www.postgresql.org" target="_blank" rel="noreferrer"> <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/postgresql/postgresql-original-wordmark.svg" alt="postgresql" width="40" height="40"/> </a> </p>

