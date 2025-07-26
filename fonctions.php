<?php
// Définition de la fonction qui exécute une requête Oracle avec des paramètres liés
function executerReq($idcom, $requete, $to_bind, $the_values)
{
    // Préparation de la requête SQL
    $stid = oci_parse($idcom, $requete);
    // Vérification des erreurs lors de la préparation de la requête
    if (!$stid) {
        // Récupération et gestion de l'erreur
        $e = oci_error($idcom);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
 // Boucle pour lier les paramètres de la requête avec les valeurs correspondantes
    for ($i = 0; $i < count($to_bind); $i++) {
        // Liaison des valeurs aux paramètres
        oci_bind_by_name($stid, $to_bind[$i], $the_values[$i]);
    }
  // Exécution de la requête SQL
    $r = oci_execute($stid);
    if (!$r) {
            // Vérification des erreurs lors de l'exécution de la requête
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    return $stid;
}
?>
