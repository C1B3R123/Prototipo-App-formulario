<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conecta ao banco de dados 'gestao_alunos' [cite: 23]

$id = $_GET['id'] ?? null; // Obtém o ID do aluno [cite: 23]
$coluna = $_GET['coluna'] ?? null; // Obtém o nome da coluna a ser editada [cite: 23]
$valor = $_GET['valor'] ?? null; // Obtém o novo valor [cite: 24]

// Lista de colunas permitidas para edição para segurança
$allowed_columns = ['nome', 'ra', 'email', 'curso'];

if ($id && $coluna && $valor !== null && in_array($coluna, $allowed_columns)) {
    // Escapar o valor para prevenir SQL Injection
    $valor = $conn->real_escape_string($valor);

    // Ajusta a query para os novos campos
    $query = "UPDATE alunos SET $coluna='$valor' WHERE id=$id"; // Query para atualizar o campo [cite: 24]
    $conn->query($query); // Executa a query de atualização [cite: 25]
}
?>