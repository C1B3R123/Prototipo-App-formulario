<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão

$errors = [];
$success_message = "";

// Para protótipo e facilidade de criação do primeiro admin,
// esta página permite acesso direto. Em produção, você DEVERIA
// verificar se o usuário atual é um admin logado aqui!
// Ex: if (!is_admin()) { redirect_if_not_admin(); }
// Para este caso, vamos deixar acessível para permitir a criação inicial.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'aluno'; // Padrão é 'aluno'

    // Validação
    if (empty($username)) {
        $errors['username'] = "Nome de usuário é obrigatório.";
    }
    if (empty($password)) {
        $errors['password'] = "Senha é obrigatória.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "A senha deve ter pelo menos 6 caracteres.";
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "As senhas não coincidem.";
    }
    if (!in_array($role, ['admin', 'aluno'])) {
        $errors['role'] = "Perfil inválido.";
    }

    if (empty($errors)) {
        // Verifica se o nome de usuário já existe
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $errors['username'] = "Este nome de usuário já está em uso.";
        } else {
            // Hash da senha
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insere o novo usuário
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt_insert->execute()) {
                $success_message = "Usuário '$username' cadastrado com sucesso como " . htmlspecialchars($role) . "!";
                // Limpa os campos após o sucesso
                $_POST = [];
            } else {
                $errors['global'] = "Erro ao cadastrar usuário. Tente novamente.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Novo Usuário</h1>
        <?php if (!empty($success_message)): ?>
            <div class="success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (isset($errors['global'])): ?>
            <div class="error"><?= htmlspecialchars($errors['global']) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input
                type="text"
                name="username"
                placeholder="Nome de usuário"
                class="<?= isset($errors['username']) ? 'input-error' : '' ?>"
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                required>
            <span class="error"><?= $errors['username'] ?? '' ?></span>

            <input
                type="password"
                name="password"
                placeholder="Senha"
                class="<?= isset(isset($errors['password'])) ? 'input-error' : '' ?>"
                required>
            <span class="error"><?= $errors['password'] ?? '' ?></span>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirmar Senha"
                class="<?= isset($errors['confirm_password']) ? 'input-error' : '' ?>"
                required>
            <span class="error"><?= $errors['confirm_password'] ?? '' ?></span>

            <select name="role" class="select-field">
                <option value="aluno" <?= (($_POST['role'] ?? '') == 'aluno') ? 'selected' : '' ?>>Aluno</option>
                <option value="admin" <?= (($_POST['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
            <span class="error"><?= $errors['role'] ?? '' ?></span>

            <button type="submit">Cadastrar Usuário</button>
        </form>
        <button class="back-button" onclick="window.location.href='login.php'">Voltar ao Login</button>
    </div>
</body>
<footer>
    <p>&copy; <?= date("Y") ?> - Protótipo</p>
</footer>
</html>
