<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Verificar se uma nova categoria foi enviada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_categoria'])) {
    $nova_categoria = $_POST['nova_categoria'];
    $preco_categoria = $_POST['preco_categoria']; // Campo para o preço da categoria
    try {
        $stmt = $conn->prepare("INSERT INTO categorias (nome_categoria, preco) VALUES (?, ?)");
        $stmt->execute([$nova_categoria, $preco_categoria]);
        echo "<p>Categoria criada com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao criar categoria: " . $e->getMessage();
    }
}

// Remover categoria
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_categoria'])) {
    $id_categoria = $_POST['id_categoria'];
    try {
        // Remover usuários associados
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE categoria = ?");
        $stmt->execute([$id_categoria]);

        // Remover a categoria
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        $stmt->execute([$id_categoria]);

        echo "<p>Categoria e usuários associados removidos com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao remover categoria: " . $e->getMessage();
    }
}

// Editar categoria
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_categoria'])) {
    $id_categoria = $_POST['id_categoria'];
    $nova_categoria = $_POST['nova_categoria'];
    $novo_preco = $_POST['novo_preco']; // Novo preço da categoria
    try {
        $stmt = $conn->prepare("UPDATE categorias SET nome_categoria = ?, preco = ? WHERE id = ?");
        $stmt->execute([$nova_categoria, $novo_preco, $id_categoria]);
        echo "<p>Categoria editada com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao editar categoria: " . $e->getMessage();
    }
}

// Buscar categorias para o dropdown
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Gerenciar Categorias</title>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="home.php">Início</a></li>
                <li><a href="listar_usuarios.php">Listar Usuários</a></li>
                <li><a href="criar_usuario.php">Criar Usuário</a></li>
                <li><a href="categorias.php">Gerenciar Categorias</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Gerenciar Categorias</h1>
            
            <!-- Formulário para criar categoria -->
            <form method="POST" action="">
                <h2>Criar Categoria</h2>
                Nome da Categoria: <input type="text" name="nova_categoria" required>
                Preço: <input type="number" name="preco_categoria" step="0.01" required> <!-- Campo para o preço -->
                <button type="submit">Criar Categoria</button>
            </form>

            <!-- Listar Categorias -->
            <h2>Categorias Cadastradas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Categoria</th>
                        <th>Preço</th> <!-- Adicionando coluna de preço -->
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?php echo $categoria['id']; ?></td>
                            <td><?php echo $categoria['nome_categoria']; ?></td>
                            <td>R$ <?php echo number_format($categoria['preco'], 2, ',', '.'); ?></td> <!-- Exibir preço -->
                            <td>
                                <!-- Formulário para editar categoria -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                    <input type="text" name="nova_categoria" required placeholder="Novo Nome">
                                    <input type="number" name="novo_preco" step="0.01" required placeholder="Novo Preço"> <!-- Novo campo para o preço -->
                                    <button type="submit" name="editar_categoria">Editar</button>
                                </form>
                                <!-- Formulário para remover categoria -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                    <button type="submit" name="remover_categoria" onclick="return confirm('Tem certeza que deseja remover esta categoria?')">Remover</button>
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
