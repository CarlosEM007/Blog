<?php
require_once '../Connect/conn.php';

session_start();

$post_id = isset($_GET['id_post']) ? $_GET['id_post'] : null;
$post = null;

if ($post_id) {
    $sql = "SELECT titulo, conteudo FROM usuarios_post WHERE id_post = $1";
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
    <title>Detalhes do Post</title>
    <link href="../Style/bootstrap.css" rel="stylesheet">
    <link href="../Style/footer.css" rel="stylesheet">
    <link href="../Style/content.css" rel="stylesheet">
</head>
<header>
    <?php include '../Shared/navbar.php'; ?>
</header>
<body>
    <br><br><br>
    <div class="content-org">
        <?php if ($post): ?>
            <h1><?php echo htmlspecialchars($post['titulo']); ?></h1>
            <p><?php echo nl2br(html_entity_decode($post['conteudo'])); ?></p>
        <?php else: ?>
            <p class="text-danger"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
</body>
<?php include '../Shared/footer.php'; ?>
</html>