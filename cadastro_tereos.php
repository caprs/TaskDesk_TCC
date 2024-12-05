<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tereos</title>
    <link rel="stylesheet" href="cadastro_tereos.css" />
    <link rel="icon" type="image/png" href="img/logo sem fundo.png">

</head>
</head>

<body>
<?php
session_start(); // Adicionado para gerenciar as sessões
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco_tcc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rm = $_POST['rm'];
    $senha = $_POST['senha'];

    $sql = "SELECT nome, rm FROM usuarios WHERE rm = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $rm, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_logado'] = $row['nome']; // Armazena o nome do usuário
        $_SESSION['rm_logado'] = $row['rm'];       // Armazena o RM do usuário
        header("Location: agendamento.php");
        exit();
    } else {
        echo "<p style='color: red;'>RM ou senha inválidos.</p>";
    }
}
?>


    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="img/logo sem fundo.png" alt="Logo WorkSpot">
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Início</a></li>
                <li><a href="#container_login">Login empresarial</a></li>


            </ul>
        </nav>
    </header>

    <section id="historia">
        <img src="img/tereos.png" id="logo">
        <div class="content">
            <img src="img/tereos_banner.jpg">
            <div class="grid-container">
                <div class="grid-item">
                    <h3>Conexão com a Agricultura</h3>
                    <p>A Tereos mantém um relacionamento próximo com o mundo da agricultura, levando em conta os interesses de todos no setor e oferecendo uma visão de longo prazo. Com o aproveitamento máximo de suas matérias-primas, a Tereos gera soluções positivas.</p>
                </div>
                <div class="grid-item">
                    <h3>Apoio aos Produtores</h3>
                    <p>O Grupo apoia os produtores a aumentar o valor qualitativo de seus produtos e a desenvolver práticas agrícolas que promovam tanto o desempenho quanto a sustentabilidade.</p>
                </div>
            </div>
        </div>

    </section>

    <section id="valores">
        <img src="img/tereos cultivar.jpg" id="imgCultivar">
        <div class="grid-container2">
            <div class="grid-item2">
                <h3 class="tituloCultivar">Cultivar com Propósito</h3>
                <p class="explicacao"><span class="highlight">Cultivar</span> é mais do que uma ação, é nosso propósito. Cuidamos da terra e das pessoas, transformando matérias-primas vegetais com pragmatismo e excelência, sempre voltados para o futuro.</p>
            </div>
            <div class="grid-item2">
                <p class="explicacao"><span class="highlight">Cultivar</span> também significa colaborar. Do campo à indústria, dos produtores aos colaboradores até os nossos clientes, cultivamos talentos e promovemos a união em prol de um futuro sustentável.</p>
            </div>
        </div>
    </section>

    <section id="proposito">
        <h1 class="titulo">Nosso Propósito</h1>
        <div class="grid-container3">
            <div class="grid-item3">
                <p>“Cultivar um futuro compartilhado para o planeta e para as pessoas, atendendo às necessidades diárias da vida”. Este é o nosso propósito: fortalecer os laços com a natureza, protegendo os recursos para garantir o futuro.</p>
            </div>
        </div>

    </section>


   



    <section id="container_login">
        <div class="container">
            <h2 class="titulo_container">Login</h2>
            <form action="agendamento.php" method="post">
                <input type="text" id="rm" name="rm" placeholder="Seu RM empresarial" required>
                <input type="password" id="senha" name="senha" placeholder="Insira sua senha" required>
                <br><br>
                <input type="submit" value="Login" id="btn-cadastrar">
            </form>
        </div>
    </section>

    

</body>

</html>