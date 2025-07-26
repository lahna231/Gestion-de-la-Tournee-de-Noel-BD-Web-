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

// Recherche des traineaux
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM traineau WHERE LOWER(id_traineau) LIKE :searchTerm OR LOWER(nom_traineau) LIKE :searchTerm OR LOWER(capacite_traineau) LIKE :searchTerm OR LOWER(poid) LIKE :searchTerm";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);

    // afficher des liens qui rederige vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "renne") !== false) {
        $renneLink = true;
    }
    if (strpos(strtolower($_POST['searchTerm']), "mener") !== false) {
        $menerLink = true;
    }
    if (strpos(strtolower($_POST['searchTerm']), "entretenir") !== false) {
        $entretenirLink = true;
    }
    if (strpos(strtolower($_POST['searchTerm']), "send") !== false) {
        $sendLink = true;
    }
} else {
    $searchQuery = "SELECT * FROM traineau";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}


// Suppression d'un traineau 
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM traineau WHERE id_traineau = :id_traineau";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_traineau", $deleteId);
    oci_execute($stid);
    header("Location: traineau.php"); // Recharger la page après suppression
    exit();
}


// Ajout d'un nouveau traineau
if (isset($_POST['add'])) {
    // Récupération des données du formulaire
    $id_traineau = $_POST['id_traineau'];
    $nom_traineau = $_POST['nom_traineau'];
    $capacite_traineau = $_POST['capacite_traineau'];
    $poid = $_POST['poid'];

    // Insertion dans la base de données
    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO traineau (id_traineau, nom_traineau, capacite_traineau, poid)
                    VALUES (:id_traineau, :nom_traineau, :capacite_traineau, :poid)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_traineau", $id_traineau);
    oci_bind_by_name($stid, ":nom_traineau", $nom_traineau);
    oci_bind_by_name($stid, ":capacite_traineau", $capacite_traineau);
    oci_bind_by_name($stid, ":poid", $poid);
    oci_execute($stid);
    header("Location: traineau.php"); // Recharger la page après ajout
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Traineaux</title>
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
    <h1>Gestion des Traineaux</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder=" mot-clé ou bien tapez 'renne' ou bien 'mener' ou bien 'entretenir' ou bien 'send'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Traineau</th>
                <th>Nom</th>
                <th>Capacité</th>
                <th>Poid</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_TRAINEAU'] . "</td>";
                echo "<td>" . $row['NOM_TRAINEAU'] . "</td>";
                echo "<td>" . $row['CAPACITE_TRAINEAU'] . "</td>";
                echo "<td>" . $row['POID'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_traineau.php?id_traineau=" . $row['ID_TRAINEAU'] . "'>Modifier</a> |
                        <a href='traineau.php?delete_id=" . $row['ID_TRAINEAU'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce traineau ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Bloc des liens -->
    <?php if (isset($renneLink)): ?>
    <div class="renne-link">
        <a href="renne.php">cliquez ici pour explorer la relation entre les Traineaux et les Rennes </a>
    </div>
    <?php endif; ?>

    <?php if (isset($menerLink)): ?>
    <div class="mener-link">
        <a href="mener.php">cliquez ici pour explorer la relation entre les Traineaux et les Elfes  </a>
    </div>
    <?php endif; ?>

    <?php if (isset($entretenirLink)): ?>
    <div class="entretenir-link">
        <a href="entretenir.php">cliquez ici pour explorer la relation entre les Traineaux et les Rennes et les Elfes</a>
    </div>
    <?php endif; ?>

    <?php if (isset($sendLink)): ?>
    <div class="send-link">
        <a href="send.php">cliquez ici pour explorer la relation entre les Traineaux et  les Entrepots</a>
    </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'un traineau -->
    <h2>Ajouter un nouveau Traineau</h2>
    <form method="POST">
        <input type="text" name="id_traineau" placeholder="ID du Traineau" required>
        <input type="text" name="nom_traineau" placeholder="Nom du Traineau" required>
        <input type="text" name="capacite_traineau" placeholder="Capacité du Traineau" required>
        <input type="text" name="poid" placeholder="Poids du Traineau" required>
        <input type="submit" name="add" value="Ajouter le Traineau">
    </form>
</div>

</body>
</html> 
