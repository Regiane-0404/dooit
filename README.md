# Doo.it - Gestor de Tarefas Moderno

![Doo.it Screenshot](URL_DA_SUA_IMAGEM_AQUI) <!-- Opcional: Pode tirar um screenshot da sua app e colocar aqui -->

Doo.it é uma aplicação web de gestão de tarefas (To-Do list) construída com o objetivo de ser simples, intuitiva e eficiente. Desenvolvida com tecnologias de ponta, a aplicação oferece uma experiência de utilizador rápida e reativa, ideal para organizar atividades diárias.

---

## ✨ Funcionalidades Principais

*   **Gestão Completa de Tarefas (CRUD):** Crie, edite, visualize e apague tarefas facilmente.
*   **Sistema de Prioridades:** Organize as suas tarefas com prioridades (Alta, Média, Baixa), identificadas por um sistema de cores intuitivo.
*   **Datas e Horas:** Defina datas e horas de vencimento para nunca mais perder um prazo.
*   **Filtros Dinâmicos:** Filtre a sua lista de tarefas por estado (Pendentes, Concluídas) e por prioridade, com a interface a atualizar-se instantaneamente.
*   **Visualização Detalhada:** Expanda qualquer tarefa para ver todos os seus detalhes sem sair da página, graças a um sistema de "acordeão".
*   **Autenticação Segura:** Cada utilizador tem a sua própria conta segura, garantindo a privacidade das suas tarefas.
*   **Dashboard Inteligente:** Comece o seu dia com um painel informativo que inclui um widget de meteorologia em tempo real.
*   **Design Responsivo:** A interface adapta-se a diferentes tamanhos de ecrã, desde o desktop ao telemóvel.

---

## 🛠️ Tecnologias Utilizadas

*   **Backend:**
    *   **Framework:** Laravel 12
    *   **Linguagem:** PHP 8.4
*   **Frontend:**
    *   **Framework:** Vue.js 3 (com a Composition API)
    *   **Routing:** Inertia.js
    *   **Estilização:** Tailwind CSS & DaisyUI
*   **Base de Dados:**
    *   MySQL
*   **Ambiente de Desenvolvimento:**
    *   Laravel Herd
*   **Testes:**
    *   Pest

---

## 🚀 Instalação e Execução do Projeto

Siga os passos abaixo para executar o projeto localmente.

### Pré-requisitos

*   PHP 8.4 ou superior
*   Composer
*   Node.js & NPM
*   Um servidor MySQL
*   Laravel Herd (recomendado) ou outro ambiente local (XAMPP, etc.)

### Passos de Instalação

1.  **Clonar o repositório:**
    ```bash
    git clone https://github.com/seu-username/dooit.git
    cd dooit
    ```

2.  **Instalar as dependências do PHP:**
    ```bash
    composer install
    ```

3.  **Instalar as dependências do JavaScript:**
    ```bash
    npm install
    ```

4.  **Configurar o ambiente:**
    *   Copie o ficheiro de exemplo `.env.example` para um novo ficheiro `.env`:
        ```bash
        cp .env.example .env
        ```
    *   Gere a chave da aplicação:
        ```bash
        php artisan key:generate
        ```

5.  **Configurar a Base de Dados:**
    *   Crie uma base de dados MySQL para o projeto (ex: `dooit`).
    *   Abra o seu ficheiro `.env` e configure as variáveis da base de dados:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=dooit
        DB_USERNAME=root
        DB_PASSWORD=
        ```

6.  **Executar as Migrations:**
    *   Este comando irá criar todas as tabelas necessárias na sua base de dados.
    ```bash
    php artisan migrate
    ```

### Executar a Aplicação

1.  **Compilar os ficheiros de frontend:**
    *   Este comando irá compilar o Vue/CSS e ficar a observar por alterações.
    ```bash
    npm run dev
    ```

2.  **Aceder à aplicação:**
    *   Se estiver a usar o Laravel Herd, aceda a `http://dooit.test` (ou o URL que o Herd configurou).
    *   Se não, inicie o servidor do Laravel (`php artisan serve`) e aceda a `http://localhost:8000`.

### Executar os Testes

Para garantir que tudo está a funcionar corretamente, execute a suite de testes:```bash
vendor/bin/pest
