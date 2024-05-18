<?php

require '../../../vendor/autoload.php';
use League\Csv\Reader;

include_once "../../../php/conect/conect.php";


// Verificar se um arquivo CSV foi enviado
if(isset($_FILES['arquivo_csv']) && $_FILES['arquivo_csv']['error'] === UPLOAD_ERR_OK) {
    // Local onde o arquivo temporário CSV foi armazenado
    $arquivo_temporario = $_FILES['arquivo_csv']['tmp_name'];

    // Carregar a biblioteca CSV
   
    // Criar um leitor CSV
    $csv = Reader::createFromPath($arquivo_temporario, 'r');
    $csv->setHeaderOffset(0); // Ignorar a primeira linha (cabeçalho)

    // Iterar sobre as linhas do CSV
    foreach ($csv as $linha) {
        // Aqui você pode inserir os dados no banco de dados
        // Exemplo de como acessar os dados do CSV:
        $FTO = $linha['FTO'];
        $DISCIPLINA = $linha['Disciplina'];
        $PROJETO = $linha['Projeto'];
        $UNIDADE = $linha['Unidade'];
        $SOP = $linha['SOP'];
        $SSOP = $linha['SSOP'];

        // Insira aqui o código para inserir esses dados no banco de dados
        // Por exemplo, você pode usar PDO para inserir os dados no banco de dados
        // Substitua as variáveis pelos campos e valores reais do seu banco de dados
    }

    // Remover o arquivo temporário
    unlink($arquivo_temporario);

    // Redirecionar para uma página de sucesso ou fazer outra coisa
    echo "Arquivo CSV enviado e processado com sucesso!";
} else {
    // Se nenhum arquivo foi enviado ou ocorreu um erro no upload
    echo "Erro ao enviar o arquivo CSV.";
}
?>
