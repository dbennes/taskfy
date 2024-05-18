<!DOCTYPE html>
<html lang="pt-br">

    <?php 

        //Head do Site
        include('php/template/head.php'); 

        //Conecta ao Banco
        include ('./php/conect/conect.php');

        // Raizes
        include ('./routes.php');
 
    ?>


<body style="background: rgb(106,144,255);">

    <?php 
    
        //Menu
        include('./php/template/menu.php'); 
        
        //Body
        include('./php/template/body.php'); 
        
        //Footer
        //include('./php/template/footer.php'); 
    
    ?>
    
    <!-- SCRIPTS -->
    
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/startup-modern.js"></script>

</body>

</html>