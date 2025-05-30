<?php
// Arquivo: logout.php
session_start(); // Inicia a sessão
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão

header("Location: login.php"); // Redireciona para a página de login
exit;
?>
