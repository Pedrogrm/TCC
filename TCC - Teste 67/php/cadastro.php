<?php
include('conexao.php');
// session_start(); // Não é necessário iniciar sessão na página de cadastro

$message = ""; // Variável para guardar mensagens de sucesso ou erro
$message_type = ""; // "success" ou "error"

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $confirmar = trim($_POST['confirmar']);

    // Verificações básicas
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
        $message = "Preencha todos os campos.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "E-mail inválido.";
        $message_type = "error";
    } elseif (strlen($senha) < 6) { // Adicionado: Senha muito curta
        $message = "A senha deve ter no mínimo 6 caracteres.";
        $message_type = "error";
    } elseif ($senha !== $confirmar) {
        $message = "As senhas não coincidem.";
        $message_type = "error";
    } else {
        // Verifica se o e-mail já está cadastrado
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Este e-mail já está cadastrado.";
            $message_type = "error";
        } else {
            // Cria o hash da senha
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senhaHash);

            if ($stmt->execute()) {
                $message = "✅ Cadastro realizado com sucesso! Redirecionando para o login...";
                $message_type = "success";
                
                // Redireciona para o login após 2 segundos
                // Este header deve ser enviado ANTES do <!DOCTYPE html>
                header("Refresh: 2; URL=index.php"); 
            } else {
                $message = "Erro ao cadastrar: " . $stmt->error;
                $message_type = "error";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="inicio.css">
  <title>Cadastro</title>
  
  <style>
    .message-error {
        color: red; 
        text-align: center; 
        background: #ffebee; 
        border: 1px solid #e57373; 
        padding: 10px; 
        border-radius: 5px;
        margin-top: 15px;
    }
    .message-success {
        color: green; 
        text-align: center; 
        background: #e8f5e9; 
        border: 1px solid #81c784; 
        padding: 10px; 
        border-radius: 5px;
        margin-top: 15px;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="../img/icon.png" alt="Logo Maracatu">
    </div>
    <nav>
      <a href="index.php"><button>Login</button></a>
    </nav>
  </header>

  <main>
    <form class="cadastro" method="POST" action="">
        <div>Crie sua conta</div>

        <?php if (!empty($message)): ?>
            <p class="<?php echo $message_type === 'success' ? 'message-success' : 'message-error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <div class="placeholder">
            <input type="text" name="nome" class="form-control" placeholder="Nome" required autocomplete="off" 
                   value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>">
                   
            <input type="email" name="email" class="form-control" placeholder="E-mail" required autocomplete="off"
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                   
            <input type="password" name="senha" class="form-control" placeholder="Senha (mín. 6 caracteres)" required autocomplete="off">
            
            <input type="password" name="confirmar" class="form-control" placeholder="Confirmar Senha" required autocomplete="off">
        </div>

        <button type="submit">Sign up</button>

        <p class="link-criar-conta">
            Já tem uma conta?
            <a href="index.php">Faça o Login</a>
        </p>
    </form>
  </main>
</body>
</html>

<!-- gryg -->