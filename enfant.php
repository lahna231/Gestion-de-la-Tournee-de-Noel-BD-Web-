<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); 
    $conn = oci_connect(MYUSER, MYPASS, MYHOST); 
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        return $conn;
    }
}

// Recherche des enfants
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM enfant WHERE LOWER(id_enfant) LIKE :searchTerm OR LOWER(nom_enfant) LIKE :searchTerm OR LOWER(pren_enfant) LIKE :searchTerm OR LOWER(adresse_enfant) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "tournee") !== false) {
        $tourneeLink = "<a href='tournee.php'>Cliquez ici pour explorer la relation entre les enfants et les tournées</a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "lister") !== false) {
        $listerLink = "<a href='lister.php'>Cliquez ici pour explorer la relation entre les enfants et les jouets</a>";
    }
} else {
    $searchQuery = "SELECT * FROM enfant";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'un enfant
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM enfant WHERE id_enfant = :id_enfant";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_enfant", $deleteId);
    oci_execute($stid);
    header("Location: enfant.php"); //reaffichage de la page apres la supression
    exit();
}

// Ajout d'un nouvel enfant
if (isset($_POST['add'])) {
    $id_enfant = $_POST['id_enfant'];
    $nom_enfant = $_POST['nom_enfant'];
    $pren_enfant = $_POST['pren_enfant'];
    $adresse_enfant = $_POST['adresse_enfant'];
    $id_tournee = $_POST['id_tournee'];

    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO enfant (id_enfant, nom_enfant, pren_enfant, adresse_enfant, id_tournee)
                    VALUES (:id_enfant, :nom_enfant, :pren_enfant, :adresse_enfant, :id_tournee)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_enfant", $id_enfant);
    oci_bind_by_name($stid, ":nom_enfant", $nom_enfant);
    oci_bind_by_name($stid, ":pren_enfant", $pren_enfant);
    oci_bind_by_name($stid, ":adresse_enfant", $adresse_enfant);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_execute($stid);
    header("Location: enfant.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Enfants</title>
    <style>
body {
    background-image: url('fond.jpg');
    background-size: cover;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;  /* Alignement en haut */
    height: 100vh;
    overflow: auto;  /* S'assurer que tout le contenu est visible */
}

.container {
    background-color: rgba(255, 255, 255, 0.8);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 70%;
    max-width: 1000px;
    position: relative; /* Pour positionner les éléments fixes par rapport à ce conteneur */
    overflow: auto; /* Permet à la table de défiler sans affecter les autres éléments */
    max-height: 90vh; /* Limite la hauteur pour éviter de dépasser */
}

h1 {
    text-align: center;
    color: red;
    font-size: 2em;
    margin-bottom: 20px;
}

.search-form, .links {
    position: sticky;
    top: 0;
    background-color: rgba(255, 255, 255, 0.9);  
    z-index: 10; /* Pour s'assurer que ces éléments soient au-dessus du reste */
    padding: 20px 0;
    margin-bottom: 20px;
}

.search-form form, .links div {
    display: flex;
    justify-content: center;
    gap: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    max-height: 60vh;
    overflow-y: auto;
    display: block;  /* Permet de scroller la table indépendamment */
}

table, th, td {
    border: 1px solid red;
}

th, td {
    width: 30%;
    padding: 10px;
    text-align: center;
}

th {
    background-color: red;
    color: white;
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
    <h1>Gestion des Enfants</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'tournee' ou bien 'lister'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens -->
    <?php
    if (isset($tourneeLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $tourneeLink . "</div>";
    }

    if (isset($listerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $listerLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Enfant</th>
                <th>Nom de l'Enfant</th>
                <th>Prénom de l'Enfant</th>
                <th>Adresse de l'Enfant</th>
                <th>ID Tournee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_ENFANT'] . "</td>";
                echo "<td>" . $row['NOM_ENFANT'] . "</td>";
                echo "<td>" . $row['PREN_ENFANT'] . "</td>";
                echo "<td>" . $row['ADRESSE_ENFANT'] . "</td>";
                echo "<td>" . $row['ID_TOURNEE'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_enfant.php?id_enfant=" . $row['ID_ENFANT'] . "'>Modifier</a> |
                        <a href='enfant.php?delete_id=" . $row['ID_ENFANT'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet enfant ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un enfant -->
    <h2>Ajouter un Nouveau Enfant</h2>
    <form method="POST">
        <input type="text" name="id_enfant" placeholder="ID de l'Enfant" required>
        <input type="text" name="nom_enfant" placeholder="Nom de l'Enfant" required>
        <input type="text" name="pren_enfant" placeholder="Prénom de l'Enfant" required>
        <input type="text" name="adresse_enfant" placeholder="Adresse de l'Enfant" required>
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

        <input type="submit" name="add" value="Ajouter l'Enfant">
    </form>
</div>

</body>
</html>


