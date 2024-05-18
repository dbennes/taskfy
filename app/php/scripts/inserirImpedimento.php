<?php

include_once "../../../php/conect/conect.php";

// Verifica se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   

    // Cria a conexão
    $conn = new mysqli($servidor, $usuario, $senha, $banco);

    // Verifica se a conexão foi estabelecida com sucesso
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obtém os dados enviados via POST
    $FTO = $_POST['FTO'];
    $outros = $_POST['outros'];
    $observacoes = $_POST['observacoes'];
    $impedimentos = json_decode($_POST['impedimentos'], true);

    // Prepara e executa a consulta SQL para inserir os dados na tabela
    $sql = "INSERT INTO Impedimentos (FOLHA_TAREFA, ANDAIME, CHUVA, MATERIAL, ENGENHARIA, OUTROS, OBSERVACOES, PB, TSE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $FTO, $impedimentos['ANDAIMES'], $impedimentos['CHUVA'], $impedimentos['MATERIAL'], $impedimentos['ENGENHARIA'], $outros, $observacoes, $impedimentos['PB'], $impedimentos['TSE']);
    
    // Verifica se a consulta foi executada com sucesso
    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso!";
    } else {
        echo "Erro ao inserir os dados: " . $stmt->error;
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
} else {
    // Se não foi enviado via POST, retorna uma mensagem de erro
    echo "Erro: Este script deve ser acessado via método POST.";
}
?>
