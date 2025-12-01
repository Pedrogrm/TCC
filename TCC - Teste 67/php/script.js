console.log("✅ Script carregado com sucesso!");

// ===================================
// 📜 VARIÁVEIS GLOBAIS E ELEMENTOS
// ===================================

const gameArea = document.getElementById('game-area');
const player = document.getElementById('player');

// Elementos das Mesas
const mesa1 = document.getElementById('mesa1');
const mesa2 = document.getElementById('mesa2');
const mesa3 = document.getElementById('mesa3');

const mesa1e = document.getElementById('mesa1-guardar');
const mesa2e = document.getElementById('mesa2-guardar');
const mesa3e = document.getElementById('mesa3-guardar');

// Elementos da Cozinha
const manteiga = document.getElementById('manteiga');
const manteigaColher = document.getElementById('manteigaColher');
const chocolate = document.getElementById('chocolate');
const chocolateColher = document.getElementById('chocolateColher');
const leitecon = document.getElementById('leitecon');
const espatula = document.getElementById('espatula');

// Variáveis de Movimento do Jeca
let move = false;
let posX = 300;
let posY = 200;
let targetX = posX;
let targetY = posY;
let endposition = "right";
let playerSpeed = 6; 

// Variáveis de Jogo
let dinheiro = 25;
let paciencia = 0;
let bonus = 0;

// Variáveis de Clientes
let client = [];
let clientClicado = []; 
const maxClients = 3;
let occupiedPositions = new Set();
let num = 0;

// Configurações de Clientes
const clientPositions = { 1: 100, 2: 50, 3: 0 };
const zclient = { 0: 3, 50: 4, 100: 5 };

const clientImg = [
    "aly.gif", "leandro.gif", "marrye.gif", "nox.gif",
    "pariz.gif", "pinguin.gif", "gigs.gif", "coruja.gif", "forg.gif"
];

const clientMesa = [
    ["aly-sentada.png", "aly-pedindo.png", "aly-esperando.png"], 
    ["leandro-sentado.png", "leandro-pedindo.png", "leandro-esperando.png"], 
    ["minhoca-sentada.png", "marrie-pedindo.png", "marrie-esperando-GIF.gif"], 
    ["NOX-SENTADO.png", "NOX-pedindo.png", "NOX-SENTADO.png"], 
    ["pariz-sentada.png", "pariz-pedindo.png", "pariz-esperando.png"], 
    ["chea-sentada.png", "chea-pedindo.png", "chea-esperando.png"], 
    ["girgs-sentado.png", "girgs-pedindo.png", "girgs-sentado.png"], 
    ["coruja-sentada.png", "coruja-pedindo.png", "coruja-sentada.png"], 
    ["forg-sentado.png", "forg-pedindo.png", "forg-sentado.png"]
];

// Estados de Seleção da Cozinha
let espatulaSelecionado = false;
let manteigaSelecionado = false;
let chocolateSelecionado = false;
let leiteconSelecionado = false;


// -----------------------------------------------------
// 🔐 VARIÁVEIS DO SISTEMA DE MODAIS E AUTENTICAÇÃO
// -----------------------------------------------------
const modalInicio = document.getElementById('modal-inicio');
const btnJogar = document.getElementById('btn-Jogar'); 
const modalLoginCadastro = document.getElementById('modal-login-cadastro');
const authMessageArea = document.getElementById('auth-message-area');
const fecharLoginBtn = document.getElementById('fechar-login');

const formLogin = document.getElementById('form-login');
const formCadastro = document.getElementById('form-cadastro');
const linkAbrirCadastro = document.getElementById('link-abrir-cadastro');
const linkAbrirLogin = document.getElementById('link-abrir-login');


// ===================================
// 🏃 SCRIPT DE MOVIMENTO (JECA)
// ===================================

