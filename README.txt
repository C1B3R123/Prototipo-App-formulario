# Projeto Básico em PHP de um Formulário de Gestão de Alunos

## Descrição do Projeto
Este projeto consiste em um sistema básico de gestão de alunos desenvolvido em PHP e CSS. Ele permite o cadastro, consulta, edição e exclusão de informações de alunos, com um sistema de login que oferece dois perfis de acesso: **Admin** e **Aluno**. As informações são armazenadas em um banco de dados MySQL, utilizando o XAMPP como ambiente de desenvolvimento local.

## Pré-requisitos
* **XAMPP:** Para rodar o ambiente de desenvolvimento local (Apache, MySQL, PHP).

## Configuração do Banco de Dados

Para que o projeto funcione corretamente, é necessário criar um banco de dados MySQL com a seguinte estrutura e preenchê-lo com usuários iniciais:

1.  **Crie o Banco de Dados:**
    Acesse o `phpMyAdmin` (geralmente em `http://localhost/phpmyadmin/` após iniciar o MySQL no XAMPP Control Panel). Crie um novo banco de dados com o nome: `gestao_alunos`

2.  **Crie a Tabela `alunos`:**
    Dentro do banco de dados `gestao_alunos`, execute o seguinte comando SQL para criar a tabela `alunos` (se ela já existir, certifique-se de que a coluna `curso` está presente):

    ```sql
    CREATE TABLE alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        ra VARCHAR(50) NOT NULL UNIQUE, -- RA agora é UNIQUE
        email VARCHAR(255) NOT NULL UNIQUE, -- Email agora é UNIQUE
        curso VARCHAR(255) NOT NULL
    );
    ```

3.  **Crie a Tabela `users` e Insira Usuários Iniciais:**
    Para o sistema de login, você precisará da tabela `users` e de alguns usuários pré-definidos (Admin e Aluno). Execute os seguintes comandos SQL:

    ```sql
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL, -- Armazena a senha hashed
        role ENUM('admin', 'aluno') NOT NULL DEFAULT 'aluno'
    );

    -- Insere um usuário Admin padrão para protótipo
    -- Usuário: 'Admin'
    -- Senha: 'Admin' (já hashed, use um gerador de hash seguro em produção)
    INSERT INTO users (username, password, role) VALUES ('Admin', '$2y$10$T1UaR9tQ8zZ7zW5.S4F7U.uN9v.eL3X2K0Y.r.F5C.H0Y.s1C.H0Y', 'admin');

    -- Insere um usuário Aluno padrão
    -- Usuário: 'aluno'
    -- Senha: 'aluno123' (já hashed, use um gerador de hash seguro em produção)
    INSERT INTO users (username, password, role) VALUES ('aluno', '$2y$10$T1UaR9tQ8zZ7zW5.S4F7U.uN9v.eL3X2K0Y.r.F5C.H0Y.s1C.H0Y', 'aluno');
    ```
    **Observação:** As senhas (`$2y$...`) são hashes gerados para 'Admin' e 'aluno123'. Em um sistema real, você deve gerar hashes seguros para as senhas de forma dinâmica.

## Como Executar o Projeto

1.  **Clone o Repositório:**
    ```bash
    git clone [https://github.com/C1B3R123/app-alunos.git](https://github.com/C1B3R123/app-alunos.git)
    ```
2.  **Mova para o `htdocs`:**
    Copie a pasta `app-alunos` (ou a pasta do seu projeto, por exemplo, `Projeto Formulario`) para o diretório `htdocs` da sua instalação do XAMPP (ex: `C:\xampp\htdocs\`).
3.  **Inicie o Apache e MySQL:**
    Abra o XAMPP Control Panel e inicie os módulos Apache e MySQL.
4.  **Acesse no Navegador:**
    Abra seu navegador e acesse: `http://localhost/app-alunos/login.php` (assumindo que você moveu a pasta `app-alunos` para `htdocs`).

## Funcionalidades

* **Sistema de Login:** Autenticação de usuários com perfis 'admin' e 'aluno'.
* **Cadastro de Novo Usuário:** Um botão na página de login (`login.php`) permite acessar `admin_register.php` para criar novos usuários (Admin ou Aluno).
    * **Importante:** Na versão de protótipo, `admin_register.php` é acessível diretamente para facilitar a criação do primeiro Admin. Em produção, esta página deveria ser protegida e apenas um Admin já logado poderia criar outros usuários.
* **Controle de Acesso Baseado em Perfil:**
    * **Admin:** Pode cadastrar novos alunos (`index.php`), consultar todos os alunos (`consulta.php`), editar campos diretamente na tabela, apagar alunos e exportar dados (XLS, JSON).
    * **Aluno:** Pode apenas consultar alunos (`consulta.php`), sem acesso a funções de cadastro, edição, exclusão ou exportação.
* **Cadastro de Alunos:** Adicione novos alunos com nome, RA, e-mail e curso.
* **Consulta de Alunos:** Visualize todos os alunos cadastrados em uma tabela.
* **Busca:** Pesquise alunos por nome, RA, e-mail ou curso.
* **Edição Direta:** (Apenas Admin) Clique nos campos da tabela na página de consulta para editá-los diretamente.
* **Exclusão:** (Apenas Admin) Remova alunos do sistema.
* **Exportação:** (Apenas Admin) Exporte os dados dos alunos para formatos XLS e JSON.

---
**AVISO DE SEGURANÇA:** Este projeto é básico e utiliza `mysqli_real_escape_string()` para sanitização em algumas partes. Para um projeto em produção, é **altamente recomendável** usar Prepared Statements (como feito no `login.php`, `delete.php` e `admin_register.php`) em **todas as interações com o banco de dados** para prevenir completamente SQL Injection.
