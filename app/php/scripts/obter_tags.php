<?php
// Supondo que você tenha uma conexão com o banco de dados estabelecida aqui
include_once "../../../php/conect/conect.php";

// Verifica se o parâmetro ftos foi recebido na solicitação GET
if(isset($_GET['ftos'])) {
    // Sanitize o valor recebido para evitar injeção de SQL (isso é apenas um exemplo, você deve usar métodos adequados de escape e sanitização)
    $ftos = $_GET['ftos'];
    
    // Consulta SQL para obter os tags relacionados à folha de tarefa
    $query = "SELECT * FROM ftos WHERE FOLHA_TAREFA = '$ftos'";
    
    // Executa a consulta
    $result = mysqli_query($conecta, $query);

    // Verifica se a consulta foi bem-sucedida
    if($result) {
        $tags = array();

        // Loop através dos resultados e adicione cada tag ao array $tags
        while($row = mysqli_fetch_assoc($result)) {
            $tags[] = array(
                "tag" => $row['TAG'],
                "avanco" => $row['AVANCO_FT']
            );
        }

        // Retorna os tags como uma resposta JSON
        echo json_encode($tags);
    } else {
        // Se houver um erro na consulta, retorne uma resposta JSON com uma mensagem de erro
        echo json_encode(array("error" => "Erro ao executar a consulta: " . mysqli_error($conexao)));
    }
} else {
    // Se o parâmetro ftos não foi recebido, retorne uma resposta JSON com uma mensagem de erro
    echo json_encode(array("error" => "Parâmetro 'ftos' não foi recebido na solicitação GET."));
}

// Fecha a conexão com o banco de dados (se necessário)
mysqli_close($conecta);
?>
