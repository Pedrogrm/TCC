<?php
ini_set('display_errors', 0); // Desliga a exibição de erros HTML para requisições AJAX
error_reporting(E_ALL);

// Credenciais de Conexão Remota
$usuario = 'root';
$senha = 'Senai@118'; 
$database = 'jeca';
$host = '10.106.12.113'; 

// $usuario = 'root';
// $senha = ''; 
// $database = 'jeca';
// $host = 'localhost'; 

// Tenta Conectar
$mysqli = new mysqli($host, $usuario, $senha, $database);

// SE A CONEXÃO FALHAR:
if ($mysqli->connect_error) {
    
    // Identifica se estamos em um script AJAX que precisa de JSON
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    // Se for AJAX, retorna um JSON de erro
    if (strpos($_SERVER['SCRIPT_NAME'], 'processar_') !== false || $is_ajax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => '❌ Erro de conexão com o banco de dados. Verifique o servidor/IP. Código: ' . $mysqli->connect_errno
        ]);
        die();
    } else {
        // Se não for AJAX (e.g., acesso direto), apenas mata o script com o erro
        die("❌ Erro fatal na conexão com o servidor MySQL: " . $mysqli->connect_error);
    }
}
// Não há tag de fechamento ?>