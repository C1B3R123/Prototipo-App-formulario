<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conecta ao banco de dados 'gestao_alunos'

$id = $_GET['id'] ?? null; // Obtém o ID do aluno a ser excluído 
// Verifica se o ID foi fornecido
if ($id) { 
    // Ajusta a query para deletar por ID da tabela 'alunos'
    $query = "DELETE FROM alunos WHERE id = $id"; // Query para deletar um aluno
    $conn->query($query); // Executa a query 

header("Location: consulta.php"); // Redireciona para a página de consulta 
exit;
?>
