<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/png" href="img/logo sem fundo.png">


</head>
<body>

<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "banco_tcc"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT email, senha FROM login WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        session_start();
        $usuario = $resultado->fetch_assoc();
        
        $_SESSION['email'] = $usuario['email'];
        
        header("Location: salas.html");
        exit();
    } else {
        echo "<p style='color: red; text-align: center; padding: 20px;'>Email ou senha incorretos</p>";
    }

    $stmt->close();
}
$conn->close();
?>

    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="img/logo sem fundo.png" alt="Logo WorkSpot">
            </div>
            <ul class="nav-links">
                <li><a href="index.html" class="btn-navbar">Início</a></li>
            </ul>
        </nav>
       </header>

       <section class="container_login">
            <div class="container">
             <h2 class="titulo_container">Login</h2>
                <form action="login.php" method="post" id="login">
                    <input type="email" id="email" name="email" placeholder="Seu email cadastrado" required>
                    <input type="password" id="senha" name="senha" placeholder="Insira sua senha" required>
                    <input type="submit" value="Login" id="btn-cadastrar">
                </form>
            </div>
        </section>


       
</body>

<script>
    document.getElementById("login").addEventListener("submit", function (e) {
    const email = document.getElementById("email").value;
    const telefone = document.getElementById("telefone").value;

    // Verifica se o e-mail contém "@" e "."
    if (!email.includes("@") || !email.includes(".")) {
      alert("Por favor, insira um endereço de e-mail válido.");
      e.preventDefault();
    }
  });

</script>
</html>