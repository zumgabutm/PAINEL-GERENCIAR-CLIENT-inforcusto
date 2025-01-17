<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Obter categorias (planos) para o dropdown
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_vencimento = $_POST['data_vencimento']; // Data de vencimento selecionada
    $plano = $_POST['plano']; // ID do plano selecionado

    // Calcular a data de criação
    $data_criacao = date('Y-m-d');

    // Verificar se a categoria existe
    $stmt = $conn->prepare("SELECT COUNT(*) FROM categorias WHERE id = ?");
    $stmt->execute([$plano]);
    $categoria_existe = $stmt->fetchColumn();

    if ($categoria_existe) {
        try {
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, data_criacao, data_vencimento, categoria) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $data_criacao, $data_vencimento, $plano]);
            echo "<p style='color: green;'>Usuário criado com sucesso!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erro ao criar usuário: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Erro: A categoria selecionada não existe.</p>";
    }
}

// Exibir os saldos
$total_atual = 0;
$total_anterior = 0;

// Somar os preços das categorias associadas aos usuários
$usuarios = $conn->query("SELECT u.categoria, c.preco FROM usuarios u JOIN categorias c ON u.categoria = c.id")->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $total_atual += $usuario['preco'];
}

// O saldo do mês anterior será zero se não houver usuários criados
$usuarios_mes_anterior = $conn->query("SELECT SUM(c.preco) as total FROM usuarios u JOIN categorias c ON u.categoria = c.id WHERE MONTH(u.data_criacao) = MONTH(NOW()) - 1 AND YEAR(u.data_criacao) = YEAR(NOW())")->fetch(PDO::FETCH_ASSOC);
$total_anterior = $usuarios_mes_anterior['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Criar Usuário</title>
    <style>
        /* Estilos para o formulário de criação de usuário */
        body {
            font-family: Arial, sans-serif;
            background-color: #212529;
            color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-right: 20px;
        }

        .sidebar h2 {
            color: #FFEA00;
        }

        .main-content {
            flex: 1;
            background-color: #495057;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #FF4081;
            text-align: center;
            margin-bottom: 20px;
        }

        .saldos h2 {
            color: #4CAF50;
        }

        form {
            background-color: #6c757d;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #FFEA00;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            background-color: #343a40;
            color: #fff;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="home.php">📊INICIO📊</a></li>
                <li><a href="criar_usuario.php">+ NOVA VENDA</a></li>
                <li><a href="listar_usuarios.php">👥 VER / CLIENTE</a></li>
                <li><a href="categorias.php">📦 ADICIONAR PRODUTO</a></li>
                <li><a href="gerenciar_planos.php">SUPORTE ONLINE 🟢</a></li>
                <li><a href="index.php">❌ SAIR</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Criar 👤Usuário👤</h1>
            
            <div class="saldos">
                <h2>Saldo Atual: R$ 💰 <span id="saldoAtual" style="display: none;"><?php echo number_format($total_atual, 2, ',', '.'); ?></span>
                    <button id="btnSaldoAtual" onclick="toggleSaldo('saldoAtual', 'btnSaldoAtual')">👁️</button>
                </h2>
                <h2>Saldo Mês Anterior: R$ 💰 <span id="saldoAnterior" style="display: none;"><?php echo number_format($total_anterior, 2, ',', '.'); ?></span>
                    <button id="btnSaldoAnterior" onclick="toggleSaldo('saldoAnterior', 'btnSaldoAnterior')">👁️</button>
                </h2>
            </div>

            <form method="POST" action="">
                <label for="nome">NOME DO CLIENTE👤</label>
                <input type="text" name="nome" required>

                <label for="data_vencimento">Data de Vencimento 📅</label>
                <input type="date" name="data_vencimento" required>

                <label for="plano">ESCOLHA UM PRODUTO</label>
                <select name="plano" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>">
                            <?php echo $categoria['nome_categoria']; ?> - R$ <?php echo number_format($categoria['preco'], 2, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">CADASTRAR CLIENTE👤</button>
            </form>
        </div>
    </div>
</body>
</html>
