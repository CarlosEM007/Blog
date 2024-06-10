<?php
require_once '../Connect/conn.php';

session_start();

$user_id = $_SESSION['id_usuario'];
$sucesso = null;
$erros = array();

if (isset($_POST['btn_publi']) || isset($_POST['btn_save'])) {
    $title = $_POST['inp_titulo'];
    $cont = $_POST['txt_conteudo'];
    $resu = $_POST['inp_resumo'];
    $id_post = isset($_POST['id_post']) ? intval($_POST['id_post']) : null;

    $publicado = isset($_POST['btn_publi']) ? 'T' : 'F';

    if (!empty($title) && !empty($cont) && !empty($resu)) {
        if (strlen($cont) <= 1000) {
            if ($id_post) {
                $sql_update_post = "UPDATE usuarios_post SET titulo = $1, conteudo = $2, publicado = $3, resumo = $4 WHERE id_post = $5 AND id_usu = $6";
                pg_prepare($connect, "update_post_query", $sql_update_post);
                $result_update = pg_execute($connect, "update_post_query", array($title, $cont, $publicado, $resu, $id_post, $user_id));
            } else {
                $sql_insert_post = "INSERT INTO usuarios_post (id_usu, titulo, conteudo, publicado, resumo) VALUES ($1, $2, $3, $4, $5)";
                pg_prepare($connect, "insert_post_query", $sql_insert_post);
                $result_update = pg_execute($connect, "insert_post_query", array($user_id, $title, $cont, $publicado, $resu));
            }

            if ($result_update) {
                $sucesso = $publicado == 'T' ? "Texto publicado com sucesso!" : "Rascunho salvo com sucesso!";
            } else {
                $erros[] = "Erro ao " . ($publicado == 'T' ? "publicar" : "salvar") . " o texto.";
            }
        } else {
            $erros[] = "Conteúdo não pode passar de 1000 caracteres";
        }
    } else {
        $erros[] = "Todos os campos devem ser preenchidos!";
    }
}

$_SESSION['sucesso'] = $sucesso;
$_SESSION['erros'] = $erros;

header("Location: post.php" . ($id_post ? "?id_post=" . $id_post : ""));
exit();
?>
