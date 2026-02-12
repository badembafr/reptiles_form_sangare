<?php
session_start();

// Ne pas afficher les erreurs
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Failles XSS et Clickjacking 
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Configuration BDD
define('DB_HOST', 'localhost');
define('DB_NAME', 'les_reptiles');
define('DB_USER', 'root'); 
define('DB_PASS', ''); // le mot de passe de la bdd

// Limite de tentatives de connexion
define('MAX_ATTEMPTS', 5); 

// Connexion PDO
function getDb() {
    static $pdo = null;
    if ($pdo === null) {
        try { 
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die("Erreur BDD"); 
        }
    }
    return $pdo; 
}

// Token CSRF
function getCsrfToken() {
    if (empty($_SESSION['csrf'])) { 
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf']; 
}

function verifyCsrf($token) { 
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

// Protection XSS
function escape($data) { 
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>
