<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); // Inclut les paramètres de connexion à la base de données
    $conn = oci_connect(MYUSER, MYPASS, MYHOST);  // Connexion à la base de données avec les identifiants
    if (!$conn) {
        $e = oci_error();  // Si la connexion échoue, obtenir l'erreur
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR); // Afficher l'erreur
    } else {
        return $conn;  // Si la connexion réussit, retourner la connexion
    }
}

// Si l'ID de l'atelier est passé en GET, on récupère les informations de cet atelier
if (isset($_GET['id_atelier'])) {
    $id_atelier = $_GET['id_atelier'];  // Récupère l'ID de l'atelier
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $query = "SELECT * FROM atelier WHERE id_atelier = :id_atelier";  // Requête SQL pour récupérer l'atelier
    $stid = oci_parse($conn, $query);  // Parse la requête SQL
    oci_bind_by_name($stid, ":id_atelier", $id_atelier);  // Lie l'ID de l'atelier à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    $atelier = oci_fetch_assoc($stid);  // Récupère l'atelier dans un tableau associatif
}

// Si le formulaire est soumis, on met à jour l'atelier
if (isset($_POST['update'])) {
    $id_atelier = $_POST['id_atelier'];
    $nom_atelier = $_POST['nom_atelier'];
    $type = $_POST['type'];

    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $updateQuery = "UPDATE atelier SET nom_atelier = :nom_atelier, type = :type WHERE id_atelier = :id_atelier";  // Requête SQL pour mettre à jour l'atelier
    $stid = oci_parse($conn, $updateQuery);  
    oci_bind_by_name($stid, ":id_atelier", $id_atelier);  // Lie l'ID de l'atelier à la requête SQL
    oci_bind_by_name($stid, ":nom_atelier", $nom_atelier);  // Lie le nom de l'atelier à la requête SQL
    oci_bind_by_name($stid, ":type", $type);  // Lie le type de l'atelier à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    header("Location: atelier.php");  // Redirige vers la page atelier après la mise à jour
    exit();  // Quitte le script
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Atelier</title>
    <style>
        body {
            background-image: url('fond.jpg');
            background-size: cover;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 70%;
            max-width: 600px;
        }
        h1 {
            text-align: center;
            color: red;
            font-size: 2em;
            margin-bottom: 20px;
        }
        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form input[type="submit"] {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        form input[type="submit"]:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier un Atelier</h1>

    <?php if (isset($atelier)) : ?>
        <form method="POST">
            <input type="text" name="id_atelier" value="<?php echo $atelier['ID_ATELIER']; ?>" readonly>
            <input type="text" name="nom_atelier" value="<?php echo $atelier['NOM_ATELIER']; ?>" required>
            <input type="text" name="type" value="<?php echo $atelier['TYPE']; ?>" required>
            <input type="submit" name="update" value="Mettre à jour l'atelier">
        </form>
    <?php else : ?>
        <p>Atelier non trouvé.</p>
    <?php endif; ?>
</div>

</body>
</html>

