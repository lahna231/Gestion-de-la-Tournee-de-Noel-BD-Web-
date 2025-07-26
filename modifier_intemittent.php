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

// Vérifier si un ID d'intermittent est passé dans l'URL
if (isset($_GET['id_intermittent'])) {
    $id_intermittent = $_GET['id_intermittent'];

    // Récupérer les données de l'intermittent à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM intermittent WHERE id_intermittent = :id_intermittent";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_intermittent", $id_intermittent);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si l'intermittent n'existe pas
    if (!$row) {
        echo "Intermittent non trouvé.";
        exit();
    }
}

// Modifier les informations de l'intermittent
if (isset($_POST['update'])) {
    $id_intermittent = $_POST['id_intermittent'];
    $nom_intermittent = $_POST['nom_intermittent'];
    $prenom_intermittent = $_POST['prenom_intermittent'];
    $id_entrepot = $_POST['id_entrepot'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE intermittent
                    SET nom_intermittent = :nom_intermittent,
                        prenom_intermittent = :prenom_intermittent,
                        id_entrepot = :id_entrepot
                    WHERE id_intermittent = :id_intermittent";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_intermittent", $id_intermittent);
    oci_bind_by_name($stid, ":nom_intermittent", $nom_intermittent);
    oci_bind_by_name($stid, ":prenom_intermittent", $prenom_intermittent);
    oci_bind_by_name($stid, ":id_entrepot", $id_entrepot);
    oci_execute($stid);
    header("Location: intermittent.php"); // Recharger la page des intermittents après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'Intermittent</title>
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
    </style>
</head>
<body>

<header>
    <h1>Modifier l'Intermittent</h1>
</header>

<div class="container">
    <div class="image-container">
        <!-- Image Noé -->
        <img src="fond.jpg" alt="Image de l'Intermittent">
    </div>

    <h2>Modifier les informations de l'intermittent</h2>
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_intermittent.php?id_intermittent=<?php echo $row['ID_INTERMITTENT']; ?>">
        <input type="hidden" name="id_intermittent" value="<?php echo $row['ID_INTERMITTENT']; ?>"> <!-- L'ID reste caché et non modifiable -->

        <label for="nom_intermittent">Nom de l'Intermittent:</label>
        <input type="text" name="nom_intermittent" placeholder="Nom de l'Intermittent" value="<?php echo $row['NOM_INTERMITTENT']; ?>" required>

        <label for="prenom_intermittent">Prénom de l'Intermittent:</label>
        <input type="text" name="prenom_intermittent" placeholder="Prénom de l'Intermittent" value="<?php echo $row['PRENOM_INTERMITTENT']; ?>" required>

        <label for="id_entrepot">ID de l'Entrepôt:</label>
        <input type="text" name="id_entrepot" placeholder="ID de l'Entrepôt" value="<?php echo $row['ID_ENTREPOT']; ?>" required>

        <input type="submit" name="update" value="Mettre à jour l'Intermittent">
    </form>
</div>

</body>
</html>

