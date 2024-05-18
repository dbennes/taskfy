<?php

include_once "../../../php/conect/conect.php";

// Verifica se a solicitação é um POST e se o parâmetro 'FTO' está presente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["FTO"])) {
    // Limpa e sanitiza o valor do parâmetro 'FTO'
    $FTO = mysqli_real_escape_string($conecta, $_POST["FTO"]);
    $USUARIO = mysqli_real_escape_string($conecta, $_POST["USUARIO"]);


    // Constrói a consulta SQL para obter os detalhes do usuário com base no FTO fornecido
    $query = "SELECT FOLHA_TAREFA, DISCIPLINA, PROJETO, UNIDADE, SOP, SSOP, TAG, AVANCO_FT, DATA_AVANCO FROM ftos WHERE FOLHA_TAREFA = '$FTO'";

    $data_atual = date("Y-m-d");

    //AVANÇAR TODA FOLHA TAREFA PARA SIM
    $sqlAvancar = "UPDATE ftos SET AVANCO_FT = 'SIM', AVANCO_USUARIO = '$USUARIO', DATA_AVANCO='$data_atual' WHERE FOLHA_TAREFA = '$FTO' ";
    $resultSQL = mysqli_query($conecta, $sqlAvancar);

    // Executa a consulta
    $result = mysqli_query($conecta, $query);

    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Verifica se há resultados retornados
        if (mysqli_num_rows($result) > 0) {
            // Inicializa um array para armazenar os detalhes do usuário
            $user_details_array = [];

            // Obtém os detalhes do usuário e os adiciona ao array
            while ($row = mysqli_fetch_assoc($result)) {
                $user_details_array[] = $row;
            }

            // Retorna os detalhes do usuário como JSON
            echo json_encode(["resultados" => $user_details_array]);
        } else {
            // Se nenhum usuário for encontrado, retorna uma mensagem de erro
            echo json_encode(["error" => "Nenhum usuário encontrado"]);
        }
    } else {
        // Se ocorrer um erro na consulta, retorna uma mensagem de erro
        echo json_encode(["error" => "Erro na consulta ao banco de dados"]);
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conecta);
} else {
    // Se o método da solicitação não for POST ou se o parâmetro 'FTO' não estiver presente, retorna uma mensagem de erro
    echo json_encode(["error" => "Solicitação inválida"]);
}
?>
