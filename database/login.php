<?php
session_start();
include("database/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $email = $conn->real_escape_string($email);

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: admin.html");
            exit;
        } else {
            header("Location: admin.html?erro=senha");
            exit;
        }
    } else {
        header("Location: admin.html?erro=usuario");
        exit;
    }
}
?>
