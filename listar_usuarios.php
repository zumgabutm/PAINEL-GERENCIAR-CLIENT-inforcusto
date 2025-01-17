<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit();
}

include 'conexao.php'; // Incluindo a conexão com o banco de dados

// Função para atualizar o status dos usuários
function atualizarStatusUsuarios($conn) {
    // Buscar usuários do banco de dados
    $query = $conn->query("SELECT * FROM usuarios");
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {
        $hoje = new DateTime();
        $vencimento = new DateTime($usuario['data_vencimento']);

        if ($hoje >= $vencimento) {
            // Marcar como vencido
            $stmt = $conn->prepare("UPDATE usuarios SET status = 'inativo' WHERE id = ?");
            $stmt->execute([$usuario['id']]);
        } elseif ($hoje->diff($vencimento)->days <= 2) {
            // Marcar como quase vencido
            $stmt = $conn->prepare("UPDATE usuarios SET status = 'quase vencido' WHERE id = ?");
            $stmt->execute([$usuario['id']]);
        } else {
            // Marcar como ativo
            $stmt = $conn->prepare("UPDATE usuarios SET status = 'ativo' WHERE id = ?");
            $stmt->execute([$usuario['id']]);
        }
    }
}

// Atualizar o status dos usuários ao acessar a página
atualizarStatusUsuarios($conn);

// Buscar usuários para exibir (Usando LEFT JOIN para incluir todos os usuários)
$usuarios = $conn->query("SELECT u.*, c.nome_categoria, c.preco FROM usuarios u LEFT JOIN categorias c ON u.categoria = c.id")->fetchAll(PDO::FETCH_ASSOC);

// Verificação de erro para garantir que a consulta está retornando resultados
if (!$usuarios) {
    echo "<p>Erro ao buscar usuários.</p>";
} else {
    $total_usuarios = count($usuarios); // Contar o número de usuários
}

// Remover usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    try {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        echo "<p>Usuário removido com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao remover usuário: " . $e->getMessage();
    }
}

// Renovar usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['renovar_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    $nova_data_vencimento = date('Y-m-d', strtotime('+1 month')); // Exemplo: renovando por 1 mês
    try {
        $stmt = $conn->prepare("UPDATE usuarios SET data_vencimento = ? WHERE id = ?");
        $stmt->execute([$nova_data_vencimento, $id_usuario]);
        echo "<p>Usuário renovado com sucesso!</p>";
    } catch (PDOException $e) {
        echo "Erro ao renovar usuário: " . $e->getMessage();
    }
}

// Ordenar usuários: vencendo, vencidos, ativos
usort($usuarios, function($a, $b) {
    $hoje = new DateTime();
    $vencimentoA = new DateTime($a['data_vencimento']);
    $vencimentoB = new DateTime($b['data_vencimento']);
    
    // Definir prioridade
    $statusPrioridade = [
        'quase vencido' => 1,
        'inativo' => 2,
        'ativo' => 3,
    ];

    // Comparar status
    return $statusPrioridade[$a['status']] <=> $statusPrioridade[$b['status']] ?: $vencimentoA <=> $vencimentoB;
});
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Listar 👤Usuários👤</title>
    <style>
        .ativo { color: green; }
        .inativo { color: red; }
        .perto-vencimento { color: orange; }
        .alerta { font-weight: bold; }
        #search-bar {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
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
            <h1>Listar 👤Usuários👤</h1>
            <h2>Total de 👤Usuários👤: <?php echo $total_usuarios; ?></h2>
            <input type="text" id="search-bar" placeholder="Buscar usuário...">
            <table class="table">
                <thead>
                    <tr>
                        <th>🆔</th>
                        <th>Nome do 👤Usuário👤</th>
                        <th>✅Status✅</th>
                        <th>📊Categoria📊</th>
                        <th>💲Preço💲</th>
                        <th>📅Data de Vencimento📅</th>
                        <th>⚙️Ações⚙️</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <?php
                        // Determinar a classe a ser aplicada com base na data de vencimento
                        $vencimento = new DateTime($usuario['data_vencimento']);
                        $hoje = new DateTime();
                        $dias_restantes = $hoje->diff($vencimento)->days;

                        if ($usuario['status'] === 'inativo') {
                            $classe = 'inativo';
                            $status_text = 'Inativo';
                        } elseif ($dias_restantes <= 2) {
                            $classe = 'perto-vencimento';
                            $status_text = "Perto de Vencer ($dias_restantes dias)";
                        } else {
                            $classe = 'ativo';
                            $status_text = 'Ativo';
                        }
                        ?>
                        <tr class="link">
                            <td><?php echo $usuario['id']; ?></td>
                            <td class="<?php echo $classe; ?>"><?php echo $usuario['nome']; ?></td>
                            <td class="<?php echo $classe; ?> alerta"><?php echo $status_text; ?></td>
                            <td><?php echo $usuario['nome_categoria'] ?: 'N/A'; ?></td>
                            <td><?php echo isset($usuario['preco']) ? 'R$' . number_format($usuario['preco'], 2, ',', '.') : 'N/A'; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['data_vencimento'])); ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" name="renovar_usuario">🔄Renovar🔄</button>
                                </form>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                    <button type="submit" name="remover_usuario" onclick="return confirm('Tem certeza que deseja remover este usuário?')">⛔Remover⛔</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchBar = document.getElementById("search-bar");
            const links = document.querySelectorAll(".link");

            searchBar.addEventListener("input", function() {
                const searchTerm = searchBar.value.toLowerCase();

                links.forEach(function(link) {
                    const text = link.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        link.style.display = "flex";
                    } else {
                        link.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>
</html>
