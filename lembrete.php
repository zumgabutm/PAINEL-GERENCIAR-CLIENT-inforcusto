<?php
// Função para gerar o lembrete
$lembrete = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cliente = $_POST['nome_cliente'];
    $valor = $_POST['valor'];
    $nome_plano = $_POST['nome_plano'];
    $tipo_lembrete = $_POST['tipo_lembrete'];  // Tipo de lembrete selecionado

    // Verificar tipo de lembrete e ajustar campos obrigatórios
    if ($tipo_lembrete == 'manutencao') {
        // Para "manutenção", nome e valor não são necessários
        $nome_cliente = "";  // Desmarcar nome
        $valor = "";  // Desmarcar valor
    }

    // Gerar lembrete com base no tipo selecionado
    if ($tipo_lembrete == 'hospedagem') {
        $lembrete = "
        <strong>🚨 LEMBRETE IMPORTANTE - Inforcusto 🚨</strong><br><br>
        <strong>Prezado $nome_cliente,</strong><br><br>
        ⚠️ <strong>Atenção!</strong> Seu <strong>plano de hospedagem</strong> ($nome_plano) da <strong>Inforcusto</strong> <strong>venceu!</strong> ⚠️<br><br>
        ⏳ Para evitar que seu site <strong>saia do ar</strong>, é necessário efetuar o pagamento <strong>IMEDIATAMENTE!</strong><br><br>
        💰 <strong>Valor</strong>: R$ $valor<br>
        🔑 <strong>Chave PIX</strong>: 81985072087<br><br>
        <strong>Informações de Pagamento:</strong><br>
        <strong>Nome do Remetente</strong>: Rafael da Silva Lourenço<br>
        <strong>Banco</strong>: Banco Stone Pagamentos<br>
        <strong>Após efetuar o pagamento, envie o comprovante para ativação automática da sua assinatura.</strong><br><br>
        ❗ Se já realizou o pagamento, por favor, ignore este aviso.<br>
        ❗ Caso contrário, regularize o pagamento o quanto antes para evitar qualquer interrupção no serviço.<br><br>
        🔴 <strong>Não deixe para depois!</strong> 🔴<br><br>
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    if ($tipo_lembrete == 'tv') {
        $lembrete = "
        <strong>🚨 LEMBRETE IMPORTANTE - Inforflix 🚨</strong><br><br>
        <strong>Prezado $nome_cliente,</strong><br><br>
        ⚠️ <strong>Atenção!</strong> Seu plano de TV Inforflix está para expirar.<br><br>
        ⏳ Para não deixar de desfrutar dos seus conteúdos favoritos, pedimos que efetue o pagamento o quanto antes!<br><br>
        💰 <strong>Valor</strong>: R$ $valor<br>
        🔑 <strong>Chave PIX</strong>: 81985072087<br><br>
        <strong>Informações de Pagamento:</strong><br>
        <strong>Nome do Remetente</strong>: Rafael da Silva Lourenço<br>
        <strong>Banco</strong>: Banco Stone Pagamentos<br>
        <strong>Após efetuar o pagamento, envie o comprovante para ativação automática da sua assinatura.</strong><br><br>
        ❗ Se já realizou o pagamento, por favor, ignore este aviso.<br>
        ❗ Caso contrário, regularize o pagamento para continuar com sua assinatura.<br><br>
        🔴 <strong>Não perca seus programas!</strong> 🔴<br><br>
        <strong>Atenciosamente,</strong><br>
        <strong>Inforflix</strong><br>";
    }

    if ($tipo_lembrete == 'manutencao') {
        $lembrete = "
        <strong>🚧 NOTÍCIA DE MANUTENÇÃO 🚧</strong><br><br>
        <strong>Prezado $nome_cliente,</strong><br><br>
        ⚠️ Nosso servidor está em manutenção para melhorias de desempenho.<br><br>
        ⏳ O serviço ficará indisponível por um curto período.<br><br>
        🔄 Estamos trabalhando para restaurar a funcionalidade o mais rápido possível!<br><br>
        🔴 <strong>Obrigado pela paciência!</strong> 🔴<br><br>
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    if ($tipo_lembrete == 'sistema') {
        $lembrete = "
        <strong>✔️ SISTEMA EM FUNCIONAMENTO ✔️</strong><br><br>
        <strong>Prezado $nome_cliente,</strong><br><br>
        🚀 O sistema já está no ar e funcionando normalmente.<br><br>
        ✅ Todos os serviços estão operacionais.<br><br>
        <strong>Obrigado pela sua paciência durante a manutenção!</strong><br><br>
        🔴 <strong>Nosso serviço está pronto para atendê-lo!</strong> 🔴<br><br>
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    if ($tipo_lembrete == 'promocao') {
        $lembrete = "
        <strong>🎉 PROMOÇÃO INFORCUSTO - INDICAÇÃO 🎉</strong><br><br>
        <strong>Prezado $nome_cliente,</strong><br><br>
        🔥 **Promoção por tempo limitado!** 🔥<br><br>
        👥 Indique 2 amigos e ganhe 1 mês grátis!<br>
        💸 Indique 1 amigo e pague metade do preço no próximo mês!<br><br>
        🕒 Aproveite essa oportunidade e compartilhe com seus amigos agora mesmo!<br><br>
        💰 <strong>Valor com Desconto</strong>: R$ $valor<br><br>
        🔴 <strong>Não deixe passar essa chance! Faça suas indicações agora!</strong> 🔴<br><br>
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br><br>
        <strong>Sempre trazendo novidades pra voce:</strong><br>
        inforcusto o futuro digital<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Lembretes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: auto;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn:disabled {
            background-color: #ccc;
        }
        .splash {
            display: none;
            background-color: #28a745;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .copy-btn {
            background-color: #007bff;
            padding: 10px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .copy-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bem-vindo ao Gerador de Lembretes</h1>
        <form method="POST" action="">
            <label for="nome_cliente">Nome do Cliente (Opcional para Manutenção):</label><br>
            <input type="text" name="nome_cliente" id="nome_cliente" <?php echo ($_POST['tipo_lembrete'] == 'manutencao') ? '' : 'required'; ?>><br><br>

            <label for="valor">Valor (Opcional para Manutenção):</label><br>
            <input type="text" name="valor" id="valor" <?php echo ($_POST['tipo_lembrete'] == 'manutencao') ? '' : 'required'; ?>><br><br>

            <label for="nome_plano">Nome do Plano:</label><br>
            <input type="text" name="nome_plano" id="nome_plano" required><br><br>

            <label for="tipo_lembrete">Tipo de Lembrete:</label><br>
            <select name="tipo_lembrete" id="tipo_lembrete" required>
                <option value="hospedagem">Hospedagem</option>
                <option value="tv">Inforflix (TV)</option>
                <option value="manutencao">Manutenção de Servidor</option>
                <option value="sistema">Sistema em Funcionamento</option>
                <option value="promocao">Promoção de Indicação</option>
            </select><br><br>

            <button type="submit" class="btn">Gerar Lembrete</button>
        </form>

        <?php if ($lembrete != ""): ?>
            <div class="splash" id="lembreteGerado" style="display: block;">
                <p id="lembrete"><?= $lembrete ?></p>
                <button class="copy-btn" onclick="copiarLembrete()">Copiar Lembrete</button>
                <p id="msgCopia" style="display:none;">Lembrete Copiado!</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Função para copiar o conteúdo do lembrete
        function copiarLembrete() {
            var texto = document.getElementById("lembrete").innerText;
            navigator.clipboard.writeText(texto).then(function() {
                // Exibe a mensagem de cópia
                document.getElementById("msgCopia").style.display = "block";
            }, function() {
                alert("Erro ao copiar o lembrete!");
            });
        }
    </script>
</body>
</html>
