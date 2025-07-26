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

// Si l'ID du jouet est passé dans l'URL
if (isset($_GET['id_jouet'])) {
    $id_jouet = $_GET['id_jouet'];
    $conn = connexion_OCI();
    
    // Récupérer les informations du jouet à modifier
    $selectQuery = "SELECT * FROM jouet WHERE id_jouet = :id_jouet";
    $stid = oci_parse($conn, $selectQuery);
    oci_bind_by_name($stid, ":id_jouet", $id_jouet);
    oci_execute($stid);

    // Récupérer les résultats
    $jouet = oci_fetch_assoc($stid);
}

// Mise à jour du jouet
if (isset($_POST['update'])) {
    $id_jouet = $_POST['id_jouet'];
    $nom_jouet = $_POST['nom_jouet'];
    $type = $_POST['type'];
    $statut = $_POST['statut'];
    $est_substitue_par = $_POST['est_substitue_par'];

    $conn = connexion_OCI();
    $updateQuery = "UPDATE jouet SET nom_jouet = :nom_jouet, type = :type, statut = :statut, est_substitue_par = :est_substitue_par
                    WHERE id_jouet = :id_jouet";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":id_jouet", $id_jouet);
    oci_bind_by_name($stid, ":nom_jouet", $nom_jouet);
    oci_bind_by_name($stid, ":type", $type);
    oci_bind_by_name($stid, ":statut", $statut);
    oci_bind_by_name($stid, ":est_substitue_par", $est_substitue_par);
    oci_execute($stid);
    
    header("Location: jouet.php"); // Rediriger vers la page des jouets après la mise à jour
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Jouet</title>
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
        .actions a {
            text-decoration: none;
            color: red;
            margin: 0 10px;
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
    <h1>Modifier le Jouet</h1>

    <!-- Formulaire de modification du jouet -->
    <form method="POST">
        <input type="hidden" name="id_jouet" value="<?php echo $jouet['ID_JOUET']; ?>" required>
        <input type="text" name="nom_jouet" placeholder="Nom du Jouet" value="<?php echo $jouet['NOM_JOUET']; ?>" required>
        <input type="text" name="type" placeholder="Type du Jouet" value="<?php echo $jouet['TYPE']; ?>" required>
        <input type="text" name="statut" placeholder="Statut du Jouet" value="<?php echo $jouet['STATUT']; ?>" required>
                    <select name="est_substitue_par" >
            <option value="">Choisir un Jouet Substitué</option>
            <?php
            // Récupération des tournées disponibles dans la base de données
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_jouet, nom_jouet FROM jouet";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_JOUET'] . "'>" . $row['NOM_JOUET'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="update" value="Mettre à jour le Jouet">
    </form>
</div>

</body>
</html>


