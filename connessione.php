<?php
$mysqli = new mysqli('localhost',' t3l3m4t1c4', '', "my_t3l3m4t1c4");

if ($mysqli->connect_error) {//connetto al database
    die('Errore di connessione (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    exit();
}
else {
    //echo '<center><p>' . 'Connesso. ' . $mysqli->host_info . '</p></center>';
}


