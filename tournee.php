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

// Recherche des tournées
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM tournee WHERE LOWER(id_tournee) LIKE :searchTerm OR LOWER(nom_tournee) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "enfant") !== false) {
        $link = "enfant.php";
        $linkText = "cliquez ici pour explorer la relation entre les Tournées et  les Enfants";
    }
    elseif (strpos(strtolower($_POST['searchTerm']), "entrepot") !== false) {
        $link = "entrepot.php";
        $linkText = "cliquez ici pour explorer la relation entre les Tournées et les Entrepots";
    }
    elseif (strpos(strtolower($_POST['searchTerm']), "paticiper") !== false) {
        $link = "paticiper.php";
        $linkText = "cliquez ici pour explorer la relation entre les Tournées et les intermittents ";
        }
    
} else {
    $searchQuery = "SELECT * FROM tournee";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression des tournées 
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];  // Récupération de l'ID 
    $deleteQuery = "DELETE FROM tournee WHERE id_tournee = :id_tournee";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_tournee", $deleteId);
    oci_execute($stid);  // Suppression de la tournée

    //reaffichage de la page apres la supression 
    header("Location: tournee.php");
    exit();  
}

// Ajout d'une nouvelle tournée
if (isset($_POST['add'])) {
    // Récupération des données du formulaire
    $id_tournee = $_POST['id_tournee'];
    $nom_tournee = $_POST['nom_tournee'];

    // Insertion dans la base de données
    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO tournee (id_tournee, nom_tournee)
                    VALUES (:id_tournee, :nom_tournee)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_tournee", $id_tournee);
    oci_bind_by_name($stid, ":nom_tournee", $nom_tournee);
    oci_execute($stid);
    header("Location: tournee.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Tournées</title>
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
    width: 20%;
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
    <h1>Gestion des Tournées</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="saisissez le mot-clé ou tapez 'enfant' ou 'entrepot' ou 'paticiper' pour voir les tables" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Tournée</th>
                <th>Nom de la Tournée</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_TOURNEE'] . "</td>";
                echo "<td>" . $row['NOM_TOURNEE'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_tournee.php?id_tournee=" . $row['ID_TOURNEE'] . "'>Modifier</a> |
                        <a href='tournee.php' onclick='event.preventDefault(); if (confirm(\"Êtes-vous sûr de vouloir supprimer cette tournée ?\")) { document.getElementById(\"deleteForm" . $row['ID_TOURNEE'] . "\").submit(); }'>Supprimer</a>
                      </td>";
                echo "</tr>";
                // Formulaire de suppression 
                echo "<form id='deleteForm" . $row['ID_TOURNEE'] . "' method='POST' action='tournee.php'>
                        <input type='hidden' name='delete_id' value='" . $row['ID_TOURNEE'] . "'>
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
    
    <!-- Formulaire d'ajout d'une tournée -->
    <h2>Ajouter une nouvelle Tournée</h2>
    <form method="POST">
        <input type="text" name="id_tournee" placeholder="ID de la Tournée" required>
        <input type="text" name="nom_tournee" placeholder="Nom de la Tournée" required>
        <input type="submit" name="add" value="Ajouter la Tournée">
    </form>
</div>

</body>
</html>
