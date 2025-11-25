console.log("✅ Script carregado com sucesso!");

// ===================================
// 📜 VARIÁVEIS GLOBAIS E ELEMENTOS
// ===================================

const gameArea = document.getElementById('game-area');
const player = document.getElementById('player');

// Elementos das Mesas (Imagens e Containers)
const mesa1 = document.getElementById('mesa1'); // A imagem da mesa (onde clica)
const mesa2 = document.getElementById('mesa2');
const mesa3 = document.getElementById('mesa3');

const mesa1e = document.getElementById('mesa1-guardar'); // A div (onde o cliente entra)
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
let playerSpeed = 6; // Pode ser alterado pela loja

// Variáveis de Jogo (Dinheiro, etc)
let dinheiro = 25;
let paciencia = 0;
let bonus = 0;

// Variáveis de Clientes
let client = [];
let clientClicado = []; // Array para segurar o cliente selecionado
const maxClients = 3;
let occupiedPositions = new Set();
let num = 0;

// Configurações de Clientes
const clientPositions = { 1: 100, 2: 50, 3: 0 };
const zclient = { 0: 3, 50: 4, 100: 5 };

// Sprites (Imagens)
const clientImg = [
    "aly.gif", "leandro.gif", "marrye.gif", "nox.gif",
    "pariz.gif", "pinguin.gif", "gigs.gif", "coruja.gif", "forg.gif"
];

// Estados na Mesa: [0] Sentado, [1] Pedindo, [2] Esperando
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
        // Se clicar num cliente, não move o Jeca (para evitar bugs visuais)
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

// Botões da Loja
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

// Cliques nos ingredientes
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

    // Define qual personagem é
    const numSprite = selecionarNum();
    clientDiv.src = `../img/${clientImg[numSprite]}`;
    clientDiv.dataset.sprite = JSON.stringify(numSprite); // Guarda o ID

    if (gameArea) gameArea.appendChild(clientDiv);
    client.push(clientDiv);

    // --- CLIQUE NO CLIENTE (Apenas seleciona) ---
    clientDiv.addEventListener('click', (e) => {
        e.stopPropagation(); // Impede outros cliques

        if (clientClicado.length > 0) {
            console.log("⚠️ Você já tem um cliente selecionado!");
            return;
        }

        if (!clientClicado.includes(clientDiv)) {
            clientDiv.hidden = true; // Some da fila
            occupiedPositions.delete(pos);
            clientClicado.push(clientDiv); // Guarda na "mão"
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

// --- FUNÇÃO PARA SENTAR O CLIENTE ---
function tentarSentarCliente(mesaElemento, posDireita, posEsquerda) {
    // 1. Verifica se tem cliente na mão
    if (clientClicado.length === 0) return;

    const cliente = clientClicado[0];
    const clientesNaMesa = mesaElemento.querySelectorAll(".cliente").length;

    // 2. Verifica se mesa está cheia (max 2)
    if (clientesNaMesa >= 2) {
        console.log("⛔ Mesa cheia!");
        return;
    }

    // 3. Posiciona o cliente
    if (clientesNaMesa > 0) {
        // Lado Direito
        cliente.style.transform = "scaleX(-1)";
        moverCliente(cliente, mesaElemento, posDireita, "0px");
    } else {
        // Lado Esquerdo
        cliente.style.transform = "scaleX(1)";
        moverCliente(cliente, mesaElemento, posEsquerda, "0px");
    }

    // Ajustes finais CSS
    cliente.style.width = "90px";
    cliente.style.height = "auto";
    // cliente.style.zIndex = "999";   // Por algum motivo o localhost nao ta atualizando a mudaça de zIndex = 2 pra zIndex = 999 🫠
    //// Em vez de cliente.style.zIndex = "999";
    cliente.style.setProperty('z-index', '9999', 'important');

    // 4. Anima e limpa seleção
    mudarSprite(cliente);
    clientClicado = [];
}

function moverCliente(cliente, mesa, left, top) {
    cliente.hidden = false;
    mesa.appendChild(cliente); // Move o elemento HTML para dentro da div da mesa
    cliente.style.position = "absolute";
    cliente.style.left = left;
    cliente.style.top = top;

    // Coloca imagem de sentado
    const spriteID = parseInt(cliente.dataset.sprite, 10);
    if (!Number.isNaN(spriteID)) {
        cliente.src = `../img/${clientMesa[spriteID][0]}`;
    } else {
        console.warn("Opção inválida para sprite")
    }
}

function mudarSprite(cliente) {
    if (!cliente) return;
    const spriteID = parseInt(cliente.dataset.sprite, 10);

    // Loop de animação simples (Sentado <-> Pedindo)
    if (cliente._spriteIntervalId) clearInterval(cliente._spriteIntervalId);
    
    cliente._spriteIntervalId = setInterval(() => {
        cliente.src = `../img/${clientMesa[spriteID][1]}`; // Pedindo
    }, (Math.random() * 5000) + 5000);
}

// ===================================
// 🚀 INICIALIZAÇÃO DO JOGO
// ===================================

window.addEventListener("load", () => {
    console.log("🚀 Jogo Iniciando...");

    // Configura os cliques nas mesas (APENAS UMA VEZ)
    if (mesa1 && mesa2 && mesa3) {
        mesa1.onclick = () => tentarSentarCliente(mesa1e, "120px", "-80px");
        mesa2.onclick = () => tentarSentarCliente(mesa2e, "140px", "-90px");
        mesa3.onclick = () => tentarSentarCliente(mesa3e, "140px", "-90px");
        console.log("✅ Mesas Configuradas.");
    } else {
        console.error("❌ Erro: Mesas não encontradas no HTML.");
    }

    // Limpa Placeholders dos inputs
    document.querySelectorAll('input').forEach(input => {
        const original = input.getAttribute('placeholder');
        input.onfocus = () => input.setAttribute('placeholder', '');
        input.onblur = () => input.setAttribute('placeholder', original);
    });

    // Inicia loops
    atualizarDinheiro();
    requestAnimationFrame(update); // Movimento Jeca
    clientes3(); // Gera clientes
});