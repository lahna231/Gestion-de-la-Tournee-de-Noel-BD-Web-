<?php
// lecture.php
$conn = oci_connect('lahnamessahel', 'm22315283', '10.1.16.56/oracle2');

if (!$conn) {
     $e = oci_error();

     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

}
// Préparation de la requête

$stid = oci_parse($conn, 'SELECT * FROM lutin');

if (!$stid) {

     $e = oci_error($conn);

     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

}

// Exécution de la logique de la requête

$r = oci_execute($stid);

if (!$r) {

     $e = oci_error($stid);

     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

}

// Récupération des résultats de la requête

print "<table border='1'>\n";

while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {

     print "<tr>\n";

     foreach ($row as $item) {

         print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";

     }

     print "</tr>\n";

}

print "</table>\n";


oci_free_statement($stid);

oci_close($conn);


?>
