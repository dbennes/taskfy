<div class="container-fluid"  id="container1">
   
    <div class="card shadow">
        <div class="card-header py-3">
            <p class=" m-0 fw-bold">Lista de Impedimentos</p>
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
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterInput">Filtrar:</label>
                    <input type="text" id="filterInput" class="form-control">
                </div>
                
            </div>
            <div class=" table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                <table class="table my-0" id="dataTable">
                    <thead style="font-size: 12px;">
                        <tr>
                            <th>FTOS</th>
                            <th>ANDAIME</th>
                            <th>MATERIAL</th>
                            <th>ENGENHARIA</th>
                            <th>OUTROS</th>
                            <th>PB</th>
                            <th>TSE</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" style="font-size: 12px;">
                    <?php
                        // LISTA
                        $GetDados = "SELECT * FROM impedimentos";
                        $GetDadosquery = mysqli_query($conecta, $GetDados) or die ("Não foi possível conectar.");

                        $row = mysqli_num_rows($GetDadosquery);

                        if ($row > 0) {
                            while ($GetDadosline = mysqli_fetch_array($GetDadosquery)) {
                                $ID = $GetDadosline['id'];
                                $FTOS = $GetDadosline['FOLHA_TAREFA'];
                                $ANDAIME = $GetDadosline['ANDAIME'] ?: "NÃO";
                                $CHUVA = $GetDadosline['CHUVA'] ?: "NÃO";
                                $MATERIAL = $GetDadosline['MATERIAL'] ?: "NÃO";
                                $ENGENHARIA = $GetDadosline['ENGENHARIA'] ?: "NÃO";
                                $OUTROS = $GetDadosline['OUTROS'] ?: "NÃO";
                                $PB = $GetDadosline['PB'] ?: "NÃO";
                                $TSE = $GetDadosline['TSE'] ?: "NÃO";
                                $OBSERVACOES = $GetDadosline['OBSERVACOES'];
                    ?>
                                <tr>
                                    <td><?php echo $FTOS; ?></td>
                                    <td><?php echo $ANDAIME; ?></td>
                                    <td><?php echo $MATERIAL; ?></td>
                                    <td><?php echo $ENGENHARIA; ?></td>
                                    <td><?php echo $OUTROS; ?></td>
                                    <td><?php echo $PB; ?></td>
                                    <td><?php echo $TSE; ?></td>
                                    
                                    <td class="text-center">
                                        <a href="#" style="text-decoration:none; background: rgb(255,255,255); font-size: 12px;" data-toggle="modal" data-target="#folhaTarefaModal" onclick="preencherModal('<?php echo $FTOS; ?>', '<?php echo $OBSERVACOES; ?>', '<?php echo $ID; ?>', '<?php echo $ANDAIME; ?>', '<?php echo $MATERIAL; ?>', '<?php echo $ENGENHARIA; ?>', '<?php echo $OUTROS; ?>', '<?php echo $PB; ?>', '<?php echo $TSE; ?>', )">
                                            <i class="far fa-edit"></i>
                                        </a>&nbsp;
                                        <a href="#" onclick="deletarImpedimento('<?php echo $ID; ?>')" style="background: rgb(255,255,255);border-style: none; ">
                                            <i class="fas fa-trash" style="color: rgb(213,14,14);"></i>
                                        </a>
                                    </td>
                                </tr>
                    <?php
                            }
                        }
                    ?>

                    </tbody>
                </table>

                <!-- Modal -->
                
                <div class="modal fade" id="folhaTarefaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 13px;">
                    <div class="modal-dialog modal-lg"> <!-- modal-lg para um modal maior -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Editar Impedimento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Inputs para editar os dados -->
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editFTOS" class="form-label">FOLHA TAREFA:</label>
                                            <input type="text" class="form-control" id="editFTOS" readOnly >
                                        </div>
                                    </div>
                                    <div class="col" style="display: none;">
                                        <div class="mb-3">
                                            <label for="id" class="form-label">ID:</label>
                                            <input type="text" class="form-control" id="id" readOnly >
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-9">
                                        <!-- Checkboxes -->
                                        <div class="mb-3">
                                            <p class="form-label"><strong>Impedimentos:</strong></p>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="andaime" name="impedimentos[]" value="ANDAIMES">
                                                <label class="form-check-label" for="andaime">Andaimes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="material" name="impedimentos[]" value="MATERIAL">
                                                <label class="form-check-label" for="material">Material</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="engenharia" name="impedimentos[]" value="ENGENHARIA">
                                                <label class="form-check-label" for="engenharia">Engenharia</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input placeholder="Outros impedimentos..." style="height: 30px" class="form-control" type="text" id="editOUTROS" name="outros">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="mb-3" >
                                            <p class="form-label" style="margin-bottom: 11px"><strong>Origem:</strong></p>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="PB" name="impedimentos[]" value="PB">
                                                <label class="form-check-label" for="PB">PB</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="TSE" name="impedimentos[]" value="TSE">
                                                <label class="form-check-label" for="TSE">TSE</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="editOBS" class="form-label">OBSERVAÇÃO:</label>
                                            <textarea id="editOBS" class="form-control" id="observacoes" name="observacoes" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" >
                               
                                <button type="button" class="btn btn-primary" onclick="salvarEdicao('SIM')">Atualizar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const itemsPerPageSelect = document.getElementById("itemsPerPage");
    const filterInput = document.getElementById("filterInput");
    const pagination = document.querySelector(".pagination");

    let currentPage = 1;
    let itemsPerPage = parseInt(itemsPerPageSelect.value);
    let filteredRows = [];
    let allRows = [];

    // Função para inicializar a tabela
    function initializeTable() {
        allRows = Array.from(tableBody.rows);
        updateTable();
    }

    // Função para atualizar a exibição da tabela com base no filtro
    function updateTable() {
        const filterValue = filterInput.value.trim().toLowerCase(); // Remover espaços extras e converter para minúsculas

        filteredRows = allRows.filter(row => {
            return Array.from(row.cells).some(cell => {
                const cellText = cell.textContent.trim().toLowerCase(); // Remover espaços extras e converter para minúsculas
                return cellText.includes(filterValue);
            });
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

            function preencherModal(FTOS, OBS, ID, ANDAIME, MATERIAL, ENGENHARIA, OUTROS, PB, TSE) {
                // Preencher os inputs do modal com os dados passados
                document.getElementById("editFTOS").value = FTOS;
                document.getElementById("editOBS").value = OBS;
                document.getElementById("id").value = ID;
                document.getElementById("editOUTROS").value = OUTROS;
                
                // Preencher e verificar o campo "engenharia"
                var andaimeCheckbox = document.getElementById("andaime");
                andaimeCheckbox.checked = ANDAIME === "SIM";

                // Preencher e verificar o campo "engenharia"
                var materialCheckbox = document.getElementById("material");
                materialCheckbox.checked = MATERIAL === "SIM";

                // Preencher e verificar o campo "engenharia"
                var engenhariaCheckbox = document.getElementById("engenharia");
                engenhariaCheckbox.checked = ENGENHARIA === "SIM";

                // Preencher e verificar o campo "engenharia"
                var pbCheckbox = document.getElementById("PB");
                pbCheckbox.checked = PB === "SIM";

                // Preencher e verificar o campo "engenharia"
                var tseCheckbox = document.getElementById("TSE");
                tseCheckbox.checked = TSE === "SIM";

                // Exibir o modal
                $('#folhaTarefaModal').modal('show');
            }

            function salvarEdicao() {
                // Obter os valores dos inputs
                var ftos = document.getElementById("editFTOS").value;
                var obs = document.getElementById("editOBS").value;
                var id = document.getElementById("id").value;
                var outros = document.getElementById("editOUTROS").value;

                // Obter os valores dos checkboxes
                var andaimeChecked = document.getElementById("andaime").checked ? "SIM" : "NÃO";
                var materialChecked = document.getElementById("material").checked ? "SIM" : "NÃO";
                var engenhariaChecked = document.getElementById("engenharia").checked ? "SIM" : "NÃO";
                var pbChecked = document.getElementById("PB").checked ? "SIM" : "NÃO";
                var tseChecked = document.getElementById("TSE").checked ? "SIM" : "NÃO";

                // Enviar os dados via AJAX para o arquivo PHP
                $.ajax({
                    type: "POST",
                    url: "php/scripts/atualizar_impedimento.php", // Nome do arquivo PHP
                    data: {
                        ftos: ftos,
                        obs: obs,
                        id: id,
                        andaime: andaimeChecked,
                        material: materialChecked,
                        engenharia: engenhariaChecked,
                        pb: pbChecked,
                        tse: tseChecked,
                        outros: outros
                    },
                    success: function(response) {
                        // Manipular a resposta do servidor, se necessário
                        console.log(response);
                        // Fechar o modal após atualizar os dados no banco
                        $('#folhaTarefaModal').modal('hide');

                        alert("Dados atualizados com sucesso!");

                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Lidar com erros, se houver
                        console.error(error);    
                    }
                });
            }


            function deletarImpedimento(ID) {
                // Perguntar ao usuário se ele deseja realmente excluir o impedimento
                var confirmacao = confirm("Tem certeza que deseja excluir este impedimento?");

                // Verificar se o usuário confirmou a exclusão
                if (confirmacao) {
                    var id = ID;

                    $.ajax({
                        type: "POST",
                        url: "php/scripts/deletar_impedimento.php", // Nome do arquivo PHP
                        data: {
                            id: id
                        },
                        success: function(response) {
                            // Manipular a resposta do servidor, se necessário
                            console.log(response);
                            // Exibir um alerta após a exclusão
                            alert("Impedimento excluído com sucesso!");
                            // Recarregar a página após a exclusão
                            window.location.reload();
                        },
                        error: function(xhr, status, error) {
                            // Lidar com erros, se houver
                            console.error(error);    
                        }
                    });
                }
            }

</script>