<?php

include_once "../../../php/conect/conect.php";

// Verificar se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores dos campos enviados
    $ftos = $_POST['ftos'];
    $avanco = $_POST['avanco'];
    $usuario = $_POST['usuario'];

    // Query de atualização
    $sql = "UPDATE ftos  SET AVANCO_FT = '$avanco', AVANCO_USUARIO = '$usuario' WHERE FOLHA_TAREFA = '$ftos'";

    if ($conecta->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro ao atualizar dados: " . $conecta->error;
    }

    // Fechar a conexão
    $conecta->close();
}
?>
