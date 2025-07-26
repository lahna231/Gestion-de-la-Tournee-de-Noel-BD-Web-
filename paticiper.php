<?php
// Connexion à la base de données
function connexion_OCI() {
    include_once("myparam.inc.php"); // Inclusion des paramètres de connexion à la base de données
    $conn = oci_connect(MYUSER, MYPASS, MYHOST);  // Connexion à la base de données avec les informations fournies
    if (!$conn) {
        // Si la connexion échoue, afficher une erreur
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
        return $conn; // Si la connexion est réussie, retourner la connexion
    }
}

// Recherche des participations lorsqu'on soumet le formulaire
if (isset($_POST['search'])) {
    // On récupère et prépare le terme de recherche (converti en minuscules pour la recherche insensible à la casse)
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    
    // Requête SQL pour rechercher dans les colonnes 'id_tournee' et 'id_intermittent'
    $searchQuery = "SELECT * FROM paticiper WHERE LOWER(id_tournee) LIKE :searchTerm OR LOWER(id_intermittent) LIKE :searchTerm";
    
    // Connexion à la base de données
    $conn = connexion_OCI();
    
    // Préparer et exécuter la requête
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);
    
    // Vérifier si le mot-clé "tournee" est dans le terme de recherche pour afficher le lien vers tournee.php
    if (strpos(strtolower($_POST['searchTerm']), "tournee") !== false) {
        $tourneeLink = true;
    }
    
    // Vérifier si le mot-clé "intermittent" est dans le terme de recherche pour afficher le lien vers intermittent.php
    if (strpos(strtolower($_POST['searchTerm']), "intermittent") !== false) {
        $intermittentLink = true;
    }
} else {
    // Si aucune recherche n'est effectuée, récupérer toutes les participations
    $searchQuery = "SELECT * FROM paticiper";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Participations</title>
    <style>
        /* Styles de base pour la page */
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
    background-color: rgba(255, 255, 255, 0.9);  /* Légèrement transparent */
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
    width: 5%;
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
    <h1>Gestion de la Table Paticiper</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou Bien tapez 'intermittent' ou bien 'tournee'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Tournee</th>
                <th>ID Intermittent</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Affichage des résultats de la recherche dans un tableau
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_TOURNEE'] . "</td>";
                echo "<td>" . $row['ID_INTERMITTENT'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Liens conditionnels pour rediriger vers tournee.php ou intermittent.php -->
    <?php if (isset($tourneeLink)): ?>
        <div>
            <a href="tournee.php" style="color:red;">Cliquez ici pour voir les détails des Tournées</a>
        </div>
    <?php endif; ?>

    <?php if (isset($intermittentLink)): ?>
        <div>
            <a href="intermittent.php" style="color:red;">Cliquez ici pour voir les détails des Intermittents</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

