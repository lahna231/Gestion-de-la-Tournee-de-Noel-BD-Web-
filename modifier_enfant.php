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

// Récupérer les informations de l'enfant à modifier
if (isset($_GET['id_enfant'])) {
    $id_enfant = $_GET['id_enfant'];
    $conn = connexion_OCI();
    $query = "SELECT * FROM enfant WHERE id_enfant = :id_enfant";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_enfant", $id_enfant);
    oci_execute($stid);

    if ($row = oci_fetch_assoc($stid)) {
        $id_enfant = $row['ID_ENFANT'];
        $nom_enfant = $row['NOM_ENFANT'];
        $pren_enfant = $row['PREN_ENFANT'];
        $adresse_enfant = $row['ADRESSE_ENFANT'];
        $id_tournee = $row['ID_TOURNEE'];
    } else {
        echo "Enfant non trouvé.";
        exit();
    }
}

// Mettre à jour les informations de l'enfant
if (isset($_POST['update'])) {
    $id_enfant = $_POST['id_enfant'];
    $nom_enfant = $_POST['nom_enfant'];
    $pren_enfant = $_POST['pren_enfant'];
    $adresse_enfant = $_POST['adresse_enfant'];
    $id_tournee = $_POST['id_tournee'];

    $conn = connexion_OCI();
    $updateQuery = "UPDATE enfant SET nom_enfant = :nom_enfant, pren_enfant = :pren_enfant, adresse_enfant = :adresse_enfant, id_tournee = :id_tournee WHERE id_enfant = :id_enfant";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":nom_enfant", $nom_enfant);
    oci_bind_by_name($stid, ":pren_enfant", $pren_enfant);
    oci_bind_by_name($stid, ":adresse_enfant", $adresse_enfant);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_bind_by_name($stid, ":id_enfant", $id_enfant);
    oci_execute($stid);
    
    header("Location: enfant.php"); // Redirige après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Enfant</title>
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
            max-width: 1000px;
        }
        h1 {
            text-align: center;
            color: red;
            font-size: 2em;
            margin-bottom: 20px;
        }
        form input, form select {
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
    <h1>Modifier l'Enfant</h1>

    <!-- Formulaire de modification -->
    <form method="POST">
        <input type="text" name="id_enfant" value="<?php echo $id_enfant; ?>" readonly>
        <input type="text" name="nom_enfant" value="<?php echo $nom_enfant; ?>" required>
        <input type="text" name="pren_enfant" value="<?php echo $pren_enfant; ?>" required>
        <input type="text" name="adresse_enfant" value="<?php echo $adresse_enfant; ?>" required>
            <select name="id_tournee" >
            <option value="">Choisir une tournée</option>
            <?php
            // Récupération des tournées disponibles dans la base de données
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_tournee, nom_tournee FROM tournee";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_TOURNEE'] . "'>" . $row['NOM_TOURNEE'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="update" value="Mettre à jour">
    </form>
</div>

</body>
</html>


