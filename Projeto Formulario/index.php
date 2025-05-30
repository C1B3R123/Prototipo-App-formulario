<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_admin(); // Redireciona se não for Admin

$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST["nome"] ?? '');
    $ra = $conn->real_escape_string($_POST["ra"] ?? '');
    $email = $conn->real_escape_string($_POST["email"] ?? '');
    $curso = $conn->real_escape_string($_POST["curso"] ?? '');

    // Validação
    if (empty($nome)) $errors['nome'] = "⚠ Nome é obrigatório!";
    if (empty($ra)) $errors['ra'] = "⚠ R.A. é obrigatório!";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "⚠ E-mail inválido!";
    }
    if (empty($curso)) $errors['curso'] = "⚠ Curso é obrigatório!";

    if (empty($errors)) {
        try {
            $query = "INSERT INTO alunos (nome, ra, email, curso) VALUES ('$nome', '$ra', '$email', '$curso')";
            $conn->query($query);
            $success_message = "✅ Aluno cadastrado com sucesso!";
            // Limpa os campos do formulário após o sucesso
            $_POST = [];
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), "'ra'") !== false) {
                    $errors['ra'] = "⚠ Este R.A. já está cadastrado!";
                } elseif (strpos($e->getMessage(), "'email'") !== false) {
                    $errors['email'] = "⚠ Este E-mail já está cadastrado!";
                } else {
                    $errors['global'] = "⚠ Erro ao cadastrar! Tente novamente.";
                }
            } else {
                $errors['global'] = "⚠ Erro ao cadastrar! Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alunos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gestão de Alunos</h1>
    <div class="header-buttons">
        <button class="back-button" onclick="window.location.href='consulta.php'">Consultar Alunos</button>
        <button class="back-button logout-button" onclick="window.location.href='logout.php'">Sair</button>
    </div>

    <div class="container">
        <h1>Registrar Aluno</h1>
        <form method="POST">
            <?php if (!empty($success_message)): ?>
                <div class="success"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            <?php if (isset($errors['global'])): ?>
                <div class="error"><?= htmlspecialchars($errors['global']) ?></div>
            <?php endif; ?>

            <input
                type="text"
                name="nome"
                placeholder="Nome do aluno"
                class="<?= isset($errors['nome']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            <span class="error"><?= $errors['nome'] ?? '' ?></span>

            <input
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
    <p>&copy; <?= date("Y") ?> - Protótipo</p>
</footer>
</html>
