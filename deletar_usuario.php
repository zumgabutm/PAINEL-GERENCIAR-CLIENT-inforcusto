<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Deletar usuário do banco de dados
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    echo "<p>Usuário deletado com sucesso!</p>";
}

// Redirecionar de volta para a página home
header('Location: home.php');
exit();
?>
