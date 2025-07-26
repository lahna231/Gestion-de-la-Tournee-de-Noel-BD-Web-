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

// Vérification de l'ID de l'équipe
if (isset($_GET['id_equipe'])) {
    $id_equipe = $_GET['id_equipe'];
    
    // Récupération des informations de l'équipe à modifier
    $conn = connexion_OCI();
    $query = "SELECT * FROM equipe WHERE id_equipe = :id_equipe";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_equipe", $id_equipe);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    
    // Si l'équipe n'existe pas
    if (!$row) {
        echo "Équipe introuvable!";
        exit();
    }

    // Traitement du formulaire de modification
    if (isset($_POST['update'])) {
        $nom_equipe = $_POST['nom_equipe'];
        $id_atelier = $_POST['id_atelier'];

        // Mise à jour des informations de l'équipe
        $updateQuery = "UPDATE equipe SET nom_equipe = :nom_equipe, id_atelier = :id_atelier WHERE id_equipe = :id_equipe";
        $stid = oci_parse($conn, $updateQuery);
        oci_bind_by_name($stid, ":nom_equipe", $nom_equipe);
        oci_bind_by_name($stid, ":id_atelier", $id_atelier);
        oci_bind_by_name($stid, ":id_equipe", $id_equipe);
        oci_execute($stid);

        // Redirection vers la page de gestion des équipes après la mise à jour
        header("Location: equipe.php");
        exit();
    }
} else {
    echo "ID de l'équipe manquant!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'Équipe</title>
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
            color: red; /* Couleur modifiée ici */
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
            background-color: red; /* Changement de couleur ici */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        form input[type="submit"]:hover {
            background-color: darkred; /* Changement de couleur ici */
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
        footer img {
            width: 200px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier l'Équipe</h1>

    <!-- Formulaire de modification de l'équipe -->
    <form method="POST">
        <input type="text" name="nom_equipe" placeholder="Nom de l'Équipe" value="<?php echo htmlspecialchars($row['NOM_EQUIPE']); ?>" required>
            </select>
            <select name="id_atelier" >
            <option value="">Choisir un atelier</option>
            <?php
            // Récupération des tournées disponibles dans la base de données
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_atelier, nom_atelier FROM atelier";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_ATELIER'] . "'>" . $row['NOM_ATELIER'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="update" value="Mettre à jour l'Équipe">
    </form>
</div>

</body>
</html>

