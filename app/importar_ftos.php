<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<?php 
        session_start();
        $email = $_SESSION ['email'] ;

        //Head do Site
        include('php/template/head.php'); 

        //VERIFICA LOGIN
        include('../php/funcoes/login/verificalogin.php');

        //Conecta ao Banco
        //include ('./php/conect/conect.php');

        // Raizes
        include ('../routes.php');
 
?>

<body id="page-top">

    <div id="wrapper">

        <!-- MENU LATERAL -->
        <?php include('php/template/menu.php');?>

        <div class="d-flex flex-column" id="content-wrapper" >
            <div id="content" >

                    <!-- CONTEUDO -->
                    <?php include('php/template/importar_ftos/body.php');?>
                
            </div>
            
                <!-- RODAPE -->     

        </div>

        <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>

    </div>

    <?php include('php/template/footer.php');?>
            
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>