<?php
$host = 'localhost';
$user = 'u110519718_groupzy_user';
$pass = 'y4Yu!|S@@L';
$db   = 'u110519718_groupzy';
$conn = new mysqli($host, $user, $pass, $db);
if (!$conn) die('Falha na conexão: ' . mysqli_connect_error());
