<?php
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
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Mensagem</th>
                </tr>
                <tr>
                    <td>$name</td>
                    <td>$email</td>
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
header("Location: ./");
