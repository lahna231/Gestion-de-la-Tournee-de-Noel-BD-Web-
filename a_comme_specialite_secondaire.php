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

// Recherche dans la table 'a_comme_specialite_secondaire' 
if (isset($_POST['search'])) {
    // On récupère et prépare le terme de recherche (converti en minuscules pour une recherche insensible à la casse)
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    
    // Requête SQL pour rechercher dans les colonnes 'id_elfe' et 'id_specialite'
    $searchQuery = "SELECT * FROM a_comme_specialite_secondaire WHERE LOWER(id_elfe) LIKE :searchTerm OR LOWER(id_specialite) LIKE :searchTerm";
    
    // Connexion à la base de données
    $conn = connexion_OCI();
    
    // Préparer et exécuter la requête
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);
    
    // Vérifier si le mot-clé "elfe" est dans le terme de recherche pour afficher le lien vers elfe.php
    if (strpos(strtolower($_POST['searchTerm']), "elfe") !== false) {
        $elfeLink = true;
    }
    
    // Vérifier si le mot-clé "specialite" est dans le terme de recherche pour afficher le lien vers specialite.php
    if (strpos(strtolower($_POST['searchTerm']), "specialite") !== false) {
        $specialiteLink = true;
    }
} else {
    // Si aucune recherche n'est effectuée, récupérer toutes les entrées de la table 'a_comme_specialite_secondaire'
    $searchQuery = "SELECT * FROM a_comme_specialite_secondaire";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de La Table a_comme_specialite_secondaire</title>
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
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 70%;
            max-width: 1000px;
        }
        h1 {
            text-align: center;
            color: red; 
            font-size: 2em;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid red; 
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: red; 
            color: white;
        }
        form input {
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
    <h1>Gestion de La Table a_comme_specialite_secondaire</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bie  tapez 'elfe' ou 'spécialité'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Elfe</th>
                <th>ID Spécialité</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Affichage des résultats de la recherche dans un tableau
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_ELFE'] . "</td>";
                echo "<td>" . $row['ID_SPECIALITE'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Liens conditionnels pour rediriger vers elfe.php ou specialite.php -->
    <?php if (isset($elfeLink)): ?>
        <div>
            <a href="elfe.php" style="color:red;">Cliquez ici pour voir les détails des Elfes</a>
        </div>
    <?php endif; ?>

    <?php if (isset($specialiteLink)): ?>
        <div>
            <a href="specialite.php" style="color:red;">Cliquez ici pour voir les détails des Spécialités</a>
        </div>
    <?php endif; ?>


</div>

</body>
</html>

