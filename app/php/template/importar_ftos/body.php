<div class="container-fluid" id="container1">
    <div class="card shadow mb-3">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">IMPORTAR</p>
        </div>
        <div class="card-body">
            <form action="php/scripts/processar_csv.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" for="arquivo_csv"><strong>Selecione o arquivo:</strong></label>
                    <input class="form-control" type="file" id="arquivo_csv" name="arquivo_csv" accept=".csv">
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary btn-sm" type="submit">Enviar CSV</button>
                </div>
            </form>
        </div>
    </div>
</div>
