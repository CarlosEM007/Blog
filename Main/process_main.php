<?php

require_once '../Connect/conn.php';

session_start();

$posts = array();

$sql = "SELECT id_post, titulo, resumo FROM usuarios_post WHERE publicado = 'T' ORDER BY id_post DESC";
$result = pg_query($connect, $sql);

if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $posts[] = $row;
    }

    $_SESSION['posts'] = $posts;
} else {
    $_SESSION['error'] = "Erro ao recuperar os posts.";
}
?>
