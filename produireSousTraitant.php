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

// Recherche des entrées dans la table 'produireSousTraitant' lors de la soumission du formulaire
if (isset($_POST['search'])) {
    // On récupère et prépare le terme de recherche (converti en minuscules pour une recherche insensible à la casse)
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    
    // Requête SQL pour rechercher dans les colonnes 'id_jouet' et 'id_sstraitant'
    $searchQuery = "SELECT * FROM produireSousTraitant WHERE LOWER(id_jouet) LIKE :searchTerm OR LOWER(id_sstraitant) LIKE :searchTerm";
    
    // Connexion à la base de données
    $conn = connexion_OCI();
    
    // Préparer et exécuter la requête
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);
    
    // Vérifier si le mot-clé "jouet" est dans le terme de recherche pour afficher le lien vers jouet.php
    if (strpos(strtolower($_POST['searchTerm']), "jouet") !== false) {
        $jouetLink = true;
    }
    
    // Vérifier si le mot-clé "sous_traitant" est dans le terme de recherche pour afficher le lien vers sous_traitant.php
    if (strpos(strtolower($_POST['searchTerm']), "sous_traitant") !== false) {
        $sstraitantLink = true;
    }
} else {
    // Si aucune recherche n'est effectuée, récupérer toutes les entrées de la table 'produireSousTraitant'
    $searchQuery = "SELECT * FROM produireSousTraitant";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits Sous Traitants</title>
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
    <h1>Gestion de la Table produireSousTraitant</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou Bien tapez 'jouet' ou 'sous_traitant'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Jouet</th>
                <th>ID Sous Traitant</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Affichage des résultats de la recherche dans un tableau
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_JOUET'] . "</td>";
                echo "<td>" . $row['ID_SSTRAITANT'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Liens conditionnels pour rediriger vers jouet.php ou sous_traitant.php -->
    <?php if (isset($jouetLink)): ?>
        <div>
            <a href="jouet.php" style="color:red;">Cliquez ici pour voir les détails des Jouets</a>
        </div>
    <?php endif; ?>

    <?php if (isset($sstraitantLink)): ?>
        <div>
            <a href="sous_traitant.php" style="color:red;">Cliquez ici pour voir les détails des Sous Traitants</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

