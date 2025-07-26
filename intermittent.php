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

// Recherche des intermittents
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM intermittent WHERE LOWER(id_intermittent) LIKE :searchTerm OR LOWER(nom_intermittent) LIKE :searchTerm OR LOWER(prenom_intermittent) LIKE :searchTerm OR LOWER(id_entrepot) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "cadeau") !== false) {
        $cadeauLink = "<a href='cadeau.php'>Cliquez ici pour explorer les relations entre les intermittents et les cadeaux</a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "paticiper") !== false) {
        $participerLink = "<a href='paticiper.php'>Cliquez ici pour explorer les relations entre les intermittents et les tournées</a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "entrepot") !== false) {
        $entrepotLink = "<a href='entrepot.php'>Cliquez ici pour explorer les relations entre les intermittents et les entrepôts</a>";
    }
} else {
    $searchQuery = "SELECT * FROM intermittent";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'un intermittent
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM intermittent WHERE id_intermittent = :id_intermittent";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_intermittent", $deleteId);
    oci_execute($stid);
    header("Location: intermittent.php"); // Recharger la page après suppression
    exit();
}

// Ajout d'un nouvel intermittent
if (isset($_POST['add'])) {
    $id_intermittent = $_POST['id_intermittent'];
    $nom_intermittent = $_POST['nom_intermittent'];
    $prenom_intermittent = $_POST['prenom_intermittent'];
    $id_entrepot = $_POST['id_entrepot'];

    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO intermittent (id_intermittent, nom_intermittent, prenom_intermittent, id_entrepot)
                    VALUES (:id_intermittent, :nom_intermittent, :prenom_intermittent, :id_entrepot)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_intermittent", $id_intermittent);
    oci_bind_by_name($stid, ":nom_intermittent", $nom_intermittent);
    oci_bind_by_name($stid, ":prenom_intermittent", $prenom_intermittent);
    oci_bind_by_name($stid, ":id_entrepot", $id_entrepot);
    oci_execute($stid);
    header("Location: intermittent.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Intermittents</title>
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
    <h1>Gestion des Intermittents</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'cadeau' ou bien 'paticiper' ou bien 'entrepot'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens-->
    <?php
    if (isset($cadeauLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $cadeauLink . "</div>";
    }

    if (isset($participerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $participerLink . "</div>";
    }

    if (isset($entrepotLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $entrepotLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Intermittent</th>
                <th>Nom de l'Intermittent</th>
                <th>Prénom de l'Intermittent</th>
                <th>ID Entrepot</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_INTERMITTENT'] . "</td>";
                echo "<td>" . $row['NOM_INTERMITTENT'] . "</td>";
                echo "<td>" . $row['PRENOM_INTERMITTENT'] . "</td>";
                echo "<td>" . $row['ID_ENTREPOT'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_intermittent.php?id_intermittent=" . $row['ID_INTERMITTENT'] . "'>Modifier</a> |
                        <a href='intermittent.php?delete_id=" . $row['ID_INTERMITTENT'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet intermittent ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un intermittent -->
    <h2>Ajouter un Nouvel Intermittent</h2>
    <form method="POST">
        <input type="text" name="id_intermittent" placeholder="ID de l'Intermittent" required>
        <input type="text" name="nom_intermittent" placeholder="Nom de l'Intermittent" required>
        <input type="text" name="prenom_intermittent" placeholder="Prénom de l'Intermittent" required>
   
            <select name="id_entrepot" >
            <option value="">Choisir un entrepot</option>
            <?php
            // Récupération des tournées disponibles dans la base de données
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_entrepot, nom_entrepot FROM entrepot";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_ENTREPOT'] . "'>" . $row['NOM_ENTREPOT'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="add" value="Ajouter l'Intermittent">
    </form>
</div>

</body>
</html>

