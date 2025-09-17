<?php
// ----- Error reporting -----
ini_set('display_errors', 0); // Sätt till 1 för utveckling
ini_set('log_errors', 1);
error_reporting(E_ALL);

// ----- Namespaces för PHPMailer -----
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ----- Ladda PHPMailer-klasser -----
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// ----- Läs in .env -----
$envFile = __DIR__.'/.env';
if (!file_exists($envFile)) {
    echo json_encode([
        "success" => false,
        "message" => "Miljöfilen saknas."
    ]);
    exit;
}

$env = parse_ini_file($envFile);

// ----- Skapa nytt mail-objekt -----
$mail = new PHPMailer(true);

try {
    // ----- SMTP-inställningar för Rackspace -----
    $mail->isSMTP();
    $mail->Host       = $env['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $env['SMTP_USERNAME'];
    $mail->Password   = $env['SMTP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $env['SMTP_PORT'];

    // ----- Avsändare och mottagare -----
    $mail->setFrom($env['SMTP_FROM'], $env['SMTP_FROM_NAME']);
    $mail->addAddress($env['SMTP_FROM']); // Du kan byta till annan mottagare

    // ----- Ta emot data från formuläret -----
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            "success" => false,
            "message" => "Alla fält måste fyllas i."
        ]);
        exit;
    }

    $mail->Subject = "Nytt meddelande från $name";
    $mail->Body    = "Namn: $name\nE-post: $email\n\nMeddelande:\n$message";

    // ----- Skicka mailet -----
    $mail->send();

    // ----- JSON-respons -----
    echo json_encode([
        "success" => true,
        "message" => "Tack! Ditt meddelande har skickats."
    ]);

} catch (Exception $e) {
    // ----- Logga fel -----
    $log = "[".date('Y-m-d H:i:s')."] ".$mail->ErrorInfo.PHP_EOL;
    file_put_contents(__DIR__.'/mail_error.log', $log, FILE_APPEND);

    // ----- JSON-respons till frontend -----
    echo json_encode([
        "success" => false,
        "message" => "Något gick fel. Kolla mail_error.log för detaljer."
    ]);
}
