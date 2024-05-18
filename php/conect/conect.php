<?php

    $servidor = "";
    $usuario = "";
    $senha = "";
    $banco = "";
    $conecta = mysqli_connect($servidor, $usuario, $senha, $banco) or die ("Não foi possivel conectar.");
    mysqli_set_charset($conecta, 'utf8');

?>
