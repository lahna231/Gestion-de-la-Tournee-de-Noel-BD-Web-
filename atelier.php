<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); // Inclut les paramètres de connexion à la base de données
    $conn = oci_connect(MYUSER, MYPASS, MYHOST);  // Connexion à la base de données avec les identifiants
    if (!$conn) {
        $e = oci_error();  // Si la connexion échoue, obtenir l'erreur
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR); 
    } else {
        return $conn;  // Si la connexion réussit, retourner la connexion
    }
}

// Recherche des ateliers
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";  // Récupère le terme de recherche 
    $searchQuery = "SELECT * FROM atelier WHERE LOWER(id_atelier) LIKE :searchTerm OR LOWER(nom_atelier) LIKE :searchTerm OR LOWER(type) LIKE :searchTerm";  // Requête SQL pour rechercher dans les colonnes id_atelier, nom_atelier, type
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $searchQuery);  
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);  // Lie le terme de recherche à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL

    // Si l'utilisateur recherche "équipe"
    if (strpos(strtolower($_POST['searchTerm']), "équipe") !== false) {
        $equipeLink = "<a href='equipe.php'>Cliquez ici pour explorer la relation entre les Atelier et les équipes</a>";  // Lien vers equipe.php
    }

    // Si l'utilisateur recherche "passer"
    if (strpos(strtolower($_POST['searchTerm']), "passer") !== false) {
        $passerLink = "<a href='passer.php'>Cliquez ici pour explorer la relation entre les Ateliers et les cadeaux </a>";  // Lien vers passer.php
    }

    // Si l'utilisateur recherche "produireAtelier"
    if (strpos(strtolower($_POST['searchTerm']), "produireatelier") !== false) {  // Vérification avec un mot exactement en minuscule
        $produireAtelierLink = "<a href='produireAtelier.php'>Cliquez ici pour explorer la relation entre les Ateliers et les jouets </a>";  // Lien vers produireAtelier.php
    }
} else {
    $searchQuery = "SELECT * FROM atelier";  // Si aucune recherche, récupérer tous les ateliers
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $searchQuery); 
    oci_execute($stid);  // Exécute la requête SQL
}

// Suppression d'un atelier
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];  // Récupère l'ID de l'atelier à supprimer
    $deleteQuery = "DELETE FROM atelier WHERE id_atelier = :id_atelier";  // Requête SQL pour supprimer l'atelier
    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $stid = oci_parse($conn, $deleteQuery);  
    oci_bind_by_name($stid, ":id_atelier", $deleteId);  // Lie l'ID de l'atelier à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    header("Location: atelier.php");  // Redirige vers la page atelier après suppression
    exit();  // Quitte le script
}

// Ajout d'un nouvel atelier
if (isset($_POST['add'])) {
    // Récupère les valeurs du formulaire d'ajout d'atelier
    $id_atelier = $_POST['id_atelier'];
    $nom_atelier = $_POST['nom_atelier'];
    $type = $_POST['type'];
  

    $conn = connexion_OCI();  // Appelle la fonction de connexion
    $insertQuery = "INSERT INTO atelier (id_atelier, nom_atelier, type)
                    VALUES (:id_atelier, :nom_atelier, :type)";  // Requête SQL pour insérer un nouvel atelier
    $stid = oci_parse($conn, $insertQuery);  // Parse la requête SQL
    oci_bind_by_name($stid, ":id_atelier", $id_atelier);  // Lie l'ID de l'atelier à la requête SQL
    oci_bind_by_name($stid, ":nom_atelier", $nom_atelier);  // Lie le nom de l'atelier à la requête SQL
    oci_bind_by_name($stid, ":type", $type);  // Lie le type de l'atelier à la requête SQL
    oci_execute($stid);  // Exécute la requête SQL
    header("Location: atelier.php");  // Redirige vers la page atelier après ajout
    exit();  // Quitte le script
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Ateliers</title>
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
    overflow: auto;  
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
    max-height: 90vh; 
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
    <h1>Gestion des Ateliers</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien 'équipe' ou bien 'passer' ou bien 'produireAtelier'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des liens pour "équipe", "passer" et "produireAtelier" -->
    <?php
    if (isset($equipeLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $equipeLink . "</div>";
    }

    if (isset($passerLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $passerLink . "</div>";
    }

    if (isset($produireAtelierLink)) {
        echo "<div style='text-align: center; margin: 20px 0;'>" . $produireAtelierLink . "</div>";
    }
    ?>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Atelier</th>
                <th>Nom de l'Atelier</th>
                <th>Type de l'Atelier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {  // Boucle sur les résultats de la requête
                echo "<tr>";
                echo "<td>" . $row['ID_ATELIER'] . "</td>";
                echo "<td>" . $row['NOM_ATELIER'] . "</td>";
                echo "<td>" . $row['TYPE'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_atelier.php?id_atelier=" . $row['ID_ATELIER'] . "'>Modifier</a> |
                        <a href='atelier.php?delete_id=" . $row['ID_ATELIER'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet atelier ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulaire d'ajout d'un atelier -->
    <h2>Ajouter un Nouveau Atelier</h2>
    <form method="POST">
        <input type="text" name="id_atelier" placeholder="ID de l'Atelier" required>
        <input type="text" name="nom_atelier" placeholder="Nom de l'Atelier" required>
        <input type="text" name="type" placeholder="Type de l'Atelier" required>
        <input type="submit" name="add" value="Ajouter l'Atelier">
    </form>
</div>

</body>
</html>

