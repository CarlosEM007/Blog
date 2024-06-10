<?php
include 'process_info.php';
include 'process_profile.php';

$user = $_SESSION['user'];
$user_plus = $_SESSION['user_plus'];

$postsT = $_SESSION['postsT'];
$postsF = $_SESSION['postsF'];

$sucesso = isset($_SESSION['sucesso']) ? $_SESSION['sucesso'] : array();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

unset($_SESSION['sucesso']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloggeragem</title>
    <link rel="icon" type="image/png" href="../Logo/B.png">

    <link href="../Style/bootstrap.css" rel="stylesheet">
    <link href="../Style/footer.css" rel="stylesheet">
    <link href="../Style/profile.css" rel="stylesheet">
    <link href="../Style/footer-page.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include '../Shared/navbar.php'; ?>
    </header>
    <div class="page-container">
        <div class="content-wrap">
            <br><br><br>
            <div class="row">
                <div class="col-md-4">
                    <div class="prof-info">
                        <h2>Perfil</h2>
                        <p><strong>Login:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

                        <form action="process_info.php" method="POST" enctype="multipart/form-data">

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
                            <input type="submit" name="btn_save" class="btn btn-primary" value="Salvar">
                            <input type="submit" name="btn_exit" class="btn btn-danger" value="Sair">
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

                <div class="flex-item">
                    <h1>Suas Postagens</h1>
                    <div class="post-container">
                        <?php if (!empty($postsT)) : ?>
                            <?php foreach ($postsT as $post) : ?>
                                <div class="post-info">
                                    <a href="../Main/post_page.php?id_post=<?php echo htmlspecialchars($post['id_post']); ?>">
                                        <h3><?php echo htmlspecialchars($post['titulo']); ?></h3>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Nenhum post publicado.</p>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <h1>Seus rascunhos</h1>
                    <div class="post-container">
                        <?php if (!empty($postsF)) : ?>
                            <?php foreach ($postsF as $post) : ?>
                                <div class="post-info">
                                    <a href="../Postage/post.php?id_post=<?php echo htmlspecialchars($post['id_post']); ?>">
                                        <h3><?php echo htmlspecialchars($post['titulo']) ?></h3>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Nenhum Rascunho salvo.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
<?php include '../Shared/footer.php'; ?>

</html>