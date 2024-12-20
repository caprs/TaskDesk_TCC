    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agendamento Tereos</title>
        <link rel="stylesheet" href="agendamento.css">
        <link rel="icon" type="image/png" href="img/logo sem fundo.png">

    </head>

    <body>

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
            $sala = $_POST['sala'] ?? null;
            $data_inicio = $_POST['data_inicio'] ?? null;
            $data_final = $_POST['data_final'] ?? null;
            $horario_inicio = $_POST['horario_inicial'] ?? null;
            $horario_final = $_POST['horario_final'] ?? null;
            $nome_agendador = $_POST['nome_agendador'] ?? null;
            $rm_funcionario_fk = $_POST['rm'] ?? null;

            if ($sala && $data_inicio && $data_final && $horario_inicio && $horario_final && $nome_agendador && $rm_funcionario_fk) {
                $stmt = $conn->prepare(
                    "INSERT INTO agendamentos (sala, data_inicio, data_final, horario_inicio, horario_final, nome_agendador, rm_funcionario_fk) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->bind_param("ssssssi", $sala, $data_inicio, $data_final, $horario_inicio, $horario_final, $nome_agendador, $rm_funcionario_fk);
                $stmt->execute();
                echo "<div class='message success'>Sala agendada com sucesso!</div>";
            } else{
                echo "<div class='message error'>Erro ao agendar sala. Tente novamente! </div>";
            }
            $stmt->close();
        }

        $conn->close();
        ?>





        <header>
            <nav class="navbar">
                <div class="logo">
                    <img src="img/logo sem fundo.png" alt="logotaskdesk">
                </div>
                <ul class="nav-links">
                    <li><a href="cadastro_tereos.php">Tereos</a></li>
                    <li><a href="index.html" class="btn-navbar">Início</a></li>
                </ul>
            </nav>
        </header>



        <form action="sala_disponibilidade.php" method="POST" class="disponibilidade">
            <label for="sala">Escolha a Sala:</label>
            <select id="sala" name="sala" required>
                <option value="Sala 1">Espaço de apresentação</option>
                <option value="Sala 2">Sala de reunião 1</option>
                <option value="Sala 3">Sala de reunião 2</option>
                <option value="Sala 4">Espaço de reunião com café</option>
                <option value="Sala 5">Espaço compartilhado</option>
                <option value="Sala 6">Espaço individual</option>


            </select>
            <input type="submit" value="Verificar Disponibilidade">
        </form>

        <section class="salas">
            <div class="sala">
                <div class="label">Espaço de apresentação</div>
                <img src="img/sala1.png" alt="Imagem 1">
            </div>
            <div class="sala">
                <div class="label">Sala de reunião 1</div>
                <img src="img/sala2.png" alt="Imagem 2">
            </div>
            <div class="sala">
                <div class="label">Sala de reunião 2</div>
                <img src="img/sala3.png" alt="Imagem 3">
            </div>
            <div class="sala">
                <div class="label">Espaço de reunião com café</div>
                <img src="img/sala com café.jpg" alt="Imagem 3">
            </div>
            <div class="sala">
                <div class="label">Espaço compartilhado</div>
                <img src="img/sala reuniao.jpg" alt="Imagem 3">
            </div>
            <div class="sala">
                <div class="label">Espaço individual</div>
                <img src="img/sala individual.jpeg">
            </div>

        </section>

        <header>
            <h1>Agendar Sala de Trabalho</h1>
        </header>

        <section class="container">
            <form action="agendamento.php" method="POST">
                <label for="sala">Sala:</label>
                <select id="sala" name="sala" required>
                    <option value="">Selecione a Sala</option>
                    <option value="Sala 1">Espaço de apresentação</option>
                    <option value="Sala 2">Sala de reunião 1</option>
                    <option value="Sala 3">Sala de reunião 2</option>
                    <option value="Sala 4">Espaço de reunião com café</option>
                    <option value="Sala 5">Espaço compartilhado</option>
                    <option value="Sala 6">Espaço individual</option>
                </select>

                <label for="data_inicio">Data de Início:</label>
                <input type="date" id="data_inicio" name="data_inicio" required>

                <label for="data_final">Data Final:</label>
                <input type="date" id="data_final" name="data_final" required>

                <label for="horario_inicial">Horário de Início:</label>
                <input type="time" id="horario_inicial" name="horario_inicial" required>

                <label for="horario_final">Horário Final:</label>
                <input type="time" id="horario_final" name="horario_final" required>

                <label for="nome_agendador">Quem está agendando:</label>
                <input type="text" id="nome_agendador" name="nome_agendador" placeholder="Seu nome" required>

                <label for="rm">Informe seu RM:</label>
                <input type="text" id="rm" name="rm" placeholder="Seu RM empresarial" required>

                <input type="submit" value="Agendar Sala">
            </form>



    </body>
    <script>
    // Aguarda 3 segundos e depois adiciona a classe fade-out
    setTimeout(() => {
        const message = document.querySelector('.message');
        if (message) {
            message.classList.add('fade-out');
            // Remove a mensagem do DOM após a transição
            setTimeout(() => message.remove(), 500);
        }
    }, 3000); // 3000ms = 3 segundos
</script>

    </html>