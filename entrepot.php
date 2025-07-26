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

// Recherche des entrepôts
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM entrepot WHERE LOWER(id_entrepot) LIKE :searchTerm OR LOWER(nom_entrepot) LIKE :searchTerm OR LOWER(region) LIKE :searchTerm OR LOWER(id_tournee) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "tournee") !== false) {
        $link = "tournee.php";
        $linkText = "cliquez ici pour explorer la relation entre les entrepots et les tournées";
    }
    elseif (strpos(strtolower($_POST['searchTerm']), "send") !== false) {
        $link = "send.php";
        $linkText = "cliquez ici pour explorer la relation entre les entrepots et et les traineaux  ";
    }
    if (strpos(strtolower($_POST['searchTerm']), "gestion") !== false) {
        $link = "gestion.php";
        $linkText = "cliquez ici pour explorer la relation entre les entrepots et les équipes";
    }
    elseif (strpos(strtolower($_POST['searchTerm']), "intermittent") !== false) {
        $link = "intermittent.php";
        $linkText = "cliquez ici pour explorer la relation entre les entrepots et les intermittents";
    }
} else {
    $searchQuery = "SELECT * FROM entrepot";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression de l'entrepot 
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];  // Récupération de l'ID 
    $deleteQuery = "DELETE FROM entrepot WHERE id_entrepot = :id_entrepot";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_entrepot", $deleteId);
    oci_execute($stid);  // Suppression de l'entrepôt

    header("Location: entrepot.php"); //reaffichage de la page apres la supression
    exit(); 
}

// Ajout d'un nouvel entrepôt
if (isset($_POST['add'])) {
    // Récupération des données du formulaire
    $id_entrepot = $_POST['id_entrepot'];
    $nom_entrepot = $_POST['nom_entrepot'];
    $region = $_POST['region'];
    $id_tournee = $_POST['id_tournee'];  // Récupération de l'ID de la tournée

    // Insertion dans la base de données
    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO entrepot (id_entrepot, nom_entrepot, region, id_tournee)
                    VALUES (:id_entrepot, :nom_entrepot, :region, :id_tournee)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_entrepot", $id_entrepot);
    oci_bind_by_name($stid, ":nom_entrepot", $nom_entrepot);
    oci_bind_by_name($stid, ":region", $region);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_execute($stid);
    header("Location: entrepot.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Entrepôts</title>
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
    <h1>Gestion des Entrepôts</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder=" mot-clé ou Bien tapez 'tournee' ou 'send' ou 'gestion' ou 'intermittent'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Entrepôt</th>
                <th>Nom de l'Entrepôt</th>
                <th>Région</th>
                <th>ID Tournée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_ENTREPOT'] . "</td>";
                echo "<td>" . $row['NOM_ENTREPOT'] . "</td>";
                echo "<td>" . $row['REGION'] . "</td>";
                echo "<td>" . $row['ID_TOURNEE'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_entrepot.php?id_entrepot=" . $row['ID_ENTREPOT'] . "'>Modifier</a> |
                        <a href='entrepot.php' onclick='event.preventDefault(); if (confirm(\"Êtes-vous sûr de vouloir supprimer cet entrepôt ?\")) { document.getElementById(\"deleteForm" . $row['ID_ENTREPOT'] . "\").submit(); }'>Supprimer</a>
                      </td>";
                echo "</tr>";
                // Formulaire de suppression
                echo "<form id='deleteForm" . $row['ID_ENTREPOT'] . "' method='POST' action='entrepot.php'>
                        <input type='hidden' name='delete_id' value='" . $row['ID_ENTREPOT'] . "'>
                      </form>";
            }
            ?>
        </tbody>
    </table>
    
    <!-- Bloc des liens conditionnels -->
    <?php if (isset($link)): ?>
    <div class="renne-link">
        <a href="<?php echo $link; ?>"><?php echo $linkText; ?></a>
    </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'un entrepôt -->
    <h2>Ajouter un nouvel Entrepôt</h2>
    <form method="POST">
        <input type="text" name="id_entrepot" placeholder="ID de l'Entrepôt" required>
        <input type="text" name="nom_entrepot" placeholder="Nom de l'Entrepôt" required>
        <input type="text" name="region" placeholder="Région" required>
        <label for="id_tournee">Sélectionner une Tournée :</label>
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

        <input type="submit" name="add" value="Ajouter l'Entrepôt">
    </form>
</div>

</body>
</html>

