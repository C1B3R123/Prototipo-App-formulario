<?php
// Arquivo: consulta.php (Página de Consulta de Alunos - Acesso por Admin e Aluno)
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_logged_in(); // Redireciona se não estiver logado

$search = $conn->real_escape_string($_GET['search'] ?? ''); // Sanitiza o termo de busca

// Ajusta a query para buscar por nome, RA, email ou curso
$query = "SELECT * FROM alunos WHERE nome LIKE '%$search%' OR ra LIKE '%$search%' OR email LIKE '%$search%' OR curso LIKE '%$search%'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Alunos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Consulta de Alunos</h1>

    <div class="header-buttons">
        <?php if (is_admin()): // Apenas Admin pode voltar para o cadastro ?>
            <button class="back-button" onclick="window.location.href='index.php'">⬅ Voltar ao Cadastro</button>
        <?php endif; ?>
        <button class="back-button logout-button" onclick="window.location.href='logout.php'">Sair</button>
    </div>

    <form method="GET">
        <input type="text" name="search" placeholder="Buscar aluno (Nome, RA, Email, Curso)" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>R.A.</th>
                <th>E-mail</th>
                <th>Curso</th>
                <?php if (is_admin()): // Apenas Admin vê a coluna de Ações ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["nome"]) ?></td>
                    <td><?= htmlspecialchars($row["ra"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["curso"]) ?></td>
                    <?php if (is_admin()): // Apenas Admin vê os botões de ação ?>
                    <td class="action-buttons">
                        <a href="delete.php?id=<?= $row["id"] ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja apagar este aluno?');">🗑 Apagar</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= is_admin() ? '5' : '4' ?>">Nenhum aluno encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (is_admin()): // Apenas Admin vê os botões de exportação ?>
    <div class="export-buttons">
        <a href="export_xls.php" class="download-btn">Baixar XLS</a>
        <a href="export_json.php" class="download-btn">Baixar JSON</a>
    </div>
    <?php endif; ?>
</body>
<footer>
    <p>&copy; <?= date("Y") ?> Protótipo</p>
</footer>
</html>
