<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php');
    exit;  
}

$username = $_SESSION['username']; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reptiles - Dashboard</title>
    <link rel="stylesheet" href="style/style.css"> 
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo.svg" alt="Logo Référence Open Source"> 
        </div>
        
        <h1>DASHBOARD</h1> 
        
        <div class="info"> 
            <p>Bienvenue <strong><?= escape($username) ?></strong></p>
            <p>Vous êtes connecté</p> 
        </div>
        
        <div class="buttons">
            <a href="logout.php" class="btn-logout">Déconnexion</a> 
        </div>
    </div>
    
    <img src="img/elephant-blue.png" class="elephant e1" alt=""> 
    <img src="img/elephant-pink.png" class="elephant e2" alt="">
    <img src="img/elephant-blue.png" class="elephant e3" alt=""> 
</body>
</html>
