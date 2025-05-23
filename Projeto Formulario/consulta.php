<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conexão com o banco de dados 'gestao_alunos' [cite: 1]

$search = $_GET['search'] ?? ''; // Obtém o termo de busca da URL [cite: 1]
// Ajusta a query para buscar por nome, RA, email ou curso
$query = "SELECT * FROM alunos WHERE nome LIKE '%$search%' OR ra LIKE '%$search%' OR email LIKE '%$search%' OR curso LIKE '%$search%'"; // Consulta para buscar alunos [cite: 2]
$result = $conn->query($query); // Executa a consulta [cite: 2]
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura a responsividade -->
    <title>Consulta de Alunos</title> <!-- Título da página -->
    <link rel="stylesheet" href="styles.css"> <!-- Link para o arquivo CSS -->
</head>
<body>
    <h1>Consulta de Alunos</h1>

    <button class="back-button" onclick="window.location.href='index.php'">⬅ Voltar ao Cadastro</button>

    <form method="GET"> <!-- Formulário de busca -->
        <input type="text" name="search" placeholder="Buscar aluno (Nome, RA, Email, Curso)">
        <button type="submit">Pesquisar</button>
    </form>

    <table> <!-- Tabela para exibir os dados dos alunos -->
        <thead>
            <tr>
                <th>Nome</th>
                <th>R.A.</th>
                <th>E-mail</th>
                <th>Curso</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?> <!-- Loop para listar os alunos -->
            <tr>
                <td><?= htmlspecialchars($row["nome"]) ?></td>
                <td><?= htmlspecialchars($row["ra"]) ?></td>
                <td><?= htmlspecialchars($row["email"]) ?></td>
                <td><?= htmlspecialchars($row["curso"]) ?></td>
                <td class="action-buttons">
                    <a href="delete.php?id=<?= $row["id"] ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja apagar este aluno?');">🗑 Apagar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="export-buttons"> <!-- Botões de exportação -->
        <a href="export_xls.php" class="download-btn">Baixar XLS</a>
        <a href="export_json.php" class="download-btn">Baixar JSON</a>
    </div>
</body>
<footer>
    <!-- Rodapé com ano dinâmico -->
    <p>&copy; <?= date("Y") ?> - Olha pra cá não</p> 
</footer>
</html>
