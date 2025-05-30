<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepara a consulta para evitar SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // 's' indica que username é uma string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verifica a senha hashed
        if (password_verify($password, $user['password'])) {
            // Senha correta, define variáveis de sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redireciona com base no perfil
            if ($user['role'] === 'admin') {
                header("Location: index.php"); // Admin vai para a página de cadastro
            } else {
                header("Location: consulta.php"); // Aluno vai para a página de consulta
            }
            exit;
        } else {
            $error_message = "Usuário ou senha inválidos.";
        }
    } else {
        $error_message = "Usuário ou senha inválidos.";
    }
    $stmt->close();
}

// Verifica se há uma mensagem de erro na URL (ex: acesso negado)
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    $error_message = "Acesso negado. Por favor, faça login.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão de Alunos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Nome de usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <button class="register-button" onclick="window.location.href='admin_register.php'">Cadastrar Novo Usuário</button>
    </div>
</body>
<footer>
    <p>&copy; <?= date("Y") ?> - Protótipo</p>
</footer>
</html>
