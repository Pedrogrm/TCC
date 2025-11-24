<?php 
// Habilita a exibição de erros para depuração (pode ser removido em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclui a conexão com o banco
include('conexao.php');

// Inicia a sessão
session_start();

// Inicializa a variável que guardará as mensagens de erro
$login_error = ""; 

// Verifica se o formulário foi enviado (POST)
if (isset($_POST['email']) && isset($_POST['senha'])) {

    // Limpa os dados de entrada
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // --- Verificações básicas ---
    if (empty($email)) {
        $login_error = "Por favor, preencha seu e-mail.";
    } elseif (empty($senha)) {
        $login_error = "Por favor, preencha sua senha.";
    } else {

        // --- PROTEÇÃO CONTRA INJEÇÃO DE SQL (PREPARED STATEMENTS) ---
        
        // 1. Prepara a consulta SQL usando um placeholder (?)
        $stmt = $mysqli->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        
        // 2. Vincula a variável $email ao placeholder ("s" indica que é uma string)
        $stmt->bind_param("s", $email);
        
        // 3. Executa a consulta
        if(!$stmt->execute()) {
            // Em caso de falha na execução do SQL
            $login_error = "Falha na consulta: " . $stmt->error;
        } else {
            
            // 4. Obtém os resultados
            $sql_query = $stmt->get_result();

            // 5. Verifica se encontrou o usuário
            if ($sql_query->num_rows == 1) {
                
                // Extrai os dados do usuário
                $usuario = $sql_query->fetch_assoc();

                // 6. Verifica se a senha digitada bate com o hash salvo no banco
                if (password_verify($senha, $usuario['senha'])) {

                    // Login bem-sucedido: Salva dados na sessão
                    $_SESSION['id'] = $usuario['id'];
                    $_SESSION['nome'] = $usuario['nome'];

                    // Redireciona para a página do jogo
                    // Esta linha está correta, apontando para jogo.php
                    header("Location: jogo.php"); 
                    exit; // Encerra o script após o redirecionamento

                } else {
                    // Senha incorreta.
                    $login_error = "E-mail ou senha incorretos.";
                }

            } else {
                // E-mail não encontrado.
                $login_error = "E-mail ou senha incorretos.";
            }
        }
        
        // 7. Fecha o statement
        $stmt->close();
    }
    
    // 8. Fecha a conexão
    // $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="inicio.css">
  <title>Login</title>
</head>
<body>
  <header>
    <div class="logo">
      <img src="../img/icon.png" alt="Logo Maracatu">
    </div>
    <nav>
      <a href="cadastro.php"><button>Sign up</button></a>
    </nav>
  </header>

  <main>
    <form class="cadastro" method="POST" action=""> 
        <div>Bem-vindo de volta</div>

        <?php if (!empty($login_error)): ?>
            <p style="color: red; text-align: center; background: #ffebee; border: 1px solid #e57373; padding: 10px; border-radius: 5px;">
                <?php echo htmlspecialchars($login_error); ?>
            </p>
        <?php endif; ?>

        <div class="placeholder">
            <input type="email" id="username" name="email" class="form-control" placeholder="E-mail" required autocomplete="off">
            <input type="password" id="password" name="senha" class="form-control" placeholder="Senha" required autocomplete="off">
        </div>

        <button type="submit">Login</button>

        <p class="link-criar-conta">
            Ainda não tem uma conta?
            <a href="cadastro.php">Crie agora</a>
        </p>
    </form>
</main>
</body>
</html>

<!-- gryg -->