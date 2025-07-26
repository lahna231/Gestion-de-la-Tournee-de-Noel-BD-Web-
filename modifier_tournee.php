<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); // Paramètres de la connexion à la base de données
    $conn = oci_connect(MYUSER, MYPASS, MYHOST);  // Remplace MYUSER, MYPASS, MYHOST par tes vraies infos de connexion
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        return $conn;
    }
}

// Vérifier si un ID de tournée est passé dans l'URL
if (isset($_GET['id_tournee'])) {
    $id_tournee = $_GET['id_tournee'];

    // Récupérer les données de la tournée à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM tournee WHERE id_tournee = :id_tournee";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si la tournée n'existe pas
    if (!$row) {
        echo "Tournée non trouvée.";
        exit();
    }
}

// Modifier les informations de la tournée
if (isset($_POST['update'])) {
    $id_tournee = $_POST['id_tournee'];
    $nom_tournee = $_POST['nom_tournee'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE tournee
                    SET nom_tournee = :nom_tournee
                    WHERE id_tournee = :id_tournee";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_bind_by_name($stid, ":nom_tournee", $nom_tournee);
    oci_execute($stid);
    header("Location: tournee.php"); // Recharger la page des tournées après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Tournée</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: red;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 24px;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: red;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], select {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: darkred;
        }

        .error {
            color: red;
            text-align: center;
        }

        .form-container {
            margin-top: 20px;
        }

        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .image-container img {
            width: 200px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<header>
    <h1>Modifier la Tournée</h1>
</header>

<div class="container">
    <h2>Modifier les informations de la Tournée</h2>
    
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_tournee.php?id_tournee=<?php echo $row['ID_TOURNEE']; ?>">
        <input type="hidden" name="id_tournee" value="<?php echo $row['ID_TOURNEE']; ?>"> <!-- L'ID reste caché et non modifiable -->
        
        <label for="nom_tournee">Nom de la Tournée:</label>
        <input type="text" name="nom_tournee" placeholder="Nom de la Tournée" value="<?php echo $row['NOM_TOURNEE']; ?>" required>

        <input type="submit" name="update" value="Mettre à jour la Tournée">
    </form>
</div>

</body>
</html>

