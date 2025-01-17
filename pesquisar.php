<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Obter a consulta da barra de pesquisa
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Consultar as categorias que correspondem à pesquisa
$stmt = $conn->prepare("SELECT * FROM categorias WHERE nome_categoria LIKE ?");
$stmt->execute(["%$query%"]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Resultados da Pesquisa</title>
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
                <li><a href="suport.php">SUPORT ONLINE 🟢</a></li>
                <li><a href="login.php">❌ SAIR</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Resultados da Pesquisa</h1>

            <h2>Resultados para: "<?php echo htmlspecialchars($query); ?>"</h2>
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
                    <?php if (count($categorias) > 0): ?>
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
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Nenhuma categoria encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
