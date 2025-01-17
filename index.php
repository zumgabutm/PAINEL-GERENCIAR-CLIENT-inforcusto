<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    if ($usuario === 'admin' && $senha === 'Rafap1@#') {
        $_SESSION['logado'] = true;
        header('Location: home.php');
    } else {
        echo "<p style='color:red;'>Usuário ou senha inválidos!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(270deg, #5a2d91 30%, #2c003e 70%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
        }

        form {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        img {
            width: 100px; /* Ajuste conforme necessário */
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        button {
            background-color: #6a1b9a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #4e1b6d;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px;
            }

            form {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <form method="POST">
        <img src="https://rszeus.shop/dbm/uploads/W.png" alt="Logo"> <!-- Adicione sua logo aqui -->
        <h2>💰💰💰💰</h2>
        👤Usuário👤 <input type="text" name="usuario" required>
        🔒Senha🔒 <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
