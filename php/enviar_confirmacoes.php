<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include("conexao.php");

$sql = "SELECT a.id, a.status, a.dia, a.hora, p.email, p.Nome_paciente
        FROM agendados a
        JOIN pacientes p ON a.id_paciente = p.id
        WHERE a.status = 'confirmar'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    date_default_timezone_set('America/Sao_Paulo');
    $agora = new DateTime();

    while ($row = $result->fetch_assoc()) {
    $id_agendamento = $row['id'];

    if (isset($row['email_enviado']) && $row['email_enviado'] == 1) {
        continue;
    }

    $email = $row['email'];
    $nome = $row['Nome_paciente'];
    $data_consulta_str = $row['dia'] . ' ' . $row['hora'];
    $data_consulta = DateTime::createFromFormat('Y-m-d H:i:s', $data_consulta_str);

    if (!$data_consulta) {
        $data_consulta = DateTime::createFromFormat('Y-m-d H:i', $data_consulta_str);
    }

    if (!$data_consulta) {
        echo "⚠️ Data/hora inválida para o agendamento ID $id_agendamento<br>";
        continue;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'CLINIC.H3LP@gmail.com';
        $mail->Password = '*********';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom('CLINIC.H3LP@gmail.com', 'Clinic Help');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Presença - Clinic Help';
        $mail->Body = "
            <p>Olá, <strong>$nome</strong>!</p>
            <p>Sua consulta está marcada para <strong>{$row['dia']} às {$row['hora']}</strong> e precisa de confirmação.</p>
            <p>Por favor, <a href='http://localhost/CLINIC%20HELP2.1/php/login.php'>entre em nosso site</a> e confirme sua presença.</p>
            <p>Atenciosamente,<br>Equipe Clinic Help</p>
        ";

        $mail->send();
        echo "📨 E-mail enviado para $nome ($email)<br>";

        $update = $conn->prepare("UPDATE agendados SET email_enviado = 1 WHERE id = ?");
        $update->bind_param("i", $id_agendamento);
        $update->execute();
        $update->close();

    } catch (Exception $e) {
        echo "Erro ao enviar e-mail para $nome: {$mail->ErrorInfo}<br>";
    }
}
} else {
    echo "Nenhum paciente com status 'confirmar' encontrado.";
}

?>

