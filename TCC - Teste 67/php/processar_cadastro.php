<?php
// Garante que a sessão seja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Inclui a conexão
require_once('conexao.php'); 

$response = ['success' => false, 'message' => ''];

// Verifica se a conexão falhou
if (!$mysqli || $mysqli->connect_error) {
    $response['message'] = "Erro fatal: Falha ao conectar ao banco de dados.";
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recebe e limpa os dados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmar_senha = trim($_POST['confirmar_senha'] ?? '');

    // 2. Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $response['message'] = "Preencha todos os campos (Nome, Email e Senha).";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "E-mail inválido.";
    } elseif ($senha !== $confirmar_senha) {
        $response['message'] = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $response['message'] = "A senha deve ter no mínimo 6 caracteres.";
    } else {
        // 3. Verifica se o e-mail já existe
        $stmt_check = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
        
        if (!$stmt_check) {
             $response['message'] = "Erro SQL (Check): " . $mysqli->error;
        } else {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();
            
            if ($stmt_check->num_rows > 0) {
                $response['message'] = "Este e-mail já está cadastrado.";
            } else {
                // 4. CRIPTOGRAFIA
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                
                // 5. Insere no banco
                $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt_insert = $mysqli->prepare($sql_insert);
                
                if (!$stmt_insert) {
                     $response['message'] = "Erro de preparação (INSERT): " . $mysqli->error;
                } else {
                     $stmt_insert->bind_param("sss", $nome, $email, $senhaHash);

                    if ($stmt_insert->execute()) {
                        $response['success'] = true;
                        $response['message'] = "✅ Cadastro realizado com sucesso! Faça o login.";
                    } else {
                        $response['message'] = "Erro ao cadastrar usuário: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                }
            }
            $stmt_check->close();
        }
    }
} else {
    $response['message'] = "Método de requisição inválido.";
}

// Envia a resposta final e encerra
echo json_encode($response);
exit;
?>