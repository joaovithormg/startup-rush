# 🚀 Startup Rush - API Backend

**Startup Rush** é uma competição simulada entre startups, inspirada em programas como Shark Tank e desafios de aceleração. Este projeto é uma API REST desenvolvida com [Lumen](https://lumen.laravel.com/) e [PostgreSQL](https://www.postgresql.org/) que gerencia o torneio de forma automatizada e interativa.

---

## 🔧 Tecnologias

- PHP 8.x
- [Lumen](https://lumen.laravel.com/) (micro-framework do Laravel)
- PostgreSQL
- Composer
- Laravel Eloquent ORM

---

## 📦 Instalação

```bash
# Clonar o projeto
git clone https://github.com/seu-usuario/startup-rush-api.git
cd startup-rush-api

# Instalar dependências
composer install

# Configurar o .env
cp .env.example .env

# Edite o .env com suas credenciais do PostgreSQL
# e gere a key (se necessário)

# Rodar as migrations
php artisan migrate
🚀 Rotas da API
[POST] /startups
Cadastrar uma nova startup.

Body:

json
Copiar
Editar
{
  "nome": "TechWave",
  "slogan": "Surfe na inovação",
  "ano_fundacao": 2021
}
[GET] /startups
Lista todas as startups cadastradas.

[POST] /torneio/iniciar
Inicia o torneio (apenas se houver entre 4 e 8 startups e o número for par).

[GET] /batalhas/pendentes
Lista todas as batalhas que ainda não foram resolvidas.

[POST] /batalhas/{id}/resolver
Resolve uma batalha com os eventos simulados.

Body:

json
Copiar
Editar
{
  "eventos_startup_1": ["pitch", "tracao"],
  "eventos_startup_2": ["bugs", "investidor"]
}
[POST] /torneio/avancar
Avança para a próxima fase (caso todas as batalhas da fase atual estejam concluídas).

Body:

json
Copiar
Editar
{
  "fase": 1
}
[GET] /torneio/status
Mostra o status atual do torneio, batalhas da fase, startups vivas e vencedora (se houver).

[GET] /torneio/relatorio
Relatório final com pontuação e estatísticas de cada startup (eventos por tipo).

[GET] /torneio/relatorio-detalhado
Relatório detalhado com histórico de eventos, batalhas e pontuações completas.

⚙️ Eventos disponíveis
Cada startup pode receber eventos únicos por batalha:


Evento	Efeito
pitch	+6 pontos
bugs	-4 pontos
tracao	+3 pontos
investidor	-6 pontos
fake_news	-8 pontos
🐠 Shark Fight (Desempate)
Se duas startups empatam ao final da batalha, o sistema executa automaticamente uma Shark Fight, onde uma delas recebe +2 pontos aleatórios para decidir o vencedor.

🎁 Funcionalidade Extra
Reiniciar o torneio
Modo Dark no Frontend