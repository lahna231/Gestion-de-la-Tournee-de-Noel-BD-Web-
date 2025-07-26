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

// Recherche des rennes
if (isset($_POST['search'])) {
    $searchTerm = "%" . strtolower($_POST['searchTerm']) . "%";
    $searchQuery = "SELECT * FROM renne WHERE LOWER(id_puce) LIKE :searchTerm OR LOWER(nom_renne) LIKE :searchTerm OR LOWER(pos_renne) LIKE :searchTerm OR LOWER(couleur_nez) LIKE :searchTerm OR LOWER(poid_renne) LIKE :searchTerm OR LOWER(id_traineau_gerer) LIKE :searchTerm OR LOWER(id_traineau_tirer) LIKE :searchTerm " ;
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_bind_by_name($stid, ":searchTerm", $searchTerm);
    oci_execute($stid);
    
    // Afficher des liens qui redirigent vers d'autres tables
    if (strpos(strtolower($_POST['searchTerm']), "entretenir") !== false) {
        $entretenirLinks = true;
    }
    if (strpos(strtolower($_POST['searchTerm']), "traineau") !== false) {
        $traineauLinks = true;
    }
} else {
    $searchQuery = "SELECT * FROM renne";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $searchQuery);
    oci_execute($stid);
}

// Suppression d'un renne
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM renne WHERE id_puce = :id_puce";
    $conn = connexion_OCI();
    $stid = oci_parse($conn, $deleteQuery);
    oci_bind_by_name($stid, ":id_puce", $deleteId);
    oci_execute($stid);
    header("Location: renne.php"); // Recharger la page après suppression
    exit();
}

// Ajout d'un nouveau renne
if (isset($_POST['add'])) {
    // Récupération des données du formulaire
    $id_puce = $_POST['id_puce'];
    $nom_renne = $_POST['nom_renne'];
    $pos_renne = $_POST['pos_renne'];
    $couleur_nez = $_POST['couleur_nez'];
    $poid_renne = $_POST['poid_renne'];
    $id_traineau_gerer = $_POST['id_traineau_gerer'];
    $id_traineau_tirer = $_POST['id_traineau_tirer'];

    // Insertion dans la base de données
    $conn = connexion_OCI();
    $insertQuery = "INSERT INTO renne (id_puce, nom_renne, pos_renne, couleur_nez, poid_renne, id_traineau_gerer, id_traineau_tirer)
                    VALUES (:id_puce, :nom_renne, :pos_renne, :couleur_nez, :poid_renne, :id_traineau_gerer, :id_traineau_tirer)";
    $stid = oci_parse($conn, $insertQuery);
    oci_bind_by_name($stid, ":id_puce", $id_puce);
    oci_bind_by_name($stid, ":nom_renne", $nom_renne);
    oci_bind_by_name($stid, ":pos_renne", $pos_renne);
    oci_bind_by_name($stid, ":couleur_nez", $couleur_nez);
    oci_bind_by_name($stid, ":poid_renne", $poid_renne);
    oci_bind_by_name($stid, ":id_traineau_gerer", $id_traineau_gerer);
    oci_bind_by_name($stid, ":id_traineau_tirer", $id_traineau_tirer);
    oci_execute($stid);
    header("Location: renne.php"); // Recharger la page après ajout
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Rennes</title>
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
    <h1>Gestion des Rennes</h1>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <input type="text" name="searchTerm" placeholder="Saisissez le mot-clé ou bien tapez 'entretenir' ou 'traineau'" value="<?php echo $_POST['searchTerm'] ?? ''; ?>" required>
        <input type="submit" name="search" value="Rechercher">
    </form>

    <!-- Affichage des résultats de recherche -->
    <table>
        <thead>
            <tr>
                <th>ID Puce</th>
                <th>Nom</th>
                <th>Position</th>
                <th>Couleur Nez</th>
                <th>Poids</th>
                <th>Traîneau Gérer</th>
                <th>Traîneau Tirer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = oci_fetch_assoc($stid)) {
                echo "<tr>";
                echo "<td>" . $row['ID_PUCE'] . "</td>";
                echo "<td>" . $row['NOM_RENNE'] . "</td>";
                echo "<td>" . $row['POS_RENNE'] . "</td>";
                echo "<td>" . $row['COULEUR_NEZ'] . "</td>";
                echo "<td>" . $row['POID_RENNE'] . "</td>";
                echo "<td>" . $row['ID_TRAINEAU_GERER'] . "</td>";
                echo "<td>" . $row['ID_TRAINEAU_TIRER'] . "</td>";
                echo "<td class='actions'>
                        <a href='modifier_renne.php?id_puce=" . $row['ID_PUCE'] . "'>Modifier</a> |
                        <a href='renne.php?delete_id=" . $row['ID_PUCE'] . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce renne ?\");'>Supprimer</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Bloc des liens des spécialités -->
    <?php if (isset($entretenirLinks)): ?>
    <div class="specialites-links">
        <a href="entretenir.php">cliquer ici pour explorer la relation entre les Rennes et les Traineaux et les Elfes</a>
    </div>
    <?php endif; ?>

    <!-- Bloc des liens des traîneaux -->
    <?php if (isset($traineauLinks)): ?>
    <div class="specialites-links">
        <a href="traineau.php">cliquez ici pour explorer la relation entre les Rennes et les Traineaux</a>
    </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'un renne -->
    <h2>Ajouter un Nouveau Renne</h2>
    <form method="POST">
        <input type="text" name="id_puce" placeholder="ID de la Puce" required>
        <input type="text" name="nom_renne" placeholder="Nom du Renne" required>
        <input type="text" name="pos_renne" placeholder="Position du Renne" required>
        <input type="text" name="couleur_nez" placeholder="Couleur du Nez" required>
        <input type="text" name="poid_renne" placeholder="Poids du Renne" required>
        
        
                   <select name="id_traineau_gerer" >
            <option value="">Sélectionner un traîneau à gérer</option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_traineau, nom_traineau FROM traineau";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_TRAINEAU'] . "'>" . $row['NOM_TRAINEAU'] . "</option>";
            }
            ?>
        </select>


             <select name="id_traineau_tirer" >
            <option value="">Choisir uTraîneau à tirer : </option>
            <?php
            $conn = connexion_OCI();
            $tourneeQuery = "SELECT id_traineau, nom_traineau FROM traineau";
            $stid = oci_parse($conn, $tourneeQuery);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value='" . $row['ID_TRAINEAU'] . "'>" . $row['NOM_TRAINEAU'] . "</option>";
            }
            ?>
        </select>



        <input type="submit" name="add" value="Ajouter le Renne">
    </form>
</div>

</body>
</html>

