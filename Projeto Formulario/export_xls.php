<?php
require_once 'config.php'; // Inclui o arquivo de configuração e inicia a sessão
redirect_if_not_admin(); // Redireciona se não for Admin

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=alunos.xls");

$query = "SELECT nome, ra, email, curso FROM alunos";
$result = $conn->query($query);

echo "Nome\tR.A.\tE-mail\tCurso\n";
while ($row = $result->fetch_assoc()) {
    echo "{$row['nome']}\t{$row['ra']}\t{$row['email']}\t{$row['curso']}\n";
}
?>
