<?php
session_start();
require_once '../Connect/conn.php';

$sucesso = isset($_SESSION['sucesso']) ? $_SESSION['sucesso'] : null;
$erros = isset($_SESSION['erros']) ? $_SESSION['erros'] : array();

// Limpa as mensagens da sessão para não serem exibidas novamente
unset($_SESSION['sucesso']);
unset($_SESSION['erros']);

$id_post = isset($_GET['id_post']) ? intval($_GET['id_post']) : null;
$post = null;

if ($id_post) {
    $sql = "SELECT titulo, resumo, conteudo FROM usuarios_post WHERE id_post = $1";
    $result = pg_prepare($connect, "fetch_post_query", $sql);
    $result = pg_execute($connect, "fetch_post_query", array($id_post));
    if ($result) {
        $post = pg_fetch_assoc($result);
    } else {
        $erros[] = "Erro ao carregar o rascunho: " . pg_last_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloggeragem</title>
    <link rel="icon" type="image/png" href="../Logo/B.png">
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <link href="../Style/bootstrap.css" rel="stylesheet">
    <link href="../Style/footer.css" rel="stylesheet">
    <link href="../Style/container-align.css" rel="stylesheet">
</head>

<header>
    <?php include '../Shared/navbar.php'; ?>
</header>

<body>
    <div class="container">
        <form action="process_post.php" method="post">
            <input type="hidden" name="id_post" value="<?php echo htmlspecialchars($id_post ?? ''); ?>">
            <input type="text" name="inp_titulo" placeholder="Título" maxlength="20" required value="<?php echo htmlspecialchars($post['titulo'] ?? ''); ?>">
            <input type="text" name="inp_resumo" placeholder="Resumo" maxlength="50" value="<?php echo htmlspecialchars($post['resumo'] ?? ''); ?>">
            <hr>
            <textarea name="txt_conteudo" id="editor" rows="10" cols="20"><?php echo htmlspecialchars($post['conteudo'] ?? ''); ?></textarea>
            <script>
                CKEDITOR.replace('editor');
            </script>
            <br>
            <input type="submit" name="btn_publi" class="btn btn-success" value="Publicar">
            <input type="submit" name="btn_save" class="btn btn-warning" value="Salvar Rascunho">
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
</body>
<?php include '../Shared/footer.php'; ?>
</html>
