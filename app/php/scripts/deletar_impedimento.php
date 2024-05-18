<?php

include_once "../../../php/conect/conect.php";


$id = $_POST['id'];

// Verificar se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores dos campos enviados
    
    $id = $_POST['id'];

    // Query de atualização
    $sql = "DELETE FROM impedimentos WHERE  id='$id'";

    if ($conecta->query($sql) === TRUE) {
        echo "Dados deletados com sucesso!";
    } else {
        echo "Erro ao deletar os dados: " . $conecta->error;
    }

    // Fechar a conexão
    $conecta->close();
}
?>
