<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_admin(); // Redireciona se não for Admin

$query = "SELECT nome, ra, email, curso FROM alunos";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="alunos.json"');
echo json_encode($data, JSON_PRETTY_PRINT);
?>
