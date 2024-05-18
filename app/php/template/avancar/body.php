<div class="container-fluid" id="container1">

    <div style="width: 100%" class="alert alert-warning" role="alert"> [ ATENÇÃO ] - AO LER O CODIGO DE BARRA AS ATIVIDADES SERÃO AVANÇADAS 100% !</div>

    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">AVANÇO DE FOLHA TAREFAS</p> 
        </div>
        <div class="card-body">
            <form id="userForm">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="FTO"><strong>Pesquisar FTO:</strong></label>
                            <input class="form-control" type="text" id="FTO" placeholder="FTO-00000-000-0000.00" name="FTO">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="DISCIPLINA"><strong>Disciplina:</strong></label>
                            <input class="form-control" type="text" id="DISCIPLINA" placeholder="Disciplina" name="DISCIPLINA">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="PROJETO"><strong>Projeto:</strong></label>
                            <input class="form-control" type="text" id="PROJETO" placeholder="Projeto" name="PROJETO">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="UNIDADE"><strong>Unidade:</strong></label>
                            <input class="form-control" type="text" id="UNIDADE" placeholder="Unidade" name="UNIDADE">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="SOP"><strong>SOP</strong></label>
                            <input class="form-control" type="text" id="SOP" placeholder="SOP" name="SOP">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="SSOP"><strong>SSOP</strong></label>
                            <input class="form-control" type="text" id="SSOP" placeholder="SSOP" name="SSOP">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table id="tableResult" class="table">
                            <thead>
                                <tr>
                                    <th>Folha Tarefa</th>
                                    <th>Disciplina</th>
                                    <th>Unidade</th>
                                    <th>TAG</th>
                                    <th class="text-center" >Avançada?</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary btn-sm" type="submit">Avançar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
    // Evento de envio do formulário
    $('#userForm').submit(function (event) {
        // Evita o comportamento padrão de envio do formulário
        event.preventDefault();

        // Obtém o valor do campo FTO
        var FTO = $('#FTO').val();

        // Obtém o valor do campo email
        var USUARIO = '<?php echo $email; ?>';


        // Envia a solicitação AJAX
        $.ajax({
            type: 'POST',
            url: 'php/scripts/consultarBanco.php',
            data: {
                FTO: FTO,
                USUARIO: USUARIO // Adiciona o valor do campo email aos dados enviados
            },
            success: function (data) {
                console.log(data); // Verifique o que está sendo retornado pelo PHP
                var userDetails = JSON.parse(data);

                // Preenche os campos do formulário com os detalhes retornados
                $('#DISCIPLINA').val(userDetails.resultados[0].DISCIPLINA);
                $('#PROJETO').val(userDetails.resultados[0].PROJETO);
                $('#UNIDADE').val(userDetails.resultados[0].UNIDADE);
                $('#SOP').val(userDetails.resultados[0].SOP);
                $('#SSOP').val(userDetails.resultados[0].SSOP);

                // Limpa a tabela antes de adicionar os novos dados
                $('#tableResult tbody').empty();

                // Itera sobre os resultados para adicionar cada um como uma nova linha na tabela
                userDetails.resultados.forEach(function(result) {
                    var newRow = '<tr>' +
                        '<td>' + result.FOLHA_TAREFA + '</td>' +
                        '<td>' + result.DISCIPLINA + '</td>' +
                        '<td>' + result.UNIDADE + '</td>' +
                        '<td>' + result.TAG + '</td>' +
                        '<td class="text-center">' + result.AVANCO_FT + '</td>' +
                        '<td>' + result.DATA_AVANCO + '</td>' +
                        
                        '</tr>';

                    // Adiciona a nova linha à tabela
                    $('#tableResult tbody').append(newRow);
                });

                // Cria uma div de alerta
                var alertDiv = $('<div style="width: 100%" class="alert alert-success" role="alert"> [ATENÇÃO] - Folha Tarefa Avançada!</div>');

                // Adiciona a div de alerta ao corpo do documento
                $('#container1').append(alertDiv);

                // Limpa o campo FTO
                $('#FTO').val('');

                // Remove a div de alerta após alguns segundos (opcional)
                setTimeout(function () {
                    alertDiv.remove();
                }, 5000); // Remove a div após 3 segundos (3000 milissegundos)
            },
            error: function (xhr, status, error) {
                // Se ocorrer um erro na solicitação AJAX, exiba uma mensagem de erro
                console.error(xhr.responseText);
            }
        });

    });
});

</script>
