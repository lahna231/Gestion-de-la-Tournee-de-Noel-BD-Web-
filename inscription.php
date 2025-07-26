<?php
session_start();// Démarre une session PHP
include 'db_connect.php'; // Connexion à la base de données Oracle

$message = "";// Initialise une variable pour stocker les messages d'erreur ou de succès
$success = false;// Variable pour indiquer si l'inscription a réussi ou non


if ($_SERVER["REQUEST_METHOD"] == "POST") { // Vérifie si le formulaire a été soumis
    $login = htmlspecialchars(trim($_POST['login']));// Récupère  login saisi
    $password = htmlspecialchars(trim($_POST['password']));// Récupère  le mot de passe
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));// Récupère  la confirmation du mot de passe

    if (empty($login) || empty($password) || empty($confirm_password)) { // Vérifie si tous les champs sont remplis
        $message = "Tous les champs sont obligatoires.";
    } elseif (strlen($login) > 16) { // Vérifie que l'identifiant ne dépasse pas 16 caractères car notre login dans la table lutin est un varchar de 16 caractéres
        $message = "L'identifiant ne doit pas dépasser 16 caractères.";
    } elseif ($password !== $confirm_password) { // Vérifie que les mots de passe sont pareils
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'identifiant existe déjà
        $check_query = "SELECT login FROM lutin WHERE login = :login";
        $stid = oci_parse($conn, $check_query);// Prépare la requête SQL
        oci_bind_by_name($stid, ':login', $login); // Lie le paramètre
        oci_execute($stid); // Exécute la requête

        if (oci_fetch_assoc($stid)) {
            $message = "Cet identifiant est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashed_password = hash('sha256', $password);

            // Insertion dans la base dans notre table lutin
            $insert_query = "INSERT INTO lutin (login, password) VALUES (:login, :password)";
            $insert_stid = oci_parse($conn, $insert_query);// Prépare la requête d'insertion
            oci_bind_by_name($insert_stid, ':login', $login);// Lie le paramètre login
            oci_bind_by_name($insert_stid, ':password', $hashed_password);// Lie le mot de passe haché

            if (oci_execute($insert_stid)) {// Exécute la requête et vérifie si l'insertion a réussi
                $message = "Inscription réussie !";
                $success = true;
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            background-image: url('fond.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        fieldset {
            background-color: rgba(255, 255, 255, 0.8);
            color: red;
            width: 400px;
            padding: 30px;
            border: 3px solid #000;
            border-radius: 20px;
            box-shadow: 2px 2px 20px rgb(0, 0, 0);
            text-align: center;
        }
        input {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
        }
        input[type="submit"], .btn-retour {
            background-color: rgb(186, 0, 0);
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            padding: 10px;
            width: 100%;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }
        input[type="submit"]:hover, .btn-retour:hover {
            background-color: rgb(115, 0, 29);
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
        .success {
            color: green;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<fieldset>
    <legend><b>Inscription</b></legend>

    <?php if ($success): ?> 
        <p class="success"><?php echo $message; ?></p>
        <a href="index.php" class="btn-retour">Retour à la page de connexion</a>
    <?php else: ?>
        <form method="post">
            <label for="login">Identifiant :</label>
            <input type="text" name="login" required><br><br>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" required><br><br>

            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" required><br><br>

            <input type="submit" value="S'inscrire">
        </form>

        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
    <?php endif; ?>

</fieldset>

</body>
</html>
	

