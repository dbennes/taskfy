<div class="container-fluid">

    <button id="downloadFilteredBtn" class="btn btn-primary" style="width: 100%; margin-bottom: 10px" >Baixar Arquivos Filtrados</button>

    <div class="card shadow">

        <div class="card-header py-3">
            <p class=" m-0 fw-bold">Folha Tarefas</p>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="itemsPerPage">Itens por página:</label>
                    <select id="itemsPerPage" class="form-select">
                        <option value="5" selected>5</option>
                        <option value="10" >10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterInput">Filtrar:</label>
                    <input type="text" id="filterInput" class="form-control">
                </div>
                <!-- Adicione dois campos de entrada de data para o intervalo -->
                <div class="col-md-3">
                    <label for="startDate">Data Inicial:</label>
                    <input type="date" id="startDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="endDate">Data Final:</label>
                    <input type="date" id="endDate" class="form-control">
                </div>

                
            </div>
            <div class=" table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0" id="dataTable">
                    <thead style="font-size: 12px;">
                        <tr>
                            <th>DISCIPLINA</th>
                            <th>UNIDADE</th>
                            <th>FOLHA TAREFA</th>
                            <th>DATA PREVISTA</th>
                            <th>AVANÇO</th>
                            <th>SOP</th>
                            <th></th> 
                        </tr>
                    </thead>
                    <tbody id="tableBody" style="font-size: 12px;">
                    <?php
                        // Incluir a classe Dompdf
                        require '../vendor/autoload.php';

                        //set_time_limit(2000);

                        // Criar uma instância do Dompdf
                        use Dompdf\Dompdf;
                        use Picqer\Barcode\BarcodeGeneratorPNG;

                        //AQUI PEGO OS DADOS DAS FOLHAS QUE FORAM CRIADAS E ESTAO DENTRO DA PASTA
                        $diretorio_fts = "folha_tarefas/";
                        $pasta_folha_tarefas = scandir($diretorio_fts);

                        $folha_tarefas = [];

                        // Percorrer os arquivos da pasta de folhas de tarefas
                        foreach ($pasta_folha_tarefas as $folhas_criadas) {
                            $nome_arquivo_sem_extensao = pathinfo($folhas_criadas, PATHINFO_FILENAME);
                            array_push($folha_tarefas, $nome_arquivo_sem_extensao);
                        }

                        // Remover duplicatas do array $folha_tarefas
                        $folha_tarefas = array_unique($folha_tarefas);

                        //LISTA
                        $GetDados = ("SELECT * FROM ftos ");
                        $GetDadosquery = mysqli_query($conecta, $GetDados) or die ("Não foi possivel conectar.");

                        // Filtrar o resultado se houver um filtro
                        if (!empty($_GET['filtro'])) {
                            $filtro = mysqli_real_escape_string($conecta, $_GET['filtro']);
                            $GetDados .= "WHERE FOLHA_TAREFA LIKE '%$filtro%' OR DISCIPLINA LIKE '%$filtro%' OR UNIDADE LIKE '%$filtro%' OR SOP LIKE '%$filtro%'";
                            $GetDadosquery = mysqli_query($conecta, $GetDados) or die ("Não foi possível conectar.");
                        }

                        $row = mysqli_num_rows($GetDadosquery);

                        // Array para armazenar as folhas de tarefa já exibidas
                        $folhas_exibidas = [];

                        // Se houver um filtro, a página atual é definida como 1
                        if (!empty($_GET['filtro'])) {
                            $currentPage = 1;
                        }

                        if ($row > 0) {
                            while ($GetDadosline = mysqli_fetch_array($GetDadosquery)) {
                                $FTOS = $GetDadosline ['FOLHA_TAREFA'];
                                $DISCIPLINA = $GetDadosline ['DISCIPLINA'];
                                $UNIDADE = $GetDadosline ['UNIDADE'];
                                $SOP = $GetDadosline ['SOP'];
                                $TAG = $GetDadosline ['TAG'];
                                $AVANCO = $GetDadosline ['AVANCO_FT'];
                                $DATA_PREVISTA = $GetDadosline ['DATA_PREVISTA'];

                                // Aplica o filtro aos dados antes de verificar se eles foram exibidos anteriormente
                                if (empty($_GET['filtro']) || stripos($FTOS, $_GET['filtro']) !== false) {
                                    // Verifica se a folha de tarefa já foi exibida anteriormente
                                    if (!in_array($FTOS, $folhas_exibidas)) {
                                        // Adiciona a folha de tarefa ao array de folhas exibidas
                                        $folhas_exibidas[] = $FTOS;
                        ?>
                                <tr>
                                    <td><?php echo $DISCIPLINA; ?></td>
                                    <td><?php echo $UNIDADE; ?></td>
                                    
                                    <td><?php echo $FTOS; ?></td>
                                    <td><?php echo $DATA_PREVISTA; ?></td>
                                    <td><?php echo $AVANCO; ?></td>
                                    
                                    
                                    <td><?php echo $SOP; ?></td>
                                    <?php if (in_array($FTOS, $folha_tarefas)) { ?>
                                        <td class="text-center">
                                            <a href="#" style="text-decoration:none; background: rgb(255,255,255); font-size: 12px;" data-toggle="modal" data-target="#folhaTarefaModal" 
                                                onclick="preencherModal('<?php echo $FTOS; ?>', '<?php echo $DISCIPLINA; ?>', '<?php echo $UNIDADE; ?>', '<?php echo $TAG; ?>')">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="<?php echo "folha_tarefas/". $FTOS.".pdf"; ?>" target="_blank" style="background: rgb(255,255,255);border-style: none; font-size: 15px;">
                                                <svg class="bi bi-file-earmark-arrow-down-fill" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 16 16" style="color: rgb(213 14 14)">
                                                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0"></path>
                                                </svg>  
                                            </a>
                                        </td>
                                    <?php } else { ?>
                                        <td>
                                            
                                            <i class="fas fa-clipboard-list d-flex d-xxl-flex justify-content-center align-items-center justify-content-xxl-center align-items-xxl-center" style="font-size: 16px; color: #afadad"></i>
                                        </td>
                                    <?php } ?>
                                </tr>
                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                    </tbody>
                </table>

                <!-- Modal -->
                
                <div class="modal fade" id="folhaTarefaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
                    <div class="modal-dialog modal-lg"> <!-- modal-lg para um modal maior -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Folha de Tarefa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Inputs para editar os dados -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editFTOS" class="form-label">FOLHA TAREFA:</label>
                                            <input type="text" class="form-control" id="editFTOS">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editDisciplina" class="form-label">DISCIPLINA:</label>
                                            <input type="text" class="form-control" id="editDisciplina">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editUnidade" class="form-label">UNIDADE:</label>
                                            <input type="text" class="form-control" id="editUnidade">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editSOP" class="form-label">SOP:</label>
                                            <input type="text" class="form-control" id="editSOP">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                <!-- Tabela para exibir os tags -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead style="font-size: 12px;">
                                                    <tr>
                                                        <th class="text-center">TAG</th>
                                                        <th class="text-center">AVANÇO</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tagsTableBody" style="font-size: 12px;">
                                                    <!-- Os dados da tabela serão preenchidos dinamicamente via JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="modal-footer" >
                                <div style="width: 60%;font-size: 13px;" class="alert alert-" role="alert"> Ao clicar em SIM ou NÃO, o avanço da FOLHA TAREFA será alterado.</div>
                                <button type="button" class="btn btn-primary" onclick="salvarEdicao('SIM')">Sim?</button>
                                <button type="button" class="btn btn-warning" onclick="salvarEdicao('NÃO')">Não?</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
 
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#downloadFilteredBtn').click(function () {
                    // Obter os nomes das folhas de tarefa filtradas
                    const filteredNames = filteredRows.map(row => row.cells[2].textContent.trim()); // Assumindo que o nome da folha de tarefa está na segunda célula

                    // Exibir os nomes filtrados antes de redirecionar para a página PHP
                    console.log("Nomes filtrados:", filteredNames);

                    // Redirecionar para a página PHP com os dados como parâmetros de consulta
                    window.location.href = 'php/scripts/download.php?names=' + encodeURIComponent(JSON.stringify(filteredNames));
                });
            });
        </script>
        <script>


            function salvarEdicao(avanco) {
                var ftos = document.getElementById("editFTOS").value;
                var usuario = "<?php echo $email; ?>"; // Substitua "nome_do_usuario" pelo nome do usuário real ou obtenha-o de onde quer que esteja disponível

                console.log("Folha de Tarefa:", ftos);
                console.log("Avanço:", avanco);
                console.log("Usuário:", usuario);

                // Verificar se a variável ftos não está vazia
                if (ftos.trim() !== "") {
                    $.ajax({
                        url: 'php/scripts/processar_modal.php', // Substitua 'atualizar_avanco.php' pelo caminho correto para o seu script PHP
                        type: 'POST',
                        data: { ftos: ftos, avanco: avanco, usuario: usuario },
                        success: function(response) {
                            console.log(response); // Se precisar de feedback do servidor, pode imprimir a resposta aqui
                            // Se necessário, adicione código para atualizar a interface do usuário após a atualização do avanço

                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            // Lidar com erros, se necessário
                        }
                    });
                } else {
                    console.error("Folha de Tarefa não especificada."); // Se a variável ftos estiver vazia
                }

                // Fechar o modal
                $('#folhaTarefaModal').modal('hide');
            }


        </script>

        <script>
            function preencherModal(FTOS, DISCIPLINA, UNIDADE, SOP) {
                // Preencher os inputs do modal com os dados passados
                document.getElementById("editFTOS").value = FTOS;
                document.getElementById("editDisciplina").value = DISCIPLINA;
                document.getElementById("editUnidade").value = UNIDADE;
                document.getElementById("editSOP").value = SOP;
                
                // Fazer uma solicitação AJAX para obter os tags relacionados à folha de tarefa
                $.ajax({
                    url: 'php/scripts/obter_tags.php',
                    type: 'GET',
                    data: { ftos: FTOS },
                    success: function(response) {
                        document.getElementById("tagsTableBody").innerHTML = "";
                        var data = JSON.parse(response);
                        data.forEach(function(item) {
                            var row = document.createElement("tr");
                            var tagCell = document.createElement("td");
                            tagCell.textContent = item.tag;
                            row.appendChild(tagCell);
                            var avancoCell = document.createElement("td");
                            avancoCell.textContent = item.avanco;
                            avancoCell.classList.add("text-center"); // Adiciona a classe "text-center"
                            row.appendChild(avancoCell);
                            document.getElementById("tagsTableBody").appendChild(row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

                // Exibir o modal
                $('#folhaTarefaModal').modal('show');
            }
        </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tableBody = document.getElementById("tableBody");
        const itemsPerPageSelect = document.getElementById("itemsPerPage");
        const dataTableInfo = document.getElementById("dataTable_info");
        const filterInput = document.getElementById("filterInput");
        const pagination = document.querySelector(".pagination");

        let currentPage = 1;
        let itemsPerPage = parseInt(itemsPerPageSelect.value);
        let allRows = [];

        // Função para inicializar a tabela
        function initializeTable() {
            allRows = Array.from(tableBody.rows);
            updateTable();
        }

        // Função para atualizar a exibição da tabela com base no filtro
        // Função para atualizar a exibição da tabela com base no filtro e no intervalo de datas
        function updateTable() {
            const filterValue = filterInput.value.trim().toLowerCase(); // Remover espaços extras e converter para minúsculas
            const startDate = new Date(document.getElementById("startDate").value).getTime();
            const endDate = new Date(document.getElementById("endDate").value).getTime();

            filteredRows = allRows.filter(row => {
                const cellDate = new Date(row.cells[1].textContent).getTime(); // Supondo que a data esteja na segunda célula
                return (
                    (!startDate || cellDate >= startDate) && (!endDate || cellDate <= endDate) &&
                    Array.from(row.cells).some(cell => {
                        const cellText = cell.textContent.trim().toLowerCase(); // Remover espaços extras e converter para minúsculas
                        return cellText.includes(filterValue);
                    })
                );
            });

            // Exibir as linhas correspondentes à página atual
            showCurrentPage();
        }

        // Função para exibir as linhas correspondentes à página atual
        function showCurrentPage() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredRows.length);

            allRows.forEach(row => {
                row.style.display = "none";
            });

            for (let i = startIndex; i < endIndex; i++) {
                filteredRows[i].style.display = "table-row";
            }

            // Atualizar a paginação com base no número total de resultados filtrados
            updatePagination();
        }

        // Função para atualizar a paginação com base no número total de resultados filtrados
        function updatePagination() {
            const totalRows = filteredRows.length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            dataTableInfo.textContent = `Mostrando ${totalRows} resultado(s)`;

            pagination.innerHTML = "";

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.classList.add("page-item");
                if (i === currentPage) {
                    li.classList.add("active");
                }
                const a = document.createElement("a");
                a.classList.add("page-link");
                a.href = "#";
                a.textContent = i;
                a.addEventListener("click", function (event) {
                    event.preventDefault();
                    currentPage = i;
                    showCurrentPage();
                });
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        // Atualizar a tabela quando o número de itens por página mudar
        itemsPerPageSelect.addEventListener("change", function () {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
        });

        // Atualizar a tabela quando o usuário digitar no campo de filtro
        filterInput.addEventListener("input", function () {
            currentPage = 1;
            updateTable();
        });

        // Inicializar a tabela
        initializeTable();
    });
</script>




<?php


//FUNÇÃO PARA GERAR AS SMAS

function gerarSMA($dados, $conecta) {

    $FOLHA_TAREFA = $dados['FOLHA_TAREFA'];
    $CLIENTE = $dados['CLIENTE'];
    $PROJETO = $dados['PROJETO'];
    $CONTRATO = $dados['CONTRATO'];
    $UNIDADE = $dados['UNIDADE'];
    $SOP = $dados['SOP'];
    $SSOP = $dados['SSOP'];
    $PPU = $dados['PPU'];
    $GEP = $dados['GEP'];
    $ACTIVITY_ID = $dados['ACTIVITY_ID'];

    $RESPONSAVEL = $dados['RESPONSAVEL'];
    $DISCIPLINA = $dados['DISCIPLINA'];
    $TAREFA = $dados['TAREFA'];

    $dompdf = new Dompdf();

    $nova_sma = str_replace('FTO', 'SMA', $FOLHA_TAREFA);


    //REGISTRA A SMA NO BD DE SMAs
    $sql_inserir = "INSERT INTO sma (SMA) VALUES ('$nova_sma') ON DUPLICATE KEY UPDATE SMA = SMA";
    $inserir_dados = mysqli_query($conecta, $sql_inserir) or die("Não foi possível inserir os dados: " . mysqli_error($conecta));

     // GERA CODIGO DE BARRAS DAS SMAs
    //----------------------------------------------------------------------------------//

        // Instancia o gerador de código de barras 
        $generator2 = new BarcodeGeneratorPNG();

        // O texto que será codificado no código de barras
        $textoSMA = str_replace('FTO','SMA',$FOLHA_TAREFA);

        // Caminho completo para a pasta onde você deseja salvar o arquivo
        $caminho_da_pastaSMA = 'codigosSMA/';
        $caminho_pdfSMA = '../vendor/dompdf/dompdf/src/Image/sma/';

        // Caminho completo para o arquivo onde você deseja salvar o código de barras
        $caminho_do_arquivoSMA = $caminho_da_pastaSMA . $textoSMA.'.png';
        $caminho_gerar_pdfSMA = $caminho_pdfSMA . $textoSMA.'.png';        

        // Gera o código de barras PNG
        $barcode2 = $generator2->getBarcode($textoSMA, $generator2::TYPE_CODE_128);

        // Salva o código de barras em um arq$uivo
        file_put_contents($caminho_do_arquivoSMA, $barcode2);
        file_put_contents($caminho_gerar_pdfSMA, $barcode2);
    

    // Carrega a parte estática do HTML
    $modelo_html1 = '
                        <!DOCTYPE html>
                            <html lang="pt-br">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Documento PDF</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        margin-top: 0cm;
                                        margin-left: 1cm;
                                        margin-right: 1cm;
                                        margin-bottom: 0cm;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                    }
                                    th, td {
                                        border: 1px solid #5a5a5a;
                                        text-align: left;
                                        padding: 4px;
                                    }
                                    th {
                                        background-color: #e5e5e5;
                                    }
                                    
                                    @page {
                                        margin: 0cm 0cm;
                                        margin-top: 10px;
                                        margin-bottom: 100px; /* Defina a altura do rodapé aqui */
                                    }
                                    footer {
                                        position: fixed; 
                                        
                                        bottom:  -80px; 
                                        left: 35px; 
                                        right:  35px;
                                        

                                        /** Extra personal styles **/
                                        
                                        color: white;
                                        text-align: center;
                                        
                                        
                                    
                                    }
                                </style>
                                </style>
                            </head>
                            <body style="font-size: 9px">

                            <header>
                                <table>
                                    <tbody>
                                        <tr style="height: 100px;">
                                            <td style="width: 33%;">
                                                <img src="../vendor/dompdf/dompdf/src/Image/toyo.jpg" style="height: 70px; margin-left: 40px" />
                                            </td>
                                            <td style="width: 33%; text-align: center; font-size: 12px">
                                                <div style="font-size: 22px">SMA</div>
                                                SOLICITAÇÃO DE MATERIAIS
                                                <br/>
                                                <div style="font-size: 8px">TOTAL SUSTAINABLE ENGINEERING</div>
                                            </td>
                                            <td style="width: 33%; text-align: center">
                                                <div style="width: 100%; text-align: left;font-size: 12px">SMA</div>
                                                <div style="width: 100%; text-align: center; font-size: 15px">{{FTO}}</div>
                                                <div style="width: 100%; text-align: left">Rev.0</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </header>

                            <footer style="color: black;">
                            <table ">
                                <tbody>
                                    <tr>
                                        <td style="width: 70%;">
                                            <img src="../vendor/dompdf/dompdf/src/Image/sma/'.$textoSMA.'.png" style="width: 60%; margin-left: 100px; height: 50px" />
                                            <div style="width: 100%; text-align: center">'.$textoSMA.'</div>
                                        </td>
                                        <td >
                                            <div style="width: 100%; text-align: left;font-size: 12px">RECEBIDO POR: </div><br/>
                                            <div style="width: 100%; text-align: left">DATA:  _____/_____/_____</div>
                                            <br/>
                                            <div style="width: 100%; text-align: left">NOME: ____________________________</div>
                                            <br/>
                                            <div style="width: 100%; text-align: left">ASS: ______________________________</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </footer>
                            <main>

                            

                            <br/>
                            

                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 33%;">Cliente:  {{CLIENTE}}</td>
                                        <td style="width: 33%;">Projeto:  {{PROJETO}}</td>
                                        <td style="width: 33%;">Contrato:  {{CONTRATO}}</td>
                                        <td style="width: 33%;">Grupo de Trabalho:  {{GRUPO}}</td>
                                        <td style="width: 33%;">Unidade:  {{UNIDADE}}</td>
                                    </tr>
                                    <tr>
                                        
                                        <td style="width: 33%;">SOP:  {{SOP}}</td>
                                        <td style="width: 33%;">SSOP:  {{SSOP}}</td>
                                        <td style="width: 33%;">PPU:  {{PPU}}</td>
                                        <td style="width: 33%;">GEP:  {{GEP}}</td>
                                        <td style="width: 33%;">ACTIVITY ID:  {{ACTIVITY_ID}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>

                            

                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 33%; text-align: center; margim-right: 5px">FOLHA TAREFA</th>
                                        <th style="width: 33%; text-align: center">DISCIPLINA</th>
                                        <th style="width: 33%; text-align: center">TAREFA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 25%;">'.$FOLHA_TAREFA.'</td>
                                        <td style="width: 25%;">{{DISCIPLINA}}</td>
                                        <td style="width: 25%;">'.$TAREFA.'</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <br/>';

                        //VERIFICA SE TEM MATERIAL, SE NAO TIVER A LINHA NAO APARECE   --------------------------
                                    

                                //EXTRAIR DADOS PARA EXIBIR MATERIAIS
                                $ultimos_digitos = substr($FOLHA_TAREFA, -2);


                                $GetDados4 = "SELECT * FROM materiais_ftos WHERE NO_FT = '$FOLHA_TAREFA' ";
                                $GetDadosquery4 = mysqli_query($conecta, $GetDados4) or die("Não foi possível conectar.". mysqli_error($conecta));
                                $row4 = mysqli_num_rows($GetDadosquery4);

                                if ($row4 > 0) {

                                    $modelo_html1 .= '
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%; text-align: center; margin-right: 5px">CODIGO</th>
                                                                <th style="width: 40%; text-align: center">MATERIAL DE APLICAÇÃO</th>
                                                                <th style="width: 10%; text-align: center">QTD</th>
                                                                <th style="width: 10%; text-align: center">UND</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                                        
                                                        while ($DadosMateriaisFTOs = mysqli_fetch_array($GetDadosquery4)) {
                                                            $CodigoMaterial = $DadosMateriaisFTOs['COD_MATERIAL'];
                                                            $DescricaoMaterial = $DadosMateriaisFTOs['DESCRICAO_MATERIAL'];
                                                            $QtdMaterial = $DadosMateriaisFTOs['PESO'];
                                                            $UndMaterial = $DadosMateriaisFTOs['UNIDADE'];
                                                            $Status = $DadosMateriaisFTOs['STATUS'];
                                                            
                                                            // Adiciona estilo se o status for "ATENDIDO"
                                                            $linhaEstilo = ($Status == "ATENDIDO") ? 'text-decoration: line-through;' : '';
                                                        
                                                            $modelo_html1 .= '
                                                                <tr style="border-top-color: rgb(133,135,150);' . $linhaEstilo . '">
                                                                    <td style="text-align: left">' . $CodigoMaterial . '</td>
                                                                    <td style="padding-left: 10px">' . $DescricaoMaterial . '</td>
                                                                    <td style="text-align: center">' . str_replace(".", ",", $QtdMaterial) . '</td>
                                                                    <td style="text-align: center">' . $UndMaterial . '</td>
                                                                   
                                                                </tr>
                                                            ';
                                                        }
                                }
                                    //}

                            

    // Instancia o gerador de código de barras
    $generator = new BarcodeGeneratorPNG();

    // O texto que será codificado no código de barras
    $texto = $FOLHA_TAREFA;

    // Caminho completo para a pasta onde você deseja salvar o arquivo
    $caminho_da_pasta = 'codigosSMA/';
    $caminho_pdf = '../vendor/dompdf/dompdf/src/Image/';

    // Caminho completo para o arquivo onde você deseja salvar o código de barras
    $caminho_do_arquivo = $caminho_da_pasta . $nova_sma.'.png';
    $caminho_gerar_pdf = $caminho_pdf . $nova_sma.'.png';        

    // Gera o código de barras PNG
    $barcode = $generator->getBarcode($texto, $generator::TYPE_CODE_128);

    // Salva o código de barras em um arq$uivo
    file_put_contents($caminho_do_arquivo, $barcode);
    file_put_contents($caminho_gerar_pdf, $barcode);

    //echo 'Código de barras gerado com sucesso!';

    $modelo_html1 .= '</tbody>
                </table>
                <br/>';

    // Defina o rodapé
    $modelo_html1 .= '
        </main>
        </body>
        </html>
    ';

    $SMA = str_replace('FTO','SMA',$FOLHA_TAREFA);

    // Substituir os marcadores de posição pelos dados dinâmicos
        $modelo_html1 = str_replace('{{FTO}}', $SMA, $modelo_html1);
        $modelo_html1 = str_replace('{{CLIENTE}}', $CLIENTE, $modelo_html1);
        $modelo_html1 = str_replace('{{PROJETO}}', $PROJETO, $modelo_html1);
        $modelo_html1 = str_replace('{{CONTRATO}}', $CONTRATO, $modelo_html1);
        $modelo_html1 = str_replace('{{GRUPO}}', "PLANO 45", $modelo_html1);
        $modelo_html1 = str_replace('{{UNIDADE}}', $UNIDADE, $modelo_html1);
        $modelo_html1 = str_replace('{{SOP}}', $SOP, $modelo_html1);
        $modelo_html1 = str_replace('{{SSOP}}', $SSOP, $modelo_html1);
        $modelo_html1 = str_replace('{{PPU}}', $PPU, $modelo_html1);
       
        $modelo_html1 = str_replace('{{RESPONSAVEL}}', $RESPONSAVEL, $modelo_html1);
        $modelo_html1 = str_replace('{{DISCIPLINA}}', $DISCIPLINA, $modelo_html1);

        $modelo_html1 = str_replace('{{GEP}}', $GEP, $modelo_html1);
        $modelo_html1 = str_replace('{{ACTIVITY_ID}}', $ACTIVITY_ID, $modelo_html1);

        // Carregue o HTML no Dompdf
        $dompdf->loadHtml($modelo_html1);

        // Definir o rodapé no PDF
        $dompdf->set_option('isPhpEnabled', true); // Habilita PHP no rodapé
        $dompdf->set_option('isHtml5ParserEnabled', true); // Habilita HTML5 no rodapé
        $dompdf->set_option('isRemoteEnabled', true); // Habilita recursos remotos no rodapé
        $dompdf->set_option('isJavascriptEnabled', true); // Habilita JavaScript no rodapé
        
        


        // Renderize o PDF
        $dompdf->render();

        
        // Salvar o PDF no arquivo
        $pasta_salvar_pdf = 'sma/';
        $nome_arquivo_pdf = $SMA.'.pdf';
        $caminho_arquivo_pdf = $pasta_salvar_pdf . $nome_arquivo_pdf;

        if (!is_dir($pasta_salvar_pdf)) {
            mkdir($pasta_salvar_pdf, 0755, true);
        }

        file_put_contents($caminho_arquivo_pdf, $dompdf->output());


                                                

}





$GetDados2 = ("SELECT * FROM ftos ");
$GetDadosquery2 = mysqli_query($conecta, $GetDados2) or die ("Não foi possivel conectar.");
$row2 = mysqli_num_rows ($GetDadosquery);

if ($row2 > 0 ) { 
    
    while ($DadosFTOs = mysqli_fetch_array($GetDadosquery2)) {

        $ID = $DadosFTOs['ID'];
        $CLIENTE = $DadosFTOs['CLIENTE'];
        $PROJETO = $DadosFTOs['PROJETO'];
        $CONTRATO = $DadosFTOs['CONTRATO'];
        $DISCIPLINA = $DadosFTOs['DISCIPLINA'];
        $PPU = $DadosFTOs['PPU'];
        $FOLHA_TAREFA = $DadosFTOs['FOLHA_TAREFA'];
        $UNIDADE = $DadosFTOs['UNIDADE'];
        $TAG = $DadosFTOs['TAG'];
        $DESCRICAO = $DadosFTOs['DESCRICAO'];
        $QTD = $DadosFTOs['QTD'];
        $UND = $DadosFTOs['UND'];
        $SOP = $DadosFTOs['SOP'];
        $SSOP = $DadosFTOs['SSOP'];
        $GRUPO_DE_TRABALHO = $DadosFTOs['GRUPO_DE_TRABALHO'];
        $RESPONSAVEL = $DadosFTOs['RESPONSAVEL'];
        $PREPARACAO = $DadosFTOs['PREPARACAO'];
        $AVANCO_FT = $DadosFTOs['AVANCO_FT'];
        $TAREFA = $DadosFTOs['TAREFA'];
        $GEP = $DadosFTOs['GEP'];
        $REV = $DadosFTOs['REV'];
        $ACTIVITY_ID = $DadosFTOs['ACTIVITY_ID'];
        //$PARA = $DadosFTOs['PARA'];
        $MATERIAL_DE_APLICACAO = $DadosFTOs['MATERIAL_DE_APLICACAO'];
        $QTD_MATERIAL = $DadosFTOs['QTD_MATERIAL'];
        $PESO_KG = $DadosFTOs['PESO_KG'];
        $DOCUMENTOS = $DadosFTOs['DOCUMENTOS'];
        $LIBERACAO_CQ_FT = $DadosFTOs['LIBERACAO_CQ_FT'];

        

        //

        if (!in_array($FOLHA_TAREFA, $folha_tarefas)) {
            

            $dompdf = new Dompdf();

            

            // Carrega a parte estática do HTML
            $modelo_html1 = '
                                <!DOCTYPE html>
                                    <html lang="pt-br">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <title>Documento PDF</title>
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                margin-top: 0cm;
                                                margin-left: 1cm;
                                                margin-right: 1cm;
                                                margin-bottom: 0cm;
                                            }
                                            table {
                                                width: 100%;
                                                border-collapse: collapse;
                                            }
                                            th, td {
                                                border: 1px solid #5a5a5a;
                                                text-align: left;
                                                padding: 4px;
                                            }
                                            th {
                                                background-color: #e5e5e5;
                                            }
                                            
                                            @page {
                                                margin: 0cm 0cm;
                                                margin-top: 10px;
                                                margin-bottom: 100px; /* Defina a altura do rodapé aqui */
                                            }
                                            footer {
                                                position: fixed; 
                                                
                                                bottom:  -80px; 
                                                left: 35px; 
                                                right:  35px;
                                                

                                                /** Extra personal styles **/
                                                
                                                color: white;
                                                text-align: center;
                                                
                                                
                                            
                                            }
                                        </style>
                                        </style>
                                    </head>
                                    <body style="font-size: 9px">

                                    <header>
                                        <table>
                                            <tbody>
                                                <tr style="height: 100px;">
                                                    <td style="width: 33%;">
                                                        <img src="../vendor/dompdf/dompdf/src/Image/toyo.jpg" style="height: 70px; margin-left: 40px" />
                                                    </td>
                                                    <td style="width: 33%; text-align: center; font-size: 15px">
                                                        FOLHA TAREFA<br/>
                                                        <div style="font-size: 8px">TOTAL SUSTAINABLE ENGINEERING</div>
                                                    </td>
                                                    <td style="width: 33%; text-align: center">
                                                        <div style="width: 100%; text-align: left;font-size: 12px">FT</div>
                                                        <div style="width: 100%; text-align: center; font-size: 15px">{{FTO}}</div>
                                                        <div style="width: 100%; text-align: left">Rev.0</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </header>

                                    <footer style="color: black;">
                                    <table ">
                                        <tbody>
                                            <tr>
                                                <td style="width: 70%;">
                                                    <img src="../vendor/dompdf/dompdf/src/Image/'.$FOLHA_TAREFA.'.png" style="width: 60%; margin-left: 100px; height: 50px" />
                                                    <div style="width: 100%; text-align: center">'.$FOLHA_TAREFA.'</div>
                                                </td>
                                                <td >
                                                    <div style="width: 100%; text-align: left;font-size: 12px">EXECUÇÃO FOLHA TAREFA</div><br/>
                                                    <div style="width: 100%; text-align: left">DATA:  _____/_____/_____</div>
                                                    <br/>
                                                    <div style="width: 100%; text-align: left">NOME: ____________________________</div>
                                                    <br/>
                                                    <div style="width: 100%; text-align: left">ASS: ______________________________</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </footer>
                                    <main>

                                    
                                    <br/>
                                    

                                    <table>
                                        <tbody>
                                            <tr>
                                                <td style="width: 33%;">Cliente:  {{CLIENTE}}</td>
                                                <td style="width: 33%;">Projeto:  {{PROJETO}}</td>
                                                <td style="width: 33%;">Contrato:  {{CONTRATO}}</td>
                                                <td style="width: 33%;">Grupo de Trabalho:  {{GRUPO}}</td>
                                                <td style="width: 33%;">Unidade:  {{UNIDADE}}</td>
                                            </tr>
                                            <tr>
                                                
                                                <td style="width: 33%;">SOP:  {{SOP}}</td>
                                                <td style="width: 33%;">SSOP:  {{SSOP}}</td>
                                                <td style="width: 33%;">PPU:  {{PPU}}</td>
                                                <td style="width: 33%;">GEP:  {{GEP}}</td>
                                                <td style="width: 33%;">ACTIVITY ID:  {{ACTIVITY_ID}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br/>

                                    

                                    <table>
                                        <thead>
                                            <tr>
                                                <th style="width: 33%; text-align: center; margim-right: 5px">RESPONSÁVEL</th>
                                                <th style="width: 33%; text-align: center">DISCIPLINA</th>
                                                <th style="width: 33%; text-align: center">TAREFA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width: 25%;">{{RESPONSAVEL}}</td>
                                                <td style="width: 25%;">{{DISCIPLINA}}</td>
                                                <td style="width: 25%;">'.$TAREFA.'</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    <br/>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th style="width: 33%; text-align: center">PREPARAÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td >{{PREPARACAO}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br/>

                                    <table>
                                        <thead>
                                            <tr>
                                                <th style="width: 33%; text-align: center">DESCRIÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td >{{DESCRICAO}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br/>';

                                // DADOS PARA EXIBIÇÃO DOS TAGs e QTDs
                                
                                
            $modelo_html1 .= '  <table>
                                    <thead>
                                        <tr>
                                            <th  style="width: 70%; text-align: center">TAG</th>
                                            <th  style="width: 15%; text-align: center">QTD</th>
                                            <th  style="width: 15%; text-align: center">UND</th>
                                        </tr>
                                    </thead>
                                <tbody>';

                                $GetDados3 = "SELECT * FROM ftos WHERE FOLHA_TAREFA = '$FOLHA_TAREFA' ";
                                $GetDadosquery3 = mysqli_query($conecta, $GetDados3) or die("Não foi possível conectar.". mysqli_error($conecta));
                                $row3 = mysqli_num_rows($GetDadosquery3);

                                if ($row3 > 0) {
                                    while ($DadosFTOsTAG = mysqli_fetch_array($GetDadosquery3)) {
                                        $TAG_FTO = $DadosFTOsTAG['TAG'];
                                        $QTD_FTO = $DadosFTOsTAG['QTD'];
                                        $UND_MEDIDA_FTO = $DadosFTOsTAG['UND'];
                                    
                                        $modelo_html1 .= '
                                            <tr style="border-top-color: rgb(133,135,150);">
                                                <td style="text-align: left">' . $TAG_FTO . '</td>
                                                <td style="text-align: center">' . str_replace(".", ",", $QTD_FTO) . '</td>
                                                <td style="text-align: center">' . $UND_MEDIDA_FTO . '</td>
                                            </tr>
                                        ';
                                    }
                                }

                                    
                                /*

                                    // Inicializa o contador de itens
                                    $item_count = 0;
                                    
                                    // Organiza os dados por célula
                                    $dados_por_celula = array_fill(0, 4, array());
                                    
                                    foreach ($dados as $item) {
                                        if ($FOLHA_TAREFA == $item[6]) {
                                            $dados_por_celula[$item_count % 4][] = $item[8];
                                            $item_count++;
                                        }
                                    }
                                    
                                    // Encontra o número máximo de itens em uma coluna
                                    $max_itens_coluna = max(array_map('count', $dados_por_celula));
                                    
                                    // Loop para adicionar linhas à tabela
                                    for ($row = 0; $row < $max_itens_coluna; $row++) {
                                        $modelo_html1 .= '<tr>';
                                    
                                        // Loop através das células da tabela
                                        for ($col = 0; $col < 4; $col++) {
                                            $modelo_html1 .= '<td>';
                                    
                                            // Adiciona o item à célula da tabela, se existir
                                            if (isset($dados_por_celula[$col][$row])) {
                                                $modelo_html1 .= '<div>' . $dados_por_celula[$col][$row] . '</div>';
                                            }
                                    
                                            $modelo_html1 .= '</td>';
                                        }
                                    
                                        $modelo_html1 .= '</tr>';
                                    }
                                    
                                    $modelo_html1 .= '</tbody></table>';

                                */



                                //VERIFICA SE TEM MATERIAL, SE NAO TIVER A LINHA NAO APARECE   --------------------------
                                            

                                        //EXTRAIR DADOS PARA EXIBIR MATERIAIS

                                        $ultimos_digitos = substr($FOLHA_TAREFA, -2);

                                    

                                            //if($ultimos_digitos == '00' || $ultimos_digitos == '01'){


                                                

                                                $GetDados4 = "SELECT * FROM materiais_ftos WHERE NO_FT = '$FOLHA_TAREFA' ";
                                                $GetDadosquery4 = mysqli_query($conecta, $GetDados4) or die("Não foi possível conectar.". mysqli_error($conecta));
                                                $row4 = mysqli_num_rows($GetDadosquery4);

                                                if ($row4 > 0) {

                                                    // GERAR SMAs
                                                    $dados = array(
                                                        'FOLHA_TAREFA' => $FOLHA_TAREFA,
                                                        'CLIENTE' => $CLIENTE,
                                                        'PROJETO' => $PROJETO,
                                                        'CONTRATO' => $CONTRATO,
                                                        'UNIDADE' => $UNIDADE,
                                                        'SOP' =>  $SOP,
                                                        'SSOP' => $SSOP,
                                                        'PPU' => $PPU,
                                                        'GEP' => $GEP,
                                                        'ACTIVITY_ID' => $ACTIVITY_ID,
                                                        'RESPONSAVEL' => $RESPONSAVEL,
                                                        'DISCIPLINA' => $DISCIPLINA,
                                                        'TAREFA' => $TAREFA
                                                    );

                                                    gerarSMA($dados, $conecta);

                                                    

                                                    $modelo_html1 .= '
                                                                        </tbody>
                                                                    </table>
                                                                    <br/>
                                                                    <table>
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 20%; text-align: center; margin-right: 5px">CODIGO</th>
                                                                                <th style="width: 40%; text-align: center">MATERIAL DE APLICAÇÃO</th>
                                                                                <th style="width: 10%; text-align: center">QTD</th>
                                                                                <th style="width: 10%; text-align: center">UND</th>
                                                                               
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>';
                                                                        
                                                                        
                                                                        while ($DadosMateriaisFTOs = mysqli_fetch_array($GetDadosquery4)) {
                                                                            $CodigoMaterial = $DadosMateriaisFTOs['COD_MATERIAL'];
                                                                            $DescricaoMaterial = $DadosMateriaisFTOs['DESCRICAO_MATERIAL'];
                                                                            $QtdMaterial = $DadosMateriaisFTOs['PESO'];
                                                                            $UndMaterial = $DadosMateriaisFTOs['UNIDADE'];
                                                                            $Status = $DadosMateriaisFTOs['STATUS'];
                                                                            
                                                                            // Adiciona estilo se o status for "ATENDIDO"
                                                                            $linhaEstilo = ($Status == "ATENDIDO") ? 'text-decoration: line-through;' : '';
                                                                        
                                                                            $modelo_html1 .= '
                                                                                <tr style="border-top-color: rgb(133,135,150);' . $linhaEstilo . '">
                                                                                    <td style="text-align: left">' . $CodigoMaterial . '</td>
                                                                                    <td style="padding-left: 10px">' . $DescricaoMaterial . '</td>
                                                                                    <td style="text-align: center">' . str_replace(".", ",", $QtdMaterial) . '</td>
                                                                                    <td style="text-align: center">' . $UndMaterial . '</td>
                                                                                   
                                                                                </tr>
                                                                            ';
                                                                        }

                                                    
                                                }
                                            //}

                                        /*

                                                // Verifica se os dados foram encontrados e exibe a tabela secundária se necessário
                                                if ($dados_encontrados) {

                                                    

                                                    $folha = "{{folha}}";

                                                    // Aqui começa o loop para adicionar linhas dinâmicas à tabela secundária
                                                    foreach ($dadosM as $linha) {
                                                        // Verifica se o valor desejado está presente na segunda coluna (índice 1)
                                                        if (in_array($FOLHA_TAREFA, $linha)) {
                                                            // Adiciona uma linha à tabela secundária para cada iteração do loop
                                                            $modelo_html1 .= '
                                                                <tr style="border-top-color: rgb(133,135,150);">
                                                                    <td style="text-align: left">' . $linha[1] . '</td>
                                                                    <td style="padding-left: 10px">' . $linha[2] . '</td>
                                                                    <td style="text-align: center">' . $linha[5] . '</td>
                                                                    <td style="text-align: center">' . $linha[6] . '</td>
                                                                </tr>
                                                            ';
                                                        }
                                                    }   
                                                }
                                            }

                                            */
            
            // GERA CODIGO DE BARRAS DAS FOLHAS TAREFAS
            //----------------------------------------------------------------------------------

                // Instancia o gerador de código de barras 
                $generator = new BarcodeGeneratorPNG();

                // O texto que será codificado no código de barras
                $texto = $FOLHA_TAREFA;

                // Caminho completo para a pasta onde você deseja salvar o arquivo
                $caminho_da_pasta = 'codigos/';
                $caminho_pdf = '../vendor/dompdf/dompdf/src/Image/';

                // Caminho completo para o arquivo onde você deseja salvar o código de barras
                $caminho_do_arquivo = $caminho_da_pasta . $FOLHA_TAREFA.'.png';
                $caminho_gerar_pdf = $caminho_pdf . $FOLHA_TAREFA.'.png';        

                // Gera o código de barras PNG
                $barcode = $generator->getBarcode($texto, $generator::TYPE_CODE_128);

                // Salva o código de barras em um arq$uivo
                file_put_contents($caminho_do_arquivo, $barcode);
                file_put_contents($caminho_gerar_pdf, $barcode);
                

           

            //----------------------------------------------------------------------------------//

                                            $modelo_html1 .= '</tbody>
                                                        </table>
                                                        <br/>';

                                            if ( $DOCUMENTOS != "N/A") {       

                                                $modelo_html1 .= '
                                                                        <table>
                                                                            <thead>
                                                                                <tr>
                                                                                    <th style="width: 33%; text-align: center">DOCUMENTOS DE REFERENCIA</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td >
                                                                                        {{DOCUMENTOS}}
                                                                                    </td>
                                                                                    
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <br/>';

                                                                                                }

                                                            $modelo_html1 .= '<table>
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style=" text-align: center" colspan="4">IMPEDIMENTOS</th>
                                                                                                <th style=" text-align: center" colspan="2">ORIGEM</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td > <input type="checkbox" style="height: 15px" /> ANDAIMES </td>
                                                                                                <td > <input type="checkbox" style="height: 15px"/> MATERIAL </td>
                                                                                                <td > <input type="checkbox" style="height: 15px"/> ENGENHARIA </td>
                                                                                                <td > OUTROS: _________________________________________________ </td>
                                                                                                <td > <input type="checkbox" style="height: 15px" /> PB </td>
                                                                                                <td > <input type="checkbox" style="height: 15px"/> TSE </td>
                                                                                                
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                            <tr>
                                                                                                <td COLSPAN="6">** DETALHAR IMPEDIMENTO NO VERSO</td>
                                                                                            
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                    <br/>
                                                                                    ';


                                                                                // Defina o rodapé
                                                                                $modelo_html1 .= '
                                                                                    </main>
                                                                                    </body>
                                                                                    </html>
                                                                                ';

                                                                                // Substituir os marcadores de posição pelos dados dinâmicos
                                                                                    $modelo_html1 = str_replace('{{FTO}}', $FOLHA_TAREFA, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{CLIENTE}}', $CLIENTE, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{PROJETO}}', $PROJETO, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{CONTRATO}}', $CONTRATO, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{GRUPO}}', "PLANO 45", $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{UNIDADE}}', $UNIDADE, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{SOP}}', $SOP, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{SSOP}}', $SSOP, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{PPU}}', $PPU, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{DESCRICAO}}', $DESCRICAO, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{RESPONSAVEL}}', $RESPONSAVEL, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{DISCIPLINA}}', $DISCIPLINA, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{TURNO}}', "DEFINIR", $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{PREPARACAO}}', $PREPARACAO, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{CODIGO}}', "VERIFICAR", $modelo_html1);

                                                                                    // MATERIAL PROXIMO PASSO
                                                                                    $modelo_html1 = str_replace('{{folha}}', $FOLHA_TAREFA, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{QTD}}', "teste", $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{UND}}', "teste", $modelo_html1);

                                                                                    $modelo_html1 = str_replace('{{DOCUMENTOS}}', $DOCUMENTOS, $modelo_html1);

                                                                                    $modelo_html1 = str_replace('{{CODIGO_AVANCO}}', "teste", $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{CODIGO_CQ}}', "teste", $modelo_html1);

                                                                                    $modelo_html1 = str_replace('{{GEP}}', $GEP, $modelo_html1);
                                                                                    $modelo_html1 = str_replace('{{ACTIVITY_ID}}', $ACTIVITY_ID, $modelo_html1);

            
            
            // Carregue o HTML no Dompdf
            $dompdf->loadHtml($modelo_html1);

            // Definir o rodapé no PDF
            $dompdf->set_option('isPhpEnabled', true); // Habilita PHP no rodapé
            $dompdf->set_option('isHtml5ParserEnabled', true); // Habilita HTML5 no rodapé
            $dompdf->set_option('isRemoteEnabled', true); // Habilita recursos remotos no rodapé
            $dompdf->set_option('isJavascriptEnabled', true); // Habilita JavaScript no rodapé
            
            


            // Renderize o PDF
            $dompdf->render();

            
            // Salvar o PDF no arquivo
            $pasta_salvar_pdf = 'folha_tarefas/';
            $nome_arquivo_pdf = $FOLHA_TAREFA.'.pdf';
            $caminho_arquivo_pdf = $pasta_salvar_pdf . $nome_arquivo_pdf;

            if (!is_dir($pasta_salvar_pdf)) {
                mkdir($pasta_salvar_pdf, 0755, true);
            }

            file_put_contents($caminho_arquivo_pdf, $dompdf->output());


                                                           
        }
    }
}











?>