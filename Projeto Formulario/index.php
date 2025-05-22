<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // O nome do banco de dados mudou para 'gestao_alunos'

// Variáveis para armazenar erros
$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica se o formulário foi submetido via POST 
    $nome = $_POST["nome"];
    $ra = $_POST["ra"]; // Novo campo: RA
    $email = $_POST["email"]; // Novo campo: Email
    $curso = $_POST["curso"]; // Novo campo: Curso

    // Validação dos campos
    if (empty($nome)) $errors['nome'] = "⚠ Nome é obrigatório!"; // Validação para o campo 'nome' 
    if (empty($ra)) $errors['ra'] = "⚠ R.A. é obrigatório!";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validação de formato de e-mail
        $errors['email'] = "⚠ E-mail inválido!";
    }
    if (empty($curso)) $errors['curso'] = "⚠ Curso é obrigatório!";

    // Se não houver erros, cadastrar no banco
    if (empty($errors)) {
        try {
            // Ajustar a query SQL para os novos campos
            $query = "INSERT INTO alunos (nome, ra, email, curso) VALUES ('$nome', '$ra', '$email', '$curso')"; // Insere um novo aluno na tabela 'alunos'
            $conn->query($query);
            $success_message = "✅ Aluno cadastrado com sucesso!"; // Mensagem de sucesso 
        } catch (mysqli_sql_exception $e) {
            // Verifica se o erro é de entrada duplicada para RA ou E-mail (UNIQUE)
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) { // Detecta erro de entrada duplicada 
                if (strpos($e->getMessage(), "'ra'") !== false) {
                    $errors['ra'] = "⚠ Este R.A. já está cadastrado!";
                } elseif (strpos($e->getMessage(), "'email'") !== false) {
                    $errors['email'] = "⚠ Este E-mail já está cadastrado!";
                } else {
                    $errors['global'] = "⚠ Erro ao cadastrar! Tente novamente."; // Erro global 
                }
            } else {
                $errors['global'] = "⚠ Erro ao cadastrar! Tente novamente."; // Erro global 
            }
        }
    }
}
?>

<!DOCTYPE html>
<!---Felipe Douglas, se você está vendo isso, como o PHP é uma linguagem de programação e de marcação ao mesmo tempo ? --->
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alunos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gestão de Alunos</h1>
    <button class="back-button" onclick="window.location.href='consulta.php'">Consultar Alunos</button>

    <div class="container"><!-- Formulário com tratamento básico de dados para o registro de alunos -->
        <h1>Registrar Aluno</h1>
        <form method="POST">
            <?php if (!empty($success_message)): ?>
                <div class="success"><?= $success_message ?></div>
            <?php endif; ?>
            <?php if (isset($errors['global'])): ?>
                <div class="error"><?= $errors['global'] ?></div>
            <?php endif; ?>

            <input
                type="text"
                name="nome"
                placeholder="Nome do aluno"
                class="<?= isset($errors['nome']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"> <span class="error"><?= $errors['nome'] ?? '' ?></span> <input
                type="text"
                name="ra"
                placeholder="R.A."
                class="<?= isset($errors['ra']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['ra'] ?? '') ?>">
            <span class="error"><?= $errors['ra'] ?? '' ?></span>

            <input
                type="email"
                name="email"
                placeholder="E-mail"
                class="<?= isset($errors['email']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <span class="error"><?= $errors['email'] ?? '' ?></span>

            <input
                type="text"
                name="curso"
                placeholder="Curso"
                class="<?= isset($errors['curso']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['curso'] ?? '') ?>">
            <span class="error"><?= $errors['curso'] ?? '' ?></span>

            <button type="submit">Matricular Aluno</button>
        </form>
    </div>
</body>
<footer>
    <p>&copy; <?= date("Y") ?> Quando eu sabo eu sabo</p> </footer>
</html>
