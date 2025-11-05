<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'seu-email@gmail.com';
    $mail->Password = 'sua-senha';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('seu-email@gmail.com', 'Seu Nome');
    $mail->addAddress('4552138706@estudante.sed.sc.gov.br', 'Destinatário');

    $mail->isHTML(true);
    $mail->Subject = 'Assunto';
    $mail->Body    = 'Corpo da mensagem em <b>HTML</b>';

    $mail->send();
    echo 'Mensagem enviada com sucesso';
} catch (Exception $e) {
    echo "Falha ao enviar mensagem. Erro: {$mail->ErrorInfo}";
}
?>
