<div class="container-fluid" id="container1">

    <div style="width: 100%" class="alert alert-warning" role="alert"> [ INFORMAÇÃO ] - Para otimizar o procedimento, preencha os dados e deixe a leitura do código de barras para a última etapa.</div>

    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Impedimentos</p>
        </div>
        <div class="card-body">
            <form id="userForm">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="FTO"><strong>FTOs</strong></label>
                            <input class="form-control" type="text" id="FTO" placeholder="FTO-00000-000-0000.00" name="FTO">
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-9">
                        <!-- Checkboxes -->
                        <div class="mb-3">
                            <p class="form-label"><strong>Impedimentos:</strong></p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="andaimem" name="impedimentos[]" value="ANDAIMES">
                                <label class="form-check-label" for="andaimem">Andaimes</label>
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
                                <input placeholder="Outros impedimentos..." style="width: 450px, " class="form-control" type="text" id="outros" name="outros">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <p class="form-label"><strong>Origem:</strong></p>
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
                        
                        <!-- Campo para observações -->
                        <div class="mb-3">
                            <label class="form-label" for="observacoes"><strong>Observações:</strong></label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="1"></textarea>
                        </div>
                
                <div class="mb-3">
                    <button class="btn btn-primary btn-sm" type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        // Evento de envio do formulário
        $('#userForm').submit(function(event) {
            // Evita o comportamento padrão de envio do formulário
            event.preventDefault();
            
            // Obtém os valores dos campos do formulário
            var FTO = $('#FTO').val();
            var outros = $('#outros').val();
            var observacoes = $('#observacoes').val();
            
            // Obtém os valores dos checkboxes
            var impedimentos = {};
            $('input[name="impedimentos[]"]').each(function() {
                var chave = $(this).val();
                var valor = $(this).is(':checked') ? 'SIM' : 'NAO';
                impedimentos[chave] = valor;
            });
            
            // Envia os dados via AJAX para um script PHP
            $.ajax({
                type: 'POST',
                url: 'php/scripts/inserirImpedimento.php',
                data: {
                    FTO: FTO,
                    outros: outros,
                    observacoes: observacoes,
                    impedimentos: JSON.stringify(impedimentos)
                },
                success: function(response) {
                    // Faça algo com a resposta, se necessário
                    console.log(response);

                    // Cria uma div de alerta
                    var alertDiv = $('<div style="width: 100%" class="alert alert-success" role="alert"> [ATENÇÃO] - Interferencia Informada!</div>');
                    
                    // Adiciona a div de alerta ao corpo do documento
                    $('#container1').append(alertDiv);

                    // Remove a div de alerta após alguns segundos (opcional)
                    setTimeout(function () {
                        alertDiv.remove();
                    }, 5000); // Remove a div após 3 segundos (3000 milissegundos)

                }
            });
        });
    });

</script>
