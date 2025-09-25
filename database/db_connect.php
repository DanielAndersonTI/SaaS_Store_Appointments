<?php
// Carrega as variáveis do arquivo .env
$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['DB_HOST'];         
$usuario = $env['DB_USER'];           
$senha = $env['DB_PASS'];                 
$banco = $env['DB_NAME'];  

// Criar conexão
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>