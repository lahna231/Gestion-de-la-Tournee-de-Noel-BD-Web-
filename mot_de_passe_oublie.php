<?php
session_start();// Démarre une session PHP
include 'db_connect.php'; // Connexion à la base Oracle

$message = "";// Variable pour stocker les messages d'erreur ou de succès
$success = false;// Variable indiquant si l'opération a réussi qu'on a initialisé a false 
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_actuel = htmlspecialchars(trim($_POST['login_actuel'])); //  récupère le login actuel
    $nv_mdp = htmlspecialchars(trim($_POST['nv_mdp']));//  récupère le nouveau mot de passe
    $confirm_mdp = htmlspecialchars(trim($_POST['confirm_mdp'])); // récupère la confirmation du mot de passe

    // Vérifie si tous les champs sont remplis
    if (empty($login_actuel) || empty($nv_mdp) || empty($confirm_mdp)) {
        $message = "Vous devez entrer un login, un nouveau mot de passe et la confirmation du mot de passe.";
    } elseif ($nv_mdp !== $confirm_mdp) {// Vérifie si les mots de passe correspondent
        $message = "Les mots de passe ne correspondent pas.";//donc s'ils sont pas pareil ca affiche ce message d'erreur 
    } else {
        // Vérifier si le login existe car il est important de passer son login pour pouvoir le chercher dans la table lutin
        $query = "SELECT login FROM lutin WHERE login = :login_actuel";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':login_actuel', $login_actuel);
        oci_execute($stid);

        if ($row = oci_fetch_assoc($stid)) {
            // Hachage du nouveau mot de passe pour plus de sécurité
            $hashed_password = hash('sha256', $nv_mdp);
            
            // Mise à jour du mot de passe
            $update_query = "UPDATE lutin SET password = :nv_mdp WHERE login = :login_actuel";
            $update_stid = oci_parse($conn, $update_query);
            oci_bind_by_name($update_stid, ':nv_mdp', $hashed_password);
            oci_bind_by_name($update_stid, ':login_actuel', $login_actuel);
             // Exécute la requête et vérifie si la mise à jour a réussi
            if (oci_execute($update_stid)) {
                $message = "Votre mot de passe a été mis à jour avec succès.";
                $success = true;
            } else {
                $message = "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            $message = "Identifiant incorrect ou inexistant.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier votre mot de passe</title><!-- Titre de la page -->
    <style>
        body {
            background-image: url('fond.jpg');/* Image de fond */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        fieldset {
            background-color: rgba(255, 255, 255, 0.8);/* Fond semi-transparent */
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
    <legend><b>Modifier votre mot de passe</b></legend>

    <?php if ($success): ?>
        <p class="success"><?php echo $message; ?></p>
        <a href="index.php" class="btn-retour">Retour à la page de connexion</a>
    <?php else: ?>
        <form method="post">
            <label for="login_actuel">Identifiant (Login) :</label>
            <input type="text" name="login_actuel" required><br><br>

            <label for="nv_mdp">Nouveau mot de passe :</label>
            <input type="password" name="nv_mdp" required><br><br>

            <label for="confirm_mdp">Confirmer le mot de passe :</label>
            <input type="password" name="confirm_mdp" required><br><br>

            <input type="submit" value="Modifier">
        </form>

        <?php if ($message): ?>
            <p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
    <?php endif; ?>

</fieldset>

</body>
</html>
