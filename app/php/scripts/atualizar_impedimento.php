<?php

include_once "../../../php/conect/conect.php";

// Verificar se os dados foram recebidos via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os valores dos campos enviados
    $ftos = $_POST['ftos'];
    $obs = $_POST['obs'];
    $id = $_POST['id'];
    $andaime = $_POST['andaime'];
    $material = $_POST['material'];
    $engenharia = $_POST['engenharia'];
    $pb = $_POST['pb'];
    $tse = $_POST['tse'];
    $outros = $_POST['outros'];

    // Query de atualização
    $sql = "UPDATE impedimentos SET OBSERVACOES='$obs', ANDAIME='$andaime', MATERIAL='$material', ENGENHARIA='$engenharia', PB='$pb', TSE='$tse', OUTROS='$outros' WHERE id='$id'";

    if ($conecta->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso!";
    } else {
        echo "Erro ao atualizar dados: " . $conecta->error;
    }

    // Fechar a conexão
    $conecta->close();
}
?>