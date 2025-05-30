<?php
// Arquivo: edit.php (Apenas Admin)
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_admin(); // Redireciona se não for Admin

$id = $_GET['id'] ?? null;
$coluna = $_GET['coluna'] ?? null;
$valor = $_GET['valor'] ?? null;

// Lista de colunas permitidas para edição para segurança
$allowed_columns = ['nome', 'ra', 'email', 'curso'];

if ($id && $coluna && $valor !== null && in_array($coluna, $allowed_columns)) {
    // Escapar o valor para prevenir SQL Injection
    $valor_escaped = $conn->real_escape_string($valor);

    // Prepara a consulta para atualizar o campo
    $stmt = $conn->prepare("UPDATE alunos SET {$coluna}=? WHERE id=?");
    // 's' para string (valor), 'i' para inteiro (id)
    $stmt->bind_param("si", $valor_escaped, $id);
    $stmt->execute();
    $stmt->close();
}
// Não há redirecionamento aqui, pois a edição é via AJAX ou similar
?>
