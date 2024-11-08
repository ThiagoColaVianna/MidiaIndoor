<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden; /* Remove a barra de rolagem */
            background-image: url('/images/cardapio.jpg'); /* Caminho relativo */
            background-size: cover; /* Mantém o tamanho original da imagem */
            background-position: center; /* Centraliza a imagem */
            background-repeat: no-repeat; /* Evita que a imagem se repita */
            font-family: 'Arial', sans-serif;
        }

        h1 {
            font-size: 2.5rem; /* Ajuste o tamanho do título */
            color: white;
            margin: 20px 0;
            text-align: center;
        }

        #container {
            display: flex;
            width: 80%;
            justify-content: space-between;
            flex-grow: 1; /* Ocupa o espaço disponível */
            overflow: hidden; /* Garante que não haja rolagem */
            position: relative; /* Para posicionar o overlay */
        }

        #itens_cardapio, #promocao_dia {
            width: 48%; /* Ajuste o tamanho das colunas */
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Garante que não haja rolagem */
        }

        .item-cardapio, .promocao-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0; /* Diminuição do espaço entre itens */
            border-bottom: 1px solid #ccc;
        }

        .item-cardapio p, .promocao-item p {
            margin: 0;
        }

        .item-cardapio strong, .promocao-item strong {
            font-size: 1.4rem; /* Aumento do tamanho das letras */
            font-weight: bold;
            color: #444;
        }

        .item-descricao {
            font-size: 1rem; /* Aumento do tamanho das letras */
            color: #777;
            margin-top: 5px;
        }

        .item-preco, .promocao-preco {
            font-size: 1.4rem; /* Aumento do tamanho das letras */
            color: green;
            font-weight: bold;
            text-align: right;
        }

        /* Estilo para itens em promoção */
        .item-cardapio.promocao {
            text-decoration: line-through red; /* Traço vermelho em cima do item */
            color: #ccc; /* Mudança de cor para indicar que está em promoção */
        }

        #promocao_dia {
            margin-left: 20px; /* Espaçamento entre colunas */
        }

        /* Animação para destacar itens em promoção */
        .promocao-item {
            animation: highlight 1s ease infinite alternate; /* Animação */
        }

        @keyframes highlight {
            0% { background-color: rgba(255, 215, 0, 0.5); }
            100% { background-color: rgba(255, 215, 0, 1); }
        }

        /* Loader */
        .loader {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 8px solid #d1914b;
            box-sizing: border-box;
            --c: no-repeat radial-gradient(farthest-side, #d64123 94%,#0000);
            --b: no-repeat radial-gradient(farthest-side, #000 94%,#0000);
            background:
                var(--c) 11px 15px,
                var(--b) 6px 15px,
                var(--c) 35px 23px,
                var(--b) 29px 15px,
                var(--c) 11px 46px,
                var(--b) 11px 34px,
                var(--c) 36px 0px,
                var(--b) 50px 31px,
                var(--c) 47px 43px,
                var(--b) 31px 48px,
                #f6d353;
            background-size: 15px 15px, 6px 6px;
            animation: l4 3s infinite;
            margin: 20px auto; /* Centraliza o loader */
        }

        @keyframes l4 {
            0%     { -webkit-mask: conic-gradient(#0000 0, #000 0) }
            16.67% { -webkit-mask: conic-gradient(#0000 60deg, #000 0) }
            33.33% { -webkit-mask: conic-gradient(#0000 120deg,#000 0) }
            50%    { -webkit-mask: conic-gradient(#0000 180deg,#000 0) }
            66.67% { -webkit-mask: conic-gradient(#0000 240deg,#000 0) }
            83.33% { -webkit-mask: conic-gradient(#0000 300deg,#000 0) }
            100%   { -webkit-mask: conic-gradient(#0000 360deg,#000 0) }
        }

        /* Estilo para a mensagem */
        .loading-message {
            text-align: center;
            color: black;
            font-size: 1.5rem; /* Ajuste o tamanho da fonte conforme necessário */
            animation: fade 3s infinite; /* Animação de aparecimento e desaparecimento */
        }

        @keyframes fade {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Cardápio</h1>
    <div id="container">
        <div id="itens_cardapio">
            <h2 style="text-align:center">Itens do Cardápio</h2>
            <?php
            include 'db_connect.php';
            $result = $conn->query("SELECT * FROM itens_cardapio");
            $promocao_ids = [];

            $result_promocao = $conn->query("SELECT item_id FROM promocoes");
            while ($promo_row = $result_promocao->fetch_assoc()) {
                $promocao_ids[] = $promo_row['item_id'];
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $is_promocao = in_array($row['id'], $promocao_ids);
                    echo "<div class='item-cardapio" . ($is_promocao ? " promocao" : "") . "'>";
                    echo "<div>";
                    echo "<p><strong>" . $row['nome'] . "</strong></p>";
                    if (!empty($row['descricao'])) {
                        echo "<p class='item-descricao'>" . $row['descricao'] . "</p>";
                    }
                    echo "</div>";
                    echo "<p class='item-preco'>R$ " . $row['preco'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='loader'></div>"; // Loader
                echo "<p class='loading-message'>Trabalhando em novas promoções...</p>"; // Mensagem centralizada com animação
            }
            ?>
        </div>

        <div id="promocao_dia">
            <h2 style="text-align:center">Promoção do Dia</h2>
            <?php
            $result = $conn->query("
                SELECT ic.nome, ic.preco, p.preco_promocional 
                FROM promocoes p 
                JOIN itens_cardapio ic ON p.item_id = ic.id
            ");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='promocao-item'>";
                    echo "<p><strong>" . $row['nome'] . "</strong> - Preço Original: <s>R$" . $row['preco'] . "</s></p>";
                    echo "<p class='promocao-preco'>R$" . $row['preco_promocional'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='loader'></div>"; // Loader
                echo "<p class='loading-message'>Trabalhando em novas promoções...</p>"; // Mensagem centralizada com animação
            }
            ?>
        </div>
    </div>

    <script>
        setInterval(function() {
            location.reload();
        }, 3000);
    </script>
</body>
</html>
