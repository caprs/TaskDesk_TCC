<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco_tcc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $reclamacao = $_POST['reclamacao'];

    $sql = "INSERT INTO reclamacoes (nome, email, reclamacao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("sss", $nome, $email, $reclamacao);

    if ($stmt->execute()) {
        header("Location: index.html");
        echo "Reclamação enviada com sucesso!";
        exit();

    } else {
        echo "Erro ao enviar a reclamação: " . $stmt->error;
        
    }

    $sql = "SELECT nome, reclamacao FROM reclamacoes";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='avaliacao'>";
            echo "<h3>" . htmlspecialchars($row['nome']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['reclamacao']) . "</p>";
            echo "</div>";
    }
} else {
    echo "<p>Não há reclamações no momento.</p>";
}

    // Fechar a consulta
    $stmt->close();
}

// Fechar conexão
$conn->close();
?>
