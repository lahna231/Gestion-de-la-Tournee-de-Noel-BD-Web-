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

// Recherche des équipes
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM equipe WHERE LOWER(id_equipe) LIKE :searchTerm OR LOWER(nom_equipe) LIKE :searchTerm OR LOWER(id_atelier) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "gestion") !== false) {
        $gestionLink = "<a href='gestion.php'>Cliquez ici pour explorer la relation entre les équipes et les entrepots</a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "elfe") !== false) {
        $elfeLink = "<a href='elfe.php'>Cliquez ici pour explorer la relation entre les équipes et les elfes</a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "atelier") !== false) {
        $atelierLink = "<a href='atelier.php'>Cliquez ici pour explorer la relation entre les équipes et les Ateliers </a>";
    }
} else {
    $searchQuery = "SELECT * FROM equipe";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'une équipe
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM equipe WHERE id_equipe = :id_equipe";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_equipe", $deleteId);
    oci_execute($stid);
    header("Location: equipe.php"); // Recharger la page après suppression
    exit();
}

// Ajout d'une nouvelle équipe
if (isset($_POST['add'])) {
    $id_equipe = $_POST['id_equipe'];
    $nom_equipe = $_POST['nom_equipe'];
    $id_atelier = $_POST['id_atelier'];

    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO equipe (id_equipe, nom_equipe, id_atelier)
                    VALUES (:id_equipe, :nom_equipe, :id_atelier)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_equipe", $id_equipe);
    oci_bind_by_name($stid, ":nom_equipe", $nom_equipe);
    oci_bind_by_name($stid, ":id_atelier", $id_atelier);
    oci_execute($stid);
    header("Location: equipe.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Équipes</title>
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
    <h1>Gestion des Équipes</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'gestion' ou bien 'atelier' ou bien 'elfe' " value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens -->
    <?php
    if (isset($gestionLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $gestionLink . "</div>";
    }

    if (isset($elfeLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $elfeLink . "</div>";
    }

    if (isset($atelierLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $atelierLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Équipe</th>
                <th>Nom de l'Équipe</th>
                <th>ID Atelier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_EQUIPE'] . "</td>";
                echo "<td>" . $row['NOM_EQUIPE'] . "</td>";
                echo "<td>" . $row['ID_ATELIER'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_equipe.php?id_equipe=" . $row['ID_EQUIPE'] . "'>Modifier</a> |
                        <a href='equipe.php?delete_id=" . $row['ID_EQUIPE'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette équipe ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<!-- Formulaire d'ajout d'une équipe -->
<h2>Ajouter une Nouvelle Équipe</h2>
<form method="POST">
    <input type="text" name="id_equipe" placeholder="ID de l'Équipe" required>
    <input type="text" name="nom_equipe" placeholder="Nom de l'Équipe" required>
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

    <input type="submit" name="add" value="Ajouter l'Équipe">
</form>

</body>
</html>


