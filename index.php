<?php
// Using Connection to DB
require_once './Connect/conn.php';

// Session on
session_start();

$erros = array();

if(isset($_POST['btn_send'])){
    $logintry = pg_escape_string($connect, $_POST['inp_login']);
    $pswtry = $_POST['inp_password'];

    if(!empty($logintry) && !empty($pswtry)){
        // Check if the user exists by login or email
        $sql = "SELECT * FROM usuarios WHERE login = $1 OR email = $1";
        $sqlr = pg_prepare($connect, "check_user_query", $sql);
        $sqlr = pg_execute($connect, "check_user_query", array($logintry));

        if(pg_num_rows($sqlr) > 0){
            $dados = pg_fetch_array($sqlr);
            // Verify the password
            if(password_verify($pswtry, $dados['senha'])){
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $dados['id'];
                $_SESSION['nome_usuario'] = $dados['login'];
                header("location: ./Account/profile.php");
            } else {
                $erros[] = "<script>alert('Senha incorreta!');</script>";
            }
        } else {
            $erros[] = "<script>alert('Usuário não existe!');</script>";
        }
    } else {
        $erros[] = "<script>alert('todos os campos devem ser preenchidos');</script>";
    }
}

if(isset($_POST['btn_regis'])){
    header("location: newaccount.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloggeragem</title>
    <link rel="icon" type="image/png" href="./Logo/B.png">

    <link href="./Style/bootstrap.css" rel="stylesheet">
    <link href="./Style/container-align.css" rel="stylesheet">
    <link href="./Style/footer.css" rel="stylesheet">
    
</head>
<header>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Bloggeragem</a>
  </div>
</nav>
</header>
<body>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="login-form">
            <h2>Login</h2>
            <div class="input-container">
                <label for="inp_login">Login:  </label>
                <input name="inp_login" type="text" placeholder="Login ou Email">
            </div>
            <div class="input-container">
                <label for="inp_password">Senha:</label>
                <input name="inp_password" type="password" placeholder="Senha" style="margin-bottom: 5px">
            </div>
            <button name="btn_send" type="submit" class="btn btn-dark" style="margin-right: 28%">Login</button>
            <button name="btn_regis" type="submit" class="btn btn-success">Registrar-se</button>
        </form>

        <figure>
    <?php
    // Fazendo a consulta SQL para obter uma frase aleatória
    $query = "SELECT * FROM frases_massa ORDER BY RANDOM() LIMIT 1";
    $result = pg_query($connect, $query);
    $frase = pg_fetch_assoc($result);
    
    // Exibindo a frase dentro do bloco de citação
    ?>
    <blockquote class="blockquote">
        <p class="mb-0"><em>"<?php echo $frase['frase']; ?>"</em></p>
    </blockquote>
    <figcaption class="blockquote-footer">
        Por <cite title="Source Title"><?php echo $frase['dono']; ?></cite>
    </figcaption>
</figure>
    </div>

    <?php
        foreach($erros as $erro){
            echo $erro;
        }
    ?>
   
</body>
<?php include './Shared/footer.php'; ?>
</html>

