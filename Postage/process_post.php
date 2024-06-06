<?php
// Usando Conexão com o DB
require_once '../Connect/conn.php';

// Session on
session_start();

$user_id = $_SESSION['id_usuario'];
$sucesso = null;
$salvo = null;
$erros = array();


if (isset($_POST['btn_publi']))
{
    $title = $_POST['inp_titulo'];
    $cont = $_POST['txt_conteudo'];
    $resu = $_POST['inp_resumo'];

    if (!empty($title) && !empty($cont) && !empty($resu)){

        if(strlen($cont) <= 1000){
            $sql_update_post = "INSERT INTO usuarios_post (id_usu, titulo, conteudo, publicado, resumo)
                                VALUES ($1, $2, $3, 'T', $4)";

            pg_prepare($connect, "update_user_post_query", $sql_update_post);
            $result_update = pg_execute($connect, "update_user_post_query", array($user_id, $title, $cont, $resu));

            if ($result_update)
            {
                header("Location: post.php?success=1");
                exit();
            }
            else
            {
                $erros[] = "Erro ao publicar o texto.";
            }
        }
        else
        {
            $erros[] = "Conteudo não pode passar de 1000 caracteres";
        }
    }
    else
    {
        $erros[] = "Todos os campos devem ser preenchidos para publicar!";
    }
}

if(isset($_POST['btn_save']))
{
    $title = $_POST['inp_titulo'];
    $cont = $_POST['txt_conteudo'] ?? ' ';
    $resu = $_POST['inp_resumo'] ?? ' ';

    if (!empty($title)){
        if(strlen($cont) <= 1000){
            $sql_update_post = "INSERT INTO usuarios_post (id_usu, titulo, conteudo, publicado, resumo)
                                VALUES ($1, $2, $3, 'F', $4)";

            pg_prepare($connect, "update_user_post_query", $sql_update_post);
            $result_update = pg_execute($connect, "update_user_post_query", array($user_id, $title, $cont, $resu));

            if ($result_update)
            {
                header("Location: post.php?salvo=1");

                exit();
            }
            else
            {
                $erros[] = "Erro ao publicar o texto.";
            }
        }
        else
        {
            $erros[] = "Conteudo não pode passar de 1000 caracteres!";
        }
    }
    else
    {
        $erros[] = "Pelo menos Titulo deve ser preenchido!";
    }
}

if (isset($_GET['success'])) {
    $sucesso = "Texto publicado com sucesso!";
}

if (isset($_GET['salvo'])) {
    $sucesso = "Texto Salvo com sucesso!";
}


// Armazena as mensagens de sucesso e erro na sessão
$_SESSION['sucesso'] = $sucesso;
$_SESSION['erros'] = $erros;

// Redireciona de volta ao formulário
header("Location: post.php");
exit();
?>