<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$from = "rafaeldeoliveiraadao@gmail.com";
$to = "rafaeldeoliveiraadao@gmail.com";
$subject = "Checking PHP mail";
$message = "PHP mail works just fine";
$headers = "From:" . $from;

if (mail($to, $subject, $message, $headers)) {
    echo "E-mail enviado com sucesso!";
}
