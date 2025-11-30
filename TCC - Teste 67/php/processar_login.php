<?php
// Garante que a sessão seja iniciada ANTES de qualquer output (incluindo o header JSON)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Garante que a resposta seja JSON (IMPORTANTE: deve vir antes de echo)
header('Content-Type: application/json');

// Inclui o arquivo de conexão. Certifique-se de que o caminho está correto.
// O arquivo conexao.php deve declarar a variável $mysqli
require_once('conexao.php'); 

$response = ['success' => false, 'message' => ''];

// Verifica se a conexão falhou (caso o conexao.php não tenha um die() com JSON)
if (!$mysqli || $mysqli->connect_error) {
    // Se a conexão falhar, retorna o erro JSON e mata o script.
    $response['message'] = "Erro fatal: Falha ao conectar ao banco de dados.";
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    // 1. Validação simples
    if (empty($email) || empty($senha)) {
        $response['message'] = "Preencha o e-mail e a senha.";
        echo json_encode($response);
        exit;
    }

    // 2. Busca o usuário pelo e-mail
    $stmt = $mysqli->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    
    // Verificação de falha na preparação (ex: erro SQL)
    if (!$stmt) {
        $response['message'] = "Erro de preparação SQL: " . $mysqli->error;
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        $sql_query = $stmt->get_result();
        
        if ($sql_query->num_rows == 1) {
            $usuario = $sql_query->fetch_assoc();
            
            // 3. Verifica a senha criptografada (HASH)
            if (password_verify($senha, $usuario['senha'])) {
                
                // SUCESSO: Salva dados na sessão
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                
                $response['success'] = true;
                $response['message'] = "Login realizado com sucesso!";
            } else {
                $response['message'] = "E-mail ou senha incorretos.";
            }
        } else {
            // E-mail não encontrado
            $response['message'] = "E-mail ou senha incorretos.";
        }
    } else {
        $response['message'] = "Falha ao executar a consulta no banco de dados.";
    }
    
    $stmt->close();
} else {
    $response['message'] = "Método de requisição inválido.";
}

// Envia a resposta final
echo json_encode($response);
exit;
?>