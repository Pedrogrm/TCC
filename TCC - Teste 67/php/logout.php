<?php
// logout.php

// Sempre inicie a sessão para poder destruí-la
session_start();

// Destrói todos os dados da sessão
session_destroy();

// Redireciona para o login
header("Location: index.php");
exit;
?>

<!-- gryg -->