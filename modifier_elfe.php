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

// Vérifier si un ID d'elfe est passé dans l'URL
if (isset($_GET['id_elfe'])) {
    $id_elfe = $_GET['id_elfe'];

    // Récupérer les données de l'elfe à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM elfe WHERE id_elfe = :id_elfe";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_elfe", $id_elfe);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);

    // Si l'elfe n'existe pas
    if (!$row) {
        echo "Elfe non trouvé.";
        exit();
    }
}

// Modifier les informations de l'elfe
if (isset($_POST['update'])) {
    $id_elfe = $_POST['id_elfe'];
    $nom_elfe = $_POST['nom_elfe'];
    $role = $_POST['role'];
    $id_equipe_se_regroupe = $_POST['id_equipe_se_regroupe'];
    $id_equipe_dirige = $_POST['id_equipe_dirige'];
    $id_elfe_remplace = $_POST['id_elfe_remplace'];

    // Mettre à jour les informations dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE elfe
                    SET nom_elfe = :nom_elfe,
                        role = :role,
                        id_equipe_se_regroupe = :id_equipe_se_regroupe,
                        id_equipe_dirige = :id_equipe_dirige,
                        id_elfe_remplace = :id_elfe_remplace
                    WHERE id_elfe = :id_elfe";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_elfe", $id_elfe);
    oci_bind_by_name($stid, ":nom_elfe", $nom_elfe);
    oci_bind_by_name($stid, ":role", $role);
    oci_bind_by_name($stid, ":id_equipe_se_regroupe", $id_equipe_se_regroupe);
    oci_bind_by_name($stid, ":id_equipe_dirige", $id_equipe_dirige);
    oci_bind_by_name($stid, ":id_elfe_remplace", $id_elfe_remplace);
    oci_execute($stid);
    header("Location: elfe.php"); // Recharger la page des elfes après modification
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Elfe</title>
    <style>
        /* Définir une police propre et moderne */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc; /* Fond clair */
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* En-tête */
        header {
            background-color: red; /* Changement de couleur ici */
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
            color: red; /* Changement de couleur ici */
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
            background-color: red; /* Changement de couleur ici */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: darkred; /* Changement de couleur ici */
        }

        /* Message d'erreur */
        .error {
            color: red;
            text-align: center;
        }

        /* Espacement entre les champs */
        .form-container {
            margin-top: 20px;
        }

        /* Image */
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
    <h1>Modifier l'Elfe</h1>
</header>

<div class="container">
    <div class="image-container">
        <!-- Image Noé -->
        <img src="fond.jpg" alt="Image de l'Elfe">
    </div>

    <h2>Modifier les informations de l'Elfe</h2>
    
    <!-- Formulaire de modification -->
    <form method="POST" action="modifier_elfe.php?id_elfe=<?php echo $row['ID_ELFE']; ?>">
        <input type="hidden" name="id_elfe" value="<?php echo $row['ID_ELFE']; ?>"> <!-- L'ID reste caché et non modifiable -->
        
        <label for="nom_elfe">Nom de l'Elfe:</label>
        <input type="text" name="nom_elfe" placeholder="Nom de l'Elfe" value="<?php echo $row['NOM_ELFE']; ?>" required>
        
        <label for="role">Rôle de l'Elfe:</label>
        <input type="text" name="role" placeholder="Rôle de l'Elfe" value="<?php echo $row['ROLE']; ?>" required>

             <select name="id_equipe_se_regroupe" >
            <option value="">Choisir une equipe pour se regrouper</option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_equipe, nom_equipe FROM equipe";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_EQUIPE'] . "'>" . $row['NOM_EQUIPE'] . "</option>";
            }
            ?>
        </select>


             <select name="id_equipe_dirige" >
            <option value="">Choisir une equipe a dériger </option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_equipe, nom_equipe FROM equipe";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_EQUIPE'] . "'>" . $row['NOM_EQUIPE'] . "</option>";
            }
            ?>
        </select>
        
             <select name="id_elfe_remplace" >
            <option value="">ID de l'Elfe Remplaçant </option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_elfe, nom_elfe FROM elfe";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_ELFE'] . "'>" . $row['NOM_ELFE'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="update" value="Mettre à jour l'Elfe">
    </form>
</div>

</body>
</html>
