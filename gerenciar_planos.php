<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Criar novo plano
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['novo_plano'])) {
    $nome_plano = $_POST['nome_plano'];
    $preco = $_POST['preco'];

    try {
        $stmt = $conn->prepare("INSERT INTO categorias (nome_categoria, preco) VALUES (?, ?)");
        $stmt->execute([$nome_plano, $preco]);
        echo "<p>Plano criado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao criar plano: " . $e->getMessage();
    }
}

// Remover plano
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_plano'])) {
    $id_plano = $_POST['id_plano'];
    try {
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id_plano]);
        echo "<p>Plano removido com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao remover plano: " . $e->getMessage();
    }
}

// Editar plano
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_plano'])) {
    $id_plano = $_POST['id_plano'];
    $novo_nome_plano = $_POST['novo_nome_plano'];
    $novo_preco = $_POST['novo_preco'];

    try {
        $stmt = $conn->prepare("UPDATE categorias SET nome_categoria = ?, preco = ? WHERE id = ?");
        $stmt->execute([$novo_nome_plano, $novo_preco, $id_plano]);
        echo "<p>Plano atualizado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao atualizar plano: " . $e->getMessage();
    }
}

// Buscar planos para exibir
$planos = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Cálculo do saldo atual
$total_atual = 0;

// Somar os preços dos planos associados aos usuários
$usuarios = $conn->query("SELECT u.categoria, c.preco FROM usuarios u JOIN categorias c ON u.categoria = c.id")->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $total_atual += $usuario['preco'];
}

// O saldo do mês anterior será zero se não houver usuários criados
$total_anterior = 0;

$usuarios_mes_anterior = $conn->query("SELECT SUM(c.preco) as total FROM usuarios u JOIN categorias c ON u.categoria = c.id WHERE MONTH(u.data_criacao) = MONTH(NOW()) - 1 AND YEAR(u.data_criacao) = YEAR(NOW())")->fetch(PDO::FETCH_ASSOC);
$total_anterior = $usuarios_mes_anterior['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Gerenciar Planos</title>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="home.php">📊INICIO📊</a></li>
                <li><a href="criar_usuario.php">Criar 👤Usuário👤</a></li>
                <li><a href="listar_usuarios.php">Listar 👤Usuários👤</a></li>
                <li><a href="categorias.php">Suport online 🟢</a></li>
                <li><a href="suport.php">Gerenciar 📋Planos📋</a></li>
                <li><a href="login.php"> ❌SAIR❌</a></li> <!-- Novo botão de Planos -->
            </ul>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Gerenciar Planos</h1>

            <!-- Exibir saldos -->
            <div class="saldos">
                <h2>
    Saldo Atual: R$ 💰 <span id="saldoAtual" style="display: none;"><?php echo number_format($total_atual, 2, ',', '.'); ?></span>
    <button id="btnSaldoAtual" onclick="toggleSaldo('saldoAtual', 'btnSaldoAtual')">👁️</button>
</h2>
<h2>
    Saldo Mês Anterior: R$ 💰 <span id="saldoAnterior" style="display: none;"><?php echo number_format($total_anterior, 2, ',', '.'); ?></span>
    <button id="btnSaldoAnterior" onclick="toggleSaldo('saldoAnterior', 'btnSaldoAnterior')">👁️</button>
</h2>

<script>
function toggleSaldo(id, buttonId) {
    const saldoElement = document.getElementById(id);
    const buttonElement = document.getElementById(buttonId);
    if (saldoElement.style.display === 'none') {
        saldoElement.style.display = 'inline';
        buttonElement.textContent = '👁️'; // Olho aberto
        localStorage.setItem(id, 'visible'); // Salva estado como visível
    } else {
        saldoElement.style.display = 'none';
        buttonElement.textContent = '👁️‍🗨️'; // Olho fechado
        localStorage.setItem(id, 'hidden'); // Salva estado como oculto
    }
}

// Verifica estado ao carregar a página
window.onload = function() {
    const saldoAtualState = localStorage.getItem('saldoAtual');
    const saldoAnteriorState = localStorage.getItem('saldoAnterior');
    
    if (saldoAtualState === 'hidden') {
        document.getElementById('saldoAtual').style.display = 'none';
        document.getElementById('btnSaldoAtual').textContent = '👁️‍🗨️'; // Olho fechado
    } else {
        document.getElementById('saldoAtual').style.display = 'inline';
        document.getElementById('btnSaldoAtual').textContent = '👁️'; // Olho aberto
    }
    
    if (saldoAnteriorState === 'hidden') {
        document.getElementById('saldoAnterior').style.display = 'none';
        document.getElementById('btnSaldoAnterior').textContent = '👁️‍🗨️'; // Olho fechado
    } else {
        document.getElementById('saldoAnterior').style.display = 'inline';
        document.getElementById('btnSaldoAnterior').textContent = '👁️'; // Olho aberto
    }
}
</script>

            </div>

            <!-- Formulário para criar plano -->
            <form method="POST" action="">
                <h2>Criar Plano</h2>
                Nome do Plano: <input type="text" name="nome_plano" required>
                Preço: <input type="number" name="preco" step="0.01" required>
                <button type="submit" name="novo_plano">Criar Plano</button>
            </form>

            <!-- Listar Planos -->
            <h2>Planos Cadastrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Plano</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planos as $plano): ?>
                        <tr>
                            <td><?php echo $plano['id']; ?></td>
                            <td><?php echo $plano['nome_categoria']; ?></td>
                            <td>R$ <?php echo number_format($plano['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <!-- Formulário para editar plano -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_plano" value="<?php echo $plano['id']; ?>">
                                    <input type="text" name="novo_nome_plano" required placeholder="Novo Nome">
                                    <input type="number" name="novo_preco" step="0.01" required placeholder="Novo Preço">
                                    <button type="submit" name="editar_plano">Editar</button>
                                </form>
                                <!-- Formulário para remover plano -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_plano" value="<?php echo $plano['id']; ?>">
                                    <button type="submit" name="remover_plano" onclick="return confirm('Tem certeza que deseja remover este plano?')">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
