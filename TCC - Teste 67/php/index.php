<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Evita erro se o usuário não estiver logado
$nomeUsuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Visitante';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../img/jeca-logo.png">
    <title>Jeca Tatu</title>
</head>
<body>
    <div id="game">
        <header>
            <div class="log">
                <img src="../img/icon.png" alt=""> 
            </div>
            <nav>
                <button id="btnAbrirConfig"><img src="../img/iconeEng2.png" alt=""></button>
                <button id="btnAbrirLoja"><img src="../img/iconeCar.png" alt=""></button>
                <button id="btnAbrirPerfil"><img src="../img/iconeJeca.png" alt=""></button>
            </nav>

            <div class="dinheiro-display">
                <span><img src="../img/saco.png" alt=""></span>
                <span class="ponto">:</span>
                <span id="dinheiro-quantidade">25</span>
            </div>
        </header>
    </div>

    <main>
        <container id="container">
        <div class="mesa-guardar" id="mesa1-guardar">
            <img id="mesa1" src="../img-cenario/Mesa.png">
        </div>

        <div class="mesa-guardar" id="mesa2-guardar">
            <img id="mesa2" src="../img-cenario/Mesa.png">
        </div>

        <div class="mesa-guardar" id="mesa3-guardar">
            <img id="mesa3" src="../img-cenario/Mesa.png">
        </div>

        <div id="parede"></div>
        <div id="parede2"></div>
        <div id="chao"></div>
        <img id="janela" src="../img-cenario/Janela.gif">

        </container>

        <div id="cozinha">
            <div id="score-display" style="position: absolute; z-index: 9999999999999999;">Misture</div>
            <div id="barravazia"><div id="barracheia"></div></div>

            <img id="espatula" src="../img/espatula.png" draggable="true">

            <img id="leitecon" src="../img-ingrediente/Leite condensado.png" draggable="true">
            <img id="chocolate" src="../img-ingrediente/Achocolatado.png" draggable="true">
            <img id="chocolateColher" src="../img-ingrediente/Achocolatado-colher.png" draggable="true">
            <img id="manteiga" src="../img-ingrediente/Manteiga.png" draggable="true">
            <img id="manteigaColher" src="../img-ingrediente/Manteiga-colher.png" draggable="true">

            <img id="panela" src="../img-cozinha/PANELLALAAAA.png">
            <div id="panela-fundo"></div>

            <img id="good" src="../img/good.png">
            <img id="great" src="../img/great.png">
            <img id="awesome" src="../img/awesome.png">
            <img src="../img-cenario/cozinha.png" alt="fundo" class="fundo">
        </div>


        <div id="game-area">
            <img src="../img/Jeca-paradoR.gif" id="player" class="player" alt="Player">
            <img src="../img-cenario/fundo.png" alt="fundo" class="fundo">
        </div>
            
    </main>

    <div id="receitas" class="modal">
        <div class="modal-content">
            <span class="fechar">&times;</span>
            <h2>📒 Receitas</h2>
            <p>Este é o conteúdo do seu modal.</p>
        </div>
    </div>

    <div id="loja" class="modal">
        <div class="modal-content">
            <span class="fechar">&times;</span>
            <h2>🛒 Loja de Power-ups</h2>

            <div id="powerupsLoja">
                <div class="powerup" id="powerup-velocidade">
                    <h4>Pés Rápidos</h4>
                    <p>Velocidade +10</p>
                    <p>Custo: <span class="preco">10</span> 💰</p>
                    <button id="btnAumentarVelocidade">Comprar</button>
                </div>

                <div class="powerup" id="powerup-vida">
                    <h4>Bom Cozinheiro</h4>
                    <p>+5 de paciência</p>
                    <p>Custo: <span class="preco">15</span> 💰</p>
                    <button id="btnAumentarVida">Comprar</button>
                </div>

                <div class="powerup" id="powerup-dano">
                    <h4>Tempero Especial</h4>
                    <p>+5% de bônus</p>
                    <p>Custo: <span class="preco">20</span> 💰</p>
                    <button id="btnAumentarDano">Comprar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="perfil" class="modal">
        <div class="modal-content">
            <span class="fechar">&times;</span>
            <h2><img src="../img/iconeJeca.png" alt="Jeca" style="width: 30px; margin-top: 10px;"> Perfil</h2>
            <p> </p>
            Bem Vindo, <strong class="nomedousuario"><?php echo htmlspecialchars($nomeUsuario); ?></strong>!
            <p> </p>
            <p>
                <button class="btn" onclick="window.location.replace('logout.php')">Sair</button>
            </p>
        </div>
    </div>

    <div id="config" class="modal">
        <div class="modal-content">
            <span class="fechar">&times;</span>
            <h2>⚙️ Configurações</h2>
            <div class="config-opcoes">
                <button id="btnSom">🔊 Som: Ativado</button>
            </div>
        </div>
    </div>

    <div id="modal-inicio" class="modal">
        <div class="modal-contentinicio">
            <button id="btn-Jogar"><img src="../img/botão.webp" alt="Jogar"></button>
        </div>
    </div>

    <div id="modal-login-cadastro" class="modal" style="display: none;">
        <div class="modal-content-login">
            <span class="fechar" id="fechar-login">&times;</span>
            
            <div id="auth-message-area"></div>
            
            <form id="form-login" class="cadastro" method="POST" action="processar_login.php"> 
                <div>Bem-vindo de volta</div>

                <div class="placeholder">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required autocomplete="off">
                    <input type="password" name="senha" class="form-control" placeholder="Senha" required autocomplete="off">
                </div>

                <button type="submit">Login</button>

                <p class="link-criar-conta">
                    Ainda não tem uma conta?
                    <a href="#" id="link-abrir-cadastro">Crie agora</a>
                </p>
            </form>

            <form id="form-cadastro" class="cadastro" method="POST" action="processar_cadastro.php" style="display: none;">
                <div>Crie sua conta</div>
                
                <div class="placeholder">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required autocomplete="off">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required autocomplete="off">
                    <input type="password" name="senha" class="form-control" placeholder="Senha (mín. 6 caracteres)" required autocomplete="off">
                    <input type="password" name="confirmar_senha" class="form-control" placeholder="Confirmar Senha" required autocomplete="off"> 
                </div>

                <button type="submit">Sign up</button>

                <p class="link-criar-conta">
                    Já tem uma conta?
                    <a href="#" id="link-abrir-login">Faça o Login</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        // O PHP vai escrever "true" se tiver sessão, ou "false" se não tiver
        const usuarioEstaLogado = <?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>;
        
        console.log("Status do Login:", usuarioEstaLogado); // Para debug
    </script>

    <script src="script.js"></script>
</body>
</html>