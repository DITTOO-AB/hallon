<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

header('Content-Type: application/json');

$env = parse_ini_file(__DIR__.'/.env');

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $env['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $env['SMTP_USERNAME'];
    $mail->Password   = $env['SMTP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $env['SMTP_PORT'];

    $mail->setFrom($env['SMTP_FROM'], $env['SMTP_FROM_NAME']);
    $mail->addAddress('din-mottagare@exempel.com');

    $name    = trim($_POST['name'] ?? '');
    $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = trim($_POST['message'] ?? '');

    $mail->Subject = "Nytt meddelande från $name";
    $mail->Body    = "Namn: $name\nE-post: $email\n\nMeddelande:\n$message";

    $mail->send();

    echo json_encode(["success" => true, "message" => "Tack! Ditt meddelande har skickats."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Något gick fel: {$mail->ErrorInfo}"]);
}
