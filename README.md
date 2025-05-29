# Projeto Básico em PHP de um Formulário

## Descrição do Projeto
Este projeto consiste em um formulário básico desenvolvido em PHP e CSS. Ele permite o cadastro, consulta, edição e exclusão de informações de alunos, armazenando-as em um banco de dados MySQL, utilizando o XAMPP como ambiente de desenvolvimento local.

(lembrando que irá ser mais comentado que o normal para fins educativos.)

## Pré-requisitos
* **XAMPP:** Para rodar o ambiente de desenvolvimento local (Apache, MySQL, PHP).

## Configuração do Banco de Dados

Para que o projeto funcione corretamente, é necessário criar um banco de dados MySQL com a seguinte estrutura:

1.  **Crie o Banco de Dados:**
    Acesse o `phpMyAdmin` (geralmente em `http://localhost/phpmyadmin/` após iniciar o MySQL no XAMPP Control Panel).
    Crie um novo banco de dados com o nome: `gestao_alunos`

2.  **Crie a Tabela `alunos`:**
    Antes de fazer os passos de Como executar o projeto, dentro do banco de dados `gestao_alunos`, execute o seguinte comando SQL para criar a tabela `alunos`:

    ```sql
    CREATE TABLE alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email CHAR UNIQUE NOT NULL,
        ra VARCHAR(50) NOT NULL,
    );
    ```
 
    **Explicação das Colunas:**
    * `id`: Chave primária auto-incrementável para identificar cada aluno de forma única.
    * `nome`: Nome completo do aluno.
    * `email`: E-mail do aluno (único para cada registro).
    * `ra`: Número de matrícula do aluno.

   

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
    Escreva [localhost](http://localhost/) na url do navegador que está usando ou pode-se acessar pelo botão Admin no na linha Apache no XAMPP control
    
## Funcionalidades

* **Cadastro de Alunos:** Adicione novos alunos com nome, CPF, matrícula, endereço e telefone.
* **Consulta de Alunos:** Visualize todos os alunos cadastrados em uma tabela.
* **Busca:** Pesquise alunos por qualquer campo.
* **Edição Direta:** Clique nos campos da tabela na página de consulta para editá-los diretamente.
* **Exclusão:** Remova alunos do sistema.
* **Exportação:** Exporte os dados dos alunos para formatos XLS e JSON.

## Estrutura do Projeto

* `index.php`: Página principal para cadastro de alunos.
* `consulta.php`: Página para visualizar, pesquisar e editar alunos.
* `edit.php`: Script PHP para processar edições de campo.
* `delete.php`: Script PHP para processar a exclusão de alunos.
* `export_json.php`: Script PHP para exportar dados para JSON.
* `export_xls.php`: Script PHP para exportar dados para XLS.
* `styles.css`: Folha de estilos CSS para o design da aplicação.

## Ajuda-me
Se quiser, Fique avontade em melhorar o projeto e dar ideias de funcionalidades, além de apontar erros com o Issues do Github.

