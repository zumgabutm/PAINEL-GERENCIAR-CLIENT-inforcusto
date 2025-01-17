<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Verificar se o formulário de renovação foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['renovar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $dias = $_POST['dias'];
    $novo_plano = $_POST['plano']; // ID do novo plano

    // Calcular a nova data de vencimento
    $nova_data_vencimento = date('Y-m-d', strtotime("+$dias days"));

    // Obter o preço do novo plano
    $stmt = $conn->prepare("SELECT preco FROM categorias WHERE id = ?");
    $stmt->execute([$novo_plano]);
    $preco_novo_plano = $stmt->fetchColumn();

    // Iniciar uma transação
    $conn->beginTransaction();

    try {
        // Atualizar a data de vencimento e a categoria do usuário
        $stmt = $conn->prepare("UPDATE usuarios SET data_vencimento = ?, categoria = ? WHERE id = ?");
        $stmt->execute([$nova_data_vencimento, $novo_plano, $id_usuario]);

        // Obter o saldo atual
        $stmt = $conn->query("SELECT valor FROM saldo WHERE id = 1"); // Ajuste conforme sua tabela de saldo
        $saldo_atual = $stmt->fetchColumn();

        // Somar o preço do novo plano ao saldo total
        $novo_saldo = $saldo_atual + $preco_novo_plano;

        // Atualizar o saldo
        $stmt = $conn->prepare("UPDATE saldo SET valor = ? WHERE id = 1");
        $stmt->execute([$novo_saldo]);

        // Confirmar a transação
        $conn->commit();

        echo "<p>Usuário renovado com sucesso!</p>";
    } catch (PDOException $e) {
        // Reverter a transação em caso de erro
        $conn->rollBack();
        echo "Erro ao renovar usuário: " . $e->getMessage();
    }
}
