<?php
$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conecta ao banco de dados 'gestao_alunos' [cite: 26]

// Ajusta a query para selecionar todos os campos da tabela 'alunos'
$query = "SELECT nome, ra, email, curso FROM alunos"; // Seleciona todos os alunos [cite: 26]
$result = $conn->query($query);

$data = []; // Array para armazenar os dados [cite: 27]
while ($row = $result->fetch_assoc()) { // Itera sobre os resultados [cite: 27]
    $data[] = $row; // Adiciona cada linha ao array [cite: 27]
}

header('Content-Type: application/json'); // Define o tipo de conteúdo como JSON
header('Content-Disposition: attachment; filename="alunos.json"'); // Define o nome do arquivo para download
echo json_encode($data, JSON_PRETTY_PRINT); // Codifica os dados em JSON e os exibe [cite: 27]
?>