function update() {
    const dx = targetX - posX;
    const dy = targetY - posY;
    const distance = Math.sqrt(dx * dx + dy * dy);

    if (distance > 3) {
        move = true;
        posX += (dx / distance) * playerSpeed;
        posY += (dy / distance) * playerSpeed;

        if (player) {
            player.style.left = posX + "px";
            player.style.top = posY + "px";
        }
    } else if (distance <= 3 && move === true) {
        move = false;
        if (player) {
            if (endposition === "left") player.src = "../img/Jeca-paradoL.gif";
            if (endposition === "right") player.src = "../img/Jeca-paradoR.gif";
        }
    }
    requestAnimationFrame(update);
}

if (gameArea) {
    gameArea.addEventListener('click', (event) => {
        if (event.target.classList.contains('cliente')) return;

        move = true;
        const rect = gameArea.getBoundingClientRect();
        targetX = event.clientX - rect.left;
        targetY = event.clientY - rect.top - 70;

        if (player) {
            if (targetX < posX) {
                player.src = "../img/Jeca-correndoL.gif";
                endposition = "left";
            } else {
                player.src = "../img/Jeca-correndoR.gif";
                endposition = "right";
            }
        }
    });
}


// ===================================
// ⚙️ SISTEMA DE MODAIS E LOJA
// ===================================

function setupModal(btnId, modalId) {
    const btn = document.getElementById(btnId);
    const modal = document.getElementById(modalId);
    if (!btn || !modal) return;

    const span = modal.querySelector(".fechar");
    btn.addEventListener("click", () => modal.style.display = "flex");
    if (span) span.addEventListener("click", () => modal.style.display = "none");
    
    window.addEventListener("click", (event) => {
        if (event.target === modal) modal.style.display = "none";
    });
}

// Configura Modais
setupModal("btnAbrirConfig", "config");
setupModal("btnAbrirLoja", "loja");
setupModal("btnAbrirReceitas", "receitas");
setupModal("btnAbrirPerfil", "perfil");

function atualizarDinheiro() {
    const display = document.getElementById("dinheiro-quantidade");
    if (display) display.textContent = dinheiro;
}

function comprarPowerUp(custo, efeito) {
    if (dinheiro >= custo) {
        dinheiro -= custo;
        efeito();
        atualizarDinheiro();
        alert("Compra realizada com sucesso!");
    } else {
        alert("Dinheiro insuficiente!");
    }
}

const btnVelocidade = document.getElementById("btnAumentarVelocidade");
if (btnVelocidade) {
    btnVelocidade.addEventListener("click", () => comprarPowerUp(10, () => playerSpeed += 1.5));
}


// ===================================
// 🍽️ COZINHA (INTERAÇÃO)
// ===================================

document.addEventListener("mousemove", (event) => {
    const mouseX = event.clientX;
    const mouseY = event.clientY;

    if (manteigaSelecionado && manteigaColher) {
        manteigaColher.style.display = "flex";
        manteigaColher.style.top = (mouseY - manteigaColher.height) + "px";
        manteigaColher.style.left = (mouseX - manteigaColher.width) - 40 + "px";
    } else if (chocolateSelecionado && chocolateColher) {
        chocolateColher.style.display = "flex";
        chocolateColher.style.top = (mouseY - chocolateColher.height) + "px";
        chocolateColher.style.left = (mouseX - chocolateColher.width) - 40 + "px";
    } else if (leiteconSelecionado && leitecon) {
        leitecon.style.top = (mouseY - leitecon.height) + "px";
        leitecon.style.left = (mouseX - leitecon.width) + "px";
    } else if (espatulaSelecionado && espatula) {
        espatula.style.top = (mouseY - espatula.height) + "px";
        espatula.style.left = (mouseX - espatula.width) + "px";
    }
});

if (manteiga) manteiga.onclick = () => manteigaSelecionado = !manteigaSelecionado;
if (manteigaColher) manteigaColher.onclick = () => manteigaSelecionado = false;
if (chocolate) chocolate.onclick = () => chocolateSelecionado = !chocolateSelecionado;
if (chocolateColher) chocolateColher.onclick = () => chocolateSelecionado = false;
if (leitecon) leitecon.onclick = () => leiteconSelecionado = !leiteconSelecionado;
if (espatula) espatula.onclick = () => espatulaSelecionado = !espatulaSelecionado;


