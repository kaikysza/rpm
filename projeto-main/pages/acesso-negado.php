<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
            color: #333;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 40px;
            max-width: 400px;
            background-color: #ffffff;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            animation: fadeIn 0.5s ease-in-out;
            transform: scale(1.05);
        }

        .icon {
            font-size: 3em;
            color: #dc3545;
            margin-bottom: 20px;
            animation: shake 0.6s ease-in-out;
        }

        h1 {
            color: #dc3545;
            font-size: 2em;
            margin-bottom: 15px;
        }

        p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        a {
            display: inline-block;
            padding: 12px 24px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.3);
        }

        a:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>Acesso Negado</h1>
        <p>Você não tem permissão para acessar esta página. Por favor, verifique suas credenciais ou entre em contato com o administrador do sistema.</p>
        <a href="inicio.php">Voltar ao Início</a>
    </div>
</body>

</html>