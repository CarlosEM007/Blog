<?php
session_start();

$sucesso = isset($_SESSION['sucesso']) ? $_SESSION['sucesso'] : null;
$erros = isset($_SESSION['erros']) ? $_SESSION['erros'] : array();

// Limpa as mensagens da sessão para não serem exibidas novamente
unset($_SESSION['sucesso']);
unset($_SESSION['erros']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Texto</title>
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
            <input type="text" name="inp_titulo" placeholder="Título" maxlength="20" required>
            <input type="text" name="inp_resumo" placeholder="Resumo" maxlength="50">
            <hr>
            <textarea name="txt_conteudo" id="editor" rows="10" cols="20"></textarea>
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