<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Buscar usuários do banco de dados
$query = $conn->query("SELECT * FROM usuarios");
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $hoje = new DateTime();
    $vencimento = new DateTime($usuario['data_vencimento']);

    if ($hoje >= $vencimento) {
        // Marcar como vencido
        $stmt = $conn->prepare("UPDATE usuarios SET status = 'vencido' WHERE id = ?");
        $stmt->execute([$usuario['id']]);
    } elseif ($hoje->diff($vencimento)->days <= 5) {
        // Marcar como quase vencido
        $stmt = $conn->prepare("UPDATE usuarios SET status = 'quase vencido' WHERE id = ?");
        $stmt->execute([$usuario['id']]);
    }
}

// Feedback opcional
echo "Verificação concluída.";
?>
