🚀 Startup Rush

Torneio de Startups Competitivas

Lumen
Vue.js
PostgreSQL

Um sistema interativo que simula um torneio eliminatório entre startups, com pontuações dinâmicas, eventos aleatórios e batalhas administradas por um usuário-jurado.



📌 Funcionalidades

✔️ Cadastro de startups (nome, slogan, ano de fundação).
✔️ Sorteio automático de batalhas em rodadas eliminatórias.
✔️ Eventos que afetam pontuações (ex: Pitch convincente, Fake news no pitch).
✔️ Shark Fight para desempates (rodada relâmpago com +2pts aleatórios).
✔️ Painel administrativo para registrar eventos e definir vencedores.
✔️ Relatório final com ranking e estatísticas das startups.
✔️ Feature Extra: Histórico de Torneios (salva e exibe resultados de competições anteriores).
🛠️ Tecnologias

    Backend: Lumen (API REST)

    Frontend: Vue.js + TailwindCSS

    Banco de Dados: PostgreSQL

    Deploy: Docker (opcional)

⚙️ Instalação
Pré-requisitos

    Node.js (v16+)

    PHP (v8+) e Composer

    PostgreSQL (v14+)

Passos

    Clone o repositório:
    bash

git clone https://github.com/seu-usuario/startup-rush.git
cd startup-rush

Instale as dependências:
bash

# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate

# Frontend
cd ../frontend
npm install

Configure o banco de dados:

    Crie um banco startuprush no PostgreSQL.

    Atualize o .env do backend:
    env

    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5432
    DB_DATABASE=startuprush
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha

Execute as migrations:
bash

php artisan migrate --seed

Inicie os servidores:
bash

    # Backend (em /backend)
    php -S localhost:8000 -t public

    # Frontend (em /frontend)
    npm run dev

    Acesse: http://localhost:3000.

🎮 Como Usar

    Cadastro de Startups:

        Acesse /admin e insira 4 a 8 startups (nomes, slogans e anos).

    Iniciar Torneio:

        Clique em "Iniciar Torneio" para sortear as primeiras batalhas.

    Administrar Batalhas:

        Selecione uma batalha pendente.

        Registre eventos (ex: Boa tração de usuários).

        O sistema calcula o vencedor automaticamente.

    Shark Fight (Empates):

        Em caso de empate, o sistema roda uma rodada relâmpago.

    Relatório Final:

        Ao final, veja o ranking e o slogan da startup campeã!

🐛 Problemas Comuns

    Erro ao conectar ao PostgreSQL: Verifique as credenciais no .env.

    Eventos não registrando: Confira se a batalha está ativa no painel admin.

    Shark Fight não disparando: O empate deve ser exato (ex: 70x70).

📜 Licença

MIT License.

Feature Extra

O sistema inclui um botão de Reiniciar e um Modo Dark para a web.
