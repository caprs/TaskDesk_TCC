<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
    <link rel="icon" type="image/png" href="img/logo sem fundo.png">

</head>
<body>
<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco_tcc";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo os dados do formulário
    $nome_completo = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];

    $sql = "INSERT INTO login (nome_completo, email, telefone, data_nasc, senha, cpf) 
            VALUES ('$nome_completo', '$email', '$telefone', '$data_nasc', '$senha', '$cpf')";

    if ($conn->query($sql) === TRUE) {
        $id_cliente = $conn->insert_id;

        session_start();
        $_SESSION['nome'] = $nome_completo; 


    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Fechando a conexão
$conn->close();
?>
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="img/logo sem fundo.png" alt="Logo WorkSpot">
        </div>
        <ul class="nav-links">
            <li><a href="login.php">Login</a></li>
            <li><a href="index.html" class="btn-navbar">Início</a></li>
        </ul>
    </nav>
</header>

<section class="container_login">
    <div class="container">
        <h2 class="titulo_container">Crie sua conta</h2>
        <form action="cadastro.php" method="post" id="cpfForm">
            <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
            <input type="text" id="cpf" name="cpf" placeholder="Seu CPF" required>
            <input type="text" id="email" name="email" placeholder="Email" required>
            <input type="tel" id="telefone" name="telefone" placeholder="Telefone" required>
            <input type="date" id="data_nasc" placeholder="Data de nascimento" name="data_nasc" required>
            <input type="password" id="senha" name="senha" placeholder="Crie uma senha forte" required>

            <input type="submit" value="Cadastrar" id="btn-cadastrar">
        </form>
    </div>
</section>

<script>
    document.getElementById("cpfForm").addEventListener("submit", function (e) {
    const email = document.getElementById("email").value;
    const telefone = document.getElementById("telefone").value;

    // Verifica se o e-mail contém "@" e "."
    if (!email.includes("@") || !email.includes(".")) {
      alert("Por favor, insira um endereço de e-mail válido.");
      e.preventDefault();
    }

    // Verifica se o telefone contém exatamente 11 números
    if (!/^\d{11}$/.test(telefone)) {
      alert("Por favor, insira um número de telefone válido com 11 dígitos.");
      e.preventDefault();
    }
  });

    const cpfInput = document.getElementById('cpf');
    const form = document.getElementById('cpfForm');

    // Formatar CPF enquanto o usuário digita
    cpfInput.addEventListener('input', function () {
        let cpf = cpfInput.value;
        cpf = cpf.replace(/\D/g, ''); // Remove todos os caracteres que não são números

        // Adiciona os pontos e o traço automaticamente
        if (cpf.length <= 11) {
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }

        cpfInput.value = cpf;
    });

    // Função para validar CPF real
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, ''); // Remove tudo que não for dígito

        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false; // Verifica se o CPF tem 11 dígitos e se não é uma sequência de números repetidos
        }

        let soma = 0;
        let resto;

        // Validação do primeiro dígito verificador
        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;

        // Validação do segundo dígito verificador
        soma = 0;
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(10, 11))) return false;

        return true; // CPF é válido
    }

    // Validar CPF quando o formulário for enviado
    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Impede o envio do formulário se o CPF for inválido

        const cpf = cpfInput.value;

        if (!validarCPF(cpf)) {
            alert("Cadastro inválido! Tente novamente");
        } else {
            alert("Cadastro válido! Enviado com sucesso.");
            form.submit(); // Enviar o formulário se o CPF for válido
        }
    });
</script>

</body>
</html>
