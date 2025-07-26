<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); // Inclut les paramètres de connexion à la base de données
    $conn = oci_connect(MYUSER, MYPASS, MYHOST);  // Connexion à la base de données avec les identifiants
    if (!$conn) {
        $e = oci_error();  // Si la connexion échoue, obtenir l'erreur
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR); // Afficher l'erreur
    } else {
        return $conn;  // Si la connexion réussit, retourner la connexion
    }
}

// Recherche des cadeaux
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";  // Récupère le terme de recherche et le prépare pour une requête SQL
    $searchQuery = "SELECT * FROM cadeau WHERE LOWER(id_cadeau) LIKE :searchTerm OR LOWER(nom_cadeau) LIKE :searchTerm OR LOWER(poid_cadeau) LIKE :searchTerm  OR LOWER(statut_cadeau) LIKE :searchTerm OR LOWER(id_intermittent) LIKE :searchTerm  "; 
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $searchQuery); 
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);  // Lie le terme de recherche à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL

    // Si l'utilisateur recherche "intermittent"
    if (strpos(strtolower($_POST['searchTerm']), "intermittent") !== false) {
        $intermittentLink = "<a href='intermittent.php'>Cliquez ici pour explorer la relation entre les cadeaux et les intermittents</a>";  // Lien vers intermittent.php
    }

    // Si l'utilisateur recherche "passer"
    if (strpos(strtolower($_POST['searchTerm']), "passer") !== false) {
        $passerLink = "<a href='passer.php'>Cliquez ici pour explorer la relation entre les cadeaux et les ateliers  </a>";  // Lien vers passer.php
    }

    // Si l'utilisateur recherche "fabriquer"
    if (strpos(strtolower($_POST['searchTerm']), "fabriquer") !== false) {
        $fabriquerLink = "<a href='fabriquer.php'>Cliquez ici pour explorer la relation entre les cadeaux et leur matiéres premiere </a>";  // Lien vers fabriquer.php
    }
} else {
    $searchQuery = "SELECT * FROM cadeau";  // Si aucune recherche, récupérer tous les cadeaux
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $searchQuery);  
    oci_execute($stid);  // Exécute la requête SQL
}

// Suppression d'un cadeau
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];  // Récupère l'ID du cadeau à supprimer
    $deleteQuery = "DELETE FROM cadeau WHERE id_cadeau = :id_cadeau";  // Requête SQL pour supprimer le cadeau
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $deleteQuery);  
    oci_bind_by_name($stid, ":id_cadeau", $deleteId);  // Lie l'ID du cadeau à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    header("Location: cadeau.php");  // Redirige vers la page cadeau après suppression
    exit();  // Quitte le script
}

// Ajout d'un nouveau cadeau
if (isset($_POST['add'])) {
    // Récupère les valeurs du formulaire d'ajout de cadeau
    $id_cadeau = $_POST['id_cadeau'];
    $nom_cadeau = $_POST['nom_cadeau'];
    $poid_cadeau = $_POST['poid_cadeau'];
    $statut_cadeau = $_POST['statut_cadeau'];
    $id_intermittent = $_POST['id_intermittent'];

    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $insertQuery = "INSERT INTO cadeau (id_cadeau, nom_cadeau, poid_cadeau, statut_cadeau, id_intermittent)
                    VALUES (:id_cadeau, :nom_cadeau, :poid_cadeau, :statut_cadeau, :id_intermittent  )";  // Requête SQL pour insérer un nouveau cadeau
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_cadeau", $id_cadeau);  // Lie l'ID du cadeau à la requête SQL
    oci_bind_by_name($stid, ":nom_cadeau", $nom_cadeau);  // Lie le nom du cadeau à la requête SQL
    oci_bind_by_name($stid, ":poid_cadeau", $poid_cadeau);  // Lie le poids du cadeau à la requête SQL
    oci_bind_by_name($stid, ":statut_cadeau", $statut_cadeau);  // Lie le statut du cadeau à la requête SQL
    oci_bind_by_name($stid, ":id_intermittent", $id_intermittent);  // Lie l'ID de l'intermittent à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    header("Location: cadeau.php");  // Redirige vers la page cadeau après ajout
    exit();  // Quitte le script
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Cadeaux</title>
    <style>
body {
    background-image: url('fond.jpg');
    background-size: cover;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;  
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
    position: relative; 
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
    <h1>Gestion des Cadeaux</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'intermittent' ou bien 'passer' ou bien 'fabriquer'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens pour "intermittent", "passer" et "fabriquer" -->
    <?php
    if (isset($intermittentLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $intermittentLink . "</div>";
    }

    if (isset($passerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $passerLink . "</div>";
    }

    if (isset($fabriquerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $fabriquerLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Cadeau</th>
                <th>Nom du Cadeau</th>
                <th>Poids du Cadeau</th>
                <th>Statut du Cadeau</th>
                <th>ID Intermittent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {  // Boucle sur les résultats de la requête
                echo "<tr>";
                echo "<td>" . $row['ID_CADEAU'] . "</td>";
                echo "<td>" . $row['NOM_CADEAU'] . "</td>";
                echo "<td>" . $row['POID_CADEAU'] . "</td>";
                echo "<td>" . $row['STATUT_CADEAU'] . "</td>";
                echo "<td>" . $row['ID_INTERMITTENT'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_cadeau.php?id_cadeau=" . $row['ID_CADEAU'] . "'>Modifier</a> |
                        <a href='cadeau.php?delete_id=" . $row['ID_CADEAU'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce cadeau ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un cadeau -->
    <h2>Ajouter un Nouveau Cadeau</h2>
    <form method="POST">
        <input type="text" name="id_cadeau" placeholder="ID du Cadeau" required>
        <input type="text" name="nom_cadeau" placeholder="Nom du Cadeau" required>
        <input type="text" name="poid_cadeau" placeholder="Poids du Cadeau" required>
        <input type="text" name="statut_cadeau" placeholder="Statut du Cadeau" required>
            <select name="id_intermittent" >
            <option value="">Sélectionner un Intermittent</option>
            <?php
            // Récupérer les intermittents depuis la base de données
            $conn = connexion_OCI();
            $intermittentQuery = "SELECT id_intermittent, nom_intermittent FROM intermittent"; 
            $stid_intermittent = oci_parse($conn, $intermittentQuery);
            oci_execute($stid_intermittent);
            while ($intermittent = oci_fetch_assoc($stid_intermittent)) {
                $selected = ($intermittent['ID_INTERMITTENT'] == $row['ID_INTERMITTENT']) ? "selected" : "";
                echo "<option value='" . $intermittent['ID_INTERMITTENT'] . "' $selected>" . $intermittent['NOM_INTERMITTENT'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="add" value="Ajouter le Cadeau">
    </form>
</div>

</body>
</html>

