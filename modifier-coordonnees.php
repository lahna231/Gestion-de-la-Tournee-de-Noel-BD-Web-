<?php
session_start(); // Démarre la session
include 'db_connect.php'; // Connexion à la base Oracle

$message = "";// Initialisation du message d'erreur ou de succès
$success = false;// Variable pour indiquer si l'opération a réussi

if ($_SERVER["REQUEST_METHOD"] == "POST") {// Vérifie si le formulaire a été soumis
    $login_actuel = htmlspecialchars(trim($_POST['login_actuel']));// Récupère  le login actuel
    $mdp_actuel = htmlspecialchars(trim($_POST['mdp_actuel']));// Récupère  le mot de passe actuel
    $nv_login = htmlspecialchars(trim($_POST['nv_login']));// Récupère le nouveau login
    $nv_mdp = htmlspecialchars(trim($_POST['nv_mdp']));// Récupère  le nouveau mot de passe

    if (empty($login_actuel) && empty($mdp_actuel)) {// Vérifie si login et mot de passe actuels sont vides
        $message = "Vous devez entrer soit votre login actuel, soit votre mot de passe actuel.";
    } elseif (empty($nv_login) && empty($nv_mdp)) {// Vérifie si les nouveaux identifiants sont vides
        $message = "Vous devez entrer soit un nouveau login, soit un nouveau mot de passe.";
    } else {
        // Hacher le mot de passe entré pour la comparaison
        $mdp_actuel_hache = hash('sha256', $mdp_actuel);// Hachage du mot de passe actuel  avec la fonction hash

        // Vérifier si l'utilisateur existe avec le login ou le mot de passe haché
        $query = "SELECT login FROM lutin WHERE login = :login_actuel OR password = :mdp_actuel_hache";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':login_actuel', $login_actuel);
        oci_bind_by_name($stid, ':mdp_actuel_hache', $mdp_actuel_hache);
        oci_execute($stid);

        if ($row = oci_fetch_assoc($stid)) {// Si l'utilisateur est trouvé dans la base de données
            $login_db = $row['LOGIN'];

            // Vérification si le nouveau login existe déjà
            if (!empty($nv_login)) {
                $check_query = "SELECT login FROM lutin WHERE login = :nv_login";//on recupere le login avec un select 
                $check_stid = oci_parse($conn, $check_query);
                oci_bind_by_name($check_stid, ':nv_login', $nv_login);
                oci_execute($check_stid);

                if (oci_fetch_assoc($check_stid)) {
                    $message = "Ce login est déjà utilisé.";
                    echo "<p class='error'>$message</p>";
                    exit();
                }
            }

            // Construction de la requête de mise à jour
            $update_query = "UPDATE lutin SET ";// Initialise la requête SQL pour la mise à jour 
            $params = []; // Initialise un tableau pour stocker les paramètres à lier à la requête

            if (!empty($nv_login)) {// Vérifie si un nouveau login a été saisi
                $update_query .= "login = :nv_login, "; // Ajoute le champ login à la requête avec un paramètre lié
                $params[':nv_login'] = $nv_login;// Stocke la nouvelle valeur du login dans le tableau des paramètres
            }
            if (!empty($nv_mdp)) { // Vérifie si un nouveau mot de passe a été saisi
                $hashed_password = hash('sha256', $nv_mdp);// Hache le mot de passe 
                $update_query .= "password = :nv_mdp, ";// Ajoute le champ password à la requête avec un paramètre lié
                $params[':nv_mdp'] = $hashed_password;// Stocke le mot de passe haché dans le tableau des paramètres
            }

            // Suppression de la dernière virgule et ajout de la condition WHERE
            $update_query = rtrim($update_query, ", ") . " WHERE login = :login_db";
            $params[':login_db'] = $login_db;

            // Exécuter la mise à jour
            $update_stid = oci_parse($conn, $update_query);
            foreach ($params as $key => $val) {
                oci_bind_by_name($update_stid, $key, $params[$key]);
            }

            if (oci_execute($update_stid)) {
                $message = "Vos informations ont été mises à jour avec succès.";
                $success = true;
            } else {
                $message = "Erreur lors de la mise à jour.";
            }
        } else {
            $message = "Identifiant ou mot de passe actuel incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier vos informations</title>
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
    <legend><b>Modifier vos informations</b></legend>

    <?php if ($success): ?>
        <p class="success"><?php echo $message; ?></p>
        <a href="acceuil.php" class="btn-retour">Retour à la page d'Acceuil</a>
    <?php else: ?>
        <form method="post">
            <label for="login_actuel">Identifiant (Login) actuel :</label>
            <input type="text" name="login_actuel"><br><br>

            <label for="mdp_actuel">Mot de passe actuel :</label>
            <input type="password" name="mdp_actuel"><br><br>

            <label for="nv_login">Nouveau identifiant (Login) :</label>
            <input type="text" name="nv_login"><br><br>

            <label for="nv_mdp">Nouveau mot de passe :</label>
            <input type="password" name="nv_mdp"><br><br>

            <input type="submit" value="Modifier">
        </form>

        <?php if ($message): ?>
        
            <p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
           
        <?php endif; ?>
    <?php endif; ?>

</fieldset>

</body>
</html>
	

