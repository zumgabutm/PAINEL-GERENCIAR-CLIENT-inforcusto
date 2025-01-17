<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Obter categorias com seus preços
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Calcular o saldo atual
$total_atual = 0;

// Somar os preços das categorias associadas aos usuários
$usuarios = $conn->query("SELECT u.categoria, c.preco FROM usuarios u JOIN categorias c ON u.categoria = c.id")->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $total_atual += $usuario['preco'];
}

// O saldo do mês anterior será zero se não houver usuários criados
$total_anterior = 0;

// Buscar o total do mês anterior (caso haja necessidade futura)
$usuarios_mes_anterior = $conn->query("SELECT SUM(c.preco) as total FROM usuarios u JOIN categorias c ON u.categoria = c.id WHERE MONTH(u.data_criacao) = MONTH(NOW()) - 1 AND YEAR(u.data_criacao) = YEAR(NOW())")->fetch(PDO::FETCH_ASSOC);
$total_anterior = $usuarios_mes_anterior['total'] ?? 0;

// Exibir os saldos
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>PAINEL_infor_Custo</title>
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
                <li><a href="lembrete.php">Criar lembrete</a></li>
                <li><a href="v.php">Criar lembrete-V2</a></li>
                <li><a href="login.php"> ❌SAIR❌</a></li> <!-- Novo botão de Planos -->
            </ul>
        </nav>
        <div class="main-content">
            <h1>PAINEL_infor_Custo</h1>

            <!-- Barra de Pesquisa -->
            <form method="GET" action="pesquisar.php" style="margin-bottom: 20px;">
                <input type="text" name="query" placeholder="Pesquisar categorias..." required>
                <button type="submit">🔍</button>
            </form>

            <div class="saldos">
                <h2>
                    Saldo Atual: R$ 💰 <span id="saldoAtual" style="display: none;"><?php echo number_format($total_atual, 2, ',', '.'); ?></span>
                    <button id="btnSaldoAtual" onclick="toggleSaldo('saldoAtual', 'btnSaldoAtual')">👁️</button>
                </h2>
                <h2>
                    Saldo Mês Anterior: R$ 💰 <span id="saldoAnterior" style="display: none;"><?php echo number_format($total_anterior, 2, ',', '.'); ?></span>
                    <button id="btnSaldoAnterior" onclick="toggleSaldo('saldoAnterior', 'btnSaldoAnterior')">👁️</button>
                </h2>
            </div>

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

            <h2>Categorias e Preços</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>🆔</th>
                        <th>Nome da 📊Categoria📊</th>
                        <th>💰Preço💰</th>
                        <th>⚙️Ações⚙️</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?php echo $categoria['id']; ?></td>
                            <td><?php echo $categoria['nome_categoria']; ?></td>
                            <td>R$ <?php echo number_format($categoria['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="gerenciar_categorias.php" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                    <button type="submit" name="editar_categoria">✏️Editar✏️</button>
                                </form>
                                <form method="POST" action="gerenciar_categorias.php" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                    <button type="submit" name="remover_categoria" onclick="return confirm('Tem certeza que deseja remover esta categoria?')">⛔Remover⛔</button>
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
