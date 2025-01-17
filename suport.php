<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        label {
            margin: 10px 0;
            display: block;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background: #00c853;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background: #009624;
        }
    </style>
    <script>
        function enviarWhatsApp(event) {
            event.preventDefault(); // Evita o envio padrão do formulário
            const erroEspecifico = document.getElementById('erroEspecifico').value;

            // Formata a mensagem para o WhatsApp
            const mensagem = `Suport sistema de gerenciar:\nDescrição do problema: ${encodeURIComponent(erroEspecifico)}`;
            const numero = "+5581987832868"; // Número corrigido

            // URL do WhatsApp
            const whatsappUrl = `https://api.whatsapp.com/send?phone=${numero}&text=${mensagem}`;

            // Redireciona para o WhatsApp
            window.open(whatsappUrl, '_blank');
        }
    </script>
</head>
<body>
    <h2></h2>
    <form onsubmit="enviarWhatsApp(event)">
        <label for="erroEspecifico">Descreva em poucas palavras o seu problema:</label>
        <textarea name="erroEspecifico" id="erroEspecifico" rows="4" required></textarea>

        <input type="submit" value="Enviar Suporte">
    </form>
</body>
</html>
