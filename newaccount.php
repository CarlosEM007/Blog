<?php
// Using Connection to DB
require_once './Connect/conn.php';

// Session on
session_start();

if(isset($_POST['btn_regis'])){
    $newlogin = $_POST['inp_login'];
    $newemail = $_POST['inp_email'];
    $newpsw = $_POST['inp_password'];

    if(!empty($newlogin) && !empty($newemail) && !empty($newpsw)){
        // Check if email already exists
        $checkEmailSql = "SELECT COUNT(*) FROM usuarios WHERE email = $1";
        $checkEmailResult = pg_prepare($connect, "check_email_query", $checkEmailSql);
        $checkEmailResult = pg_execute($connect, "check_email_query", array($newemail));
        $emailCount = pg_fetch_result($checkEmailResult, 0, 0);

        if ($emailCount > 0) {
            echo "<script>alert('Email já está em uso');</script>";
        } else {
            // Hash the password
            $hashedPsw = password_hash($newpsw, PASSWORD_DEFAULT);

            // Prepare SQL statement for inserting new user
            $insertSql = "INSERT INTO usuarios (login, email, senha) VALUES ($1, $2, $3)";
            $insertResult = pg_prepare($connect, "insert_user_query", $insertSql);

            // Execute the prepared statement with the parameters
            $insertResult = pg_execute($connect, "insert_user_query", array($newlogin, $newemail, $hashedPsw));

            if ($insertResult) {
                echo "<script>alert('Registro bem-sucedido');</script>";
                header("location: index.php");
            } else {
                echo "<script>alert('Erro ao registrar');</script>";
            }
        }
    }
    else
    {
        echo "<script>alert('Todos os campos devem ser preenchidos');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se</title>
    <link href="./Style/bootstrap.css" rel="stylesheet">
    <link href="./Style/container-align.css" rel="stylesheet">
    <link href="./Style/footer.css" rel="stylesheet">
</head>
<header>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./index.php">Broggeragem</a>
        </div>
    </nav>
</header>
<body>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" class="login-form">
            <h2>Registrar-se</h2>
            <div class="input-container">
            <label for="inp_login">Login:  </label>
                <input name="inp_login" type="text" placeholder="Login">
            </div>

            <div class="input-container">
                <label for="inp_email">Email:  </label>
                <input name="inp_email" type="email" placeholder="Email">
            </div>

            <div class="input-container">
                <label for="inp_password">Senha:  </label>
                <input name="inp_password" type="password" placeholder="Senha">
            </div>

            <button name="btn_regis" type="submit" class="btn btn-success">Registrar-se</button>
        </form>

        <figure>
            <blockquote class="blockquote">
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                Someone famous in <cite title="Source Title">Source Title</cite>
            </figcaption>
    </figure>
    </div>
    
</body>
<?php include './Shared/footer.php'; ?>
</html>
