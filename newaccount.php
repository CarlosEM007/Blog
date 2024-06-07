<?php
// Using Connection to DB
require_once './Connect/conn.php';

// Session on
session_start();

$erros = array();

if(isset($_POST['btn_regis'])){
    $newlogin = $_POST['inp_login'];
    $newemail = $_POST['inp_email'];
    $newpsw = $_POST['inp_password'];

    if(!empty($newlogin) && !empty($newemail) && !empty($newpsw)){
        //Verifica Disponibilidade do Email
        $checkEmailSql = "SELECT COUNT(*) FROM usuarios WHERE email = $1";
        $checkEmailResult = pg_prepare($connect, "check_email_query", $checkEmailSql);
        $checkEmailResult = pg_execute($connect, "check_email_query", array($newemail));

        $emailCount = pg_fetch_result($checkEmailResult, 0, 0);

        //Verifica Disponibilidade do Login
        $checkLoginSql = "SELECT COUNT(*) FROM usuarios WHERE login = $1";
        $checkLoginResult = pg_prepare($connect, "check_login_query", $checkLoginSql);
        $checkLoginResult = pg_execute($connect, "check_login_query", array($newlogin));

        $loginCount = pg_fetch_result($checkLoginResult, 0, 0);

        if ($emailCount > 0)
        {
            $erros[] = "<p class='text-danger'>Email já cadastrado!</p>";
        }
        else if($loginCount > 0)
        {
            $erros[] = "<p class='text-danger'>Login já cadastrado!</p>";
        }
        else
        {
            // Hash the password
            $hashedPsw = password_hash($newpsw, PASSWORD_DEFAULT);

            // Prepare SQL statement for inserting new user
            $insertSql = "INSERT INTO usuarios (login, email, senha) VALUES ($1, $2, $3)";
            $insertResult = pg_prepare($connect, "insert_user_query", $insertSql);

            // Execute the prepared statement with the parameters
            $insertResult = pg_execute($connect, "insert_user_query", array($newlogin, $newemail, $hashedPsw));

            if ($insertResult) {
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
            <a class="navbar-brand" href="./index.php">Bloggeragem</a>
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
        <div style="margin-right: 30%;">
        <?php
            foreach($erros as $erro){
                echo $erro;
            }
        ?>
        </div>

    </div>


    
</body>
<?php include './Shared/footer.php'; ?>
</html>
