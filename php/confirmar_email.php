<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include("conexao.php");

function hidden_inputs_from_array(array $arr, array $exclude = []) {
    $out = '';
    foreach ($arr as $k => $v) {
        if (in_array($k, $exclude, true)) continue;

        if (is_array($v)) {
            $val = htmlspecialchars(json_encode($v, JSON_UNESCAPED_UNICODE));
            $out .= "<input type='hidden' name='".htmlspecialchars($k)."[]' value='$val'>\n";
        } else {
            $val = htmlspecialchars($v);
            $out .= "<input type='hidden' name='".htmlspecialchars($k)."' value='$val'>\n";
        }
    }
    return $out;
}

if (isset($_POST['confirmar_codigo'])) {
    $codigoGerado = $_POST['codigo_gerado'] ?? '';
    $codigoDigitado = $_POST['codigo_digitado'] ?? '';

    if ($codigoGerado !== '' && hash_equals((string)$codigoGerado, (string)$codigoDigitado)) {

        echo "<!doctype html><html lang='pt-br'><head><meta charset='utf-8'><title>Redirecionando...</title></head><body>";
        echo "<p>✅ Código correto. Enviando dados para cadastro...</p>";

        echo "<form id='redir' method='POST' action='cadastro_usuario.php'>\n";
        echo hidden_inputs_from_array($_POST, ['codigo_gerado','codigo_digitado','confirmar_codigo']);
        echo "</form>\n";

        echo "<script>document.getElementById('redir').submit();</script>";
        echo "</body></html>";
        exit;

    } else {
        $mensagem = "❌ Código incorreto. Tente novamente.";
        $dados_orig = $_POST;
        unset($dados_orig['codigo_digitado']);
    }

} elseif (!empty($_POST)) {

    $dados_orig = $_POST;
    $email = trim($dados_orig['email'] ?? '');

    if (!$email) {
        echo "⚠️ Nenhum e-mail informado no POST.";
        exit;
    }

    $sql = "SELECT id FROM usuario WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mensagem = "❌ Já existe uma conta cadastrada com o e-mail <b>$email</b>.";
        $mostrar_form = false;
    } else {
        $mostrar_form = true;
    }

    if ($mostrar_form) {

        $codigo = (string) rand(100000, 999999);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'CLINIC.H3LP@gmail.com';
            $mail->Password = 'jrpk drqm rged ryng';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('CLINIC.H3LP@gmail.com', 'Clinic Help');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Código de Verificação - Clinic Help';
            $mail->Body = "
                <p>Olá,</p>
                <p>Seu código de verificação é: <strong>$codigo</strong></p>
                <p>Insira esse código no site para confirmar seu e-mail.</p>
                <p>Atenciosamente,<br>Equipe Clinic Help</p>
            ";

            $mail->send();
            $mensagem = "✅ Código enviado para $email.";

        } catch (Exception $e) {
            echo "❌ Erro ao enviar código: " . htmlspecialchars($mail->ErrorInfo);
            exit;
        }
    }

} else {
    echo "A página deve ser acessada via POST.";
    exit;
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Confirmação de E-mail</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f4f4f4;padding:30px;text-align:center}
    .card{display:inline-block;padding:20px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08)}
    input{padding:8px;width:240px;margin:8px 0}
    button{padding:8px 14px;margin-top:6px;cursor:pointer}
    .msg{font-weight:600;margin-bottom:12px}
  </style>
</head>
<body>
  <div class="card">
    <div class="msg"><?= isset($mensagem) ? $mensagem : '' ?></div>

    <?php if (!isset($mostrar_form) || $mostrar_form): ?>
    <form method="POST">
      <?php
        echo hidden_inputs_from_array($dados_orig ?? [], []);

        if (isset($codigo)) {
            echo "<input type='hidden' name='codigo_gerado' value='".htmlspecialchars($codigo)."'>\n";
        } elseif (isset($dados_orig['codigo_gerado'])) {
            echo "<input type='hidden' name='codigo_gerado' value='".htmlspecialchars($dados_orig['codigo_gerado'])."'>\n";
        }
      ?>

      <label for="codigo">Digite o código recebido por e-mail:</label><br>
      <input id="codigo" type="text" name="codigo_digitado" required autofocus>
      <br>
      <button type="submit" name="confirmar_codigo">Confirmar código</button>
    </form>
    <?php endif; ?>

    <p style="margin-top:12px;font-size:0.9em;color:#666">
      Se não recebeu, verifique lixo eletrônico ou reenvie o formulário.
    </p>
  </div>
  <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>
</body>
</html>
