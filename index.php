<?php
session_start();
include 'db_connect.php'; // Connexion à la base de données Oracle

// Initialisation de la variable d'erreur
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur = htmlspecialchars(trim($_POST['utilisateur']));
    $mdp = htmlspecialchars(trim($_POST['mdp']));

    if (!empty($utilisateur) && !empty($mdp)) {
        // Hacher le mot de passe fourni pour la comparaison
        $mdp_hache = hash('sha256', $mdp);

        // Vérifier si l'utilisateur existe avec le bon login et mot de passe haché
        $query = "SELECT login FROM lutin WHERE login = :utilisateur AND password = :mdp_hache";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':utilisateur', $utilisateur);
        oci_bind_by_name($stid, ':mdp_hache', $mdp_hache);
        oci_execute($stid);

        if ($row = oci_fetch_assoc($stid)) {
            // Connexion réussie, on stocke l'utilisateur en session
            $_SESSION['user'] = $utilisateur;
            header("Location: acceuil.php");
            exit();
        } else {
            // Identifiants incorrects
            $error = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        $error = "Vous devez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            background-image: url('fond.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-size: cover;
            font-family: Arial, sans-serif;
            position: relative;
        }
        fieldset {
            background-color: rgba(255, 255, 255, 0.8);
            color: red;
            width: 450px;
            padding: 30px;
            border: 3px solid #000;
            border-radius: 20px;
            box-shadow: 2px 2px 20px rgb(0, 0, 0);
            text-align: center;
            margin-bottom: 30px; 
        }
        input {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
        }
        input[type="submit"] {
            background-color: rgb(186, 0, 0);
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: rgb(115, 0, 29);
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: blue;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            text-decoration: underline;
        }

        

        

    </style>
</head>
<body>

<!-- Bloc de connexion -->
<fieldset>
    <legend><b>Connexion</b></legend>
    <form method="post">
        <label for="utilisateur">Nom d'utilisateur :</label>
        <input type="text" name="utilisateur" required><br><br>

        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp" required><br><br>

        <input type="submit" value="Se connecter">
    </form>
   
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <div class="links">
        <a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a>
        <br>
        <a href="inscription.php">Inscription</a>
    </div>
</fieldset>


<!-- Affichage des informations statiques -->
<fieldset>
    <div>
        <p><strong>Numéro de téléphone:</strong> +2137553539095</p>
        <p><strong>Adresse:</strong> 1234 North Pole Blvd, Fairbanks, Alaska, USA</p>
        <p><strong>Email:</strong> <a href="mailto:Chris.Kindle@gmail.com">Chris.Kindle@gmail.com</a></p>
    </div>
</fieldset>


</body>
</html>

