<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ladda PHPMailer-klasser
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Läs in .env
$env = parse_ini_file(__DIR__.'/.env');

// Skapa nytt mail-objekt
$mail = new PHPMailer(true);

try {
    // Serverinställningar
    $mail->isSMTP();
    $mail->Host       = $env['SMTP_HOST'];      // t.ex. secure.emailsrvr.com
    $mail->SMTPAuth   = true;
    $mail->Username   = $env['SMTP_USERNAME'];  // din Rackspace-mail
    $mail->Password   = $env['SMTP_PASSWORD'];  // lösenordet
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $env['SMTP_PORT'];      // 587

    // Avsändare och mottagare
    $mail->setFrom($env['SMTP_FROM'], $env['SMTP_FROM_NAME']);
    $mail->addAddress('din-mottagare@exempel.com'); // t.ex. din privata mejl

    // Innehåll från formuläret
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail->Subject = "Nytt meddelande från $name";
    $mail->Body    = "Namn: $name\nE-post: $email\n\nMeddelande:\n$message";

    $mail->send();

    echo json_encode(["success" => true, "message" => "Tack! Ditt meddelande har skickats."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Något gick fel: {$mail->ErrorInfo}"]);
}
