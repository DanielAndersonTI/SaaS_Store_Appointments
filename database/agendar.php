<?php
// Conecta ao banco
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome            = $_POST['nome'];
    $telefone        = $_POST['telefone'];
    $dataHora        = $_POST['dataHora'];
    $servico_id      = (int) $_POST['servico'];         // agora vem como ID
    $profissional_id = (int) $_POST['profissional_id']; // já vem como ID
    $comercio_id     = 1; // único comércio cadastrado
    $status          = 'pendente';

    // Query preparada para segurança
    $sql = "INSERT INTO agendamentos 
            (nome_cliente, telefone_cliente, data_hora, servico_id, profissional_id, comercio_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiis", $nome, $telefone, $dataHora, $servico_id, $profissional_id, $comercio_id, $status);

    if ($stmt->execute()) {
        echo "<script>
            alert('✅ Agendamento realizado com sucesso!');
            window.location.href = '../agendamentos.html';
        </script>";
    } else {
        echo "<script>
            alert('❌ Erro ao agendar: " . addslashes($stmt->error) . "');
            window.location.href = '../agendamentos.html';
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
        alert('Método de acesso inválido.');
        window.location.href = '../agendamentos.html';
    </script>";
}
?>
