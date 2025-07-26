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

// Récupérer l'ID du renne à modifier
if (isset($_GET['id_puce'])) {
    $id_puce = $_GET['id_puce'];

    // Recherche des informations actuelles du renne
    $conn = connexion_OCI();
    $query = "SELECT * FROM renne WHERE id_puce = :id_puce";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id_puce", $id_puce);
    oci_execute($stid);
    
    // Récupérer les données du renne
    if ($row = oci_fetch_assoc($stid)) {
        $nom_renne = $row['NOM_RENNE'];
        $pos_renne = $row['POS_RENNE'];
        $couleur_nez = $row['COULEUR_NEZ'];
        $poid_renne = $row['POID_RENNE'];
        $id_traineau_gerer = $row['ID_TRAINEAU_GERER'];
        $id_traineau_tirer = $row['ID_TRAINEAU_TIRER'];
    } else {
        echo "Renne non trouvé.";
        exit();
    }
} else {
    echo "ID de renne non spécifié.";
    exit();
}

// Traitement du formulaire de modification
if (isset($_POST['update'])) {
    // Récupération des données du formulaire
    $nom_renne = $_POST['nom_renne'];
    $pos_renne = $_POST['pos_renne'];
    $couleur_nez = $_POST['couleur_nez'];
    $poid_renne = $_POST['poid_renne'];
    $id_traineau_gerer = $_POST['id_traineau_gerer'];
    $id_traineau_tirer = $_POST['id_traineau_tirer'];

    // Mettre à jour les informations du renne dans la base de données
    $conn = connexion_OCI();
    $updateQuery = "UPDATE renne SET 
                    nom_renne = :nom_renne, 
                    pos_renne = :pos_renne, 
                    couleur_nez = :couleur_nez, 
                    poid_renne = :poid_renne,
                    id_traineau_gerer = :id_traineau_gerer,
                    id_traineau_tirer = :id_traineau_tirer
                    WHERE id_puce = :id_puce";
    $stid = oci_parse($conn, $updateQuery);
    oci_bind_by_name($stid, ":nom_renne", $nom_renne);
    oci_bind_by_name($stid, ":pos_renne", $pos_renne);
    oci_bind_by_name($stid, ":couleur_nez", $couleur_nez);
    oci_bind_by_name($stid, ":poid_renne", $poid_renne);
    oci_bind_by_name($stid, ":id_traineau_gerer", $id_traineau_gerer);
    oci_bind_by_name($stid, ":id_traineau_tirer", $id_traineau_tirer);
    oci_bind_by_name($stid, ":id_puce", $id_puce);
    oci_execute($stid);

    // Redirection vers la page des rennes après la mise à jour
    header("Location: renne.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Renne</title>
    <style>
        /* Style global */
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 60%;
            max-width: 800px;
        }
        h1 {
            text-align: center;
            color: red;
            font-size: 2em;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            padding: 12px;
            font-size: 16px;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
        }
        input[type="submit"] {
            background-color: red;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: darkred;
        }
        input:focus, select:focus {
            border-color: red;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        .form-title {
            margin-bottom: 10px;
            font-size: 1.2em;
            color: red;
        }
        /* Section de retour ou d'alerte */
        .alert {
            margin-top: 20px;
            text-align: center;
            color: red;
            font-size: 1.1em;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
        }
        footer img {
            width: 150px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="image-container">
        <!-- Image Noé -->
        <img src="fond.jpg" alt="Image de l'Elfe">
    </div>

    <h1>Modifier un Renne</h1>

    <!-- Formulaire de modification d'un renne -->
    <form method="POST">
        <label for="nom_renne" class="form-title">Nom du Renne :</label>
        <input type="text" id="nom_renne" name="nom_renne" value="<?php echo htmlspecialchars($nom_renne); ?>" required>

        <label for="pos_renne" class="form-title">Position du Renne :</label>
        <input type="text" id="pos_renne" name="pos_renne" value="<?php echo htmlspecialchars($pos_renne); ?>" required>

        <label for="couleur_nez" class="form-title">Couleur du Nez :</label>
        <input type="text" id="couleur_nez" name="couleur_nez" value="<?php echo htmlspecialchars($couleur_nez); ?>" required>

        <label for="poid_renne" class="form-title">Poids du Renne :</label>
        <input type="text" id="poid_renne" name="poid_renne" value="<?php echo htmlspecialchars($poid_renne); ?>" required>

       
           <select name="id_traineau_gerer" >
            <option value="">Sélectionner un traîneau à gérer</option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_traineau, nom_traineau FROM traineau";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_TRAINEAU'] . "'>" . $row['NOM_TRAINEAU'] . "</option>";
            }
            ?>
        </select>


             <select name="id_traineau_tirer" >
            <option value="">Choisir uTraîneau à tirer : </option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_traineau, nom_traineau FROM traineau";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_TRAINEAU'] . "'>" . $row['NOM_TRAINEAU'] . "</option>";
            }
            ?>
        </select>



        <input type="submit" name="update" value="Mettre à jour le Renne">
    </form>
</div>



</body>
</html>

