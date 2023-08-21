<?php
// Función que abrirá una conexión con la base de datos
function openConn($dbname = NULL){
    // Configuración de la base de datos
    $serverName = 'mysql';
    $userName = 'root';
    $password = 'ispadmin1234';

    $conn = new mysqli($serverName, $userName, $password, $dbname);
    if ($conn->connect_error){
        die("Error: Unable to connect to MySQL<br>" .
            "Debugging errno: " . $conn->connect_errno . ": " .
            "Debugging error: " . $conn->connect_error);
    }

    $conn->set_charset('utf8') or die("Error setting charset: " . $conn->error);

    return $conn;
}

function closeConn($conn) {
    $conn->close();
}

?>
