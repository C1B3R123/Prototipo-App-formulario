#Projeto Básico em PHP de um formulário básico 
## Projeto feito em PHP e CSS pra gerar um formulário básico que guarda as informações em um banco de dados MySql do XAMPPS
Para criar o BD é Facil, apenas configure o admin do MySql para que o banco de dados seja ( gestao_alunos ) que dentro deste banco de dados, rode este codigo SQL:

CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    matricula VARCHAR(50) NOT NULL,
    endereco TEXT NOT NULL,
    telefone VARCHAR(11) NOT NULL
);
