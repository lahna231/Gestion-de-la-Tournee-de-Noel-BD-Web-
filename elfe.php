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

// Recherche des elfes
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM elfe WHERE LOWER(id_elfe) LIKE :searchTerm OR LOWER(nom_elfe) LIKE :searchTerm OR LOWER(role) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // Si l'utilisateur recherche "mener",
    if (strpos(strtolower($_POST['searchTerm']), "mener") !== false) {
        // Lien vers mener.php
        $menerLink = "<a href='mener.php'>Cliquez ici pour explorer la relation entre les elfes et les traineaux </a>";
    }

    // Si l'utilisateur recherche "entretenir",
    if (strpos(strtolower($_POST['searchTerm']), "entretenir") !== false) {
        // Lien vers entretenir.php
        $entretenirLink = "<a href='entretenir.php'>Cliquez ici pour explorer la relation entre les elfes et les traineaux et les rennes </a>";
    }

    // Si l'utilisateur recherche "spécialité_principale",
    if (strpos(strtolower($_POST['searchTerm']), "specialite_principale") !== false) {
        // Lien vers specialite_principale.php
        $specialitePrincipaleLink = "<a href='a_comme_specialite_principale.php'>Cliquez ici pour découvrir les spécialités principales des elfes</a>";
    }

    // Si l'utilisateur recherche "spécialité_secondaire",
    if (strpos(strtolower($_POST['searchTerm']), "specialite_secondaire") !== false) {
        // Lien vers specialite_secondaire.php
        $specialiteSecondaireLink = "<a href='a_comme_specialite_secondaire.php'>Cliquez ici pour découvrir les spécialités secondaires des elfes</a>";
    }
} else {
    $searchQuery = "SELECT * FROM elfe";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'un elfe
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM elfe WHERE id_elfe = :id_elfe";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_elfe", $deleteId);
    oci_execute($stid);
    header("Location: elfe.php"); // Recharger la page après suppression
    exit();
}

// Ajout d'un nouvel elfe
if (isset($_POST['add'])) {
    $id_elfe = $_POST['id_elfe'];
    $nom_elfe = $_POST['nom_elfe'];
    $role = $_POST['role'];
    $id_equipe_se_regroupe = $_POST['id_equipe_se_regroupe'];
    $id_equipe_dirige = $_POST['id_equipe_dirige'];
    $id_elfe_remplace = $_POST['id_elfe_remplace'];

    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO elfe (id_elfe, nom_elfe, role, id_equipe_se_regroupe, id_equipe_dirige, id_elfe_remplace)
                    VALUES (:id_elfe, :nom_elfe, :role, :id_equipe_se_regroupe, :id_equipe_dirige, :id_elfe_remplace)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_elfe", $id_elfe);
    oci_bind_by_name($stid, ":nom_elfe", $nom_elfe);
    oci_bind_by_name($stid, ":role", $role);
    oci_bind_by_name($stid, ":id_equipe_se_regroupe", $id_equipe_se_regroupe);
    oci_bind_by_name($stid, ":id_equipe_dirige", $id_equipe_dirige);
    oci_bind_by_name($stid, ":id_elfe_remplace", $id_elfe_remplace);
    oci_execute($stid);
    header("Location: elfe.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Elfes</title>
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
    <h1>Gestion des Elfes</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'mener' ou bien 'entretenir' ou bien 'specialite_principale' ou bien 'specialite_secondaire'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens pour "mener", "entretenir", "spécialité_principale" et "spécialité_secondaire" -->
    <?php
    if (isset($menerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $menerLink . "</div>";
    }

    if (isset($entretenirLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $entretenirLink . "</div>";
    }

    if (isset($specialitePrincipaleLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $specialitePrincipaleLink . "</div>";
    }

    if (isset($specialiteSecondaireLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $specialiteSecondaireLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Elfe</th>
                <th>Nom de l'Elfe</th>
                <th>Rôle</th>
                <th>Équipe Se Regroupe</th>
                <th>Équipe Dirige</th>
                <th>Elfe Remplace</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_ELFE'] . "</td>";
                echo "<td>" . $row['NOM_ELFE'] . "</td>";
                echo "<td>" . $row['ROLE'] . "</td>";
                echo "<td>" . $row['ID_EQUIPE_SE_REGROUPE'] . "</td>";
                echo "<td>" . $row['ID_EQUIPE_DIRIGE'] . "</td>";
                echo "<td>" . $row['ID_ELFE_REMPLACE'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_elfe.php?id_elfe=" . $row['ID_ELFE'] . "'>Modifier</a> |
                        <a href='elfe.php?delete_id=" . $row['ID_ELFE'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet elfe ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un elfe -->
    <h2>Ajouter un Nouveau Elfe</h2>
    <form method="POST">
        <input type="text" name="id_elfe" placeholder="ID de l'Elfe" required>
        <input type="text" name="nom_elfe" placeholder="Nom de l'Elfe" required>
        <input type="text" name="role" placeholder="Rôle de l'Elfe" required>
        
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
        <input type="submit" name="add" value="Ajouter l'Elfe">
    </form>
</div>

</body>
</html>
