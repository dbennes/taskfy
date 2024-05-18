<?php

error_reporting(E_ALL); 
ini_set('display_errors', 1);

// Verifica se o parâmetro 'names' está presente na URL
if(isset($_GET['names'])) {
    // Obtém os nomes dos arquivos da URL e decodifica o JSON
    $filteredNames = json_decode($_GET['names'], true);

    // Cria um novo arquivo ZIP
    $zip = new ZipArchive();
    $zipFileName = 'folhas_tarefa.zip'; // Nome do arquivo ZIP
    if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
        // Diretório onde estão localizados os arquivos PDF
        $pdfDirectory = '../../folha_tarefas/';

        // Adiciona cada folha de tarefa ao arquivo ZIP
        foreach ($filteredNames as $fileName) {
            // Verifica se o arquivo existe antes de adicioná-lo ao ZIP
            $filePath = $pdfDirectory . $fileName . ".pdf";
            if(file_exists($filePath) && is_file($filePath)) {
                // Adiciona o arquivo ao ZIP com a extensão .pdf
                $zip->addFile($filePath, $fileName . ".pdf");
            }
        }

        // Fecha o arquivo ZIP
        $zip->close();

        // Define cabeçalhos para forçar o download do arquivo ZIP
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$zipFileName");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($zipFileName);

        // Remove o arquivo ZIP após o download
        unlink($zipFileName);
    } else {
        // Se houver algum erro ao criar o arquivo ZIP, retorna uma resposta de erro
        echo json_encode(array('error' => 'Erro ao criar o arquivo ZIP.'));
    }
} else {
    // Se o parâmetro 'names' não estiver presente na URL, exiba uma mensagem de erro
    echo "Erro: Nenhum nome de arquivo foi recebido.";
}
?>