// ===================================
// 👥 SISTEMA DE CLIENTES E MESAS
// ===================================

function selecionarNum() {
    return Math.floor(Math.random() * clientImg.length);
}

function criarCliente() {
    num++;
    let pos = clientPositions[num];
    let z = zclient[pos];

    occupiedPositions.add(pos);

    const clientDiv = document.createElement('img');
    clientDiv.classList.add('cliente');
    clientDiv.style.left = `${pos}px`;
    clientDiv.style.zIndex = `${z}`;

    const numSprite = selecionarNum();
    clientDiv.src = `../img/${clientImg[numSprite]}`;
    clientDiv.dataset.sprite = JSON.stringify(numSprite);

    if (gameArea) gameArea.appendChild(clientDiv);
    client.push(clientDiv);

    clientDiv.addEventListener('click', (e) => {
        e.stopPropagation();

        if (clientClicado.length > 0) {
            console.log("⚠️ Você já tem um cliente selecionado!");
            return;
        }

        if (!clientClicado.includes(clientDiv)) {
            clientDiv.hidden = true;
            occupiedPositions.delete(pos);
            clientClicado.push(clientDiv);
            console.log("✅ Cliente pego! Clique numa mesa.");
        }
    });
}

function clientes3() {
    num = 0;
    let create = 0;
    const interval = setInterval(() => {
        if (create < maxClients) {
            criarCliente();
            create++;
        } else {
            clearInterval(interval);
        }
    }, 500);
}

function tentarSentarCliente(mesaElemento, posDireita, posEsquerda) {
    if (clientClicado.length === 0) return;

    const cliente = clientClicado[0];
    const clientesNaMesa = mesaElemento.querySelectorAll(".cliente").length;

    if (clientesNaMesa >= 2) {
        console.log("⛔ Mesa cheia!");
        return;
    }

    if (clientesNaMesa > 0) {
        cliente.style.transform = "scaleX(-1)";
        moverCliente(cliente, mesaElemento, posDireita, "10px");
    } else {
        cliente.style.transform = "scaleX(1)";
        moverCliente(cliente, mesaElemento, posEsquerda, "10px");
    }

    cliente.style.setProperty('z-index', '9999', 'important');
    mudarSprite(cliente);
    clientClicado = [];
}

function moverCliente(cliente, mesa, left, top) {
    cliente.hidden = false;
    mesa.appendChild(cliente);
    cliente.style.position = "absolute";
    cliente.style.left = left;
    cliente.style.top = top;

    const spriteID = parseInt(cliente.dataset.sprite, 10);
    if (!Number.isNaN(spriteID)) {
        cliente.src = `../img/${clientMesa[spriteID][0]}`;
    }
}

function mudarSprite(cliente) {
    if (!cliente) return;
    const spriteID = parseInt(cliente.dataset.sprite, 10);

    if (cliente._spriteIntervalId) clearInterval(cliente._spriteIntervalId);
    
    cliente._spriteIntervalId = setInterval(() => {
        cliente.src = `../img/${clientMesa[spriteID][1]}`;
    }, (Math.random() * 5000) + 5000);
}


// ===================================
// 🚀 LÓGICA DE INICIALIZAÇÃO DO JOGO
// ===================================

function iniciarLogicaDoJogo() {
    if (mesa1 && mesa2 && mesa3) {
        mesa1.onclick = () => tentarSentarCliente(mesa1e, "115px", "-75px");
        mesa2.onclick = () => tentarSentarCliente(mesa2e, "150px", "-75px");
        mesa3.onclick = () => tentarSentarCliente(mesa3e, "150px", "-75px");
        console.log("✅ Mesas Configuradas.");
    } else {
        console.error("❌ Erro: Mesas não encontradas no HTML.");
    }

    document.querySelectorAll('input').forEach(input => {
        const original = input.getAttribute('placeholder');
        input.onfocus = () => input.setAttribute('placeholder', '');
        input.onblur = () => input.setAttribute('placeholder', original);
    });

    atualizarDinheiro();
    requestAnimationFrame(update);
    clientes3();
    console.log("✅ Lógica do jogo iniciada!");
}

