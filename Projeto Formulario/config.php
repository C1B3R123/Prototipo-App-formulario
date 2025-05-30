<?php
// Arquivo: config.php

// Inicia a sessão PHP
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuração de exibição de erros para desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações do Banco de Dados
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gestao_alunos');

// Conexão com o banco de dados
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

/**
 * Verifica se o usuário está logado.
 * @return bool True se logado, False caso contrário.
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se o usuário logado tem o perfil de administrador.
 * @return bool True se for admin, False caso contrário.
 */
function is_admin() {
    return is_logged_in() && $_SESSION['role'] === 'admin';
}

/**
 * Redireciona o usuário para a página de login se não estiver logado.
 */
function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Redireciona o usuário para a página de login se não for admin.
 */
function redirect_if_not_admin() {
    if (!is_admin()) {
        header("Location: login.php?error=access_denied");
        exit;
    }
}
?>
