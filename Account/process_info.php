<?php
// Using Connection to DB
require_once '../Connect/conn.php';

// Session on
session_start();

// Check if user is logged in
if (!isset($_SESSION['logado'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id_usuario'];
$erros = array();
$sucesso = null;


$sql = "SELECT login, email FROM usuarios WHERE id = $1";
$result = pg_prepare($connect, "fetch_user_query", $sql);
$result = pg_execute($connect, "fetch_user_query", array($user_id));
$user = pg_fetch_assoc($result);

$_SESSION['user'] = $user;

$sql_plus = "SELECT * FROM usuarios_plus WHERE id_usu = $1";
$result_plus = pg_prepare($connect, "fetch_user_plus_query", $sql_plus);
$result_plus = pg_execute($connect, "fetch_user_plus_query", array($user_id));
$user_plus = pg_fetch_assoc($result_plus);

$_SESSION['user_plus'] = $user_plus;

if (!$user_plus) {
    //Verifica se há valores no banco de dados
    $sql_insert_plus = "INSERT INTO usuarios_plus (id_usu, telefone, sobre_mim, nome) VALUES ($1, '', '', '')";
    pg_prepare($connect, "insert_user_plus_query", $sql_insert_plus);
    pg_execute($connect, "insert_user_plus_query", array($user_id));
}


if (isset($_POST['btn_save'])) {
    $telefone = $_POST['telefone'];
    $sobre_mim = $_POST['sobre_mim'];
    $nome = $_POST['nome'];

    $sql_update_plus = "UPDATE usuarios_plus SET nome = $1, telefone = $2, sobre_mim = $3 WHERE id_usu = $4";
    pg_prepare($connect, "update_user_plus_query", $sql_update_plus);
    $result_update = pg_execute($connect, "update_user_plus_query", array($nome, $telefone, $sobre_mim, $user_id));

    if ($result_update) {
        $sucesso = "Informações atualizadas com sucesso!";
        $_SESSION['sucesso'] = $sucesso;
        $user_plus = array("telefone" => $telefone, "sobre_mim" => $sobre_mim, "nome" => $nome);
    } else {
        $erros[] = "Erro ao atualizar as informações.";
        $_SESSION['erros'] = $erros;
    }
}

if (isset($_POST['btn_exit'])) {
    header("location: ../Connect/logout.php");
}

header("location: profile.php");
?>
