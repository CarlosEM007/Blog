<?php
require_once '../Connect/conn.php';

session_start();

$post_id = isset($_GET['id_post']) ? $_GET['id_post'] : null;
$post = null;

if ($post_id) {
    $sql = "SELECT u.login, up.titulo, up.conteudo, up.resumo FROM usuarios_post up 
            INNER JOIN usuarios u on u.id = up.id_usu 	
            WHERE id_post = $1";

    $result = pg_prepare($connect, "get_post", $sql);
    $result = pg_execute($connect, "get_post", array($post_id));

    if ($result && pg_num_rows($result) > 0) {
        $post = pg_fetch_assoc($result);
    } else {
        $_SESSION['error'] = "Post não encontrado.";
    }
} else {
    $_SESSION['error'] = "ID de post inválido.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloggeragem</title>
    <link rel="icon" type="image/png" href="../Logo/B.png">

    <link href="../Style/bootstrap.css" rel="stylesheet">
    <link href="../Style/footer-page.css" rel="stylesheet">
    <link href="../Style/content.css" rel="stylesheet">
</head>
<body>
    <header>
        <?php include '../Shared/navbar.php'; ?>
    </header>

    <div class="page-container">
        <div class="content-wrap">
            <br><br><br>
            <div class="content-org">
                <?php if ($post): ?>
                    <h1><?php echo htmlspecialchars($post['titulo']); ?></h1>
                    <h5><?php echo htmlspecialchars($post['resumo']); ?></h5>
                    <hr>
                    <p><?php echo nl2br(html_entity_decode($post['conteudo'])); ?></p>
                <?php else: ?>
                    <p class="text-danger"><?php echo $_SESSION['error']; ?></p>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <hr>
                <p class="text-info"><em>Publicado por: </em><?php echo htmlspecialchars($post['login']); ?></p>
            </div>
        </div>
        <footer class="footer">
            <div class="footer-content">
                <p>&copy; 2024 Brogger. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
</body>
</html>