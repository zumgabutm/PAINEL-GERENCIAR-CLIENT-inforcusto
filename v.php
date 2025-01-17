<?php
// Inicializar o lembrete como vazio
$lembrete = "";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se a chave 'tipo_lembrete' existe no array $_POST
    $nome_cliente = $_POST['nome_cliente'] ?? ''; // Valor padrão vazio se não for enviado
    $valor = $_POST['valor'] ?? ''; // Valor padrão vazio se não for enviado
    $nome_plano = $_POST['nome_plano'] ?? ''; // Valor padrão vazio se não for enviado
    $tipo_lembrete = $_POST['tipo_lembrete'] ?? ''; // Se não existir, atribui valor vazio

    // Garantir que o valor seja um número (float)
    $valor = str_replace(',', '.', $valor);  // Substitui vírgula por ponto
    $valor = floatval($valor);  // Converte para float

    // Gerar lembrete com base no tipo selecionado
    if ($tipo_lembrete == 'hospedagem') {
        $lembrete = "
        <strong>💰Surpresa!Seu PIX🏅🎊 Parabéns! Aproveite🎁</strong><br><br>
        <strong>🧑‍💼 Cliente:</strong> <strong>$nome_cliente</strong><br><br>
        <strong>⚠️ Atenção!</strong> Seu <strong>plano de hospedagem</strong> ($nome_plano) da <strong>Inforcusto</strong> <strong>Vence hoje</strong> ⚠️<br><br>
        <strong>⏳ Para evitar que seu site <strong>saia do ar</strong>, é necessário efetuar o pagamento <strong>IMEDIATAMENTE!</strong></strong><br><br>
        
        <strong>💰 Valor:</strong> R$ <strong>$valor</strong><br>
        <strong>🔑 Chave PIX:</strong> 81985072087<br><br>
        
        <strong>📋 Dados do pagamento</strong><br>
        <strong>📝 </strong> Rafael da Silva Lourenço<br>
        <strong>🏦 </strong> Banco Stone Pagamentos<br><br>
        <strong>⚡ Após efetuar o pagamento, envie o comprovante para ativação automática da sua assinatura.</strong><br><br>
        
        <strong>❗ Se já realizou o pagamento, por favor, ignore este aviso.</strong><br>
        <strong>❗ Caso contrário, regularize o pagamento o quanto antes para evitar qualquer interrupção no serviço.</strong><br><br>
        
        <strong>🔴 Não deixe para depois!</strong> 🔴<br><br>
        
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    // Lembrete de manutenção
    if ($tipo_lembrete == 'manutencao') {
        $lembrete = "
        <strong>⚙️🚨MANUTENÇÃO DO SISTEMA🚨Inforcusto ⚙️</strong><br><br>
        <strong>🧑‍💼 Cliente:</strong> <strong>$nome_cliente</strong><br><br>
        <strong>⚠️ Atenção!</strong> Estamos realizando uma <strong>manutenção do sistema</strong> e seu acesso será temporariamente interrompido.<br><br>
        <strong>🔧 A manutenção está programada para ser concluída em breve, e seu serviço será normalizado automaticamente.</strong><br><br>
        <strong>⚡ Pedimos desculpas por qualquer inconveniente.</strong><br><br>
        <strong>🔴 Agradecemos a sua compreensão!</strong> 🔴<br><br>
        
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    // Lembrete de promoção
    if ($tipo_lembrete == 'promocao') {
        $lembrete = "
        <strong>🎉 OFERTA IMPERDÍVEL - Inforcusto 🎉</strong><br><br>
        <strong>🧑‍💼 Cliente:</strong> <strong>$nome_cliente</strong><br><br>
        <strong>⚠️ Atenção! Você foi selecionado para uma promoção especial!</strong><br><br>
        <strong>🎁 Aproveite agora e ganhe <strong>DESCONTO</strong> em seu próximo pagamento!</strong><br><br>
        
        <strong>💰 Valor original:</strong> R$ <strong>$valor</strong><br>
        <strong>🎉 Desconto especial:</strong> <strong>20%</strong><br>
        <strong>💵 Valor com desconto:</strong> <strong>R$ ". number_format($valor * 0.80, 2, ',', '.') ."</strong><br><br>

        <strong>🔑 Chave PIX:</strong> 81985072087<br><br>
        
        <strong>📋 Dados do pagamento</strong><br>
        <strong>📝 </strong> Rafael da Silva Lourenço<br>
        <strong>🏦 </strong> Banco Stone Pagamentos<br><br>
        <strong>⚡ Aproveite e não perca essa oportunidade!</strong><br><br>
        
        <strong>❗ Se já realizou o pagamento, por favor, ignore este aviso.</strong><br>
        <strong>❗ Caso contrário, regularize o pagamento para garantir seu desconto!</strong><br><br>
        
        <strong>🔴 Não deixe para depois!</strong> 🔴<br><br>
        
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    // Lembrete de plano IPTV Inforflix
    if ($tipo_lembrete == 'iptv_inforflix') {
        $lembrete = "
        <strong>💰Surpresa!Seu PIX🏅🎊 Parabéns! Aproveite🎁</strong><br><br>
        <strong>🧑‍💼 Cliente:</strong> <strong>$nome_cliente</strong><br><br>
        <strong>⚠️ Atenção! Seu plano de IPTV Inforflix esta perto de vencer! ⚠️</strong><br><br>
        <strong>⏳ Para garantir o acesso contínuo ao serviço, é necessário efetuar o pagamento <strong>IMEDIATAMENTE!</strong></strong><br><br>
        
        <strong>💰 Valor:</strong> R$ <strong>$valor</strong><br>
        <strong>🔑 Chave PIX:</strong> 81985072087<br><br>
        
        <strong>📋 Dados do pagamento</strong><br>
        <strong>📝 </strong> Rafael da Silva Lourenço<br>
        <strong>🏦 </strong> Banco Stone Pagamentos<br><br>
        <strong>⚡ Após efetuar o pagamento, envie o comprovante para ativação automática da sua assinatura.</strong><br><br>
        
        <strong>❗ Se já realizou o pagamento, por favor, ignore este aviso.</strong><br>
        <strong>❗ Caso contrário, regularize o pagamento o quanto antes para evitar interrupção no serviço.</strong><br><br>
        
        <strong>🔴 Não deixe para depois!</strong> 🔴<br><br>
        
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }

    // Lembrete pessoal (novo tipo de lembrete)
    if ($tipo_lembrete == 'pessoal') {
        $lembrete = "
        <strong>💰Surpresa!Seu PIX🏅🎊 Parabéns! Aproveite🎁</strong><br><br>
        <strong>🧑‍💼 Devedor:</strong> <strong>$nome_cliente</strong><br><br>
        <strong>⚠️ Atenção!</strong> Você tem um <strong>PAGAMENTO</strong> ou <strong>serviço</strong> pendente <br><br>
        <strong>⏳ Para evitar maiores complicações, é necessário o pagamento ou regularização IMEDIATAMENTE!</strong><br><br>
        
        <strong>💰 Valor devido:</strong> R$ <strong>$valor</strong><br><br>
        
        <strong>🔑 Chave PIX:</strong> 81985072087<br><br>
        
        <strong>📋 Dados do pagamento</strong><br>
        <strong>📝 </strong> Rafael da Silva Lourenço<br>
        <strong>🏦 </strong> Banco Stone Pagamentos<br><br>
        
        <strong>❗ Se já pagou, desconsidere este aviso. Caso contrário, regularize a pendência o quanto antes!</strong><br><br>
        
        <strong>🔴 *Não deixe para depois*!</strong> 🔴<br><br>
        
        <strong>Atenciosamente,</strong><br>
        <strong>Inforcusto</strong><br>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>⚠️Lembrete⚠️</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #00b3b3, #003366);
            color: #00ff00;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1, h2 {
            color: #00ff00;
            text-align: center;
        }
        .content {
            max-width: 600px;
            padding: 20px;
            background: #111;
            border-radius: 10px;
            border: 2px solid #00ff00;
            text-align: center;
        }
        .inputField {
            background-color: #222;
            color: #00ff00;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #00ff00;
            width: calc(100% - 24px);
            margin: 10px 0;
            font-size: 18px;
            transition: all 0.3s;
        }
        .inputField:focus {
            background-color: #111;
            border-color: #00cc00;
            outline: none;
        }
        select {
            background-color: #222;
            color: #00ff00;
            border-radius: 5px;
            padding: 10px;
            font-size: 18px;
            border: 2px solid #00ff00;
            width: 100%;
            margin: 10px 0;
            transition: all 0.3s;
        }
        select:focus {
            background-color: #111;
            border-color: #00cc00;
            outline: none;
        }
        .button {
            background-color: #00ff00;
            color: #111;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
            transition: all 0.3s;
        }
        .button:hover {
            background-color: #00cc00;
            transform: scale(1.1);
        }
        #lembrete {
            display: none;
            margin-top: 20px;
            color: #00ff00;
            font-size: 16px;
            padding: 20px;
            background: #222;
            border-radius: 10px;
            border: 2px solid #00ff00;
        }
        #copyMessage {
            display: none;
            color: #00ff00;
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>⚠️ Lembrete importante! ⚠️</h1>
        <form method="POST">
            <input type="text" class="inputField" name="nome_cliente" placeholder="Nome do Cliente" required><br><br>
            <input type="text" class="inputField" name="valor" placeholder="Valor" required><br><br>
            <input type="text" class="inputField" name="nome_plano" placeholder="Nome do Plano" required><br><br>
            <select class="inputField" name="tipo_lembrete" required>
                <option value="hospedagem">🗄️Hospedagem</option>
                <option value="manutencao">🛠️Manutenção</option>
                <option value="promocao">💸Promoção</option>
                <option value="iptv_inforflix">📺IPTV Inforflix</option>
                <option value="pessoal">🧑‍💼Lembrete Pessoal</option>
            </select><br><br>
            <button class="button" type="submit">💡Criar💡</button>
        </form>

        <div id="lembrete">
            <button class="button" id="copyButton" onclick="copyToClipboard()">Copiar</button>
            <h2>Lembrete Gerado!</h2>
            <p id="lembreteTexto"><?php echo $lembrete; ?></p>
            <div id="copyMessage">Dados copiados!</div>
        </div>
    </div>

    <script>
        // Exibir lembrete e botão de copiar após gerar
        <?php if ($lembrete): ?>
            document.getElementById('lembrete').style.display = 'block';
        <?php endif; ?>

        function copyToClipboard() {
            var textToCopy = document.getElementById('lembreteTexto').innerText;
            var textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            document.getElementById('copyMessage').style.display = 'block';
            setTimeout(function() {
                document.getElementById('lembrete').style.display = 'none';
                document.getElementById('copyMessage').style.display = 'none';
            }, 1500);
        }
    </script>
</body>
</html>