// ===================================================================
// 🔐 LÓGICA DE MODAIS DE AUTENTICAÇÃO E FETCH (AJAX)
// ===================================================================

if (btnJogar) {
    btnJogar.addEventListener('click', fecharModalInicioEAbrirLogin);
}

if (fecharLoginBtn) {
    fecharLoginBtn.addEventListener('click', () => {
        if (modalLoginCadastro) modalLoginCadastro.style.display = 'none';
    });
}

function fecharModalInicioEAbrirLogin() {
    if (modalInicio) modalInicio.style.display = 'none';
    if (modalLoginCadastro) modalLoginCadastro.style.display = 'flex';
}

if (linkAbrirCadastro) {
    linkAbrirCadastro.addEventListener('click', (e) => {
        e.preventDefault();
        formLogin.style.display = 'none';
        formCadastro.style.display = 'block'; 
        authMessageArea.innerHTML = '';
    });
}

if (linkAbrirLogin) {
    linkAbrirLogin.addEventListener('click', (e) => {
        e.preventDefault();
        formCadastro.style.display = 'none';
        formLogin.style.display = 'block'; 
        authMessageArea.innerHTML = '';
    });
}

function displayAuthMessage(message, isSuccess) {
    authMessageArea.innerHTML = `
        <p class="${isSuccess ? 'message-success' : 'message-error'}">
            ${message}
        </p>
    `;
}

async function submitAuthForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const messageArea = document.getElementById('auth-message-area');

    if (messageArea) messageArea.innerHTML = 'Processando...';
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });

        const textoResposta = await response.text();
        
        let result;
        try {
            result = JSON.parse(textoResposta);
        } catch (e) {
            console.error("ERRO PHP:", textoResposta);
            if (messageArea) messageArea.innerHTML = "Erro no servidor. Veja o Console (F12).";
            return;
        }
        
        if (result.success) {
            displayAuthMessage(result.message, true);
            
            if (form.id === 'form-login') {
                setTimeout(() => {
                    const modal = document.getElementById('modal-login-cadastro');
                    if(modal) modal.style.display = 'none';
                    window.location.reload(); 
                }, 1000); 

            } else if (form.id === 'form-cadastro') {
                 setTimeout(() => {
                    const formCadastro = document.getElementById('form-cadastro');
                    const formLogin = document.getElementById('form-login');
                    if(formCadastro) formCadastro.style.display = 'none';
                    if(formLogin) formLogin.style.display = 'block';
                    displayAuthMessage("Cadastro ok! Faça login.", true);
                }, 2000); 
            }
        } else {
            displayAuthMessage(result.message, false);
        }
    } catch (error) {
        if (messageArea) displayAuthMessage("Erro de conexão.", false);
        console.error('Fetch error:', error);
    }
}

if (formLogin) formLogin.addEventListener('submit', submitAuthForm);
if (formCadastro) formCadastro.addEventListener('submit', submitAuthForm);

// ===================================
// 7. ABERTURA INTELIGENTE (ONLOAD UNIFICADO)
// ===================================
window.onload = function() {
    console.log("🖥️ Página carregada.");

    if (typeof usuarioEstaLogado !== 'undefined' && usuarioEstaLogado === true) {
        console.log("✅ Usuário já logado! Iniciando jogo direto...");
        if (modalInicio) modalInicio.style.display = 'none';
        if (modalLoginCadastro) modalLoginCadastro.style.display = 'none';
        iniciarLogicaDoJogo();
    } else {
        console.log("🔒 Usuário não logado. Mostrando Início...");
        if (modalInicio) {
            modalInicio.style.display = 'flex'; 
        }
    }
};

