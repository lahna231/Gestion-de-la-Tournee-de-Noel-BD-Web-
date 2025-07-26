<?php
// Connexion à la base de données Oracle avec OCI8


// Inclusion du fichier de paramètres contenant les informations de connexion
include 'myparam.inc.php';
// Connexion à la base de données Oracle avec les paramètres définis dans myparam.inc.php
$conn = oci_connect(MYUSER, MYPASS, MYHOST);
if (!$conn) {// Vérification de la connexion
    // Récupération de l'erreur si la connexion échoue
    $e = oci_error();
      // Arrêt du script et affichage de l'erreur de connexion
    die("Erreur de connexion : " . htmlentities($e['message'], ENT_QUOTES));
}
?>
