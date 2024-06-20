<?php
    $connStr = " ";

    $connect = pg_connect($connStr);
     
    if(!$connect) {
        die("Não foi possível se conectar ao banco de dados.");
    }
?>
