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

$sql_plus = "SELECT * FROM usuarios_plus WHERE id_usu = $1";
$result_plus = pg_prepare($connect, "fetch_user_plus_query", $sql_plus);
$result_plus = pg_execute($connect, "fetch_user_plus_query", array($user_id));
$user_plus = pg_fetch_assoc($result_plus);

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
        $user_plus = array("telefone" => $telefone, "sobre_mim" => $sobre_mim, "nome" => $nome);
    } else {
        $erros[] = "Erro ao atualizar as informações.";
    }
}

if (isset($_POST['btn_exit'])) {
    header("location: ../Connect/logout.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="../Style/bootstrap.css" rel="stylesheet">
    <link href="../Style/footer.css" rel="stylesheet">
    <link href="../Style/profile.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include '../Shared/navbar.php'; ?>
    </header>

    <div class="page-container">
        <br><br><br>
        <div class="prof-info">
            <h2>Perfil</h2>
            <p><strong>Login:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                <label for="nome">Nome:</label>
                <div class="input-container">
                    <input name="nome" placeholder="Nome Verdadeiro" value="<?php echo htmlspecialchars($user_plus['nome'] ?? ''); ?>">
                </div>

                <label for="telefone">Telefone:</label>
                <div class="input-container">
                    <input name="telefone" type="tel" placeholder="55 99123-4567" pattern="[0-9]{2} [0-9]{5}-[0-9]{4}" value="<?php echo htmlspecialchars($user_plus['telefone'] ?? ''); ?>">
                </div>

                <label for="sobre_mim">Sobre mim:</label>
                <div class="input-container">
                    <textarea name="sobre_mim" placeholder="Sobre mim" maxlength="100"><?php echo htmlspecialchars($user_plus['sobre_mim'] ?? ''); ?></textarea>
                </div>
                <button name="btn_save" class="btn btn-primary" type="submit">Salvar</button>
                <button name="btn_exit" class="btn btn-danger" type="submit">Sair</button>
            </form>

            <?php
            if ($sucesso) {
                echo "<p class='text-success'>$sucesso</p>";
            }

            foreach ($erros as $erro) {
                echo "<p class='text-danger'>$erro</p>";
            }
            ?>

        </div>
    </div>

    <?php include '../Shared/footer.php'; ?>
</body>

</html>