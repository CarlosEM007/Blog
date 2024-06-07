<?php
include 'process_main.php';

$posts = isset($_SESSION['posts']) ? $_SESSION['posts'] : array();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

unset($_SESSION['posts']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
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
                <h1>Publicações</h1>
                <?php if ($error): ?>
                    <p class="text-danger"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <a href="post_page.php?id_post=<?php echo htmlspecialchars($post['id_post']); ?>">
                            <div class="content-box">
                                <h5 class="content-title"><?php echo htmlspecialchars($post['titulo']); ?></h5>
                                <p class="content-text"><?php echo nl2br(html_entity_decode($post['resumo'])); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum post encontrado.</p>
                <?php endif; ?>
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