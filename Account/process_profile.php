<?php

$user_id = $_SESSION['id_usuario'];

$postsT = array();
$postsF = array();

$sqlT = "SELECT id_post, titulo FROM usuarios_post WHERE publicado = 'T' and id_usu = $1 ORDER BY id_post DESC";
$sqlF = "SELECT id_post, titulo FROM usuarios_post WHERE publicado = 'F' and id_usu = $1 ORDER BY id_post DESC";

$resultT = pg_prepare($connect, "fetch_postsT", $sqlT);
$resultT = pg_execute($connect, "fetch_postsT", array($user_id));
if ($resultT) {
    while ($row = pg_fetch_assoc($resultT)) {
        $postsT[] = $row;
    }
} else {
    echo "Error fetching postsT: " . pg_last_error($connect);
}

$resultF = pg_prepare($connect, "fetch_postsF", $sqlF);
$resultF = pg_execute($connect, "fetch_postsF", array($user_id));
if ($resultF) {
    while ($row = pg_fetch_assoc($resultF)) {
        $postsF[] = $row;
    }
} else {
    echo "Error fetching postsF: " . pg_last_error($connect);
}

$_SESSION['postsT'] = $postsT;
$_SESSION['postsF'] = $postsF;
?>
