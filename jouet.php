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

// Recherche des jouets
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM jouet WHERE LOWER(id_jouet) LIKE :searchTerm OR LOWER(nom_jouet) LIKE :searchTerm OR LOWER(type) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "lister") !== false) {
        $listerLink = "<a href='lister.php'>Cliquez ici pour explorer la relation entre les jouets et les enfants  </a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "produireatelier") !== false) {
        $produireAtelierLink = "<a href='produireAtelier.php'>Cliquez ici pour explorer la relation entre les jouets et les Ateliers  </a>";
    }

    if (strpos(strtolower($_POST['searchTerm']), "produiresoustraitant") !== false) {
        $produireSousTraitantLink = "<a href='produireSousTraitant.php'>Cliquez ici pour explorer la relation entre les jouets et les sous_traitants </a>";
    }
} else {
    $searchQuery = "SELECT * FROM jouet";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'un jouet
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM jouet WHERE id_jouet = :id_jouet";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_jouet", $deleteId);
    oci_execute($stid);
    header("Location: jouet.php"); // Recharger la page après suppression
    exit();
}

// Ajout d'un nouveau jouet
if (isset($_POST['add'])) {
    $id_jouet = $_POST['id_jouet'];
    $nom_jouet = $_POST['nom_jouet'];
    $type = $_POST['type'];
    $statut = $_POST['statut'];
    $est_substitue_par = $_POST['est_substitue_par'];

    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO jouet (id_jouet, nom_jouet, type, statut, est_substitue_par)
                    VALUES (:id_jouet, :nom_jouet, :type, :statut, :est_substitue_par)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_jouet", $id_jouet);
    oci_bind_by_name($stid, ":nom_jouet", $nom_jouet);
    oci_bind_by_name($stid, ":type", $type);
    oci_bind_by_name($stid, ":statut", $statut);
    oci_bind_by_name($stid, ":est_substitue_par", $est_substitue_par);
    oci_execute($stid);
    header("Location: jouet.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Jouets</title>
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
    <h1>Gestion des Jouets</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'lister' ou bien 'produireAtelier' ou bien 'produireSousTraitant'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens -->
    <?php
    if (isset($listerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $listerLink . "</div>";
    }

    if (isset($produireAtelierLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $produireAtelierLink . "</div>";
    }

    if (isset($produireSousTraitantLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $produireSousTraitantLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Jouet</th>
                <th>Nom du Jouet</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Est Substitué Par</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_JOUET'] . "</td>";
                echo "<td>" . $row['NOM_JOUET'] . "</td>";
                echo "<td>" . $row['TYPE'] . "</td>";
                echo "<td>" . $row['STATUT'] . "</td>";
                echo "<td>" . $row['EST_SUBSTITUE_PAR'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_jouet.php?id_jouet=" . $row['ID_JOUET'] . "'>Modifier</a> |
                        <a href='jouet.php?delete_id=" . $row['ID_JOUET'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce jouet ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un jouet -->
    <h2>Ajouter un Nouveau Jouet</h2>
    <form method="POST">
        <input type="text" name="id_jouet" placeholder="ID du Jouet" required>
        <input type="text" name="nom_jouet" placeholder="Nom du Jouet" required>
        <input type="text" name="type" placeholder="Type du Jouet" required>
        <input type="text" name="statut" placeholder="Statut du Jouet" required>

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

        <input type="submit" name="add" value="Ajouter le Jouet">
    </form>
</div>

</body>
</html>

