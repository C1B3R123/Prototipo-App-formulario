<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_logged_in(); // Redireciona se não estiver logado

$search = $conn->real_escape_string($_GET['search'] ?? ''); // Sanitiza o termo de busca
$show_all = isset($_GET['show_all']); // Verifica se o botão "Mostrar todos" foi clicado

$query = "SELECT * FROM alunos";
if (!empty($search)) {
    // Ajusta a query para buscar por nome, RA, email ou curso se houver termo de busca
    $query .= " WHERE nome LIKE '%$search%' OR ra LIKE '%$search%' OR email LIKE '%$search%' OR curso LIKE '%$search%'";
} else if (!$show_all) {
    // Se não há busca e não é para mostrar tudo, retorna um conjunto vazio
    // para que a tabela só apareça quando houver busca ou clique em "Mostrar Todos"
    $query .= " WHERE 1=0"; // Condição falsa para não retornar nada
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Alunos</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleAllStudents');
            const studentTable = document.getElementById('studentTable');

            // Função para alternar a visibilidade da tabela
            function toggleTableVisibility() {
                if (studentTable.style.display === 'none' || studentTable.style.display === '') {
                    studentTable.style.display = 'table';
                    toggleButton.textContent = 'Esconder Alunos';
                    // Atualiza a URL para refletir o estado de "mostrar todos"
                    const url = new URL(window.location.href);
                    url.searchParams.set('show_all', 'true');
                    window.history.pushState({path: url.href}, '', url.href);
                } else {
                    studentTable.style.display = 'none';
                    toggleButton.textContent = 'Mostrar Todos os Alunos';
                    // Remove o parâmetro 'show_all' da URL
                    const url = new URL(window.location.href);
                    url.searchParams.delete('show_all');
                    window.history.pushState({path: url.href}, '', url.href);
                }
            }

            // Define o estado inicial da tabela e do botão
            if (<?= json_encode($show_all) ?>) {
                studentTable.style.display = 'table';
                toggleButton.textContent = 'Esconder Alunos';
            } else {
                studentTable.style.display = 'none';
                toggleButton.textContent = 'Mostrar Todos os Alunos';
            }

            // Se houver termo de busca, garante que a tabela esteja visível
            if ("<?= htmlspecialchars($search) ?>" !== "") {
                studentTable.style.display = 'table';
                toggleButton.textContent = 'Esconder Alunos'; // O botão muda para esconder se a busca for visível
            }
        });
    </script>
</head>
<body>
    <h1>Consulta de Alunos</h1>

    <div class="header-buttons">
        <?php if (is_admin()): // Apenas Admin pode voltar para o cadastro ?>
            <button class="back-button" onclick="window.location.href='index.php'">⬅ Voltar ao Cadastro</button>
        <?php endif; ?>
        <button class="back-button logout-button" onclick="window.location.href='logout.php'">Sair</button>
    </div>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Buscar aluno (Nome, RA, Email, Curso)" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <button id="toggleAllStudents" class="toggle-button">Mostrar Todos os Alunos</button>

    <table id="studentTable" style="display: none;">
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
                <tr class="<?= is_admin() ? 'clickable-row' : '' ?>"
                    <?php if (is_admin()): ?>
                        onclick="window.location.href='edit_student.php?id=<?= $row['id'] ?>'"
                    <?php endif; ?>
                >
                    <td><?= htmlspecialchars($row["nome"]) ?></td>
                    <td><?= htmlspecialchars($row["ra"]) ?></td>
                    <td><?= htmlspecialchars($row["email"]) ?></td>
                    <td><?= htmlspecialchars($row["curso"]) ?></td>
                    <?php if (is_admin()): // Apenas Admin vê os botões de ação ?>
                    <td class="action-buttons no-click">
                        <a href="delete.php?id=<?= $row["id"] ?>" class="delete-btn" onclick="event.stopPropagation(); return confirm('Tem certeza que deseja apagar este aluno?');">🗑 Apagar</a>
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
    <p>&copy; <?= date("Y") ?> - Protótipo</p>
</footer>
</html>
