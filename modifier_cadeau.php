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

// Vérifier si un ID de cadeau est passé dans l'URL
if (isset($_GET['id_cadeau'])) {
    $id_cadeau = $_GET['id_cadeau'];

    // Récupérer les données du cadeau à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM cadeau WHERE id_cadeau = :id_cadeau";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_cadeau", $id_cadeau);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si le cadeau n'existe pas
    if (!$row) {
        echo "Cadeau non trouvé.";
        exit();
    }
}

// Modifier les informations du cadeau
if (isset($_POST['update'])) {
    $id_cadeau = $_POST['id_cadeau'];
    $nom_cadeau = $_POST['nom_cadeau'];
    $poid_cadeau = $_POST['poid_cadeau'];
    $statut_cadeau = $_POST['statut_cadeau'];
    $id_intermittent = $_POST['id_intermittent'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE cadeau
                    SET nom_cadeau = :nom_cadeau, poid_cadeau = :poid_cadeau, statut_cadeau = :statut_cadeau, id_intermittent = :id_intermittent
                    WHERE id_cadeau = :id_cadeau";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_cadeau", $id_cadeau);
    oci_bind_by_name($stid, ":nom_cadeau", $nom_cadeau);
    oci_bind_by_name($stid, ":poid_cadeau", $poid_cadeau);
    oci_bind_by_name($stid, ":statut_cadeau", $statut_cadeau);
    oci_bind_by_name($stid, ":id_intermittent", $id_intermittent);
    oci_execute($stid);
    header("Location: cadeau.php"); // Recharger la page des cadeaux après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cadeau</title>
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

        input[type="text"], input[type="number"], select {
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
    <h1>Modifier le Cadeau</h1>
</header>

<div class="container">
    <h2>Modifier les informations du Cadeau</h2>
    
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_cadeau.php?id_cadeau=<?php echo $row['ID_CADEAU']; ?>">
        <input type="hidden" name="id_cadeau" value="<?php echo $row['ID_CADEAU']; ?>"> <!-- L'ID reste caché et non modifiable -->
        
        <label for="nom_cadeau">Nom du Cadeau:</label>
        <input type="text" name="nom_cadeau" placeholder="Nom du Cadeau" value="<?php echo $row['NOM_CADEAU']; ?>" required>

        <label for="poid_cadeau">Poids du Cadeau:</label>
        <input type="number" name="poid_cadeau" placeholder="Poids du Cadeau" value="<?php echo $row['POID_CADEAU']; ?>" required>

        <label for="statut_cadeau">Statut du Cadeau:</label>
        <input type="text" name="statut_cadeau" placeholder="Statut du Cadeau" value="<?php echo $row['STATUT_CADEAU']; ?>" required>

        <label for="id_intermittent">Intermittent:</label>
        <select name="id_intermittent" >
            <option value="">Sélectionner un Intermittent</option>
            <?php
            // Récupérer les intermittents depuis la base de données
            $conn = connexion_OCI();
            $intermittentQuery = "SELECT id_intermittent, nom_intermittent FROM intermittent"; 
            $stid_intermittent = oci_parse($conn, $intermittentQuery);
            oci_execute($stid_intermittent);
            while ($intermittent = oci_fetch_assoc($stid_intermittent)) {
                $selected = ($intermittent['ID_INTERMITTENT'] == $row['ID_INTERMITTENT']) ? "selected" : "";
                echo "<option value='" . $intermittent['ID_INTERMITTENT'] . "' $selected>" . $intermittent['NOM_INTERMITTENT'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="update" value="Mettre à jour le Cadeau">
    </form>
</div>

</body>
</html>

