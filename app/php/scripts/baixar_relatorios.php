<?php
include_once "../../../php/conect/conect.php";

// Incluir a classe PhpSpreadsheet
require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Verificar se o tipo de dados foi enviado
if(isset($_GET['type'])) {
    $type = $_GET['type'];

    // Consulta ao banco de dados
    if ($type === 'RAF' || $type === 'RIF') {
        // Selecione a tabela correta com base no tipo de dados solicitado
        $table = ($type === 'RAF') ? 'ftos' : 'impedimentos'; // Substitua 'outra_tabela' pelo nome da tabela para o relatório RIF

        // Consulta ao banco de dados
        $sql = "SELECT * FROM $table";
        $result = $conecta->query($sql);

        // Criar um novo objeto Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Cria uma folha de cálculo
        $sheet = $spreadsheet->getActiveSheet();

        // Adiciona cabeçalhos dinamicamente
        $columnHeaders = [];
        if ($result->num_rows > 0) {
            $row = 1;
            while ($row_data = $result->fetch_assoc()) {
                if ($row === 1) {
                    // Adiciona cabeçalhos na primeira linha
                    $columnHeaders = array_keys($row_data);
                    $sheet->fromArray($columnHeaders, NULL, 'A1');

                    // Estilo para os cabeçalhos
                    $styleHeader = [
                        'font' => ['bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                    ];
                    $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($styleHeader);

                    $row++;
                }
                // Adiciona dados
                $sheet->fromArray($row_data, NULL, 'A' . $row);

                $row++;
            }
        }

        // Definir a largura das colunas
        foreach(range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Cria um objeto Writer para salvar o arquivo
        $writer = new Xlsx($spreadsheet);

        // Definir os cabeçalhos para download do arquivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $type . '_relatorio.xlsx"');
        header('Cache-Control: max-age=0');

        // Salvar o arquivo no buffer de saída (Output)
        $writer->save('php://output');

        // Fechar a conexão
        $conecta->close();
    } else {
        echo 'Tipo de dados inválido.';
    }
} else {
    echo 'Tipo de dados não foi especificado.';
}
?>
