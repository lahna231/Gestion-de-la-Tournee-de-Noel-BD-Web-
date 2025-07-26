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

// Vérifier si un ID de traineau est passé dans l'URL
if (isset($_GET['id_traineau'])) {
    $id_traineau = $_GET['id_traineau'];

    // Récupérer les données du traineau à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM traineau WHERE id_traineau = :id_traineau";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_traineau", $id_traineau);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si le traineau n'existe pas
    if (!$row) {
        echo "Traîneau non trouvé.";
        exit();
    }
}

// Modifier les informations du traineau
if (isset($_POST['update'])) {
    $id_traineau = $_POST['id_traineau'];
    $nom_traineau = $_POST['nom_traineau'];
    $capacite_traineau = $_POST['capacite_traineau'];
    $poid = $_POST['poid'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE traineau
                    SET nom_traineau = :nom_traineau,
                        capacite_traineau = :capacite_traineau,
                        poid = :poid
                    WHERE id_traineau = :id_traineau";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_traineau", $id_traineau);
    oci_bind_by_name($stid, ":nom_traineau", $nom_traineau);
    oci_bind_by_name($stid, ":capacite_traineau", $capacite_traineau);
    oci_bind_by_name($stid, ":poid", $poid);
    oci_execute($stid);
    header("Location: traineau.php"); // Recharger la page des traineaux après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Traineau</title>
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
    <h1>Modifier le Traineau</h1>
</header>

<div class="container">

<div class="container">
    <div class="image-container">
        <!-- Image Noé -->
        <img src="fond.jpg" alt="Image de l'Elfe">
    </div>

    <h2>Modifier les informations du traineau </h2>
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_traineau.php?id_traineau=<?php echo $row['ID_TRAINEAU']; ?>">
        <input type="hidden" name="id_traineau" value="<?php echo $row['ID_TRAINEAU']; ?>"> <!-- L'ID reste caché et non modifiable -->

        <label for="nom_traineau">Nom du Traineau:</label>
        <input type="text" name="nom_traineau" placeholder="Nom du Traineau" value="<?php echo $row['NOM_TRAINEAU']; ?>" required>

        <label for="capacite_traineau">Capacité du Traineau:</label>
        <input type="text" name="capacite_traineau" placeholder="Capacité du Traineau" value="<?php echo $row['CAPACITE_TRAINEAU']; ?>" required>

        <label for="poid">Poids du Traineau:</label>
        <input type="text" name="poid" placeholder="Poids du Traineau" value="<?php echo $row['POID']; ?>" required>

        <input type="submit" name="update" value="Mettre à jour le Traineau">
    </form>
</div>

</body>
</html>


