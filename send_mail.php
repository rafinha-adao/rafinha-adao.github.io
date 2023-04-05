<?php
$honeypot   = $_POST['firstname'];
if (!empty($honeypot)) return;

$name       = $_POST['name'];
$email      = $_POST['email'];
$message    = $_POST['message'];
$to         = "rafaeldeoliveiraadao@gmail.com";
$subject    = "Novo e-mail! (rafinhaadao.com)";
$message    = "
    <html>
        <head>
            <title>Novo e-mail! (rafinhaadao.com)</title>
        </head>
        <body>
            <table>
                <tr>
                    <th align='right'>Nome:</th>
                    <td>$name</td>
                </tr>
                <tr>
                    <th align='right'>E-mail:</th>
                    <td>$email</td>
                </tr>
                <tr>
                    <th align='right'>Mensagem:</th>
                    <td>$message</td>
                </tr>
            </table>
        </body>
    </html>
";
$headers    = "MIME-Version: 1.0" . "\r\n";
$headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers    .= "From: $email" . "\r\n";
$headers    .= "Cc: raphinha.oliveira11@gmail.com" . "\r\n";

mail($to, $subject, $message, $headers);
header("Location: ./success.html");
