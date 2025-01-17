<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Verificar se uma nova categoria foi enviada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_categoria'])) {
    $nova_categoria = $_POST['nova_categoria'] ?? null;
    $preco_categoria = $_POST['preco_categoria'] ?? null; // Novo campo para preço
    if ($nova_categoria && $preco_categoria) {
        try {
            $stmt = $conn->prepare("INSERT INTO categorias (nome_categoria, preco) VALUES (?, ?)");
            $stmt->execute([$nova_categoria, $preco_categoria]); // Inserindo o preço
            echo "<p>Categoria criada com sucesso!</p>";
        } catch (PDOException $e) {
            echo "Erro ao criar categoria: " . $e->getMessage();
        }
    } else {
        echo "<p>Erro: Preencha todos os campos.</p>";
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
    $nova_categoria = $_POST['nova_categoria'] ?? null;
    $preco_categoria = $_POST['preco_categoria'] ?? null; // Novo campo para preço
    if ($nova_categoria && $preco_categoria) {
        try {
            $stmt = $conn->prepare("UPDATE categorias SET nome_categoria = ?, preco = ? WHERE id = ?");
            $stmt->execute([$nova_categoria, $preco_categoria, $id_categoria]); // Atualizando o preço
            echo "<p>Categoria editada com sucesso!</p>";
        } catch (PDOException $e) {
            echo "Erro ao editar categoria: " . $e->getMessage();
        }
    } else {
        echo "<p>Erro: Preencha todos os campos.</p>";
    }
}

// Buscar categorias para exibir
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Gerenciar Categorias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #343a40;
            color: white;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 200px;
            background-color: #212529;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-right: 20px;
        }

        .sidebar h2 {
            margin: 0 0 10px;
            color: #FFEA00;
        }

        .main-content {
            flex: 1;
            background-color: #343a40;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #FF4081;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #FFEA00;
        }

        form {
            background-color: #495057;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            background-color: #e9ecef;
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

        .table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #495057;
            color: #FFEA00;
        }

        .table tr:nth-child(even) {
            background-color: #343a40;
        }
    </style>
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
                <li><a href="login.php"> ❌SAIR❌</a></li>
            </ul>
        </nav>
        <div class="main-content">
            <h1>Gerenciar Categorias</h1>

            <!-- Formulário para criar categoria -->
            <form method="POST" action="">
                <h2>Criar Categoria</h2>
                Nome da Categoria: <input type="text" name="nova_categoria" required>
                Preço: <input type="number" name="preco_categoria" step="0.01" required>
                <button type="submit">Criar Categoria</button>
            </form>

            <!-- Listar Categorias -->
            <h2>Categorias Cadastradas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Categoria</th>
                        <th>Preço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?php echo $categoria['id']; ?></td>
                            <td><?php echo $categoria['nome_categoria']; ?></td>
                            <td>R$ <?php echo number_format($categoria['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?php echo $categoria['id']; ?>">
                                    <input type="text" name="nova_categoria" required placeholder="Novo Nome">
                                    <input type="number" name="preco_categoria" step="0.01" required placeholder="Novo Preço">
                                    <button type="submit" name="editar_categoria">Editar</button>
                                </form>
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
