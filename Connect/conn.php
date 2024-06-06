<?php
    $connStr = "host=myproject01.cwkpounfp1vb.us-east-1.rds.amazonaws.com port=5432 dbname=BlogData user=My_project01 password=guilherme";

    $connect = pg_connect($connStr);
     
    if(!$connect) {
        die("Não foi possível se conectar ao banco de dados.");
    }
?>