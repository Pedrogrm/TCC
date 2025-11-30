<?php
// auth.php - Gerencia Login e Cadastro em um único lugar

// Inicia sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define que a resposta será SEMPRE JSON
header('Content-Type: application/json; charset=utf-8');

// Inclui a conexão
require_once('conexao.php');

// Array de resposta padrão
$response = [
    'success' => false, 
    'message' => 'Erro desconhecido.'
];

// Verifica conexão com banco
if (!isset($mysqli) || $mysqli->connect_error) {
    $response['message'] = "Erro fatal: Banco de dados indisponível.";
    echo json_encode($response);
    exit;
}

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Identifica qual ação o usuário quer fazer (login ou cadastro)
    // Vamos adicionar um campo 'acao' no formulário HTML
    $acao = $_POST['acao'] ?? '';

    // ======================================================
    // 🔐 LÓGICA DE LOGIN
    // ======================================================
    if ($acao === 'login') {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($email) || empty($senha)) {
            $response['message'] = "Preencha o e-mail e a senha.";
        } else {
            // Prepara a busca
            $stmt = $mysqli->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
            
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $usuario = $result->fetch_assoc();
                    
                    // Verifica Senha
                    if (password_verify($senha, $usuario['senha'])) {
                        // Sucesso!
                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['nome'] = $usuario['nome'];
                        
                        $response['success'] = true;
                        $response['message'] = "Login realizado com sucesso! Carregando...";
                        // Retornamos o nome para usar no jogo se precisar
                        $response['nome_usuario'] = $usuario['nome']; 
                    } else {
                        $response['message'] = "Senha incorreta.";
                    }
                } else {
                    $response['message'] = "E-mail não encontrado.";
                }
                $stmt->close();
            } else {
                $response['message'] = "Erro interno no banco (Prepare).";
            }
        }
    }

    // ======================================================
    // 📝 LÓGICA DE CADASTRO
    // ======================================================
    elseif ($acao === 'cadastro') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');
        $confirmar = trim($_POST['confirmar_senha'] ?? '');

        // Validações
        if (empty($nome) || empty($email) || empty($senha)) {
            $response['message'] = "Preencha todos os campos.";
        } elseif ($senha !== $confirmar) {
            $response['message'] = "As senhas não coincidem.";
        } elseif (strlen($senha) < 6) {
            $response['message'] = "A senha deve ter no mínimo 6 caracteres.";
        } else {
            // Verifica se e-mail já existe
            $check = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $response['message'] = "Este e-mail já está em uso.";
            } else {
                // Cria o usuário
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $insert = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                
                if ($insert) {
                    $insert->bind_param("sss", $nome, $email, $senhaHash);
                    
                    if ($insert->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Cadastro realizado! Faça login agora.";
                    } else {
                        $response['message'] = "Erro ao salvar no banco.";
                    }
                    $insert->close();
                } else {
                    $response['message'] = "Erro interno no banco (Insert).";
                }
            }
            $check->close();
        }
    } else {
        $response['message'] = "Ação inválida (login ou cadastro não especificado).";
    }

} else {
    $response['message'] = "Método inválido. Use POST.";
}

// Retorna o JSON final
echo json_encode($response);
exit;
?>