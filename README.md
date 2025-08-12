# TCC
## Desenvolvendo um game apenas com js

## 📌 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Diário de Bordo](#Diário-de-Bordo)


## 🎮 Sobre o Projeto

Um jogo inspirado em Dungeons & Dragons, Potion Punch e Dinner Simulator da plataforma Roblox, com mecânicas simples.
- Simulador

- Cozinha

## 📖 Diário de Bordo

### Dia 1 
Viemos com a ideia, procuramos exemplos e pensamos na estética do jogo

### Dia 2
Tentamos decidir outras coisas, como a mecânica principal do jogo. Ainda não foi decidido se será um sistema de simulador ou cozinha
Pegamos esse código e começamos a analisar. Gabriel com o simulador, Isabella com o sistema de cozinha.

### Dia 3

Depois de analisar, começamos a resolver problema no código, para fazer exatamenteo que queríamos.
 1. Clientes sobrepondo outros clientes
 2. Sobreposição de itens
 3. ter que seguir a ordem dos itens pedidos pelo cliente

Hoje, na cozinha, conseguimos resolver o primeiro item e o segundo, utilizando uma lógica nova no spawn de clientes, porém, acrescentou outro problema:
 - Só recebe 6 clientes

No simulador, conseguimos melhorar a colisão do jogador com a fruta, acrescentamos música e sistema mais complexo de upgrades

### Dia 4

Tentei arrumar o problema dos 6 clientes, utilizando o copilot do vs code. Por um momento, mais clientes eram chamados constantemente, mesmo tendo 6 clientes na tela (o máximo). O processo de spawn deles era comentado no console, mas não apareciam graficamente. Após atender um dos clientes, por terem muitos outros spawnados, o programa travava. Foi identificado que era necessário definir um "else", para caso tenha atingido o máximo de clientes, pare de gerar outros novos, até surgir espaço. Coloquei um else com um console.log nele ara depurar o código, mas, após atinir o máximo e atender mais um cliente, o programa trava e o texto do console.log não aparece.

### Dia 5

Pedi ajuda ao professor para verificar o protótipo e nos ajudar a lidar com os erros dele.
