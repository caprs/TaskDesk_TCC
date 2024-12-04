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
        echo "<h3>Agendamentos para " . htmlspecialchars($sala) . ":</h3>";
        echo "<ul>";
        foreach ($agendamentos as $agendamento) {
            // Formatar a data para dd/mm/yy
            $data_inicio = date('d/m/y', strtotime($agendamento['data_inicio']));
            $data_final = date('d/m/y', strtotime($agendamento['data_final']));
            echo "<li>De " . $data_inicio . " a " . $data_final . " das " . $agendamento['horario_inicio'] . " até " . $agendamento['horario_final'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>A sala está disponível para agendamento.</p>";
    }
    
    $stmt->close();
}

$conn->close();
?>
