<?php
require_once 'config.php';

// Redirection si deja connecté
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';
$error = false;

// Règles de validation
$username_rules = "Identifiant : 3-20 caractères (lettres et chiffres uniquement)";
$password_rules = "Mot de passe : 8-64 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!verifyCsrf($_POST['csrf'] ?? '')) {
        $message = 'Erreur de validation du formulaire';
        $error = true;
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $action = $_POST['action'] ?? '';
        
        if (empty($username) || empty($password)) {
            $message = 'Tous les champs sont requis';
            $error = true;
        } else {
            
            // Validation username : lettres et chiffres uniquement, 3-20 caractères
            if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $username)) {
                $message = 'Identifiant invalide (3-20 caractères, lettres et chiffres uniquement)';
                $error = true;
            }
            // Validation mot de passe : 8-64 caractères, 1 maj, 1 min, 1 chiffre, 1 spécial
            elseif (strlen($password) < 8 || strlen($password) > 64) {
                $message = 'Mot de passe trop court ou trop long (8-64 caractères)';
                $error = true;
            }
            elseif (!preg_match('/[A-Z]/', $password)) {
                $message = 'Mot de passe doit contenir au moins 1 majuscule';
                $error = true;
            }
            elseif (!preg_match('/[a-z]/', $password)) {
                $message = 'Mot de passe doit contenir au moins 1 minuscule';
                $error = true;
            }
            elseif (!preg_match('/[0-9]/', $password)) {
                $message = 'Mot de passe doit contenir au moins 1 chiffre';
                $error = true;
            }
            elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
                $message = 'Mot de passe doit contenir au moins 1 caractère spécial';
                $error = true;
            }
            else {
                $pdo = getDb();
                
                // AJOUT COMPTE (Inscription)
                if ($action === 'register') {
                    
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                    $stmt->execute([$username]);
                    
                    if ($stmt->fetch()) {
                        $message = 'Cet identifiant existe déjà';
                        $error = true;
                    } else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                        
                        if ($stmt->execute([$username, $hash])) {
                            $message = 'Compte créé avec succès';
                            $error = false;
                        } else {
                            $message = 'Erreur lors de la création';
                            $error = true;
                        }
                    }
                }
                // VALIDER (Connexion)
                elseif ($action === 'login') {
                    
                    $stmt = $pdo->prepare("SELECT id, username, password, login_attempts FROM users WHERE username = ?");
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();
                    
                    if ($user) {
                        if ($user['login_attempts'] >= MAX_ATTEMPTS) {
                            $message = 'Compte bloqué';
                            $error = true;
                        }
                        elseif (password_verify($password, $user['password'])) {
                            // Connexion OK
                            $stmt = $pdo->prepare("UPDATE users SET login_attempts = 0, last_login = NOW() WHERE id = ?");
                            $stmt->execute([$user['id']]);
                            
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            session_regenerate_id(true);
                            
                            header('Location: dashboard.php');
                            exit;
                        } else {
                            // Mauvais mot de passe
                            $attempts = $user['login_attempts'] + 1;
                            $stmt = $pdo->prepare("UPDATE users SET login_attempts = ? WHERE id = ?");
                            $stmt->execute([$attempts, $user['id']]);
                            
                            $message = 'Identifiant ou mot de passe incorrect';
                            $error = true;
                        }
                    } else {
                        $message = 'Identifiant ou mot de passe incorrect';
                        $error = true;
                    }
                }
            }
        }
    }
}

$csrf = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Reptiles</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo.svg" alt="Reptile">
        </div>
        
        <h1>AUTHENTIFICATION</h1>
        
        <form method="POST">
            <input type="hidden" name="csrf" value="<?= escape($csrf) ?>">
            
            <div class="field">
                <label>Identifiant</label>
                <input type="text" name="username" required autofocus>
            </div>
            
            <div class="field">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="buttons">
                <button type="reset">Reset</button>
                <button type="submit" name="action" value="login">Valider</button>
                <button type="submit" name="action" value="register" class="btn-add">Ajout Compte</button>
            </div>
        </form>
        
        <?php if ($message): ?>
        <div class="msg <?= $error ? 'err' : 'ok' ?>">
            <?= escape($message) ?>
        </div>
        <?php endif; ?>
        
        <div class="rules">
            <div><?= $username_rules ?></div>
            <div><?= $password_rules ?></div>
        </div>
    </div>
    
    <img src="img/elephant-blue.png" class="elephant e1" alt="">
    <img src="img/elephant-pink.png" class="elephant e2" alt="">
    <img src="img/elephant-blue.png" class="elephant e3" alt="">
</body>
</html>
