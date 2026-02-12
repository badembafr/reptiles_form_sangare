# Reptile - Authentification Sécurisée

Formulaire PHP natif avec sécurité

---

**Étudiant :** Bademba SANGARE  
**Formation :** M1 Informatique et Big Data  
**Cadre :** Théorie de l'information appliquée à la sécurité des systèmes

---

## Démo en ligne

Testez l'application : **https://bademba.fr/reptiles_form**

## Code source

GitHub : **https://github.com/badembafr/reptiles_form_sangare**

## Installation

### 1. Importer la base de données dans XAMPP

Ouvrir phpMyAdmin :
- Créer une base : `les_reptiles`
- Onglet "Importer"
- Sélectionner `database.sql`
- Cliquer "Exécuter"

### 2. Configuration

Éditer `config.php` si besoin :
```php
define('DB_USER', 'root');
define('DB_PASS', '');  // Le mot de passe MySQL
```

### 3. Accès

Placer les fichiers dans `htdocs/reptiles_form_sangare/`
Ouvrir `http://localhost/reptiles_form_sangare/`

## Utilisation

### Compte de test

Un compte existe déjà dans la base de données :

Identifiant : `christophe01`  
Mot de passe : `n1SD1cQIzUFnWy*M3%Y0`

### Créer un compte

1. Remplir identifiant et mot de passe
2. Cliquer sur "Ajout Compte"

**Règles :**
- Identifiant : 3-20 caractères (lettres et chiffres uniquement)
- Mot de passe : 8-64 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial

### Se connecter

1. Remplir identifiant et mot de passe
2. Cliquer sur "Valider"

## Sécurité

### Protection XSS
Échappement des sorties avec `htmlspecialchars()`
Header `X-XSS-Protection: 1; mode=block`

### Protection Clickjacking
Header `X-Frame-Options: DENY`

### Protection CSRF
Token unique par session vérifié sur chaque POST

### Protection SQL Injection
Requêtes préparées PDO
`ATTR_EMULATE_PREPARES => false`

### Mots de passe
Hachage `password_hash()` (bcrypt)
Vérification `password_verify()`

### Limitation tentatives
Maximum 5 tentatives de connexion
Blocage automatique au-delà

### Validation stricte
- Username : regex `^[a-zA-Z0-9]{3,20}$`
- Password : 8-64 caractères avec complexité obligatoire

## Fichiers

```
config.php      - Config BDD et sécurité
index.php       - Connexion et inscription
dashboard.php   - Page après connexion
logout.php      - Déconnexion
database/database.sql    - Script BDD
style/style.css       - Styles
img/logo.svg        - Logo
img/elephant-*.png  - Elephants PHP animés
```
