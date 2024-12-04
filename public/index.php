<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Inicializa a variável $checked para evitar avisos de "undefined"
$checked = '';

// Mensagem de sucesso (aparece após o cadastro bem-sucedido)
if (isset($_GET['success']) && $_GET['success'] === 'cadastro_sucesso') {
    echo '<div class="success-popup show">Cadastro realizado com sucesso! Já pode acessar.</div>';
    //Marcar o checkbox "Acessar"
    $checked = isset($_GET['success']) && $_GET['success'] === 'cadastro_sucesso' ? 'checked' : '';
}

// Mensagem de erro (quando as credenciais forem inválidas)
if (isset($_GET['error']) && $_GET['error'] === 'credenciais_invalidas') {
    echo '<div class="error-popup show">Credenciais inválidas! Tente novamente.</div>';
    //Marcar o checkbox "Acessar"
    $checked = isset($_GET['error']) && $_GET['error'] === 'credenciais_invalidas' ? 'checked' : '';
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Precificador - Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script>
        setTimeout(function() {
            var popupSuccess = document.querySelector('.success-popup');
            if (popupSuccess) {
                popupSuccess.classList.remove('show');
            }

            var popupError = document.querySelector('.error-popup');
            if (popupError) {
                popupError.classList.remove('show');
            }
        }, 4000);
    </script>

<body class="body-login">
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true" <?php echo $checked; ?>>

        <div class="signup">
            <form action="../src/cadastrar_usuario.php" method="POST">
                <label for="chk" aria-hidden="true">Registrar-se</label>
                <input type="text" name="nome_usuario" placeholder="Usuário" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit">Registrar</button>
            </form>
        </div>
        
        <div class="login">
            <form action="../src/autenticar.php" method="POST">
                <label for="chk" aria-hidden="true">Acessar</label>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>

</html>