// ===================================
// 📒 LÓGICA DO CARROSSEL DE RECEITAS
// ===================================
// IMPORTANTE: Essas classes precisam estar no seu HTML para funcionar!
const track = document.querySelector('.carousel-track');
const slides = Array.from(document.querySelectorAll('.carousel-slide'));
const nextButton = document.getElementById('nextBtn');
const prevButton = document.getElementById('prevBtn');

// Debug: Ver quantos slides foram encontrados
console.log("Slides encontrados:", slides.length);

let currentSlideIndex = 0;

function updateCarousel() {
    // Calcula quantos % deve mover para a esquerda
    const amountToMove = -100 * currentSlideIndex; 
    if(track) {
        track.style.transform = `translateX(${amountToMove}%)`;
    }
}

if (nextButton) {
    nextButton.addEventListener('click', () => {
        console.log("Botão Próximo clicado. Índice atual:", currentSlideIndex);
        if (currentSlideIndex === slides.length - 1) {
            currentSlideIndex = 0; // Volta pro início
        } else {
            currentSlideIndex++; // Vai pro próximo
        }
        updateCarousel();
    });
}

if (prevButton) {
    prevButton.addEventListener('click', () => {
        console.log("Botão Anterior clicado.");
        if (currentSlideIndex === 0) {
            currentSlideIndex = slides.length - 1; // Vai pro fim
        } else {
            currentSlideIndex--; // Volta um
        }
        updateCarousel();
    });
}

// ===================================
// 🎬 LÓGICA DA CUT SCENE (INTRODUÇÃO)
// ===================================
const modalCutscene = document.getElementById('modal-cutscene');
const btnStartGame = document.getElementById('btn-start-game');

// Elementos do Carrossel da Cutscene
const cutTrack = document.querySelector('.cutscene-track');
const cutSlides = Array.from(document.querySelectorAll('.cutscene-slide'));
const cutNextBtn = document.getElementById('cutNextBtn');
const cutPrevBtn = document.getElementById('cutPrevBtn');

let cutIndex = 0;

function updateCutscene() {
    const moveAmount = -100 * cutIndex;
    if(cutTrack) cutTrack.style.transform = `translateX(${moveAmount}%)`;
}

if (cutNextBtn) {
    cutNextBtn.addEventListener('click', () => {
        if (cutIndex === cutSlides.length - 1) cutIndex = 0;
        else cutIndex++;
        updateCutscene();
    });
}

if (cutPrevBtn) {
    cutPrevBtn.addEventListener('click', () => {
        if (cutIndex === 0) cutIndex = cutSlides.length - 1;
        else cutIndex--;
        updateCutscene();
    });
}

// Botão "COMEÇAR O JOGO" dentro da cutscene
if (btnStartGame) {
    btnStartGame.addEventListener('click', () => {
        // Fecha a cutscene
        if(modalCutscene) modalCutscene.style.display = 'none';
        
        // E FINALMENTE INICIA O JOGO
        iniciarLogicaDoJogo();
    });
}


// ===================================
// 7. ABERTURA INTELIGENTE (MODIFICADO)
// ===================================
window.onload = function() {
    console.log("🖥️ Página carregada.");

    // Se o usuário está logado...
    if (typeof usuarioEstaLogado !== 'undefined' && usuarioEstaLogado === true) {
        
        console.log("✅ Usuário logado! Abrindo Cut Scene...");
        
        // Garante que os outros modais estão fechados
        if (modalInicio) modalInicio.style.display = 'none';
        if (modalLoginCadastro) modalLoginCadastro.style.display = 'none';

        // 🚨 AQUI É A MUDANÇA: Abre a Cut Scene em vez do jogo direto
        if (modalCutscene) {
            modalCutscene.style.display = 'flex'; // Abre o modal da história
        } else {
            // Se não tiver modal (segurança), inicia o jogo direto
            iniciarLogicaDoJogo();
        }

    } else {
        // Se NÃO está logado, mostra a tela inicial normal
        console.log("🔒 Usuário não logado. Mostrando Início...");
        if (modalInicio) {
            modalInicio.style.display = 'flex'; 
        }
    }
};