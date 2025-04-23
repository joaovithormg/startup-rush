# Instruções de Execução - Sistema de Torneio de Startups

## Visão Geral

Este sistema permite organizar e executar torneios entre startups, gerenciar batalhas, e gerar relatórios de desempenho. O frontend é desenvolvido em Vue.js, enquanto o backend utiliza o framework Lumen (PHP).

## Estrutura do Sistema

### Frontend (Vue.js)
- **Home** (`/`): Página inicial da aplicação
- **Startups** (`/startups`): Cadastro e visualização de startups
- **Torneio** (`/torneio`): Gerenciamento do torneio
- **Batalhas** (`/batalhas`): Avaliação de batalhas pendentes
- **Relatórios** (`/relatorios`): Visualização de relatório de desempenho

### Backend (Lumen)
- **Startups**: Cadastro e listagem
- **Torneio**: Iniciar, avançar, verificar status e resetar
- **Batalhas**: Listar batalhas pendentes e resolver confrontos
- **Relatórios**: Gerar relatórios simples e detalhados do torneio

## Pré-requisitos

1. Node.js e npm para o frontend
2. PHP 8+ e Composer para o backend
3. Banco de dados (PostgreSQL)
4. Servidor web (Apache/Nginx) ou ambiente de desenvolvimento PHP

## Instalação

### Backend (Lumen)

1. Clone o repositório
   ```bash
   git clone [URL_DO_REPOSITORIO]
   cd [PASTA_DO_BACKEND]
   ```

2. Instale as dependências
   ```bash
   composer install
   ```

3. Configure o ambiente
   ```bash
   cp .env.example .env
   # Edite o arquivo .env com as configurações de banco de dados
   ```

4. Execute as migrações
   ```bash
   php artisan migrate
   ```

5. Inicie o servidor de desenvolvimento
   ```bash
   php -S localhost:8000 -t public
   ```

### Frontend (Vue.js)

1. Navegue até a pasta do frontend
   ```bash
   cd [PASTA_DO_FRONTEND]
   ```

2. Instale as dependências
   ```bash
   npm install
   ```

3. Configure a API URL
   ```bash
   # Edite o arquivo .env ou .env.local
   VUE_APP_API_URL=http://localhost:8000
   ```

4. Inicie o servidor de desenvolvimento
   ```bash
   npm run serve
   ```

5. Acesse a aplicação em `http://localhost:8080`

## Fluxo de Utilização

### 1. Cadastro de Startups

1. Acesse a página de Startups (`/startups`)
2. Preencha o formulário com dados das startups:
   - Nome
   - Slogan
   - Ano de fundação
3. Envie o formulário para cadastrar novas startups
4. Visualize a lista de startups cadastradas na mesma página

**API Relacionada:**
- `POST /startups` - Cadastrar uma nova startup
- `GET /startups` - Listar todas as startups cadastradas

### 2. Configuração e Início do Torneio

1. Acesse a página de Torneio (`/torneio`)
2. Configure os parâmetros do torneio:
   - Formato (eliminatória simples, grupos, etc.)
   - Quantidade de rodadas
   - Critérios de avaliação
3. Selecione as startups participantes
4. Clique em "Iniciar Torneio"

**API Relacionada:**
- `POST /torneio/iniciar` - Iniciar o torneio com as configurações definidas
- `GET /torneio/status` - Verificar o status atual do torneio

### 3. Gerenciamento de Batalhas

1. Acesse a página de Batalhas (`/batalhas`)
2. Visualize as batalhas pendentes
3. Para cada batalha:
   - Analise os dados das startups confrontantes
   - Avalie de acordo com os critérios estabelecidos
   - Selecione a vencedora
   - Adicione comentários e pontuações
4. Confirme a resolução da batalha

**API Relacionada:**
- `GET /batalhas/pendentes` - Listar batalhas pendentes
- `POST /batalhas/{id}/resolver` - Resolver uma batalha específica

### 4. Progressão do Torneio

1. Volte à página de Torneio (`/torneio`)
2. Verifique o status atual e o bracket do torneio
3. Após todas as batalhas da rodada atual serem resolvidas, clique em "Avançar Torneio" para prosseguir para a próxima fase
4. Se necessário, utilize a função "Resetar Torneio" para recomeçar

**API Relacionada:**
- `POST /torneio/avancar` - Avançar para a próxima fase do torneio
- `POST /torneio/resetar` - Resetar o torneio para o estado inicial

### 5. Geração e Visualização de Relatórios

1. Acesse a página de Relatórios (`/relatorios`)
2. Selecione o tipo de relatório desejado:
   - Relatório Simples: visão geral do torneio
   - Relatório Detalhado: dados completos de todas as batalhas e participantes
3. Visualize os dados na própria página
4. Utilize as opções de exportação (PDF, CSV) se disponíveis

**API Relacionada:**
- `GET /torneio/relatorio-detalhado` - Gerar relatório detalhado com todas as informações

## Solução de Problemas

### Problemas Comuns no Backend

1. **Erro de conexão com o banco de dados**
   - Verifique as credenciais no arquivo `.env`
   - Certifique-se de que o serviço de banco de dados está em execução

2. **Erro 500 nas requisições API**
   - Verifique os logs em `storage/logs/lumen.log`
   - Confirme se as migrações foram executadas corretamente

3. **Erro "Route not found"**
   - Verifique se o servidor está sendo executado na porta correta
   - Confirme se a rota está definida corretamente em `routes/web.php`

### Problemas Comuns no Frontend

1. **Erro de conexão com a API**
   - Verifique se a URL da API está configurada corretamente
   - Certifique-se de que o backend está em execução

2. **Componentes não carregando**
   - Verifique os erros no console do navegador
   - Confirme se os arquivos Vue estão no caminho correto (`../views/`)

3. **Problemas de navegação**
   - Verifique se o histórico web está configurado corretamente
   - Confirme se não há conflitos de rota no arquivo de rotas

## Comandos Úteis

### Backend (Lumen)

```bash
# Limpar cache
php artisan cache:clear

# Verificar status das migrações
php artisan migrate:status

# Resetar banco de dados
php artisan migrate:fresh

# Rodar seeds (se disponível)
php artisan db:seed
```

### Frontend (Vue.js)

```bash
# Lint e corrigir arquivos
npm run lint

# Compilar para produção
npm run build

# Auditar dependências
npm audit

# Limpar cache npm
npm cache clean --force
```

