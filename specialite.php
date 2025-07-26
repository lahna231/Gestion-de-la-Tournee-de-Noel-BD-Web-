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

// Recherche des entrées dans la table 'specialite' lors de la soumission du formulaire
if (isset($_POST['search'])) {
    // On récupère et prépare le terme de recherche (converti en minuscules pour une recherche insensible à la casse)
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    
    // Requête SQL pour rechercher dans les colonnes 'id_specialite' et 'nom_specialite'
    $searchQuery = "SELECT * FROM specialite WHERE LOWER(id_specialite) LIKE :searchTerm OR LOWER(nom_specialite) LIKE :searchTerm";
    
    // Connexion à la base de données
    $conn = connexion_OCI();
    
    // Préparer et exécuter la requête
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);
    
    // Vérifier si le mot-clé "specialite_secondaire" est dans le terme de recherche pour afficher le lien vers a_comme_specialite_secondaire.php
    if (strpos(strtolower($_POST['searchTerm']), "specialite_secondaire") !== false) {
        $specialiteSecondaireLink = true;
    }
    
    // Vérifier si le mot-clé "specialite_principale" est dans le terme de recherche pour afficher le lien vers a_comme_specialite_principale.php
    if (strpos(strtolower($_POST['searchTerm']), "specialite_principale") !== false) {
        $specialitePrincipaleLink = true;
    }
} else {
    // Si aucune recherche n'est effectuée, récupérer toutes les entrées de la table 'specialite'
    $searchQuery = "SELECT * FROM specialite";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de La Table Specialité</title>
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
    <h1>Gestion de La Table Specialité</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="mot-clé ou bien tapez 'specialite_secondaire' ou 'specialite_principale'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>id_specialite</th>
                <th>nom_specialite</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Vérification des résultats
            $resultCount = 0; // Initialisation du compteur de résultats
            while ($row = oci_fetch_assoc($stid)) {
                $resultCount++; // Incrémenter le compteur pour chaque résultat trouvé
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ID_SPECIALITE']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NOM_SPECIALITE']) . "</td>";
                echo "</tr>";
            }
            
            // Si aucun résultat n'a été trouvé
            if ($resultCount == 0) {
                echo "<tr><td colspan='2'>Aucun résultat trouvé</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Lien conditionnel pour rediriger vers a_comme_specialite_secondaire.php -->
    <?php if (isset($specialiteSecondaireLink)): ?>
        <div>
            <a href="a_comme_specialite_secondaire.php" style="color:red;">Cliquez ici pour voir les détails de la Table Specialité Secondaire</a>
        </div>
    <?php endif; ?>

    <!-- Lien conditionnel pour rediriger vers a_comme_specialite_principale.php -->
    <?php if (isset($specialitePrincipaleLink)): ?>
        <div>
            <a href="a_comme_specialite_principale.php" style="color:red;">Cliquez ici pour voir les détails de la Table Specialité Principale</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

