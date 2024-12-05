<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidade</title>
    <link rel="stylesheet" href="disponibilidade.css">
    <link rel="icon" type="image/png" href="img/logo sem fundo.png">

</head>

<body>
<header>
      <nav class="navbar">
        <div class="logo">
          <img src="img/logo sem fundo.png" alt="Logo WorkSpot" />
        </div>
        <ul class="nav-links">
          <li><a href="#inicio">Início</a></li>

          <li><a href="#contato">Contate-nos</a></li>
        </ul>
      </nav>
    </header>
</body>

</html>

<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "banco_tcc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sala = $_POST['sala'];

    // Consulta os agendamentos da sala
    $query = "SELECT data_inicio, data_final, horario_inicio, horario_final FROM agendamentos WHERE sala = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $sala);
    $stmt->execute();
    $result = $stmt->get_result();

    $agendamentos = [];
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }

    if (count($agendamentos) > 0) {
        echo "<div class='agendamento-info'>";
        echo "<h3>Agendamentos para " . htmlspecialchars($sala) . ":</h3>";
        echo "<ul>";
        foreach ($agendamentos as $agendamento) {
            // Formatar a data para dd/mm/yy
            $data_inicio = date('d/m/y', strtotime($agendamento['data_inicio']));
            $data_final = date('d/m/y', strtotime($agendamento['data_final']));
            echo "<li>De " . $data_inicio . " a " . $data_final . " das " . $agendamento['horario_inicio'] . " até " . $agendamento['horario_final'] . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='agendamento-info'>";
        echo "<p>A sala está disponível para agendamento.</p>";
        echo "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<div class="botao-container">
    <a href="agendamento.php" class="btn-voltar">Voltar para Agendamento</a>
</div>
