<?php
include(__DIR__ . "/db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pegar dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar_senha'];
    $codigo = $_POST['codigo_autorizacao'];

    // 1. Verifica se as senhas coincidem
    if ($senha !== $confirmar) {
        header("Location: ../cadastro_admin.html?erro=senha");
        exit;
    }

    // 2. Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 3. Verifica código de autorização
    $sql = "SELECT comercio_id FROM codigos_autorizacao WHERE codigo = '$codigo' AND usado = FALSE LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $comercio_id = $row['comercio_id'];

        // 4. Verifica se email já existe
        $sqlCheck = "SELECT id FROM usuarios WHERE email = '$email'";
        $resultCheck = $conn->query($sqlCheck);
        
        if ($resultCheck->num_rows > 0) {
            header("Location: ../cadastro_admin.html?erro=email");
            exit;
        }

        // 5. Insere o novo administrador
        $sqlInsert = "INSERT INTO usuarios (nome, email, senha, comercio_id) 
                      VALUES ('$nome', '$email', '$senhaHash', '$comercio_id')";
        
        if ($conn->query($sqlInsert) === TRUE) {
            // 6. Marca código como usado
            $conn->query("UPDATE codigos_autorizacao SET usado = TRUE WHERE codigo = '$codigo'");
            
            // ✅ MOSTRAR PÁGINA DE SUCESSO BONITA
            mostrarPaginaSucesso($nome, $email);
            
        } else {
            echo "❌ Erro ao criar usuário: " . $conn->error;
        }
    } else {
        header("Location: ../cadastro_admin.html?erro=codigo");
        exit;
    }
} else {
    echo "❌ Método de requisição inválido!";
}

// Função para mostrar página de sucesso
function mostrarPaginaSucesso($nome, $email) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Cadastro Concluído | D'Barber Shop</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Redirecionar após 3 segundos
            setTimeout(function() {
                window.location.href = "../login.html";
            }, 3000);
        </script>
    </head>
    <body class="bg-neutral-900 text-gray-200 font-sans min-h-screen flex items-center justify-center">
        
        <!-- Modal de Sucesso -->
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-neutral-800 rounded-lg shadow-xl max-w-md w-full p-6 text-center">
                <!-- Ícone de Sucesso -->
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <!-- Título -->
                <h3 class="text-2xl font-bold text-green-400 mb-2">Cadastro Concluído!</h3>
                
                <!-- Mensagem -->
                <p class="text-gray-300 mb-4">
                    Conta criada com sucesso para <span class="font-semibold text-amber-400"><?php echo $nome; ?></span>
                    (<span class="text-amber-300"><?php echo $email; ?></span>)
                </p>
                
                <!-- Contagem regressiva -->
                <p class="text-sm text-gray-400 mb-4">
                    Redirecionando para login em <span id="countdown" class="font-bold text-amber-400">3</span> segundos...
                </p>
                
                <!-- Botão -->
                <button onclick="window.location.href='../login.html'" 
                        class="bg-amber-500 hover:bg-amber-600 text-black font-bold px-6 py-2 rounded-full transition duration-200">
                    Ir para Login Agora
                </button>
            </div>
        </div>

        <script>
            // Contagem regressiva
            let seconds = 3;
            const countdownElement = document.getElementById('countdown');
            
            const countdown = setInterval(function() {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdown);
                }
            }, 1000);
        </script>
    </body>
    </html>
    <?php
    exit; // Importante: encerra a execução após mostrar a página
}
?>