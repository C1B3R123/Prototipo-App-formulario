<?php
// Arquivo: delete.php (Apenas Admin)
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_admin(); // Redireciona se não for Admin

$id = $_GET['id'] ?? null;

if ($id) {
    // Prepara a consulta para deletar por ID
    $stmt = $conn->prepare("DELETE FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' indica que id é um inteiro
    $stmt->execute();
    $stmt->close();
}

header("Location: consulta.php");
exit;
?>
