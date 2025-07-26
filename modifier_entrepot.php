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

// Vérifier si un ID d'entrepôt est passé dans l'URL
if (isset($_GET['id_entrepot'])) {
    $id_entrepot = $_GET['id_entrepot'];

    // Récupérer les données de l'entrepôt à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM entrepot WHERE id_entrepot = :id_entrepot";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_entrepot", $id_entrepot);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si l'entrepôt n'existe pas
    if (!$row) {
        echo "Entrepôt non trouvé.";
        exit();
    }
}

// Modifier les informations de l'entrepôt
if (isset($_POST['update'])) {
    $id_entrepot = $_POST['id_entrepot'];
    $nom_entrepot = $_POST['nom_entrepot'];
    $region = $_POST['region'];
    $id_tournee = $_POST['id_tournee'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE entrepot
                    SET nom_entrepot = :nom_entrepot,
                        region = :region,
                        id_tournee = :id_tournee
                    WHERE id_entrepot = :id_entrepot";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_entrepot", $id_entrepot);
    oci_bind_by_name($stid, ":nom_entrepot", $nom_entrepot);
    oci_bind_by_name($stid, ":region", $region);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_execute($stid);
    header("Location: entrepot.php"); // Recharger la page des entrepôts après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Entrepôt</title>
    <style>
      
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc; 
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* En-tête */
        header {
            background-color: red; 
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 24px;
        }

        /* Conteneur principal */
        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Titre de la page */
        h2 {
            color: red; 
            text-align: center;
        }

        /* Formulaire */
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
    <h1>Modifier l'Entrepôt</h1>
</header>

<div class="container">
    <div class="image-container">
        <!-- Image Noé -->
        <img src="fond.jpg" alt="Image de l'Entrepôt">
    </div>

    <h2>Modifier les informations de l'Entrepôt</h2>
    
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_entrepot.php?id_entrepot=<?php echo $row['ID_ENTREPOT']; ?>">
        <input type="hidden" name="id_entrepot" value="<?php echo $row['ID_ENTREPOT']; ?>"> <!-- L'ID reste caché et non modifiable -->
        
        <label for="nom_entrepot">Nom de l'Entrepôt:</label>
        <input type="text" name="nom_entrepot" placeholder="Nom de l'Entrepôt" value="<?php echo $row['NOM_ENTREPOT']; ?>" required>
        
        <label for="region">Région de l'Entrepôt:</label>
        <input type="text" name="region" placeholder="Région de l'Entrepôt" value="<?php echo $row['REGION']; ?>" required>

        <label for="id_tournee">Tournée de l'Entrepôt:</label>
        <select name="id_tournee" >
            <option value="">Sélectionner une tournée</option>
            <?php
            // Récupération des tournées disponibles dans la base de données
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_tournee, nom_tournee FROM tournee";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row_tournee = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row_tournee['ID_TOURNEE'] . "' " . (($row['ID_TOURNEE'] == $row_tournee['ID_TOURNEE']) ? 'selected' : '') . ">" . $row_tournee['NOM_TOURNEE'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="update" value="Mettre à jour l'Entrepôt">
    </form>
</div>

</body>
</html>

