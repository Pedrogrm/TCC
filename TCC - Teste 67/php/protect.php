<?php
// 1. INICIA A SESSÃO
// Deve ser a primeira coisa, sempre.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. FORÇA O NAVEGADOR A NÃO FAZER CACHE
// Esta é a correção para o problema do "botão voltar".
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0'); // Força a expiração imediata

// 3. VERIFICA SE O USUÁRIO ESTÁ LOGADO (Sua lógica)
if (!isset($_SESSION['id'])) {
    // Redireciona para o login em vez de mostrar "die()"
    // É uma experiência melhor para o usuário.
    header('Location: index.php');
    exit; // Importante: pare o script aqui
}

// Se o script chegou até aqui, o usuário está logado e o cache está desativado.

// if(!isset($_SESSION)) {
//     session_start();
// }

// if(!isset($_SESSION['id'])) {
//     die("Você não pode acessar esta página porque não setá logado. <p><a href =\"index.php\">Entrar</a></p>");
// }


?>

<!-- gryg -->