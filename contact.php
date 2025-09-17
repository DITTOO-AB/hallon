<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $to = "info@entreprenorsbolaget.se"; // Din e-postadress
    $subject = "Nytt meddelande från $name";
    $body = "Namn: $name\nE-post: $email\n\nMeddelande:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["success" => true, "message" => "Tack! Ditt meddelande har skickats."]);
    } else {
        echo json_encode(["success" => false, "message" => "Tyvärr, något gick fel. Försök igen senare."]);
    }
}
?>
