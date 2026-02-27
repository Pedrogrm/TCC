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
    <title>Jeca Maracatu</title>
</head>
<body>
    <div id="game">
        <header>
            <div class="log">
                <img src="../img/icon.png" alt=""> 
            </div>
            <nav>
                <button id="btnAbrirConfig"><img src="../img/iconeEng2.png" alt=""></button>
                <button id="btnAbrirReceitas"><img src="../img/iconeRec.png" alt=""></button>
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
      <div class="carousel-container">
        
        <div class="carousel-track">
          
            <div class="carousel-slide">
                <h3>Brigadeiro</h3>
                <img src="../img-comida/brigadeiro.png" alt="Brigadeiro" class="img-receita"> 
                <p>Tradicional doce brasileiro criado na década de 1940 no Rio de janeiro, durante a campanha eleitoral do Brigadeiro Eduardo Gomes. Popularizado por todo o país, tornou-se presença indispensável em festas de aniversário, comemorações e eventos, sendo encontrado em praticamente qualquer celebração brasileira. Sua origem é atribuída à confeiteira Heloísa Nabuco de Oliveira. </p>
            </div>

            <div class="carousel-slide">
                <h3>Pudim</h3>
                <img src="../img-comida/pudim.png" alt="Pudim" class="img-receita">
                <p>O pudim surgiu na Idade Média na Europa, com origens em preparações salgadas feitas com ovos, leite e carnes. A versão doce começou a surgir por volta do século XVII, e Portugal é frequentemente creditado por ter popularizado a receita na Europa, que chegou ao Brasil com os colonizadores. No Brasil, o pudim ganhou uma nova identidade com a popularização do leite condensado no século XIX, tornando-se uma sobremesa clássica nacional. </p>
            </div>

            <div class="carousel-slide">
                <h3>Coxinha</h3>
                <img src="../img-comida/Coxinhapng.png" alt="Coxinha" class="img-receita">
                <p>A coxinha surgiu no século XIX em São Paulo, na cozinha imperial do Brasil, como uma adaptação de um prato francês que inspirou a receita de croquetes. Alguns dizem que por conta da falta do ingrediente principal, os cozinheiros desfiariam o frango, misturaram-no a uma massa e moldaram o salgado no formato de uma coxa de galinha para fritar para a Princesa Isabel comer. Já em outra versão atribui o surgimento da coxinha à industrialização de São Paulo, onde era vendida como um substituto mais acessível para as coxas de frango.</p>
            </div>

        </div>

        <button id="prevBtn" class="carousel-btn prev">❮</button>
        <button id="nextBtn" class="carousel-btn next">❯</button>

      </div>
      </div>
  </div>

    <!-- <div id="loja" class="modal">
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
    </div> -->

    <div id="modal-cutscene" class="modal" style="display: none;">
    <div class="modal-content-cutscene">
        
        <div class="cutscene-carousel">
            <div class="cutscene-track">
                
                <div class="cutscene-slide">
                   
                    <img src="../img-cutscene/1.jpeg" alt="Jeca Chegando" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    
                    <img src="../img-cutscene/2.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/3.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/4.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/5.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/6.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/7.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/8.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/9.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/10.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/11.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/12.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/13.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/14.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/15.jpeg" alt="Jeca" class="img-cutscene">
                </div>

                <div class="cutscene-slide">
                    <img src="../img-cutscene/16.jpeg" alt="Jeca" class="img-cutscene">
                </div>

            </div>

            <button id="cutPrevBtn" class="carousel-btn prev">❮</button>
            <button id="cutNextBtn" class="carousel-btn next">❯</button>
        </div>

        <button id="btn-start-game" class="btn-jogar-final">COMEÇAR O JOGO ▶</button>

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