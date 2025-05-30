<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta com Timer</title>
    <style>
        #myAlert {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #e0e0e0;
            color: #333333;
            border: 1px solid #cccccc;
            border-radius: 8px;
            padding: 15px 25px;
            font-size: 1rem;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out;
        }

        #myAlert .alert-text {
            flex-grow: 1;
            margin-right: 20px;
        }

        #myAlert .btn-close {
            margin-left: 10px;
            color: #721c24;
            opacity: 1;
        }

        #myAlert .btn-close:hover {
            opacity: 0.75;
        }

        @keyframes slideIn {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(0);
            }
        }

        #myAlert {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_SESSION['mensagem'])):
    ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="myAlert">
            <span class="alert-text"><?= $_SESSION['mensagem']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php
        unset($_SESSION['mensagem']);
    endif;
    ?>

    <script>
        src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        // Define o tempo em milissegundos
        const timer = 4000;

        setTimeout(() => {
            const alert = document.getElementById('myAlert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, timer);
    </script>

</body>

</html>