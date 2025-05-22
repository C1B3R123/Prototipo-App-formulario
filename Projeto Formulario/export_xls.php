<?php
// Define os cabeçalhos para download de arquivo Excel (XLS)
header("Content-Type: application/vnd.ms-excel"); // Define o tipo de conteúdo como Excel
header("Content-Disposition: attachment; filename=alunos.xls"); // Define o nome do arquivo

$conn = new mysqli("localhost", "root", "", "gestao_alunos"); // Conecta ao banco de dados 'gestao_alunos' 
// Ajusta a query para selecionar todos os campos da tabela 'alunos'
$query = "SELECT nome, ra, email, curso FROM alunos"; // Seleciona todos os alunos
$result = $conn->query($query); // Executa a consulta

// Cabeçalho da tabela no arquivo XLS
echo "Nome\tR.A.\tE-mail\tCurso\n"; // Escreve os cabeçalhos das colunas
while ($row = $result->fetch_assoc()) {
    // Ajusta a saída para os novos campos
    echo "{$row['nome']}\t{$row['ra']}\t{$row['email']}\t{$row['curso']}\n"; // Escreve os dados dos alunos
}
?>
