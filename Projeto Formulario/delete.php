<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conecta ao banco de dados 'gestao_alunos' [cite: 21]

$id = $_GET['id'] ?? null; // Obtém o ID do aluno a ser excluído [cite: 21]

if ($id) { // Verifica se o ID foi fornecido [cite: 22]
    // Ajusta a query para deletar por ID da tabela 'alunos'
    $query = "DELETE FROM alunos WHERE id = $id"; // Query para deletar um aluno [cite: 22]
    $conn->query($query); // Executa a query [cite: 22]
}

header("Location: consulta.php"); // Redireciona para a página de consulta [cite: 22]
exit;
?>