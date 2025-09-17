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
    $mail->Host       = $env['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $env['SMTP_USERNAME'];
    $mail->Password   = $env['SMTP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $env['SMTP_PORT'];

    // Avsändare och mottagare
    $mail->setFrom($env['SMTP_USERNAME'], 'Entreprenörsbolaget');
    $mail->addAddress('din-mottagare@exempel.com'); // byt ut mot din egen adress

    // Innehåll från formuläret
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail->Subject = "Nytt meddelande från $name";
    $mail->Body    = "Namn: $name\nE-post: $email\n\nMeddelande:\n$message";

    // Skicka
    $mail->send();

    // JSON-respons till frontend
    echo json_encode(["success" => true, "message" => "Tack! Ditt meddelande har skickats."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Något gick fel: {$mail->ErrorInfo}"]);
}
