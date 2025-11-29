<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$mensagem = $_POST['mensagem'];

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'Clinic.h3lp@gmail.com';
    $mail->Password = 'jrpk drqm rged ryng';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('Clinic.h3lp@gmail.com', 'contato');

    $mail->addAddress('Clinic.h3lp@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Nova mensagem enviada pelo formulario do site';

    $mail->Body = "
        <h2>Nova mensagem enviada pelo Site Clinic Help</h2>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>E-mail:</strong> {$email}</p>
        <p><strong>Mensagem:</strong><br>{$mensagem}</p>
        <hr>
        <p style='font-size:12px;color:#888'>Enviado automaticamente pelo formulário do site.</p>
    ";

    $mail->send();
    header("Location: contato.php");

} catch (Exception $e) {
    echo "Falha ao enviar mensagem. Erro: {$mail->ErrorInfo}";
}
?>
